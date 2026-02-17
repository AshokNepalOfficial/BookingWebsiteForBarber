@extends('layouts.admin')

@section('title', 'Edit Booking | Admin Panel')
@section('page-title', 'Edit Booking #' . $booking->id)

@section('content')

<div class="max-w-4xl">
    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Customer Information (Read-only) -->
            <div class="bg-dark-800 p-4 rounded-lg">
                <h4 class="text-white font-medium mb-3">Customer Information</h4>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400">Name:</span>
                        <span class="text-white ml-2">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Email:</span>
                        <span class="text-white ml-2">{{ $booking->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Phone:</span>
                        <span class="text-white ml-2">{{ $booking->user->phone_no }}</span>
                    </div>
                </div>
            </div>

            <!-- Services Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-3">Services *</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($services ?? [] as $service)
                    <label class="flex items-center gap-3 p-3 bg-dark-800 rounded-lg border border-white/5 hover:border-gold-500/50 cursor-pointer transition-all">
                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" 
                               {{ $booking->services->contains($service->id) ? 'checked' : '' }}
                               class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                        <div class="flex-1">
                            <p class="text-white font-medium text-sm">{{ $service->title }}</p>
                            <p class="text-gray-400 text-xs">{!! $formatPrice($service->price) !!} - {{ $service->duration_minutes }} min</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('service_ids')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Barber Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Assign Barber (Optional)</label>
                <select name="barber_id" class="input-dark">
                    <option value="">Any Available Barber</option>
                    @foreach($barbers ?? [] as $barber)
                    <option value="{{ $barber->id }}" {{ $booking->barber_id == $barber->id ? 'selected' : '' }}>
                        {{ $barber->first_name }} {{ $barber->last_name }}
                    </option>
                    @endforeach
                </select>
                @error('barber_id')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Appointment Date *</label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date', $booking->appointment_date->format('Y-m-d')) }}" required
                           class="input-dark">
                    @error('appointment_date')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Appointment Time *</label>
                    <input type="time" name="appointment_time" value="{{ old('appointment_time', $booking->appointment_time) }}" required
                           class="input-dark">
                    @error('appointment_time')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status and Payment -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Booking Status *</label>
                    <select name="status" class="input-dark">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Payment Status *</label>
                    <select name="payment_status" class="input-dark">
                        <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ $booking->payment_status == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ $booking->payment_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('payment_status')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Special Requests -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Special Requests</label>
                <textarea name="special_requests" rows="3" class="input-dark" 
                          placeholder="Any special requests or notes...">{{ old('special_requests', $booking->special_requests) }}</textarea>
                @error('special_requests')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Update Booking
            </button>
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
