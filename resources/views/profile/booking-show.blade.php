@extends('layouts.frontend_custom_2')

@section('title', 'Booking Details | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

@section('content')
<div class="pt-32 pb-20 bg-dark-800 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
             @php
             $user = Auth::user();
             @endphp
            <div class="lg:w-1/4">
                <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden sticky top-32">
                    <div class="p-6 text-center border-b border-white/5">
                        <div class="w-20 h-20 bg-gold-500 rounded-full mx-auto flex items-center justify-center text-dark-900 text-3xl font-bold mb-4">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-serif text-white">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>
                    <nav class="p-4 space-y-1">
                        <a href="{{ route('profile.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-th-large w-5"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.bookings') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gold-500 text-dark-900 font-bold transition-all">
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
                    </nav>
                </div>
            </div>
                        
            <div class="lg:w-3/4">
                <div class="mb-8 flex items-center justify-between">
                    <h1 class="text-3xl font-serif text-white">Booking Details</h1>
                    <a href="{{ route('profile.bookings') }}" class="text-gold-500 hover:text-gold-400 flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back to History
                    </a>
                </div>

                <div class="bg-dark-900 rounded-2xl border border-white/5 overflow-hidden shadow-2xl">
                    <!-- Status Header -->
                    <div class="px-8 py-6 bg-dark-700 border-b border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <span class="text-gray-500 text-xs uppercase tracking-widest font-bold">Booking ID</span>
                            <h3 class="text-white font-serif text-xl">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-500 text-xs uppercase tracking-widest font-bold">Status:</span>
                            <span class="px-4 py-1.5 bg-gold-500/10 text-gold-500 rounded-full text-xs font-bold uppercase tracking-wider border border-gold-500/20">
                                {{ $booking->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Booking Info -->
                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Date & Time -->
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-gold-500/10 rounded-lg flex items-center justify-center text-gold-500">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider font-bold">Date</p>
                                        <p class="text-white font-medium">{{ $booking->appointment_date->format('l, F d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-gold-500/10 rounded-lg flex items-center justify-center text-gold-500">
                                        <i class="far fa-clock"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider font-bold">Time</p>
                                        <p class="text-white font-medium">{{ $booking->appointment_time }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Barber & Location -->
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-gold-500/10 rounded-lg flex items-center justify-center text-gold-500">
                                        <i class="far fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider font-bold">Barber / Stylist</p>
                                        <p class="text-white font-medium">{{ $booking->staff ? $booking->staff->first_name . ' ' . $booking->staff->last_name : 'Any Available Barber' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-gold-500/10 rounded-lg flex items-center justify-center text-gold-500">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs uppercase tracking-wider font-bold">Location</p>
                                        <p class="text-white font-medium">{{ $setting('site_address', 'Lalitpur, Nepal') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services List -->
                        <div class="pt-8 border-t border-white/5">
                            <h4 class="text-white font-serif text-lg mb-4">Selected Services</h4>
                            <div class="space-y-3">
                                @php $total = 0; @endphp
                                @foreach($booking->services as $service)
                                <div class="flex justify-between items-center p-4 rounded-xl bg-white/5 border border-white/5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-gold-500/10 flex items-center justify-center text-gold-500 text-sm">
                                            <i class="fas {{ $service->icon ?? 'fa-cut' }}"></i>
                                        </div>
                                        <div>
                                            <p class="text-white text-sm font-medium">{{ $service->title }}</p>
                                            <p class="text-gray-500 text-[10px]">{{ $service->duration_minutes }} min</p>
                                        </div>
                                    </div>
                                    <p class="text-white font-bold">{!! $formatPrice($service->price) !!}</p>
                                </div>
                                @php $total += $service->price; @endphp
                                @endforeach
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="pt-8 mt-8 border-t border-white/5">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-white font-medium">{!! $formatPrice($total) !!}</span>
                            </div>
                            @if($booking->discount_amount > 0)
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-green-500">Membership Discount</span>
                                <span class="text-green-500">-{!! $formatPrice($booking->discount_amount) !!}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center pt-4 border-t border-white/10">
                                <span class="text-xl font-serif text-white">Total Amount</span>
                                <span class="text-2xl font-bold text-gold-500">{!! $formatPrice($total - $booking->discount_amount) !!}</span>
                            </div>
                        </div>

                        @if($booking->special_request)
                        <div class="pt-8 mt-8 border-t border-white/5">
                            <p class="text-gray-500 text-xs uppercase tracking-wider font-bold mb-2">Special Request / Notes</p>
                            <div class="p-4 rounded-xl bg-white/5 border border-white/5 text-gray-300 text-sm italic">
                                "{{ $booking->special_request }}"
                            </div>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="pt-10 flex flex-col sm:flex-row gap-4">
                            @if($booking->isEditableByCustomer())
                            <a href="{{ route('booking.edit', $booking->id) }}" class="flex-1 bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-4 rounded-xl text-center transition-all shadow-lg shadow-gold-500/20">
                                <i class="fas fa-edit mr-2"></i> Reschedule Booking
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="p-6 bg-dark-700 text-center border-t border-white/5">
                        <p class="text-gray-500 text-xs italic">
                            If you need to cancel or have any questions, please contact us at {{ $setting('site_phone', '+977-9847494308') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
