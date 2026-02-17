@extends('layouts.admin')

@section('title', 'Blog Management | Admin Panel')

@section('page-title', 'Blog Management')

@section('header-actions')
<a href="{{ route('admin.blogs.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Add Post</span>
</a>
@endsection

@section('content')

<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs text-gray-500 uppercase bg-dark-700">
            <tr>
                <th class="px-6 py-3">Title</th>
                <th class="px-6 py-3">Category</th>
                <th class="px-6 py-3">Author</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Views</th>
                <th class="px-6 py-3">Published</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts ?? [] as $post)
            <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-12 h-12 rounded object-cover">
                        @else
                        <div class="w-12 h-12 bg-dark-800 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-600"></i>
                        </div>
                        @endif
                        <div>
                            <div class="text-white font-medium">{{ $post->title }}</div>
                            @if($post->is_featured)
                            <span class="text-xs bg-gold-500/20 text-gold-400 px-2 py-0.5 rounded">Featured</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">{{ $post->category ?? '—' }}</td>
                <td class="px-6 py-4">{{ $post->author->first_name ?? 'Unknown' }}</td>
                <td class="px-6 py-4">
                    @if($post->is_published)
                    <span class="badge badge-confirmed">Published</span>
                    @else
                    <span class="badge badge-pending">Draft</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-400">{{ number_format($post->views_count) }}</td>
                <td class="px-6 py-4 text-gray-400">
                    {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.blogs.edit', $post->id) }}" class="text-blue-400 hover:text-blue-300" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.blogs.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-blog text-4xl mb-4 block"></i>
                    <p>No blog posts found. Create your first post!</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($posts->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $posts->links() }}
    </div>
</div>
@endif

@endsection
