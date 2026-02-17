@extends('layouts.admin')

@section('title', 'Booking Details | Admin Panel')
@section('page-title', 'Booking #' . $booking->id)

@section('header-actions')
<a href="{{ route('admin.bookings.edit', $booking->id) }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-edit sm:mr-2"></i> <span class="hidden sm:inline">Edit Booking</span>
</a>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Booking Information -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Booking Information</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Customer</p>
                    <p class="text-white font-medium">
                        <a href="{{ route('admin.customers.show', $booking->user->id) }}" class="text-gold-500 hover:text-gold-400">
                            {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Email</p>
                    <p class="text-white font-medium">{{ $booking->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Phone</p>
                    <p class="text-white font-medium">{{ $booking->user->phone_no }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Barber</p>
                    <p class="text-white font-medium">
                        @if($booking->barber)
                            {{ $booking->barber->first_name }} {{ $booking->barber->last_name }}
                        @else
                            <span class="text-gray-500">Any Available</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Services</h3>
            
            <div class="space-y-3">
                @foreach($booking->services as $service)
                <div class="flex items-center justify-between p-4 bg-dark-800 rounded-lg border border-white/5">
                    <div class="flex items-center gap-3">
                        <i class="fas {{ $service->icon }} text-gold-500 text-xl"></i>
                        <div>
                            <p class="text-white font-medium">{{ $service->title }}</p>
                            <p class="text-gray-400 text-sm">{{ $service->duration_minutes }} minutes</p>
                        </div>
                    </div>
                    <p class="text-gold-500 font-bold text-lg">{!! $formatPrice($service->price) !!}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-xl text-white mb-6 pb-4 border-b border-white/10">Appointment Details</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Date</p>
                    <p class="text-white font-medium text-lg">{{ $booking->appointment_date->format('l, F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Time</p>
                    <p class="text-white font-medium text-lg">{{ $booking->appointment_time }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Status</p>
                    <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Payment Status</p>
                    <span class="badge badge-{{ $booking->payment_status }}">{{ ucfirst($booking->payment_status) }}</span>
                </div>
                @if($booking->special_requests)
                <div class="col-span-2">
                    <p class="text-gray-400 text-sm mb-1">Special Requests</p>
                    <p class="text-white">{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Actions -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Update Status & Payment</h3>
            
            <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Status Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Booking Status *</label>
                    <select name="status" id="bookingStatus" class="input-dark">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Payment Tracking Section -->
                <div id="paymentSection" class="space-y-4 pb-4 mb-4 border-b border-white/10">
                    <p class="text-gray-400 text-sm mb-3"><i class="fas fa-info-circle mr-1"></i> Add payment details for tracking</p>
                    
                    <!-- Transaction Reference -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            Transaction Reference
                            <span class="text-xs text-gray-500">(For payment tracking)</span>
                        </label>
                        <input type="text" name="transaction_reference" 
                               class="input-dark" 
                               placeholder="e.g., TXN123456789">
                        <p class="text-xs text-gray-500 mt-1">Enter the transaction code received after payment</p>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Payment Method</label>
                        <select name="payment_method" class="input-dark">
                            <option value="">Select method (optional)</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="online">Online Transfer</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Payment Proof Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            Payment Proof Image
                            <span class="text-xs text-gray-500">(Optional)</span>
                        </label>
                        <input type="file" name="payment_proof" accept="image/*"
                               class="block w-full text-sm text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-gold-500 file:text-dark-900
                                      hover:file:bg-gold-600
                                      file:cursor-pointer
                                      border border-white/10 rounded bg-dark-800 p-2">
                        <p class="text-xs text-gray-500 mt-1">Upload receipt or payment screenshot (Max 5MB)</p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Payment Notes</label>
                        <textarea name="payment_notes" rows="2" class="input-dark" 
                                  placeholder="Additional payment information..."></textarea>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-2 px-4 rounded transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Status
                </button>
            </form>
        </div>

        <!-- Payment Information -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Payment Information</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-400">Subtotal:</span>
                    <span class="text-white font-semibold">{!! $formatPrice($booking->total_price) !!}</span>
                </div>
                @if($booking->discount_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-400">Discount:</span>
                    <span class="text-green-400 font-semibold">-{!! $formatPrice($booking->discount_amount) !!}</span>
                </div>
                @if($booking->discount_reason)
                <div class="text-xs text-gray-500 -mt-2">
                    <i class="fas fa-tag mr-1"></i> {{ $booking->discount_reason }}
                </div>
                @endif
                @endif
                <div class="flex justify-between pt-3 border-t border-white/10">
                    <span class="text-gray-400 font-medium">Total Amount:</span>
                    <span class="text-gold-500 font-bold text-xl">{!! $formatPrice($booking->total_price - $booking->discount_amount) !!}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Payment Status:</span>
                    <span class="badge badge-{{ $booking->payment_status }}">{{ ucfirst($booking->payment_status) }}</span>
                </div>
            </div>

            @if($booking->transactions->count() > 0)
            <div class="mt-4 pt-4 border-t border-white/10">
                <p class="text-gray-400 text-sm mb-2">Transactions:</p>
                @foreach($booking->transactions as $transaction)
                <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                   class="block text-blue-400 hover:text-blue-300 text-sm">
                    Transaction #{{ $transaction->id }} - {!! $formatPrice($transaction->amount) !!}
                </a>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="font-serif text-lg text-white mb-4">Quick Actions</h3>
            
            <div class="space-y-2">
                <a href="{{ route('admin.bookings.edit', $booking->id) }}" 
                   class="block w-full bg-dark-800 hover:bg-dark-700 text-white py-2 px-4 rounded text-sm text-center transition-colors">
                    <i class="fas fa-edit mr-2"></i> Edit Booking
                </a>
                
                <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this booking?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="block w-full bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 py-2 px-4 rounded text-sm text-center transition-colors">
                        <i class="fas fa-trash mr-2"></i> Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
