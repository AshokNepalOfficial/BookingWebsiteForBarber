<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Models\Transaction;
use App\Events\BookingCreated;
use App\Events\BookingStatusUpdated;
use App\Notifications\NewBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'services']);
        
        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Search by customer
        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // $bookings = $query->orderBy('appointment_date', 'desc')
        //                   ->orderBy('appointment_time', 'desc')
        //                   ->paginate($request->get('per_page', 20));
        

        $bookings = $query->orderBy('created_at', 'desc')  // latest created first
                  ->paginate($request->get('per_page', 20));

        $services = Service::active()->get();
        
        return view('admin.bookings.index', compact('bookings', 'services'));
    }
    
    public function show($id)
    {
        $booking = Booking::with(['user', 'services', 'barber', 'transactions'])
            ->findOrFail($id);
        
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function edit($id)
    {
        $booking = Booking::with('services')->findOrFail($id);
        $services = Service::active()->get();
        $barbers = User::whereIn('role', ['staff', 'admin'])->get();
        
        return view('admin.bookings.edit', compact('booking', 'services', 'barbers'));
    }
    
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'barber_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'required|in:pending,verified,rejected',
            'special_requests' => 'nullable|string|max:1000',
        ]);
        
        // Update booking
        $booking->update([
            'barber_id' => $validated['barber_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'special_requests' => $validated['special_requests'],
        ]);
        
        // Sync services
        $booking->services()->sync($validated['service_ids']);
        
        // Calculate total price
        $totalPrice = $booking->services()->sum('price');
        $booking->update(['total_price' => $totalPrice]);
        
        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Booking updated successfully!');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'transaction_reference' => 'nullable|string|max:255',
            'payment_method' => 'nullable|in:cash,card,online,bank_transfer',
            'payment_proof' => 'nullable|image|max:5120', // 5MB max
            'payment_notes' => 'nullable|string|max:1000',
        ]);
        
        $oldStatus = $booking->status;
        $booking->status = $validated['status'];
        
        // Create transaction record if payment details are provided
        if (!empty($validated['transaction_reference']) || !empty($validated['payment_method']) || $request->hasFile('payment_proof')) {
            
            // Check if transaction already exists for this booking to prevent duplicates
            $existingBookingTransaction = Transaction::where('booking_id', $booking->id)
                ->where('transaction_type', 'service_payment')
                ->first();
            
            if ($existingBookingTransaction) {
                // Update existing transaction instead of creating new one
                if (!empty($validated['transaction_reference'])) {
                    // Check if reference is taken by another transaction
                    $duplicateRef = Transaction::where('transaction_reference', $validated['transaction_reference'])
                        ->where('id', '!=', $existingBookingTransaction->id)
                        ->first();
                    if ($duplicateRef) {
                        return back()->withErrors(['transaction_reference' => 'This transaction reference already exists in the system.']);
                    }
                    $existingBookingTransaction->transaction_reference = $validated['transaction_reference'];
                }
                
                if (!empty($validated['payment_method'])) {
                    $existingBookingTransaction->payment_method = $validated['payment_method'];
                }
                
                if ($request->hasFile('payment_proof')) {
                    $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                    $existingBookingTransaction->payment_proof_image = $paymentProofPath;
                }
                
                if (!empty($validated['payment_notes'])) {
                    $existingBookingTransaction->notes = $validated['payment_notes'];
                }
                
                $existingBookingTransaction->save();
                $transactionAction = 'updated';
            } else {
                // Check for duplicate transaction reference
                if (!empty($validated['transaction_reference'])) {
                    $existingTransaction = Transaction::where('transaction_reference', $validated['transaction_reference'])->first();
                    if ($existingTransaction) {
                        return back()->withErrors(['transaction_reference' => 'This transaction reference already exists in the system.']);
                    }
                }
                
                // Calculate total price if not set
                $totalPrice = $booking->total_price;
                if (!$totalPrice) {
                    $totalPrice = $booking->services()->sum('price');
                    $booking->update(['total_price' => $totalPrice]);
                }
                
                // Apply discount if provided
                if ($booking->discount_amount > 0) {
                    $totalPrice -= $booking->discount_amount;
                }
                
                // Upload payment proof if provided
                $paymentProofPath = null;
                if ($request->hasFile('payment_proof')) {
                    $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                }
                
                // Create transaction record
                $transaction = Transaction::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'amount' => $totalPrice,
                    'transaction_type' => 'service_payment',
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                    'transaction_reference' => $validated['transaction_reference'],
                    'payment_proof_image' => $paymentProofPath,
                    'verification_status' => 'verified', // Auto-verify admin/staff payments
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                    'notes' => $validated['payment_notes'],
                    'is_offline' => true,
                ]);
                $transactionAction = 'recorded';
            }
            
            // Update booking payment status
            $booking->payment_status = 'verified';
        }
        
        if ($validated['status'] === 'confirmed' && $oldStatus !== 'confirmed') {
            $booking->confirmed_at = now();
        } elseif ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $booking->completed_at = now();
            
            // Update loyalty points
            $user = $booking->user;
            $user->increment('loyalty_points');
            
            if ($user->loyalty_points >= 10) {
                $user->loyalty_points = 0;
                $user->save();
            }
        }
        
        $booking->save();
        
        // Dispatch event to send status update emails via queue
        if ($oldStatus !== $validated['status']) {
            // Reload booking with relationships for email
            $booking = $booking->fresh(['user', 'services', 'staff']);
            event(new BookingStatusUpdated($booking, $oldStatus, $validated['status']));
        }
        
        $message = 'Booking status updated to ' . $validated['status'];
        if (isset($transactionAction)) {
            $message .= ' and payment transaction ' . $transactionAction . '.';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function calendar(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        
        $bookings = Booking::with(['user', 'services'])
            ->whereDate('appointment_date', $date)
            ->orderBy('appointment_time')
            ->get();
        
        return view('admin.bookings.calendar', compact('bookings', 'date'));
    }
    
    public function create()
    {
        $services = Service::active()->get();
        $customers = User::whereIn('role', ['customer', 'member'])->get();
        $barbers = User::whereIn('role', ['staff', 'admin'])->get();
        
        return view('admin.bookings.create', compact('services', 'customers', 'barbers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_type' => 'required|in:registered,walkin',
            'user_id' => 'required_if:booking_type,registered|nullable|exists:users,id',
            'walkin_token' => 'required_if:booking_type,walkin|nullable|string|max:20',
            'walkin_first_name' => 'nullable|string|max:100',
            'walkin_last_name' => 'nullable|string|max:100',
            'walkin_email' => 'nullable|email|max:255',
            'walkin_phone' => 'nullable|string|max:20',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'barber_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'special_requests' => 'nullable|string|max:1000',
        ]);
        
        // Handle user creation/retrieval
        if ($validated['booking_type'] === 'walkin') {
            // Check if walk-in token already exists (returning customer)
            $user = User::where('walkin_token', $validated['walkin_token'])->first();
            
            if (!$user) {
                // Create new walk-in user
                // If no email provided, use a special format that won't receive emails
                $email = !empty($validated['walkin_email']) 
                    ? $validated['walkin_email'] 
                    : 'walkin-' . strtolower($validated['walkin_token']) . '@noemail.local';
                
                $phone = $validated['walkin_phone'] ?? null;
                
                $user = User::create([
                    'first_name' => $validated['walkin_first_name'] ?? 'Walk-in',
                    'last_name' => $validated['walkin_last_name'] ?? 'Guest',
                    'email' => $email,
                    'phone_no' => $phone,
                    'role' => 'customer',
                    'is_guest' => true,
                    'walkin_token' => $validated['walkin_token'],
                    'loyalty_points' => 0,
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                ]);
            } else {
                // Update existing walk-in customer with new contact info if provided
                $updates = [];
                if (!empty($validated['walkin_email']) && $user->email !== $validated['walkin_email']) {
                    $updates['email'] = $validated['walkin_email'];
                }
                if (!empty($validated['walkin_phone']) && $user->phone_no !== $validated['walkin_phone']) {
                    $updates['phone_no'] = $validated['walkin_phone'];
                }
                if (!empty($updates)) {
                    $user->update($updates);
                }
            }
            
            $userId = $user->id;
        } else {
            $userId = $validated['user_id'];
        }
        
        // Create booking
        $booking = Booking::create([
            'user_id' => $userId,
            'barber_id' => $validated['barber_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'special_requests' => $validated['special_requests'],
            'status' => 'confirmed',
            'payment_status' => 'pending',
            'confirmed_at' => now(),
        ]);
        
        // Attach services
        $booking->services()->attach($validated['service_ids']);
        
        // Calculate total price
        $totalPrice = $booking->services()->sum('price');
        $booking->update(['total_price' => $totalPrice]);
        
        $message = 'Booking created successfully!';
        if ($validated['booking_type'] === 'walkin') {
            $message .= ' Walk-in Token: ' . $validated['walkin_token'];
        }
        
        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', $message);
    }
    
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }
}