@extends('layouts.admin')

@section('title', 'Add Gallery Image | Admin Panel')

@section('page-title', 'Add New Gallery Image')

@section('content')

<div class="max-w-2xl">
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Image *</label>
            <input type="file" name="image" accept="image/*" required class="input-dark">
            <p class="text-xs text-gray-500 mt-1">Max size: 5MB</p>
            @error('image')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Title (Optional)</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="input-dark" placeholder="e.g., Premium Haircut Style">
            @error('title')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Description (Optional)</label>
            <textarea name="description" rows="3" class="input-dark" placeholder="Brief description of this image">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                <input type="text" name="category" value="{{ old('category', 'general') }}"
                       class="input-dark" placeholder="e.g., haircuts, beards, salon">
                @error('category')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', 0) }}"
                       class="input-dark" placeholder="0">
                @error('display_order')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                <span class="text-sm text-gray-300">Image is active and visible</span>
            </label>
        </div>

        <div class="flex gap-3 pt-4 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Add Image
            </button>
            <a href="{{ route('admin.gallery.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
