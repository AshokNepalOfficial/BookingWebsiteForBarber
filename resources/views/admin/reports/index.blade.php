@extends('layouts.admin')

@section('title', 'Reports & Analytics | Admin Panel')

@section('page-title', 'Reports & Analytics')

@section('content')

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-500/10 to-green-600/5 border border-green-500/20 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-400 text-xl"></i>
            </div>
            <span class="text-xs text-gray-400">Today</span>
        </div>
        <h3 class="text-2xl font-bold text-white mb-1">{!! $formatPrice($stats['today_revenue']) !!}</h3>
        <p class="text-sm text-gray-400">Today's Revenue</p>
    </div>

    <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/5 border border-blue-500/20 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-blue-400 text-xl"></i>
            </div>
            <span class="text-xs text-gray-400">This Month</span>
        </div>
        <h3 class="text-2xl font-bold text-white mb-1">{!! $formatPrice($stats['month_revenue']) !!}</h3>
        <p class="text-sm text-gray-400">Monthly Revenue</p>
    </div>

    <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/5 border border-purple-500/20 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-purple-400 text-xl"></i>
            </div>
            <span class="text-xs text-gray-400">This Year</span>
        </div>
        <h3 class="text-2xl font-bold text-white mb-1">{!! $formatPrice($stats['year_revenue']) !!}</h3>
        <p class="text-sm text-gray-400">Yearly Revenue</p>
    </div>
</div>

<!-- Additional Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Today's Visitors</p>
                <h4 class="text-2xl font-bold text-white">{{ number_format($stats['today_visitors']) }}</h4>
            </div>
            <i class="fas fa-users text-gold-500 text-3xl opacity-20"></i>
        </div>
    </div>

    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Today's Bookings</p>
                <h4 class="text-2xl font-bold text-white">{{ number_format($stats['today_bookings']) }}</h4>
            </div>
            <i class="fas fa-calendar-check text-blue-500 text-3xl opacity-20"></i>
        </div>
    </div>

    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-1">Monthly Bookings</p>
                <h4 class="text-2xl font-bold text-white">{{ number_format($stats['month_bookings']) }}</h4>
            </div>
            <i class="fas fa-calendar text-green-500 text-3xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <a href="{{ route('admin.reports.revenue') }}" class="bg-dark-900 border border-white/5 hover:border-gold-500/50 rounded-lg p-6 transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-green-500/10 group-hover:bg-green-500/20 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-chart-line text-green-400 text-2xl"></i>
            </div>
            <div>
                <h4 class="text-white font-medium mb-1">Revenue Reports</h4>
                <p class="text-xs text-gray-500">Hourly, Daily, Monthly</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.visitors') }}" class="bg-dark-900 border border-white/5 hover:border-gold-500/50 rounded-lg p-6 transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-500/10 group-hover:bg-blue-500/20 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-users text-blue-400 text-2xl"></i>
            </div>
            <div>
                <h4 class="text-white font-medium mb-1">Visitor Analytics</h4>
                <p class="text-xs text-gray-500">Traffic & Demographics</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.bookings') }}" class="bg-dark-900 border border-white/5 hover:border-gold-500/50 rounded-lg p-6 transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-purple-500/10 group-hover:bg-purple-500/20 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-calendar-alt text-purple-400 text-2xl"></i>
            </div>
            <div>
                <h4 class="text-white font-medium mb-1">Booking Reports</h4>
                <p class="text-xs text-gray-500">Status & Trends</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.transactions.index') }}" class="bg-dark-900 border border-white/5 hover:border-gold-500/50 rounded-lg p-6 transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gold-500/10 group-hover:bg-gold-500/20 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-receipt text-gold-400 text-2xl"></i>
            </div>
            <div>
                <h4 class="text-white font-medium mb-1">Transactions</h4>
                <p class="text-xs text-gray-500">Payment History</p>
            </div>
        </div>
    </a>
</div>

@endsection
