@extends('layouts.admin')

@section('title', 'Create Blog Post | Admin Panel')

@section('page-title', 'Create New Blog Post')

@section('content')

<div class="max-w-4xl">
    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-400 mb-2">Post Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="input-dark" placeholder="e.g., 5 Grooming Tips for Modern Men">
                @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Slug (URL)</label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                       class="input-dark" placeholder="e.g., grooming-tips (auto-generated if empty)">
                @error('slug')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                <input type="text" name="category" value="{{ old('category') }}"
                       class="input-dark" placeholder="e.g., Grooming Tips">
                @error('category')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Featured Image</label>
            <input type="file" name="featured_image" accept="image/*" class="input-dark">
            @error('featured_image')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Excerpt (Short Summary)</label>
            <textarea name="excerpt" rows="2" class="input-dark" placeholder="Brief summary for preview">{{ old('excerpt') }}</textarea>
            @error('excerpt')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Content *</label>
            <div id="editor" style="height: 400px;"></div>
            <textarea name="content" id="content-input" required class="hidden">{{ old('content') }}</textarea>
            @error('content')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Tags (comma-separated)</label>
            <input type="text" name="tags" value="{{ old('tags') }}"
                   class="input-dark" placeholder="grooming, style, tips, barbershop">
            @error('tags')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Meta Description (SEO)</label>
            <textarea name="meta_description" rows="2" class="input-dark" placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
            @error('meta_description')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-400 mb-2">Meta Keywords (SEO)</label>
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                   class="input-dark" placeholder="keyword1, keyword2, keyword3">
            @error('meta_keywords')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4 mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                <span class="text-sm text-gray-300">Featured post</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                <span class="text-sm text-gray-300">Publish immediately</span>
            </label>
        </div>

        <div class="flex gap-3 pt-4 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Create Post
            </button>
            <a href="{{ route('admin.blogs.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
var quill = new window.Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            ['link', 'image', 'video'],
            ['blockquote', 'code-block'],
            ['clean']
        ]
    }
});

var content = document.querySelector('#content-input').value;
if (content) {
    quill.root.innerHTML = content;
}

quill.on('text-change', function() {
    document.querySelector('#content-input').value = quill.root.innerHTML;
});

document.querySelector('form').addEventListener('submit', function() {
    document.querySelector('#content-input').value = quill.root.innerHTML;
});
</script>
@endpush
