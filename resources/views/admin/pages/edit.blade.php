@extends('layouts.admin')

@section('title', 'Edit Page | Admin Panel')

@section('page-title', 'Edit Page: ' . $page->title)

@section('content')

<div class="max-w-4xl">
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Page Title *</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" required
                       class="input-dark" placeholder="e.g., Privacy Policy">
                @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug', $page->slug) }}"
                       class="input-dark" placeholder="e.g., privacy-policy">
                @error('slug')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Page Content</label>
            <textarea name="content" rows="15" class="input-dark" placeholder="Enter page content (HTML allowed)">{{ old('content', $page->content) }}</textarea>
            @error('content')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Meta Description (SEO)</label>
            <textarea name="meta_description" rows="2" class="input-dark" placeholder="Brief description for search engines">{{ old('meta_description', $page->meta_description) }}</textarea>
            @error('meta_description')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Meta Keywords (SEO)</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}"
                       class="input-dark" placeholder="keyword1, keyword2, keyword3">
                @error('meta_keywords')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', $page->display_order) }}"
                       class="input-dark" placeholder="0">
                @error('display_order')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                <span class="text-sm text-gray-300">Page is active and visible</span>
            </label>
        </div>

        <div class="flex gap-3 pt-4 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Update Page
            </button>
            <a href="{{ route('admin.pages.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
