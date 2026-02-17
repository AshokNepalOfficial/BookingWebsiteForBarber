@extends('layouts.frontend_custom')

@section('title', 'Appointment Success | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

@section('content')
<div class="pt-32 pb-20 bg-dark-800 min-h-screen flex items-center">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-dark-900 rounded-2xl border border-white/5 p-8 md:p-12 text-center shadow-2xl relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gold-500"></div>
            
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center text-green-500 text-4xl mx-auto mb-8 animate-bounce">
                <i class="fas fa-check-circle"></i>
            </div>

            <h1 class="text-3xl md:text-4xl font-serif text-white mb-4">Booking Confirmed!</h1>
            <p class="text-gray-400 mb-8 text-lg">
                Thank you, <span class="text-white font-bold">{{ $booking->user->first_name }}</span>! Your appointment has been successfully scheduled.
            </p>

            <!-- Booking Summary Card -->
            <div class="bg-dark-700 rounded-xl p-6 mb-8 text-left border border-white/5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-1">Date & Time</p>
                        <p class="text-white">{{ $booking->appointment_date->format('l, M d') }} at {{ $booking->appointment_time }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-1">Service</p>
                        <p class="text-white">
                            @foreach($booking->services as $service)
                                {{ $service->title }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-1">Barber</p>
                        <p class="text-white">{{ $booking->barber ? $booking->barber->first_name : 'Any Available' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-1">Booking ID</p>
                        <p class="text-white">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            <p class="text-gray-500 text-sm mb-10 italic">
                A confirmation email has been sent to {{ $booking->user->email }}. Please arrive 5 minutes before your scheduled time.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('profile.dashboard') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-8 py-4 rounded-xl transition-all">
                    Go to My Dashboard
                </a>
                <a href="{{ route('home') }}" class="bg-white/5 hover:bg-white/10 text-white font-bold px-8 py-4 rounded-xl transition-all border border-white/10">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Auto-process queue in background using image beacon -->
<img src="{{ route('queue.process', ['secretKey' => config('app.queue_secret_key')]) }}" 
     style="display:none;" 
     width="1" 
     height="1" 
     alt="">
@endsection
