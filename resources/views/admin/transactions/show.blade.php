@extends('layouts.admin')

@section('title', 'Transaction Details | Admin Panel')
@section('page-title', 'Transaction #' . $transaction->id)
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Transaction Information -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Transaction Information</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Transaction ID</p>
                    <p class="text-white font-medium">#{{ $transaction->id }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Date</p>
                    <p class="text-white font-medium">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Customer</p>
                    <p class="text-white font-medium">
                        <a href="{{ route('admin.customers.show', $transaction->user->id) }}" class="text-gold-500 hover:text-gold-400">
                            {{ $transaction->user->first_name }} {{ $transaction->user->last_name }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Email</p>
                    <p class="text-white font-medium">{{ $transaction->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Transaction Type</p>
                    <p class="text-white font-medium">{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Payment Method</p>
                    <p class="text-white font-medium">{{ ucfirst($transaction->payment_method) }}</p>
                </div>
                @if($transaction->transaction_reference)
                <div class="col-span-2">
                    <p class="text-gray-400 text-sm mb-1">Transaction Reference</p>
                    <p class="text-white font-medium">{{ $transaction->transaction_reference }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Related Booking -->
        @if($transaction->booking)
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Related Booking</h3>
            
            <div class="flex items-center justify-between p-4 bg-dark-800 rounded-lg border border-white/5">
                <div class="flex-1">
                    <p class="text-white font-medium">
                        Booking #{{ $transaction->booking->id }}
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                        @foreach($transaction->booking->services as $service)
                            {{ $service->title }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                    <p class="text-gray-400 text-sm">
                        {{ $transaction->booking->appointment_date->format('M d, Y') }} at {{ $transaction->booking->appointment_time }}
                    </p>
                </div>
                <a href="{{ route('admin.bookings.show', $transaction->booking->id) }}" 
                   class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-4 py-2 rounded text-sm transition-colors">
                    View Booking
                </a>
            </div>
        </div>
        @endif

        <!-- Payment Proof -->
        @if($transaction->payment_proof_image)
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Payment Proof</h3>
            
            <div class="bg-dark-800 p-4 rounded-lg">
                <img src="{{ asset('storage/' . $transaction->payment_proof_image) }}" 
                     alt="Payment Proof" 
                     class="max-w-full h-auto rounded-lg border border-white/10">
                <p class="text-gray-400 text-sm mt-2">Uploaded on {{ $transaction->updated_at->format('M d, Y g:i A') }}</p>
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($transaction->notes)
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-4 pb-4 border-b border-white/10">Notes</h3>
            <p class="text-gray-300">{{ $transaction->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Amount Card -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <p class="text-gray-400 text-sm mb-2">Transaction Amount</p>
            <p class="text-gold-500 font-bold text-4xl">{!! $formatPrice($transaction->amount) !!}</p>
        </div>

        <!-- Status Card -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Verification Status</h3>
            
            <div class="mb-4">
                <span class="badge badge-{{ $transaction->verification_status }}">
                    {{ ucfirst($transaction->verification_status) }}
                </span>
            </div>

            @if($transaction->verified_at)
            <div class="text-sm space-y-2">
                <div>
                    <p class="text-gray-400">Verified By:</p>
                    <p class="text-white">
                        @if($transaction->verifier)
                            {{ $transaction->verifier->first_name }} {{ $transaction->verifier->last_name }}
                        @else
                            System
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-400">Verified At:</p>
                    <p class="text-white">{{ $transaction->verified_at->format('M d, Y g:i A') }}</p>
                </div>
            </div>
            @endif

            <!-- Verification Actions -->
            @if($transaction->verification_status == 'pending')
            <form action="{{ route('admin.transactions.verify', $transaction->id) }}" method="POST" class="mt-6 space-y-3">
                @csrf
                
                <textarea name="notes" rows="3" class="input-dark text-sm" placeholder="Add verification notes (optional)"></textarea>
                
                <div class="grid grid-cols-2 gap-2">
                    <button type="submit" name="status" value="verified"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-sm transition-colors">
                        <i class="fas fa-check mr-1"></i> Verify
                    </button>
                    <button type="submit" name="status" value="rejected"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded text-sm transition-colors">
                        <i class="fas fa-times mr-1"></i> Reject
                    </button>
                </div>
            </form>
            @endif
        </div>

        <!-- Offline Payment Badge -->
        @if($transaction->is_offline)
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
            <div class="flex items-center gap-2 text-blue-400">
                <i class="fas fa-money-bill-wave"></i>
                <span class="font-medium">Offline Payment</span>
            </div>
            <p class="text-gray-400 text-xs mt-1">Recorded by staff</p>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Quick Actions</h3>
            
            <div class="space-y-2">
                <a href="{{ route('admin.customers.show', $transaction->user->id) }}" 
                   class="block w-full bg-dark-800 hover:bg-dark-700 text-white py-2 px-4 rounded text-sm text-center transition-colors">
                    <i class="fas fa-user mr-2"></i> View Customer
                </a>
                
                @if($transaction->booking)
                <a href="{{ route('admin.bookings.show', $transaction->booking->id) }}" 
                   class="block w-full bg-dark-800 hover:bg-dark-700 text-white py-2 px-4 rounded text-sm text-center transition-colors">
                    <i class="fas fa-calendar mr-2"></i> View Booking
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
