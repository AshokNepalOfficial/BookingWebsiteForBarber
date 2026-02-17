@extends('layouts.admin')
@section('title', 'Booking History - ' . $customer->first_name . ' ' . $customer->last_name)
@section('page-title', 'Booking History')

@section('content')

<!-- Customer Info Card -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gold-500/20 flex items-center justify-center">
                <i class="fas fa-user text-gold-500 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-serif text-white mb-1">
                    {{ $customer->first_name }} {{ $customer->last_name }}
                    @if($customer->walkin_token)
                    <span class="text-sm font-mono text-gold-500 ml-2">
                        <i class="fas fa-ticket-alt"></i> {{ $customer->walkin_token }}
                    </span>
                    @endif
                </h2>
                <p class="text-gray-400">{{ $customer->email }}</p>
            </div>
        </div>
        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>
</div>

<!-- Bookings Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-800 border-b border-white/5">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Date & Time</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Services</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Payment</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($bookings as $booking)
            <tr class="hover:bg-dark-800/50">
                <td class="px-6 py-4 text-white font-mono">#{{ $booking->id }}</td>
                <td class="px-6 py-4 text-gray-300">
                    <div class="font-medium">{{ \Carbon\Carbon::parse($booking->appointment_date)->format('M d, Y') }}</div>
                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->appointment_time)->format('h:i A') }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="space-y-1">
                        @foreach($booking->services as $service)
                        <div class="text-sm text-gray-300">
                            <i class="fas fa-cut text-gold-500 mr-1"></i> {{ $service->title }}
                        </div>
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4 text-white font-semibold">
                    {!! $formatPrice($booking->total_price ?? 0) !!}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded
                        {{ $booking->status === 'completed' ? 'bg-green-500/20 text-green-400' : '' }}
                        {{ $booking->status === 'confirmed' ? 'bg-blue-500/20 text-blue-400' : '' }}
                        {{ $booking->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                        {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-400' : '' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded
                        {{ $booking->payment_status === 'verified' ? 'bg-green-500/20 text-green-400' : '' }}
                        {{ $booking->payment_status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                        {{ $booking->payment_status === 'failed' ? 'bg-red-500/20 text-red-400' : '' }}">
                        {{ ucfirst($booking->payment_status ?? 'pending') }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                       class="text-blue-400 hover:text-blue-300">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                    No bookings found for this customer.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
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
