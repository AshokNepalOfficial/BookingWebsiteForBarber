@extends('layouts.admin')

@section('title', 'Pages Management | Admin Panel')

@section('page-title', 'Pages Management')

@section('header-actions')
<a href="{{ route('admin.pages.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Add Page</span>
</a>
@endsection

@section('content')

<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs text-gray-500 uppercase bg-dark-700">
            <tr>
                <th class="px-6 py-3">Title</th>
                <th class="px-6 py-3">Slug</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Order</th>
                <th class="px-6 py-3">Created</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages ?? [] as $page)
            <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                <td class="px-6 py-4 text-white font-medium">{{ $page->title }}</td>
                <td class="px-6 py-4 text-gray-400 font-mono text-xs">/{{ $page->slug }}</td>
                <td class="px-6 py-4">
                    @if($page->is_active)
                    <span class="badge badge-confirmed">Active</span>
                    @else
                    <span class="badge badge-cancelled">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-400">{{ $page->display_order }}</td>
                <td class="px-6 py-4 text-gray-400">{{ $page->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-blue-400 hover:text-blue-300" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this page?')">
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
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-file-alt text-4xl mb-4 block"></i>
                    <p>No pages found. Create your first page!</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($pages->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $pages->links() }}
    </div>
</div>
@endif

@endsection
