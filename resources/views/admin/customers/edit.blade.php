@extends('layouts.admin')

@section('title', 'Edit Customer | Admin Panel')

@section('page-title', 'Edit Customer')

@section('content')

<div class="max-w-3xl">
    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- First Name & Last Name -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required
                           class="input-dark">
                    @error('first_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required
                           class="input-dark">
                    @error('last_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
                       class="input-dark">
                @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Phone Number *</label>
                <input type="text" name="phone_no" value="{{ old('phone_no', $customer->phone_no) }}" required
                       class="input-dark" placeholder="+1 (555) 123-4567">
                @error('phone_no')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Customer Role *</label>
                <select name="role" class="input-dark">
                    <option value="customer" {{ old('role', $customer->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="member" {{ old('role', $customer->role) == 'member' ? 'selected' : '' }}>Member</option>
                </select>
                @error('role')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Type -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Customer Type</label>
                <div class="bg-dark-800 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-white font-medium mb-1">Account Status</p>
                            <p class="text-xs text-gray-400">
                                @if($customer->is_guest)
                                    This is a guest/walk-in account. Convert to registered customer?
                                @else
                                    This is a registered customer account
                                @endif
                            </p>
                        </div>
                        <span class="px-3 py-1 text-sm rounded {{ $customer->is_guest ? 'bg-yellow-500/20 text-yellow-400' : 'bg-green-500/20 text-green-400' }}">
                            {{ $customer->is_guest ? 'Guest' : 'Registered' }}
                        </span>
                    </div>
                    
                    @if($customer->is_guest)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="convert_to_registered" value="1" class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                        <span class="text-sm text-gray-300">Convert to Registered Customer (will remove guest status and walk-in token)</span>
                    </label>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Converting a guest to registered allows them to login and manage their bookings
                </p>
            </div>

            <!-- Assign Membership -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Assign Membership</label>
                <select name="membership_id" class="input-dark">
                    <option value="">-- Select Membership (Optional) --</option>
                    @foreach($memberships as $membership)
                    <option value="{{ $membership->id }}">
                        {{ $membership->name }} - {!! $formatPrice($membership->price) !!} ({{ $membership->duration_days }} days)
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Assign a membership plan to this customer. Will not override existing active membership.
                </p>
            </div>

            <!-- Customer Stats (Read-only) -->
            <div class="grid grid-cols-3 gap-4 pt-6 border-t border-white/10">
                <div class="bg-dark-800 p-4 rounded-lg">
                    <p class="text-gray-400 text-xs uppercase mb-1">Total Bookings</p>
                    <p class="text-white font-bold text-xl">{{ $customer->bookings()->count() }}</p>
                </div>
                <div class="bg-dark-800 p-4 rounded-lg">
                    <p class="text-gray-400 text-xs uppercase mb-1">Loyalty Points</p>
                    <p class="text-gold-500 font-bold text-xl">{{ $customer->loyalty_points }}</p>
                </div>
                <div class="bg-dark-800 p-4 rounded-lg">
                    <p class="text-gray-400 text-xs uppercase mb-1">Guest Account</p>
                    <p class="text-white font-bold text-xl">{{ $customer->is_guest ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Update Customer
            </button>
            <a href="{{ route('admin.customers.show', $customer->id) }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
