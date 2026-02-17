@extends('layouts.admin')
@section('title', 'Edit Testimonial | Admin Panel')
@section('page-title', 'Edit Testimonial')
@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-400 mb-2">Customer Name *</label>
                <input type="text" name="customer_name" required class="input-dark" value="{{ old('customer_name', $testimonial->customer_name) }}"></div>
            <div><label class="block text-sm font-medium text-gray-400 mb-2">Customer Title</label>
                <input type="text" name="customer_title" class="input-dark" value="{{ old('customer_title', $testimonial->customer_title) }}"></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-400 mb-2">Testimonial *</label>
                <textarea name="testimonial" required rows="4" class="input-dark">{{ old('testimonial', $testimonial->testimonial) }}</textarea></div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Customer Photo</label>
                @if($testimonial->customer_image)
                <div class="mb-2"><img src="{{ asset('storage/'.$testimonial->customer_image) }}" class="w-20 h-20 rounded-full" alt=""></div>
                @endif
                <input type="file" name="customer_image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-gold-500 file:text-dark-900 hover:file:bg-gold-600 border border-white/10 rounded bg-dark-800 p-2">
            </div>
            <div><label class="block text-sm font-medium text-gray-400 mb-2">Rating *</label>
                <select name="rating" required class="input-dark">
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select></div>
            <div><label class="block text-sm font-medium text-gray-400 mb-2">Display Order</label>
                <input type="number" name="display_order" class="input-dark" value="{{ old('display_order', $testimonial->display_order) }}"></div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" class="mr-2" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm text-gray-400">Active (show on website)</label>
            </div>
        </div>
        <div class="flex gap-3 mt-6 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded"><i class="fas fa-save mr-2"></i> Update Testimonial</button>
            <a href="{{ route('admin.testimonials.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
