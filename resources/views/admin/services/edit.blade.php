@extends('layouts.admin')

@section('title', 'Edit Service | Admin Panel')

@section('page-title', 'Edit Service')

@section('content')

<div class="max-w-3xl">
    <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Service Title *</label>
                <input type="text" name="title" value="{{ old('title', $service->title) }}" required
                       class="input-dark" placeholder="e.g., Classic Haircut">
                @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Subtitle</label>
                <input type="text" name="sub_title" value="{{ old('sub_title', $service->sub_title) }}"
                       class="input-dark" placeholder="e.g., Scissor cut & styling">
                @error('sub_title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price and Duration -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Price ($) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $service->price) }}" required
                           class="input-dark" placeholder="45.00">
                    @error('price')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Duration (minutes) *</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" required
                           class="input-dark" placeholder="30">
                    @error('duration_minutes')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Icon -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Font Awesome Icon Class</label>
                <input type="text" name="icon" value="{{ old('icon', $service->icon) }}"
                       class="input-dark" placeholder="fa-cut">
                <p class="text-xs text-gray-500 mt-1">
                    Preview: <i class="fas {{ $service->icon }} text-gold-500 ml-2"></i>
                    | Visit <a href="https://fontawesome.com/icons" target="_blank" class="text-gold-500 hover:underline">FontAwesome</a> for icon names
                </p>
                @error('icon')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-dark-800 border-gray-600 rounded focus:ring-gold-500">
                <label for="is_active" class="text-sm font-medium text-gray-400">Service is active and available for booking</label>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Update Service
            </button>
            <a href="{{ route('admin.services.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
