@extends('layouts.frontend_custom_2')
@section('title', 'My Dashboard | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

@section('content')
<div class="pt-32 pb-20 bg-dark-800 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden sticky top-32">
                    <div class="p-6 text-center border-b border-white/5">
                        <div class="w-20 h-20 bg-gold-500 rounded-full mx-auto flex items-center justify-center text-dark-900 text-3xl font-bold mb-4">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-serif text-white">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-gold-500/10 text-gold-500 text-xs rounded-full uppercase tracking-widest font-bold">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <nav class="p-4 space-y-1">
                        <a href="{{ route('profile.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gold-500 text-dark-900 font-bold transition-all">
                            <i class="fas fa-th-large w-5"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.bookings') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="far fa-calendar-alt w-5"></i> My Bookings
                        </a>
                        <a href="{{ route('profile.membership') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-crown w-5"></i> Membership
                        </a>
                        <a href="{{ route('profile.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-history w-5"></i> Transactions
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-user-edit w-5"></i> Edit Profile
                        </a>
                        <div class="pt-4 mt-4 border-t border-white/5">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-400/10 transition-all w-full text-left">
                                    <i class="fas fa-sign-out-alt w-5"></i> Logout
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Welcome Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-serif text-white mb-2">Welcome back, {{ $user->first_name }}!</h1>
                    <p class="text-gray-400">Manage your appointments, memberships, and profile details.</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-dark-900 p-6 rounded-xl border border-white/5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center text-blue-500 text-xl">
                            <i class="far fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Total Bookings</p>
                            <h3 class="text-2xl font-bold text-white">{{ $stats['total_bookings'] }}</h3>
                        </div>
                    </div>
                    <div class="bg-dark-900 p-6 rounded-xl border border-white/5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-gold-500/10 rounded-lg flex items-center justify-center text-gold-500 text-xl">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Loyalty Points</p>
                            <h3 class="text-2xl font-bold text-white">{{ $stats['loyalty_points'] }}</h3>
                        </div>
                    </div>
                    <div class="bg-dark-900 p-6 rounded-xl border border-white/5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center text-green-500 text-xl">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Sessions Done</p>
                            <h3 class="text-2xl font-bold text-white">{{ $stats['completed_sessions'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Upcoming Appointments -->
                    <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden">
                        <div class="p-6 border-b border-white/5 flex justify-between items-center">
                            <h3 class="font-serif text-lg text-white">Upcoming Appointments</h3>
                            <a href="{{ route('profile.bookings') }}" class="text-gold-500 text-sm hover:text-gold-400">View All</a>
                        </div>
                        <div class="p-6">
                            @forelse($upcomingBookings as $booking)
                            <div class="flex items-center gap-4 p-4 rounded-lg bg-white/5 mb-4 last:mb-0">
                                <div class="w-14 h-14 bg-dark-800 rounded-lg flex flex-col items-center justify-center text-center border border-white/10">
                                    <span class="text-xs text-gold-500 uppercase font-bold">{{ $booking->appointment_date->format('M') }}</span>
                                    <span class="text-xl font-bold text-white leading-none">{{ $booking->appointment_date->format('d') }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-white font-medium">
                                        @foreach($booking->services as $service)
                                            {{ $service->title }}@if(!$loop->last), @endif
                                        @endforeach
                                    </h4>
                                    <p class="text-gray-400 text-xs mt-1">
                                        <i class="far fa-clock mr-1"></i> {{ $booking->appointment_time }}
                                        @if($booking->barber)
                                        <span class="mx-2">|</span>
                                        <i class="far fa-user mr-1"></i> {{ $booking->barber->first_name }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 bg-gold-500/10 text-gold-500 text-[10px] rounded uppercase font-bold">
                                        {{ $booking->status }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('profile.bookings.show', $booking->id) }}" class="text-[10px] text-gray-500 hover:text-gold-500 underline transition-all">View Details</a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center text-gray-600 mx-auto mb-4">
                                    <i class="far fa-calendar-times text-2xl"></i>
                                </div>
                                <p class="text-gray-500 italic">No upcoming appointments</p>
                                <a href="{{ config('app.url') }}#openBookNowForm" class="inline-block mt-4 text-gold-500 hover:underline">Book Now</a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Membership & Loyalty -->
                    <div class="space-y-8">
                        <!-- Active Membership -->
                        <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden">
                            <div class="p-6 border-b border-white/5">
                                <h3 class="font-serif text-lg text-white">Active Membership</h3>
                            </div>
                            <div class="p-6">
                                @if($activeMembership)
                                <div class="bg-gradient-to-br from-gold-500/20 to-transparent p-6 rounded-xl border border-gold-500/30">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-gold-500 font-serif text-xl">{{ $activeMembership->membership->name }}</h4>
                                            <p class="text-gray-400 text-xs">Expires on {{ $activeMembership->end_date->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-gold-500 text-2xl">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 mt-4">
                                        <div class="h-1.5 flex-1 bg-white/10 rounded-full overflow-hidden">
                                            @php
                                                $totalDays = $activeMembership->start_date->diffInDays($activeMembership->end_date);
                                                $passedDays = $activeMembership->start_date->diffInDays(now());
                                                $percentage = ($totalDays > 0) ? min(100, round(($passedDays / $totalDays) * 100)) : 0;
                                            @endphp
                                            <div class="h-full bg-gold-500" style="width: {{ 100 - $percentage }}%"></div>
                                        </div>
                                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">{{ 100 - $percentage }}% Left</span>
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-6">
                                    <p class="text-gray-500 mb-4">You don't have an active membership.</p>
                                    <a href="{{ route('profile.membership') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 px-6 py-2 rounded-lg font-bold transition-all text-sm">
                                        View Plans
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Loyalty Progress -->
                        <div class="bg-dark-900 rounded-xl border border-white/5 p-6">
                            <h3 class="font-serif text-lg text-white mb-4">Loyalty Reward Progress</h3>
                            <div class="flex flex-wrap gap-2 justify-between mb-2">
                                @for($i = 1; $i <= 10; $i++)
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border {{ $user->loyalty_points >= $i ? 'bg-gold-500 border-gold-500 text-dark-900' : 'border-white/10 text-gray-600' }}">
                                    @if($user->loyalty_points >= $i)
                                    <i class="fas fa-check text-[10px]"></i>
                                    @else
                                    <span class="text-[10px]">{{ $i }}</span>
                                    @endif
                                </div>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-500 text-center mt-4 italic">
                                Reach 10 points to get 1 session free! Currently you have {{ $user->loyalty_points }} points.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
