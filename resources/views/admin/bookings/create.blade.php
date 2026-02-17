@extends('layouts.admin')

@section('title', 'New Booking | Admin Panel')

@section('page-title', 'Create New Booking')

@section('content')

<div class="max-w-4xl">
    <!-- Booking Type Selector -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <button type="button" onclick="setBookingType('registered')" id="btnRegistered"
                class="booking-type-btn active bg-dark-900 border-2 border-gold-500 text-white p-6 rounded-lg hover:bg-dark-800 transition-all">
            <i class="fas fa-user-check text-4xl text-gold-500 mb-3"></i>
            <h3 class="font-serif text-xl mb-2">Registered Customer</h3>
            <p class="text-gray-400 text-sm">Select from existing customers</p>
        </button>
        
        <button type="button" onclick="setBookingType('walkin')" id="btnWalkin"
                class="booking-type-btn bg-dark-900 border-2 border-white/10 text-white p-6 rounded-lg hover:bg-dark-800 transition-all">
            <i class="fas fa-walking text-4xl text-gold-500 mb-3"></i>
            <h3 class="font-serif text-xl mb-2">Walk-in Customer</h3>
            <p class="text-gray-400 text-sm">Generate token for guest</p>
        </button>
    </div>

    <form action="{{ route('admin.bookings.store') }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf

        <input type="hidden" name="booking_type" id="bookingType" value="registered">

        <div class="space-y-6">
            <!-- REGISTERED CUSTOMER SECTION -->
            <div id="registeredSection">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Select Customer *</label>
                    <select name="user_id" id="userId" class="input-dark">
                        <option value="">Choose a customer...</option>
                        @foreach($customers ?? [] as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->first_name }} {{ $customer->last_name }} - {{ $customer->email }}
                            ({{ $customer->loyalty_points }} pts)
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- WALK-IN CUSTOMER SECTION -->
            <div id="walkinSection" class="hidden">
                <!-- Token Display/Input -->
                <div class="bg-dark-800 border border-gold-500/30 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gold-500">
                            <i class="fas fa-ticket-alt mr-2"></i> Walk-in Token
                        </label>
                        <button type="button" onclick="generateToken()" 
                                class="bg-gold-500 hover:bg-gold-600 text-dark-900 text-xs font-bold px-3 py-1 rounded transition-colors">
                            <i class="fas fa-sync-alt mr-1"></i> Generate New
                        </button>
                    </div>
                    <input type="text" name="walkin_token" id="walkinToken" readonly
                           class="input-dark text-gold-500 font-mono text-lg text-center tracking-wider"
                           placeholder="Click 'Generate New' or enter existing token">
                    <p class="text-xs text-gray-400 mt-2">
                        <i class="fas fa-info-circle"></i> 
                        For returning walk-in customers, enter their existing token to track loyalty points
                    </p>
                </div>

                <!-- Toggle: New vs Returning -->
                <div class="flex items-center gap-4 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="walkin_type" value="new" checked 
                               onchange="toggleWalkinType()"
                               class="w-4 h-4 text-gold-500 bg-dark-800 border-gray-600">
                        <span class="text-sm text-gray-400">New Walk-in (Auto-generate token)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="walkin_type" value="returning" 
                               onchange="toggleWalkinType()"
                               class="w-4 h-4 text-gold-500 bg-dark-800 border-gray-600">
                        <span class="text-sm text-gray-400">Returning Walk-in (Enter existing token)</span>
                    </label>
                </div>

                <!-- Customer Name (Optional for walk-ins) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            First Name <span class="text-xs text-gray-500">(Optional)</span>
                        </label>
                        <input type="text" name="walkin_first_name" class="input-dark" placeholder="Guest">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            Last Name <span class="text-xs text-gray-500">(Optional)</span>
                        </label>
                        <input type="text" name="walkin_last_name" class="input-dark" placeholder="Customer">
                    </div>
                </div>

                <!-- Contact Information (Optional) -->
                <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
                    <p class="text-blue-400 text-sm mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Provide email/phone if customer wants to receive booking confirmations
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                Email <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <input type="email" name="walkin_email" class="input-dark" placeholder="customer@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                Phone Number <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <input type="text" name="walkin_phone" class="input-dark" placeholder="+1 (555) 123-4567">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-3">Select Services *</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($services ?? [] as $service)
                    <label class="flex items-center gap-3 p-3 bg-dark-800 rounded-lg border border-white/5 hover:border-gold-500/50 cursor-pointer transition-all">
                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
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
                    <option value="{{ $barber->id }}">
                        {{ $barber->first_name }} {{ $barber->last_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Appointment Date *</label>
                    <input type="date" name="appointment_date" value="{{ date('Y-m-d') }}" required
                           class="input-dark">
                    @error('appointment_date')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Appointment Time *</label>
                    <input type="time" name="appointment_time" value="{{ date('H:i') }}" required
                           class="input-dark">
                    @error('appointment_time')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Special Requests -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Special Requests</label>
                <textarea name="special_requests" rows="3" class="input-dark" 
                          placeholder="Any special requests or notes..."></textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Create Booking
            </button>
            <a href="{{ route('admin.bookings.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function setBookingType(type) {
    const bookingType = document.getElementById('bookingType');
    const registeredSection = document.getElementById('registeredSection');
    const walkinSection = document.getElementById('walkinSection');
    const btnRegistered = document.getElementById('btnRegistered');
    const btnWalkin = document.getElementById('btnWalkin');
    const userId = document.getElementById('userId');

    bookingType.value = type;

    if (type === 'registered') {
        registeredSection.classList.remove('hidden');
        walkinSection.classList.add('hidden');
        btnRegistered.classList.add('active', 'border-gold-500');
        btnRegistered.classList.remove('border-white/10');
        btnWalkin.classList.remove('active', 'border-gold-500');
        btnWalkin.classList.add('border-white/10');
        userId.required = true;
    } else {
        registeredSection.classList.add('hidden');
        walkinSection.classList.remove('hidden');
        btnWalkin.classList.add('active', 'border-gold-500');
        btnWalkin.classList.remove('border-white/10');
        btnRegistered.classList.remove('active', 'border-gold-500');
        btnRegistered.classList.add('border-white/10');
        userId.required = false;
        generateToken(); // Auto-generate token for new walk-in
    }
}

function generateToken() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Exclude similar looking characters
    let token = 'WI-'; // WI = Walk-In prefix
    for (let i = 0; i < 8; i++) {
        token += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('walkinToken').value = token;
    document.getElementById('walkinToken').readOnly = true;
}

function toggleWalkinType() {
    const walkinType = document.querySelector('input[name="walkin_type"]:checked').value;
    const tokenInput = document.getElementById('walkinToken');
    
    if (walkinType === 'new') {
        generateToken();
        tokenInput.readOnly = true;
    } else {
        tokenInput.value = '';
        tokenInput.readOnly = false;
        tokenInput.placeholder = 'Enter existing walk-in token (e.g., WI-ABC12345)';
    }
}
</script>
@endpush
