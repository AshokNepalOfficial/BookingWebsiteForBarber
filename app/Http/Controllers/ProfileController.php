<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\UserMembership;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $upcomingBookings = Booking::where('user_id', $user->id)
            ->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['services', 'barber'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();
            
        $activeMembership = $user->activeMembership()->with('membership')->first();
        
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'loyalty_points' => $user->loyalty_points,
            'completed_sessions' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        return view('profile.dashboard', compact('user', 'upcomingBookings', 'activeMembership', 'stats'));
    }

    /**
     * Display the customer's booking history.
     */
    public function bookings()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->with(['services', 'barber'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('profile.bookings', compact('user','bookings'));
    }

    /**
     * Display a specific booking detail.
     */
    public function showBooking($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)
            ->with(['services', 'barber'])
            ->findOrFail($id);

        return view('profile.booking-show', compact('user', 'booking'));
    }

    /**
     * Show the form for editing the customer's profile.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the customer's profile.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_no' => 'required|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->phone_no = $validated['phone_no'];
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Display the customer's membership details.
     */
    public function membership()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $memberships = UserMembership::where('user_id', $user->id)
            ->with('membership')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('profile.membership',compact('user','memberships'));
    }

    /**
     * Display the customer's transaction history.
     */
    public function transactions()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->with('booking')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('profile.transactions', compact('user','transactions'));
    }
}
