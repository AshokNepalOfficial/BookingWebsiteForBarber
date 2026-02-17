<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\UserMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'booking', 'verifier']);
        
        if ($request->has('status')) {
            $query->where('verification_status', $request->status);
        }
        
        if ($request->has('type')) {
            $query->where('transaction_type', $request->type);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('transaction_reference', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('email', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $transactions = $query->latest()->paginate($request->get('per_page', 20));
        
        // Calculate stats
        $totalRevenue = Transaction::where('verification_status', 'verified')->sum('amount');
        $pendingCount = Transaction::where('verification_status', 'pending')->count();
        $monthlyRevenue = Transaction::where('verification_status', 'verified')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $todayRevenue = Transaction::where('verification_status', 'verified')
            ->whereDate('created_at', now()->toDateString())
            ->sum('amount');
        
        return view('admin.transactions.index', compact(
            'transactions',
            'totalRevenue',
            'pendingCount',
            'monthlyRevenue',
            'todayRevenue'
        ));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'booking.services', 'userMembership', 'verifier'])
            ->findOrFail($id);
        
        return view('admin.transactions.show', compact('transaction'));
    }

    public function pendingPayments()
    {
        $transactions = Transaction::with(['user', 'booking'])
            ->where('verification_status', 'pending')
            ->latest()
            ->get();
        
        return view('admin.transactions.pending', compact('transactions'));
    }

    public function recordOfflinePayment(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'user_membership_id' => 'nullable|exists:user_memberships,id',
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:service_payment,membership_payment,refund',
            'payment_method' => 'required|in:cash,card,online,bank_transfer',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check for duplicate transaction reference
        if ($validated['transaction_reference']) {
            $exists = Transaction::where('transaction_reference', $validated['transaction_reference'])->exists();
            if ($exists) {
                return back()->withErrors(['transaction_reference' => 'Transaction reference already exists.']);
            }
        }

        // Check if booking already has a verified payment
        if ($validated['booking_id']) {
            $existingPayment = Transaction::where('booking_id', $validated['booking_id'])
                ->where('verification_status', 'verified')
                ->exists();
            
            if ($existingPayment) {
                return back()->withErrors(['booking_id' => 'This booking already has a verified payment.']);
            }
        }

        $transaction = Transaction::create([
            'booking_id' => $validated['booking_id'] ?? null,
            'user_membership_id' => $validated['user_membership_id'] ?? null,
            'user_id' => $validated['user_id'],
            'amount' => $validated['amount'],
            'transaction_type' => $validated['transaction_type'],
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'],
            'verification_status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'notes' => $validated['notes'],
            'is_offline' => true,
        ]);

        // Update booking payment status if applicable
        if ($transaction->booking_id) {
            $booking = Booking::find($transaction->booking_id);
            $booking->payment_status = 'verified';
            $booking->save();
        }

        // Send confirmation email
        // Mail::to($transaction->user->email)->send(new PaymentConfirmation($transaction));

        return redirect()->route('admin.transactions.show', $transaction->id)
            ->with('success', 'Payment recorded successfully!');
    }

    public function uploadProof(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $validated = $request->validate([
            'payment_proof' => 'required|image|max:5120', // 5MB max
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        // Check for duplicate reference
        if ($request->transaction_reference) {
            $exists = Transaction::where('transaction_reference', $request->transaction_reference)
                ->where('id', '!=', $id)
                ->exists();
            
            if ($exists) {
                return back()->withErrors(['transaction_reference' => 'Transaction reference already exists.']);
            }
        }

        // Store payment proof image
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        
        $transaction->payment_proof_image = $path;
        $transaction->transaction_reference = $request->transaction_reference;
        $transaction->verification_status = 'pending';
        $transaction->save();

        // Notify receptionist
        // Mail::to('receptionist@barbershop.com')->send(new PaymentProofUploaded($transaction));

        return back()->with('success', 'Payment proof uploaded successfully. Awaiting verification.');
    }

    public function verify(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $transaction->verification_status = $validated['status'];
        $transaction->verified_by = Auth::id();
        $transaction->verified_at = now();
        $transaction->notes = $validated['notes'];
        $transaction->save();

        // Update booking payment status
        if ($transaction->booking_id && $validated['status'] === 'verified') {
            $booking = Booking::find($transaction->booking_id);
            $booking->payment_status = 'verified';
            $booking->save();
            
            // Send verification email
            // Mail::to($transaction->user->email)->send(new PaymentVerified($transaction));
        } elseif ($validated['status'] === 'rejected') {
            // Send rejection email
            // Mail::to($transaction->user->email)->send(new PaymentRejected($transaction, $validated['notes']));
        }

        return redirect()->back()->with('success', 'Payment ' . $validated['status'] . ' successfully!');
    }

    public function audit(Request $request)
    {
        $query = Transaction::with(['user', 'verifier']);
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->latest()->get();
        
        $totalAmount = $transactions->where('verification_status', 'verified')->sum('amount');
        $pendingCount = $transactions->where('verification_status', 'pending')->count();
        
        return view('admin.transactions.audit', compact('transactions', 'totalAmount', 'pendingCount'));
    }
}