<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use App\Models\UserMembership;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's statistics
        $todayBookings = Booking::whereDate('appointment_date', today())->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        
        // Revenue statistics
        $todayRevenue = Transaction::whereDate('created_at', today())
            ->where('verification_status', 'verified')
            ->sum('amount');
        
        $monthRevenue = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('verification_status', 'verified')
            ->sum('amount');
        
        // Recent bookings
        $recentBookings = Booking::with(['user', 'services'])
            ->latest()
            ->take(10)
            ->get();
        
        // Active memberships
        $activeMemberships = UserMembership::where('status', 'active')
            ->where('end_date', '>=', now())
            ->count();
        
        // Time-based revenue chart data (Today's hourly revenue)
        $timeRevenueData = Transaction::whereDate('created_at', today())
            ->where('verification_status', 'verified')
            ->selectRaw('HOUR(created_at) as hour, SUM(amount) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('total', 'hour')
            ->toArray();
        
        // Fill in missing hours with 0
        $hourlyRevenue = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyRevenue[] = [
                'hour' => sprintf('%02d:00', $i),
                'revenue' => $timeRevenueData[$i] ?? 0
            ];
        }
        
        return view('admin.dashboard', compact(
            'todayRevenue',
            'pendingBookings',
            'totalCustomers',
            'activeMemberships',
            'recentBookings',
            'hourlyRevenue'
        ));
    }
    
    public function statistics(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());
        
        // Bookings by status
        $bookingStats = Booking::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();
        
        // Revenue breakdown
        $revenueByType = Transaction::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('verification_status', 'verified')
            ->selectRaw('transaction_type, sum(amount) as total')
            ->groupBy('transaction_type')
            ->get();
        
        // Customer growth
        $newCustomers = User::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('role', 'customer')
            ->count();
        
        // Service performance
        $servicePerformance = Service::withCount(['bookings' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->with(['bookings' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('bookings.created_at', [$dateFrom, $dateTo])
                      ->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
                      ->where('transactions.verification_status', 'verified')
                      ->selectRaw('service_id, sum(transactions.amount) as revenue');
            }])
            ->get();
        
        return view('admin.statistics', compact(
            'bookingStats',
            'revenueByType',
            'newCustomers',
            'servicePerformance',
            'dateFrom',
            'dateTo'
        ));
    }
}