@extends('layouts.receptionist')

@section('title', 'Dashboard | Receptionist Panel')

@section('page-title', 'Receptionist Dashboard')

@section('header-actions')
<a href="{{ route('receptionist.bookings.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">New Booking</span>
</a>
@endsection

@section('content')

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Today's Bookings</p>
        <h3 class="text-2xl font-bold text-white mt-1">{{ $todayBookings }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
         <p class="text-gray-400 text-xs uppercase">Pending Requests</p>
         <h3 class="text-2xl font-bold text-gold-500 mt-1">{{ $pendingBookings }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Confirmed Today</p>
        <h3 class="text-2xl font-bold text-green-400 mt-1">{{ $confirmedTodayBookings }}</h3>
   </div>
</div>

<!-- Today's Schedule -->
<div class="mb-8">
    <h2 class="text-xl font-bold text-white mb-4">Today's Schedule</h2>
    <div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
        @if($todaysBookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                    <tr>
                        <th class="px-6 py-3">Time</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Services</th>
                        <th class="px-6 py-3">Barber</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todaysBookings as $booking)
                    <tr class="border-b border-white/5 hover:bg-dark-800">
                        <td class="px-6 py-4 text-white font-medium">{{ $booking->appointment_time }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-white">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->phone_no }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($booking->services as $service)
                                <span class="text-xs px-2 py-1 bg-blue-500/10 text-blue-400 rounded">{{ $service->title }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $booking->staff ? $booking->staff->first_name : 'Not Assigned' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($booking->status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                            @elseif($booking->status === 'confirmed')
                            <span class="badge badge-confirmed">Confirmed</span>
                            @elseif($booking->status === 'completed')
                            <span class="badge badge-completed">Completed</span>
                            @else
                            <span class="badge badge-cancelled">{{ $booking->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('receptionist.bookings.show', $booking->id) }}" class="text-gold-500 hover:text-gold-400">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-calendar-times text-4xl mb-3"></i>
            <p>No bookings scheduled for today</p>
        </div>
        @endif
    </div>
</div>

<!-- Available Barbers -->
<div class="mb-8">
    <h2 class="text-xl font-bold text-white mb-4">Available Barbers</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($availableBarbers as $barber)
        <div class="bg-dark-900 rounded-lg border border-white/5 p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gold-500 rounded-full flex items-center justify-center text-dark-900 font-bold">
                    {{ substr($barber->first_name, 0, 1) }}{{ substr($barber->last_name, 0, 1) }}
                </div>
                <div>
                    <p class="text-white font-medium">{{ $barber->first_name }} {{ $barber->last_name }}</p>
                    <p class="text-xs text-gray-500">{{ $barber->position ?? 'Barber' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Recent Bookings -->
<div>
    <h2 class="text-xl font-bold text-white mb-4">Recent Bookings</h2>
    <div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
        @if($recentBookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Date & Time</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr class="border-b border-white/5 hover:bg-dark-800">
                        <td class="px-6 py-4 text-white">#{{ $booking->id }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-white">{{ $booking->appointment_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->appointment_time }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</td>
                        <td class="px-6 py-4">
                            @if($booking->status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                            @elseif($booking->status === 'confirmed')
                            <span class="badge badge-confirmed">Confirmed</span>
                            @elseif($booking->status === 'completed')
                            <span class="badge badge-completed">Completed</span>
                            @else
                            <span class="badge badge-cancelled">{{ $booking->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('receptionist.bookings.show', $booking->id) }}" class="text-gold-500 hover:text-gold-400">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center text-gray-500">
            <p>No recent bookings</p>
        </div>
        @endif
    </div>
</div>

@endsection
