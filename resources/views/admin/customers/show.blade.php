@extends('layouts.admin')

@section('title', 'Customer Details | Admin Panel')

@section('page-title', $customer->first_name .' '.$customer->last_name)

@section('header-actions')
<a href="{{ route('admin.customers.edit', $customer->id) }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-edit sm:mr-2"></i> <span class="hidden sm:inline">Edit Customer</span>
</a>
@endsection

@section('content')

<!-- Customer Info Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Total Bookings</p>
        <h3 class="text-3xl font-bold text-white mt-1">{{ $totalBookings }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Completed</p>
        <h3 class="text-3xl font-bold text-green-400 mt-1">{{ $completedBookings }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Total Spent</p>
        <h3 class="text-3xl font-bold text-gold-500 mt-1">{!! $formatPrice($totalSpent) !!}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Loyalty Points</p>
        <h3 class="text-3xl font-bold text-gold-500 mt-1">{{ $customer->loyalty_points }}/10</h3>
    </div>
</div>

<!-- Customer Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Personal Information -->
    <div class="lg:col-span-2">
        @if($customer->walkin_token)
        <!-- Walk-in Token Badge -->
        <div class="bg-gold-500/10 border-2 border-gold-500/50 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">
                        <i class="fas fa-walking mr-2"></i> Walk-in Customer Token
                    </p>
                    <p class="text-gold-500 font-mono text-2xl font-bold">{{ $customer->walkin_token }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 text-xs">Give this to customer</p>
                    <p class="text-gray-400 text-xs">for next visit</p>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Personal Information</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">First Name</p>
                    <p class="text-white font-medium">{{ $customer->first_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Last Name</p>
                    <p class="text-white font-medium">{{ $customer->last_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Email</p>
                    <p class="text-white font-medium">{{ $customer->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Phone Number</p>
                    <p class="text-white font-medium">{{ $customer->phone_no }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Customer Type</p>
                    <p class="text-white font-medium">
                        @if($customer->is_guest)
                        <span class="badge badge-new">Guest</span>
                        @else
                        <span class="badge badge-confirmed">Registered</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Member Since</p>
                    <p class="text-white font-medium">{{ $customer->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6 mt-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Recent Bookings</h3>
            
            <div class="space-y-4">
                @forelse($recentBookings as $booking)
                <div class="flex items-center justify-between p-4 bg-dark-800 rounded-lg border border-white/5">
                    <div class="flex-1">
                        <p class="text-white font-medium">
                            @foreach($booking->services as $service)
                                {{ $service->title }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $booking->appointment_date->format('M d, Y') }} at {{ $booking->appointment_time }}
                        </p>
                    </div>
                    <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                </div>
                @empty
                <p class="text-gray-400 text-center py-8">No bookings yet</p>
                @endforelse
            </div>

            @if($recentBookings->count() > 0)
            <a href="{{ route('admin.customers.bookings', $customer->id) }}" class="block text-center text-gold-500 hover:text-gold-400 mt-4">
                View All Bookings <i class="fas fa-arrow-right ml-1"></i>
            </a>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Membership Status -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Membership Status</h3>
            
            @if($activeMembership)
            <div class="bg-gold-500/10 border border-gold-500/30 rounded-lg p-4">
                <p class="text-gold-500 font-bold text-lg">{{ $activeMembership->membership->name }}</p>
                <p class="text-gray-400 text-sm mt-2">
                    Expires: {{ $activeMembership->end_date->format('M d, Y') }}
                </p>
                <p class="text-gray-400 text-sm">
                    Free Services: {{ $activeMembership->remaining_free_services }}
                </p>
            </div>
            @else
            <p class="text-gray-400 text-sm">No active membership</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Quick Actions</h3>
            
            <div class="space-y-2">
                <a href="{{ route('admin.customers.bookings', $customer->id) }}" 
                   class="block w-full bg-dark-800 hover:bg-dark-700 text-white py-2 px-4 rounded text-sm text-center transition-colors">
                    <i class="fas fa-calendar-alt mr-2"></i> View Booking History
                </a>
                <a href="{{ route('admin.customers.transactions', $customer->id) }}" 
                   class="block w-full bg-dark-800 hover:bg-dark-700 text-white py-2 px-4 rounded text-sm text-center transition-colors">
                    <i class="fas fa-dollar-sign mr-2"></i> View Transactions
                </a>
                <a href="{{ route('admin.customers.loyalty', $customer->id) }}" 
                   class="block w-full bg-dark-800 hover:bg-dark-700 text-white py-2 px-4 rounded text-sm text-center transition-colors">
                    <i class="fas fa-star mr-2"></i> Manage Loyalty Points
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
