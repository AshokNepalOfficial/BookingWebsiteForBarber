<?php

namespace App\Http\Controllers\Barber;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // My bookings statistics
        $myTodayBookings = Booking::where('staff_id', $user->id)
            ->whereDate('appointment_date', today())
            ->count();
            
        $myUpcomingBookings = Booking::where('staff_id', $user->id)
            ->where('status', 'confirmed')
            ->where('appointment_date', '>=', today())
            ->count();
            
        $myCompletedBookings = Booking::where('staff_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        // Today's schedule
        $todaysSchedule = Booking::with(['user', 'services'])
            ->where('staff_id', $user->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time', 'asc')
            ->get();
        
        // Upcoming appointments
        $upcomingAppointments = Booking::with(['user', 'services'])
            ->where('staff_id', $user->id)
            ->where('status', 'confirmed')
            ->where('appointment_date', '>', today())
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->take(10)
            ->get();
        
        // Recent completed
        $recentCompleted = Booking::with(['user', 'services'])
            ->where('staff_id', $user->id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(5)
            ->get();
        
        return view('barber.dashboard', compact(
            'myTodayBookings',
            'myUpcomingBookings',
            'myCompletedBookings',
            'todaysSchedule',
            'upcomingAppointments',
            'recentCompleted'
        ));
    }
}
