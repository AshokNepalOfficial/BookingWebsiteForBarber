@extends('layouts.admin')

@section('title', 'Create Customer | Admin Panel')

@section('page-title', 'Create New Customer')

@section('content')

<div class="max-w-3xl">
    <form action="{{ route('admin.customers.store') }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf

        <div class="space-y-6">
            <!-- First Name & Last Name -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                           class="input-dark" placeholder="John">
                    @error('first_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                           class="input-dark" placeholder="Doe">
                    @error('last_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="input-dark" placeholder="john.doe@example.com">
                @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Phone Number *</label>
                <input type="text" name="phone_no" value="{{ old('phone_no') }}" required
                       class="input-dark" placeholder="+1 (555) 123-4567">
                @error('phone_no')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Password *</label>
                <input type="password" name="password" required
                       class="input-dark" placeholder="Minimum 8 characters">
                @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Minimum 8 characters required
                </p>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Customer Role *</label>
                <select name="role" class="input-dark">
                    <option value="customer" {{ old('role', 'customer') == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                </select>
                @error('role')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Select "Member" if this customer has a membership plan
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                    <div>
                        <p class="text-blue-300 font-medium mb-1">New Customer Information</p>
                        <p class="text-blue-200/80 text-sm">
                            This will create a registered customer account. The customer will be able to login using the provided email and password. 
                            You can assign a membership plan after creating the customer from their profile page.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-user-plus mr-2"></i> Create Customer
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
