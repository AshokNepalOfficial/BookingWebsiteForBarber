@extends('layouts.admin')

@section('title', 'Bookings Management | Admin Panel')

@section('page-title', 'Booking Management')

@section('header-actions')
<a href="{{ route('admin.bookings.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">New Booking</span>
</a>
@endsection

@section('content')

<!-- Filter Tabs -->
<div class="mb-6 flex gap-2 overflow-x-auto pb-2">
    <a href="{{ route('admin.bookings.index') }}" 
       class="px-4 py-2 rounded {{ !request('status') ? 'bg-gold-500 text-dark-900' : 'bg-dark-900 text-gray-400' }} hover:bg-gold-500 hover:text-dark-900 transition-colors whitespace-nowrap">
        All
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-gold-500 text-dark-900' : 'bg-dark-900 text-gray-400' }} hover:bg-gold-500 hover:text-dark-900 transition-colors whitespace-nowrap">
        Pending
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'confirmed' ? 'bg-gold-500 text-dark-900' : 'bg-dark-900 text-gray-400' }} hover:bg-gold-500 hover:text-dark-900 transition-colors whitespace-nowrap">
        Confirmed
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'completed' ? 'bg-gold-500 text-dark-900' : 'bg-dark-900 text-gray-400' }} hover:bg-gold-500 hover:text-dark-900 transition-colors whitespace-nowrap">
        Completed
    </a>
    <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'cancelled' ? 'bg-gold-500 text-dark-900' : 'bg-dark-900 text-gray-400' }} hover:bg-gold-500 hover:text-dark-900 transition-colors whitespace-nowrap">
        Cancelled
    </a>
</div>

<!-- Bookings Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[1000px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Client</th>
                    <th class="px-6 py-3">Service</th>
                    <th class="px-6 py-3">Date & Time</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Payment</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4">#{{ $booking->id }}</td>
                    <td class="px-6 py-4 font-medium text-white">
                        {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                        @if($booking->user->walkin_token)
                        <br><span class="text-xs text-gold-500 font-mono">
                            <i class="fas fa-ticket-alt mr-1"></i>{{ $booking->user->walkin_token }}
                        </span>
                        @elseif($booking->user->is_guest)
                        <span class="ml-2 text-xs bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded">Guest</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @foreach($booking->services as $service)
                            <div>{{ $service->title }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">
                        {{ $booking->appointment_date->format('M d, Y') }}<br>
                        <span class="text-xs text-gray-500">{{ $booking->appointment_time }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-{{ $booking->payment_status }}">{{ ucfirst($booking->payment_status) }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-400 hover:text-blue-300" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-gold-500 hover:text-gold-400" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($booking->status === 'pending')
                            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="text-green-400 hover:text-green-300" title="Confirm">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            @if($booking->status === 'confirmed')
                            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="text-blue-400 hover:text-blue-300" title="Mark Complete">
                                    <i class="fas fa-check-double"></i>
                                </button>
                            </form>
                            @endif
                            @if($canAccess('bookings'))
                            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-4 block"></i>
                        <p>No bookings found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($bookings->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $bookings->links() }}
    </div>
</div>
@endif

@endsection
