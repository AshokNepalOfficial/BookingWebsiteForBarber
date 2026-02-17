<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\UserMembership;
use App\Models\Transaction;
use App\Models\LoyaltyAdjustment;
use App\Models\Membership;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['customer', 'member']);
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_no', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        $customers = $query->withCount('bookings')
                          ->latest()
                          ->paginate($request->get('per_page', 20));
        
        return view('admin.customers.index', compact('customers'));
    }
    
    public function create()
    {
        return view('admin.customers.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone_no' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:customer,member',
        ]);
        
        $validated['password'] = bcrypt($validated['password']);
        $validated['is_guest'] = false;
        
        $customer = User::create($validated);
        
        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Customer created successfully!');
    }
    
    public function show($id)
    {
        $customer = User::with(['bookings.services', 'userMemberships.membership', 'transactions'])
            ->findOrFail($id);
        
        $totalBookings = $customer->bookings()->count();
        $completedBookings = $customer->bookings()->where('status', 'completed')->count();
        $totalSpent = $customer->transactions()
            ->where('verification_status', 'verified')
            ->where('transaction_type', 'service_payment')
            ->sum('amount');
        
        $activeMembership = $customer->userMemberships()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->with('membership')
            ->first();
        
        $recentBookings = $customer->bookings()
            ->with('services')
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.customers.show', compact(
            'customer',
            'totalBookings',
            'completedBookings',
            'totalSpent',
            'activeMembership',
            'recentBookings'
        ));
    }
    
    public function edit($id)
    {
        $customer = User::findOrFail($id);
        $memberships = Membership::where('is_active', true)->get();
        return view('admin.customers.edit', compact('customer', 'memberships'));
    }
    
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_no' => 'required|string|max:20',
            'role' => 'required|in:customer,member',
            'convert_to_registered' => 'nullable|boolean',
            'membership_id' => 'nullable|exists:memberships,id',
        ]);
        
        // Handle guest to registered conversion
        if ($request->has('convert_to_registered') && $customer->is_guest) {
            $validated['is_guest'] = false;
            $validated['walkin_token'] = null;
            
            $message = 'Customer updated and converted to registered account!';
        } else {
            $message = 'Customer updated successfully!';
        }
        
        $customer->update($validated);
        
        // Handle membership assignment
        if ($request->filled('membership_id')) {
            $membership = Membership::find($request->membership_id);
            
            // Check if customer already has active membership
            $existingMembership = $customer->userMemberships()
                ->where('status', 'active')
                ->where('end_date', '>=', now())
                ->first();
            
            if (!$existingMembership) {
                UserMembership::create([
                    'user_id' => $customer->id,
                    'membership_id' => $membership->id,
                    'start_date' => now(),
                    'end_date' => now()->addDays($membership->duration_days),
                    'status' => 'active',
                ]);
                
                $message .= ' Membership assigned successfully!';
            } else {
                $message .= ' Note: Customer already has an active membership.';
            }
        }
        
        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', $message);
    }
    
    public function loyaltyPoints(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $completedBookings = $customer->bookings()
            ->where('status', 'completed')
            ->with('services')
            ->latest()
            ->get();
        
        $adjustments = LoyaltyAdjustment::where('user_id', $id)
            ->with('admin')
            ->latest()
            ->paginate($request->get('per_page', 10));
        
        return view('admin.customers.loyalty', compact('customer', 'completedBookings', 'adjustments'));
    }
    
    public function adjustLoyaltyPoints(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $validated = $request->validate([
            'points' => 'required|integer',
            'action' => 'required|in:add,subtract,set',
            'reason' => 'required|string|max:500',
        ]);
        
        $pointsBefore = $customer->loyalty_points;
        $pointsAdjusted = $validated['points'];
        
        switch ($validated['action']) {
            case 'add':
                $customer->loyalty_points += $validated['points'];
                break;
            case 'subtract':
                $customer->loyalty_points = max(0, $customer->loyalty_points - $validated['points']);
                $pointsAdjusted = -$validated['points'];
                break;
            case 'set':
                $customer->loyalty_points = max(0, $validated['points']);
                $pointsAdjusted = $validated['points'] - $pointsBefore;
                break;
        }
        
        $pointsAfter = $customer->loyalty_points;
        $customer->save();
        
        // Log the adjustment
        LoyaltyAdjustment::create([
            'user_id' => $customer->id,
            'admin_id' => auth()->id(),
            'points_before' => $pointsBefore,
            'points_adjusted' => $pointsAdjusted,
            'points_after' => $pointsAfter,
            'action' => $validated['action'],
            'reason' => $validated['reason'],
        ]);
        
        return redirect()->back()->with('success', 'Loyalty points adjusted successfully!');
    }
    
    public function bookingHistory(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $bookings = $customer->bookings()
            ->with(['services', 'transactions'])
            ->orderBy('appointment_date', 'desc')
            ->paginate($request->get('per_page', 20));
        
        // Calculate total_price for each booking if not set
        foreach ($bookings as $booking) {
            if (!$booking->total_price || $booking->total_price == 0) {
                $booking->total_price = $booking->services->sum('price');
            }
        }
        
        return view('admin.customers.booking-history', compact('customer', 'bookings'));
    }
    
    public function membershipHistory($id)
    {
        $customer = User::findOrFail($id);
        
        $memberships = $customer->userMemberships()
            ->with('membership')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.customers.membership-history', compact('customer', 'memberships'));
    }
    
    public function transactionHistory(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $transactions = $customer->transactions()
            ->with(['booking', 'verifier'])
            ->latest()
            ->paginate($request->get('per_page', 20));
        
        return view('admin.customers.transaction-history', compact('customer', 'transactions'));
    }
}