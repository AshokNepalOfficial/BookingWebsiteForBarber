@extends('layouts.frontend_custom_2')
@section('title', 'My Bookings | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

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

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-serif text-white">My Bookings</h1>
                    <a href="{{ config('app.url') }}#openBookNowForm" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded-lg transition-all">
                        Book Now
                    </a>

                </div>

                <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-dark-700">
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold border-b border-white/5">Service</th>
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold border-b border-white/5">Date & Time</th>
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold border-b border-white/5">Barber</th>
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold border-b border-white/5">Status</th>
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold border-b border-white/5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($bookings as $booking)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-white">
                                            @foreach($booking->services as $service)
                                                {{ $service->title }}@if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                        <div class="text-[10px] text-gray-500 mt-0.5">ID: #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">
                                        {{ $booking->appointment_date->format('M d, Y') }}<br>
                                        <span class="text-xs text-gray-500">{{ $booking->appointment_time }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">
                                        @if($booking->barber)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 bg-gold-500/10 rounded-full flex items-center justify-center text-gold-500 text-[10px]">
                                                    {{ substr($booking->barber->first_name, 0, 1) }}
                                                </div>
                                                {{ $booking->barber->first_name }}
                                            </div>
                                        @else
                                            <span class="text-gray-600">Any available</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-gold-500/10 text-gold-500 text-[10px] rounded uppercase font-bold">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('profile.bookings.show', $booking->id) }}" class="text-gray-400 hover:text-gold-500 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($booking->isEditableByCustomer())
                                        <a href="{{ route('booking.edit', $booking->id) }}" class="text-gray-400 hover:text-blue-500 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center text-gray-600 mx-auto mb-4">
                                            <i class="far fa-calendar-times text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 italic">You haven't made any bookings yet.</p>
                                        <a href="{{ route('booking.create') }}" class="inline-block mt-4 text-gold-500 hover:underline">Start Booking</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
