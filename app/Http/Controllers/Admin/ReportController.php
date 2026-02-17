<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\VisitorLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        // Quick Stats
        $stats = [
            'today_revenue' => Transaction::whereDate('created_at', $today)->where('verification_status', 'verified')->sum('amount'),
            'month_revenue' => Transaction::whereDate('created_at', '>=', $thisMonth)->where('verification_status', 'verified')->sum('amount'),
            'year_revenue' => Transaction::whereDate('created_at', '>=', $thisYear)->where('verification_status', 'verified')->sum('amount'),
            'today_visitors' => VisitorLog::whereDate('created_at', $today)->distinct('ip_address')->count('ip_address'),
            'today_bookings' => Booking::whereDate('created_at', $today)->count(),
            'month_bookings' => Booking::whereDate('created_at', '>=', $thisMonth)->count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    public function revenue(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        $query = Transaction::where('verification_status', 'verified')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        switch ($period) {
            case 'hourly':
                $data = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date', 'hour')
                ->orderBy('date')
                ->orderBy('hour')
                ->get();
                break;

            case 'daily':
                $data = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                break;

            case 'monthly':
                $data = $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
                break;

            case 'yearly':
                $data = $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(amount) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('year')
                ->orderBy('year')
                ->get();
                break;

            default:
                $data = collect();
        }

        $totalRevenue = $data->sum('total');
        $totalTransactions = $data->sum('count');

        return view('admin.reports.revenue', compact('data', 'period', 'startDate', 'endDate', 'totalRevenue', 'totalTransactions'));
    }

    public function visitors(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        $visitors = VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 50));

        // Statistics
        $stats = [
            'total_visits' => VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->count(),
            'unique_visitors' => VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->distinct('ip_address')->count('ip_address'),
            'mobile_visits' => VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->where('device_type', 'mobile')->count(),
            'desktop_visits' => VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])->where('device_type', 'desktop')->count(),
        ];

        // Device breakdown
        $deviceStats = VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->get();

        // Browser breakdown
        $browserStats = VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select('browser', DB::raw('COUNT(*) as count'))
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Platform breakdown
        $platformStats = VisitorLog::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select('platform', DB::raw('COUNT(*) as count'))
            ->groupBy('platform')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.reports.visitors', compact('visitors', 'stats', 'deviceStats', 'browserStats', 'platformStats', 'startDate', 'endDate'));
    }

    public function bookings(Request $request)
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        $query = Booking::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        switch ($period) {
            case 'daily':
                $data = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                break;

            case 'monthly':
                $data = $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
                break;

            default:
                $data = collect();
        }

        return view('admin.reports.bookings', compact('data', 'period', 'startDate', 'endDate'));
    }
}
