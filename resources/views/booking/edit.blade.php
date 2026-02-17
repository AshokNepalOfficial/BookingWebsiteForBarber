@extends('layouts.frontend_custom')

@section('title', 'Reschedule Booking | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

@section('content')
<div class="pt-32 pb-20 bg-dark-800 min-h-screen">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-serif text-white">Reschedule Appointment</h1>
            <a href="{{ route('booking.show', $booking->id) }}" class="text-gold-500 hover:text-gold-400 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-dark-900 rounded-2xl border border-white/5 p-8 shadow-2xl">
                    <form action="{{ route('booking.update', $booking->id) }}" method="POST" id="rescheduleForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Date Selection -->
                            <div class="space-y-3">
                                <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Choose New Date</label>
                                <input type="date" name="appointment_date" value="{{ $booking->appointment_date->format('Y-m-d') }}" 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-4 text-white focus:border-gold-500 outline-none transition-all">
                            </div>

                            <!-- Time Selection -->
                            <div class="space-y-3">
                                <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Choose New Time</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @php
                                        $timeSlots = json_decode($setting('booking_time_slots', '[]'), true) ?: ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
                                    @endphp
                                    @foreach($timeSlots as $slot)
                                    <label class="relative group cursor-pointer">
                                        <input type="radio" name="appointment_time" value="{{ $slot }}" class="peer hidden" {{ $booking->appointment_time == $slot ? 'checked' : '' }}>
                                        <div class="bg-dark-800 border border-white/10 rounded-lg py-3 text-center text-sm text-gray-400 peer-checked:bg-gold-500 peer-checked:text-dark-900 peer-checked:border-gold-500 transition-all font-bold">
                                            {{ $slot }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Special Request -->
                            <div class="space-y-3">
                                <label class="text-sm text-gray-400 uppercase tracking-widest font-bold">Special Request (Optional)</label>
                                <textarea name="special_request" rows="4" class="w-full bg-dark-800 border border-white/10 rounded-xl px-4 py-4 text-white focus:border-gold-500 outline-none transition-all resize-none">{{ $booking->special_request }}</textarea>
                            </div>
                        </div>

                        <div class="mt-10">
                            <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-4 rounded-xl transition-all shadow-lg shadow-gold-500/20">
                                Confirm Reschedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Service Summary -->
                <div class="bg-dark-900 rounded-2xl border border-white/5 p-6 shadow-2xl">
                    <h3 class="text-white font-serif text-lg mb-4">Selected Services</h3>
                    <div class="space-y-3">
                        @foreach($booking->services as $service)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/5">
                            <div class="w-8 h-8 rounded bg-gold-500/10 flex items-center justify-center text-gold-500">
                                <i class="fas {{ $service->icon ?? 'fa-cut' }}"></i>
                            </div>
                            <span class="text-gray-300 text-sm">{{ $service->title }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reschedule Policy -->
                <div class="bg-blue-500/5 rounded-2xl border border-blue-500/20 p-6 shadow-2xl">
                    <div class="flex items-center gap-3 text-blue-400 mb-3">
                        <i class="fas fa-info-circle"></i>
                        <h4 class="font-bold text-xs uppercase tracking-widest">Policy</h4>
                    </div>
                    <p class="text-gray-400 text-xs leading-relaxed">
                        You can reschedule your appointment free of charge up to 24 hours before the scheduled time. For immediate changes, please call us directly.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
