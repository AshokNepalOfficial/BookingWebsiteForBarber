@extends('layouts.admin')

@section('title', 'Gallery Management | Admin Panel')

@section('page-title', 'Gallery Management')

@section('header-actions')
<a href="{{ route('admin.gallery.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Add Image</span>
</a>
@endsection

@section('content')

<div id="gallery-sortable" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($items ?? [] as $item)
    <div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden hover:border-gold-500/50 transition-all group sortable-drag" data-id="{{ $item->id }}">
        <div class="aspect-square overflow-hidden bg-dark-800">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
        </div>
        <div class="p-4">
            <div class="mb-2">
                @if($item->title)
                <h3 class="text-white font-medium text-sm mb-1 truncate">{{ $item->title }}</h3>
                @endif
                <span class="text-xs text-gray-500">{{ $item->category }}</span>
                @if(!$item->is_active)
                <span class="ml-2 text-xs bg-red-500/20 text-red-400 px-2 py-0.5 rounded">Inactive</span>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.gallery.edit', $item->id) }}" class="flex-1 text-center bg-dark-800 border border-gold-500 text-gold-500 py-1.5 rounded hover:bg-gold-500 hover:text-dark-900 transition-colors text-xs">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-dark-800 border border-red-500/30 text-red-400 py-1.5 px-3 rounded hover:bg-red-500 hover:text-white transition-colors text-xs">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <i class="fas fa-images text-4xl text-gray-600 mb-4 block"></i>
        <p class="text-gray-400">No gallery items found</p>
        <a href="{{ route('admin.gallery.create') }}" class="inline-block mt-4 bg-gold-500 text-dark-900 px-6 py-2 rounded font-bold hover:bg-gold-600 transition-colors">
            Add Your First Image
        </a>
    </div>
    @endforelse
</div>

@if($items->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $items->links() }}
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gallery = document.getElementById('gallery-sortable');
    if (gallery) {
        new window.Sortable(gallery, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                const items = Array.from(gallery.children).map((item, index) => ({
                    id: item.dataset.id,
                    order: index
                }));
                
                fetch('{{ route("admin.gallery.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Order updated successfully');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
});
</script>
@endpush
