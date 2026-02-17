<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\BookingCreated;
use App\Events\GuestUserCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class BookingController extends Controller
{
    public function create()
    {
        $services = Service::active()->get();
        
        // Get booking settings
        $maxDaysAdvance = Setting::where('key', 'booking_max_days_advance')->value('value') ?? 15;
        $enableBarberSelection = Setting::where('key', 'enable_barber_selection')->value('value') ?? 0;
        $timeSlots = Setting::where('key', 'booking_time_slots')->value('value');
        $timeSlots = $timeSlots ? json_decode($timeSlots, true) : [
            '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', 
            '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'
        ];
        
        $maxDate = Carbon::now()->addDays($maxDaysAdvance)->format('Y-m-d');
        
        return view('booking.create', compact('services', 'maxDate', 'enableBarberSelection', 'timeSlots'));
    }

    public function store(Request $request)
    {
        // Get max days advance setting
        $maxDaysAdvance = (int) (Setting::where('key', 'booking_max_days_advance')->value('value') ?? 15);
        $maxDate = Carbon::now()->addDays($maxDaysAdvance)->toDateString();
        
        $validated = $request->validate([
            'service_ids' => 'required|json',
            'barber_id' => 'nullable|exists:users,id',
            'appointment_date' => "required|date|after:today|before_or_equal:{$maxDate}",
            'appointment_time' => 'required',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone_no' => 'required|string|max:20',
            'special_request' => 'nullable|string|max:1000',
        ]);

        // Parse service IDs from JSON
        $serviceIds = json_decode($validated['service_ids'], true);
        
        if (empty($serviceIds)) {
            return back()->withErrors(['service_ids' => 'Please select at least one service.']);
        }

        // Check if user is authenticated or guest
        if (Auth::check()) {
            // Use authenticated user's data
            $user = Auth::user();
        } else {
            // Guest booking - use provided form data
            $firstName = $validated['first_name'];
            $lastName = $validated['last_name'];
            $email = $validated['email'];
            $phoneNo = $validated['phone_no'];
            
            // Check if email already exists
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                // Create new guest user
                $temporaryPassword = Str::random(10);
                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone_no' => $phoneNo,
                    'password' => Hash::make($temporaryPassword),
                    'role' => 'customer',
                    'is_guest' => true,
                ]);
                
                // Dispatch event to send credentials email via queue
                event(new GuestUserCreated($user, $temporaryPassword));
            }
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'barber_id' => $validated['barber_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'special_request' => $validated['special_request'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Attach services to booking
        $booking->services()->attach($serviceIds);

        // Reload booking with all relationships for events
        $booking = $booking->fresh(['user', 'services', 'staff']);

        // Dispatch event - listeners will handle emails and notifications via queue
        event(new BookingCreated($booking));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully! We will contact you soon to confirm your appointment.',
                'booking_id' => $booking->id
            ]);
        }

        return redirect()->route('home')
            ->with('success', 'Booking created successfully! We will contact you soon to confirm your appointment.');
    }

    public function show($id)
    {
        $booking = Booking::with(['services', 'barber', 'user'])->findOrFail($id);
        
        // Check authorization
        if (Auth::check() && Auth::id() !== $booking->user_id) {
            $userRole = Auth::user()->role;
            if (!in_array($userRole, ['receptionist', 'admin', 'staff', 'manager', 'barber'])) {
                abort(403);
            }
        }

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::with(['services'])->findOrFail($id);
        
        // Check if customer can edit
        if (Auth::check() && Auth::id() === $booking->user_id) {
            if (!$booking->isEditableByCustomer()) {
                return redirect()->back()->with('error', 'Booking can only be edited within 20 minutes of creation.');
            }
        } elseif (Auth::check()) {
            $userRole = Auth::user()->role;
            if (!in_array($userRole, ['receptionist', 'admin', 'staff', 'manager', 'barber'])) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $services = Service::active()->get();
        return view('booking.edit', compact('booking', 'services'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Check authorization
        $canEdit = false;
        if (Auth::check() && Auth::id() === $booking->user_id) {
            $canEdit = $booking->isEditableByCustomer();
        } elseif (Auth::check()) {
            $userRole = Auth::user()->role;
            $canEdit = in_array($userRole, ['receptionist', 'admin', 'staff', 'manager', 'barber']);
        }

        if (!$canEdit) {
            abort(403);
        }

        $validated = $request->validate([
            'service_ids' => 'sometimes|json',
            'barber_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'special_request' => 'nullable|string|max:1000',
        ]);

        // Update basic fields
        $booking->update([
            'barber_id' => $validated['barber_id'] ?? $booking->barber_id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'special_request' => $validated['special_request'],
        ]);

        // Update services if provided
        if (isset($validated['service_ids'])) {
            $serviceIds = json_decode($validated['service_ids'], true);
            if (!empty($serviceIds)) {
                $booking->services()->sync($serviceIds);
            }
        }

        return redirect()->route('home')
            ->with('success', 'Booking updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        // Only receptionist, staff, manager, barber and admin can update status
        if (!Auth::check() || !in_array(Auth::user()->role, ['receptionist', 'staff', 'admin', 'manager', 'barber'])) {
            abort(403);
        }

        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->status = $validated['status'];
        
        if ($validated['status'] === 'confirmed') {
            $booking->confirmed_at = now();
            // Send confirmation email
        } elseif ($validated['status'] === 'completed') {
            $booking->completed_at = now();
            // Update loyalty points
            $user = $booking->user;
            $user->increment('loyalty_points');
            
            // Check if reached 10 points
            if ($user->loyalty_points >= 10) {
                $user->loyalty_points = 0;
                $user->save();
                // Notify about free service
            }
            // Send completion email with loyalty status
        }

        $booking->save();

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }
}
