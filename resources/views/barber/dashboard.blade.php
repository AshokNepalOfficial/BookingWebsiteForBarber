@extends('layouts.barber')

@section('title', 'Dashboard | Barber Panel')

@section('page-title', 'My Schedule')

@section('content')

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Today's Appointments</p>
        <h3 class="text-2xl font-bold text-white mt-1">{{ $myTodayBookings }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
         <p class="text-gray-400 text-xs uppercase">Upcoming</p>
         <h3 class="text-2xl font-bold text-gold-500 mt-1">{{ $myUpcomingBookings }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Completed Total</p>
        <h3 class="text-2xl font-bold text-green-400 mt-1">{{ $myCompletedBookings }}</h3>
   </div>
</div>

<!-- Today's Schedule -->
<div class="mb-8">
    <h2 class="text-xl font-bold text-white mb-4">Today's Schedule</h2>
    <div class="bg-dark-900 rounded-lg border border-white/5">
        @if($todaysSchedule->count() > 0)
        <div class="space-y-3 p-4">
            @foreach($todaysSchedule as $booking)
            <div class="bg-dark-800 rounded-lg p-4 border border-white/5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gold-500 rounded-full flex items-center justify-center text-dark-900 font-bold">
                            <i class="far fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ $booking->appointment_time }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->appointment_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @if($booking->status === 'confirmed')
                    <span class="badge badge-confirmed">Confirmed</span>
                    @elseif($booking->status === 'completed')
                    <span class="badge badge-completed">Completed</span>
                    @else
                    <span class="badge badge-pending">{{ $booking->status }}</span>
                    @endif
                </div>
                <div class="pl-13">
                    <p class="text-white mb-1">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
                    <p class="text-xs text-gray-500 mb-2">{{ $booking->user->phone_no }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($booking->services as $service)
                        <span class="text-xs px-2 py-1 bg-blue-500/10 text-blue-400 rounded">{{ $service->title }} ({{ $service->duration_minutes }}min)</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-calendar-times text-4xl mb-3"></i>
            <p>No appointments scheduled for today</p>
        </div>
        @endif
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="mb-8">
    <h2 class="text-xl font-bold text-white mb-4">Upcoming Appointments</h2>
    <div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
        @if($upcomingAppointments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                    <tr>
                        <th class="px-6 py-3">Date & Time</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Services</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingAppointments as $booking)
                    <tr class="border-b border-white/5 hover:bg-dark-800">
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-white">{{ $booking->appointment_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->appointment_time }}</p>
                            </div>
                        </td>
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
                            <span class="badge badge-confirmed">Confirmed</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('barber.bookings.show', $booking->id) }}" class="text-gold-500 hover:text-gold-400">
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
            <p>No upcoming appointments</p>
        </div>
        @endif
    </div>
</div>

<!-- Recent Completed -->
<div>
    <h2 class="text-xl font-bold text-white mb-4">Recently Completed</h2>
    <div class="bg-dark-900 rounded-lg border border-white/5">
        @if($recentCompleted->count() > 0)
        <div class="space-y-2 p-4">
            @foreach($recentCompleted as $booking)
            <div class="flex items-center justify-between p-3 bg-dark-800 rounded-lg border border-white/5">
                <div>
                    <p class="text-white">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->appointment_date->format('M d, Y') }} at {{ $booking->appointment_time }}</p>
                </div>
                <span class="badge badge-completed">Completed</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-gray-500">
            <p>No completed appointments yet</p>
        </div>
        @endif
    </div>
</div>

@endsection
