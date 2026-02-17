<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's statistics
        $todayBookings = Booking::whereDate('appointment_date', today())->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedTodayBookings = Booking::whereDate('appointment_date', today())
            ->where('status', 'confirmed')
            ->count();
        
        // Recent bookings
        $recentBookings = Booking::with(['user', 'services', 'staff'])
            ->latest()
            ->take(10)
            ->get();
        
        // Today's bookings with details
        $todaysBookings = Booking::with(['user', 'services', 'staff'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time', 'asc')
            ->get();
        
        // Available barbers (staff with can_accept_bookings = true)
        $availableBarbers = User::where('can_accept_bookings', true)
            ->where('is_active', true)
            ->get();
        
        return view('receptionist.dashboard', compact(
            'todayBookings',
            'pendingBookings',
            'confirmedTodayBookings',
            'recentBookings',
            'todaysBookings',
            'availableBarbers'
        ));
    }
}
