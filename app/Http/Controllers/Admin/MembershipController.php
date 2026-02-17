<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\UserMembership;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::withCount(['userMemberships as active_members_count' => function($query) {
            $query->where('status', 'active')->where('end_date', '>=', now());
        }])->get();
        
        return view('admin.memberships.index', compact('memberships'));
    }

    public function create()
    {
        return view('admin.memberships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'membership_name' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'free_services_count' => 'required|integer|min:0',
            'priority_booking' => 'boolean',
            'is_active' => 'boolean',
        ]);

        Membership::create($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership plan created successfully!');
    }

    public function edit($id)
    {
        $membership = Membership::findOrFail($id);
        return view('admin.memberships.edit', compact('membership'));
    }

    public function update(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);
        
        $validated = $request->validate([
            'membership_name' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'free_services_count' => 'required|integer|min:0',
            'priority_booking' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $membership->update($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership plan updated successfully!');
    }

    public function members(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $userMemberships = UserMembership::with(['user', 'membership'])
            ->latest()
            ->paginate($perPage);
        
        return view('admin.memberships.members', compact('userMemberships'));
    }

    public function activate(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'membership_id' => 'required|exists:memberships,id',
            'transaction_id' => 'nullable|exists:transactions,id',
        ]);

        $membership = Membership::findOrFail($validated['membership_id']);
        
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($membership->duration_days);

        $userMembership = UserMembership::create([
            'user_id' => $validated['user_id'],
            'membership_id' => $validated['membership_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'remaining_free_services' => $membership->free_services_count,
            'status' => 'active',
            'payment_transaction_id' => $validated['transaction_id'] ?? null,
        ]);

        // Update user role to member if customer
        $user = User::find($validated['user_id']);
        if ($user->role === 'customer') {
            $user->role = 'member';
            $user->save();
        }

        // Send welcome email
        // Mail::to($user->email)->send(new MembershipActivated($userMembership));

        return redirect()->back()->with('success', 'Membership activated successfully!');
    }

    public function renew($id)
    {
        $userMembership = UserMembership::with('membership')->findOrFail($id);
        
        $newStartDate = Carbon::parse($userMembership->end_date)->addDay();
        $newEndDate = $newStartDate->copy()->addDays($userMembership->membership->duration_days);

        $newUserMembership = UserMembership::create([
            'user_id' => $userMembership->user_id,
            'membership_id' => $userMembership->membership_id,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
            'remaining_free_services' => $userMembership->membership->free_services_count,
            'status' => 'active',
        ]);

        // Update old membership status
        $userMembership->status = 'expired';
        $userMembership->save();

        // Send renewal confirmation
        // Mail::to($userMembership->user->email)->send(new MembershipRenewed($newUserMembership));

        return redirect()->back()->with('success', 'Membership renewed successfully!');
    }

    public function cancel($id)
    {
        $userMembership = UserMembership::findOrFail($id);
        
        $userMembership->status = 'cancelled';
        $userMembership->save();

        // Send cancellation email
        // Mail::to($userMembership->user->email)->send(new MembershipCancelled($userMembership));

        return redirect()->back()->with('success', 'Membership cancelled successfully!');
    }

    public function expiring()
    {
        $expiringMemberships = UserMembership::with(['user', 'membership'])
            ->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->get();
        
        return view('admin.memberships.expiring', compact('expiringMemberships'));
    }

    public function checkExpired()
    {
        $expiredMemberships = UserMembership::where('status', 'active')
            ->where('end_date', '<', now()->toDateString())
            ->get();

        foreach ($expiredMemberships as $membership) {
            $membership->status = 'expired';
            $membership->save();
            
            // Send expiration notice
            // Mail::to($membership->user->email)->send(new MembershipExpired($membership));
        }

        return redirect()->back()->with('success', count($expiredMemberships) . ' memberships marked as expired.');
    }
}