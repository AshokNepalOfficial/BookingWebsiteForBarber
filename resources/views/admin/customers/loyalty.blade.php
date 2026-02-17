@extends('layouts.admin')
@section('title', 'Loyalty Points - ' . $customer->first_name . ' ' . $customer->last_name)
@section('page-title', 'Loyalty Points Management')

@section('content')

<!-- Customer Info Card -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gold-500/20 flex items-center justify-center">
                <i class="fas fa-star text-gold-500 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-serif text-white mb-1">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                <p class="text-gray-400">Current Points: <span class="text-gold-500 font-bold text-xl">{{ $customer->loyalty_points }}</span></p>
            </div>
        </div>
        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Adjust Points Form -->
    <div class="lg:col-span-1">
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Adjust Loyalty Points</h3>
            
            <form action="{{ route('admin.customers.adjustLoyalty', $customer->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Points *</label>
                    <input type="number" name="points" required class="input-dark" placeholder="Enter points">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Action *</label>
                    <select name="action" required class="input-dark">
                        <option value="add">Add Points</option>
                        <option value="subtract">Subtract Points</option>
                        <option value="set">Set Points</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Reason *</label>
                    <textarea name="reason" required rows="3" class="input-dark" placeholder="Reason for adjustment..."></textarea>
                </div>

                <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-2 px-4 rounded transition-colors">
                    <i class="fas fa-save mr-2"></i> Adjust Points
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Bookings and History -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Adjustment History -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Adjustment History</h3>
            
            <div class="space-y-3">
                @forelse($adjustments as $adjustment)
                <div class="bg-dark-800 p-4 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="text-white font-medium">
                                @if($adjustment->action === 'add')
                                    <i class="fas fa-arrow-up text-green-400 mr-1"></i> Added
                                @elseif($adjustment->action === 'subtract')
                                    <i class="fas fa-arrow-down text-red-400 mr-1"></i> Subtracted
                                @else
                                    <i class="fas fa-cog text-blue-400 mr-1"></i> Set
                                @endif
                                {{ abs($adjustment->points_adjusted) }} points
                            </span>
                            <span class="text-gray-500 text-sm ml-2">{{ $adjustment->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">{{ $adjustment->points_before }} → {{ $adjustment->points_after }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400 mb-2">{{ $adjustment->reason }}</div>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-user-shield mr-1"></i> By: {{ $adjustment->admin->first_name }} {{ $adjustment->admin->last_name }}
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-history text-4xl text-gray-600 mb-3 block"></i>
                    <p class="text-gray-500">No adjustment history yet</p>
                </div>
                @endforelse
            </div>

            @if($adjustments->hasPages())
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                @include('partials.per-page-selector')
                <div class="flex-1 flex justify-center sm:justify-end">
                    {{ $adjustments->links() }}
                </div>
            </div>
            @endif
        </div>

        <!-- Completed Bookings -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Completed Bookings</h3>
            
            <div class="space-y-3">
                @forelse($completedBookings as $booking)
                <div class="bg-dark-800 p-4 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="text-white font-medium">Booking #{{ $booking->id }}</span>
                            <span class="text-gray-500 text-sm ml-2">{{ $booking->appointment_date->format('M d, Y') }}</span>
                        </div>
                        <span class="text-gold-500 font-bold">+1 Point</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($booking->services as $service)
                        <span class="text-xs px-2 py-1 rounded bg-dark-900 text-gray-400">
                            <i class="fas fa-cut mr-1"></i> {{ $service->title }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-calendar-check text-4xl text-gray-600 mb-3 block"></i>
                    <p class="text-gray-500">No completed bookings yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Loyalty Info -->
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
            <p class="text-blue-400 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Customers earn 1 loyalty point for each completed booking. After 10 points, they receive a reward and points reset.
            </p>
        </div>
    </div>
</div>

@endsection
