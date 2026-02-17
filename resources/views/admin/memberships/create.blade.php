@extends('layouts.admin')

@section('title', 'Create Membership | Admin Panel')

@section('page-title', 'Create Membership Plan')

@section('content')

<div class="max-w-3xl">
    <form action="{{ route('admin.memberships.store') }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf

        <div class="space-y-6">
            <!-- Membership Name -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Membership Name *</label>
                <input type="text" name="membership_name" value="{{ old('membership_name') }}" required
                       class="input-dark" placeholder="e.g., Gold Membership">
                @error('membership_name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                <textarea name="description" rows="3" class="input-dark" 
                          placeholder="Describe the benefits of this membership...">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price and Duration -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Price ($) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                           class="input-dark" placeholder="99.00">
                    @error('price')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Duration (days) *</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', 30) }}" required
                           class="input-dark" placeholder="30">
                    @error('duration_days')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Discount and Free Services -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Discount Percentage (%)</label>
                    <input type="number" step="0.01" name="discount_percentage" value="{{ old('discount_percentage', 0) }}"
                           class="input-dark" placeholder="10.00" min="0" max="100">
                    @error('discount_percentage')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Free Services Count *</label>
                    <input type="number" name="free_services_count" value="{{ old('free_services_count', 0) }}" required
                           class="input-dark" placeholder="2" min="0">
                    @error('free_services_count')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Features -->
            <div class="space-y-3 pt-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="priority_booking" id="priority_booking" value="1" {{ old('priority_booking') ? 'checked' : '' }}
                           class="w-4 h-4 text-gold-500 bg-dark-800 border-gray-600 rounded focus:ring-gold-500">
                    <label for="priority_booking" class="text-sm font-medium text-gray-400">Priority booking access</label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-gold-500 bg-dark-800 border-gray-600 rounded focus:ring-gold-500">
                    <label for="is_active" class="text-sm font-medium text-gray-400">Membership is active and available for purchase</label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Create Membership
            </button>
            <a href="{{ route('admin.memberships.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
