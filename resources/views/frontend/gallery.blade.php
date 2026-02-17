@extends('layouts.frontend_custom')

@section('title', 'Gallery | JB Barber Unisex Salon')

@section('content')

<!-- PAGE HEADER -->
<section class="max-w-7xl mx-auto relative py-4 bg-dark-800 border-b border-white/5 mt-auto">
    <div class="text-center mb-16">
         <h2 class="font-serif text-4xl text-white mb-4">Our Gallery</h2>
        <div class="h-0.5 w-20 bg-gold-500 mx-auto mb-4"></div>
        <p class="text-gray-400 max-w-2xl mx-auto">
            Discover our finest work - from classic cuts to modern styles, each transformation tells a story of precision and artistry.
        </p>
    </div>
</section>

<!-- CATEGORY FILTERS -->
@if($categories->count() > 1)
<section class="py-8 bg-dark-900 border-b border-white/5 sticky top-20 z-40">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <a href="{{ route('gallery') }}" 
               class="px-6 py-2 rounded-sm border transition-all {{ !$category ? 'bg-gold-500 text-dark-900 border-gold-500 font-bold' : 'border-white/10 text-gray-400 hover:text-white hover:border-gold-500/50' }}">
                All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('gallery', ['category' => $cat]) }}" 
               class="px-6 py-2 rounded-sm border transition-all capitalize {{ $category == $cat ? 'bg-gold-500 text-dark-900 border-gold-500 font-bold' : 'border-white/10 text-gray-400 hover:text-white hover:border-gold-500/50' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- GALLERY GRID -->
<section class=" bg-dark-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        @if($galleryItems->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galleryItems as $item)
            <div class="group relative overflow-hidden aspect-square bg-dark-800 border border-white/5 hover:border-gold-500/50 transition-all cursor-pointer"
                 onclick="openLightbox('{{ asset('storage/' . $item->image_path) }}', '{{ addslashes($item->title ?? '') }}', '{{ addslashes($item->description ?? '') }}')">
                <img src="{{ asset('storage/' . $item->image_path) }}" 
                     alt="{{ $item->title }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                     loading="lazy">
                
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-dark-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                        <i class="fas fa-search-plus text-white text-3xl mb-3"></i>
                        @if($item->title)
                        <h3 class="text-white font-medium text-center mb-1">{{ $item->title }}</h3>
                        @endif
                        @if($item->category)
                        <p class="text-gold-500 text-xs uppercase tracking-wider">{{ $item->category }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($galleryItems->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $galleryItems->appends(['category' => $category])->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-20">
            <i class="fas fa-images text-6xl text-gray-600 mb-6 block"></i>
            <h3 class="text-white text-2xl font-serif mb-2">No Images Found</h3>
            <p class="text-gray-400">
                @if($category)
                No images found in the "{{ $category }}" category.
                @else
                Our gallery is currently being updated. Check back soon!
                @endif
            </p>
            @if($category)
            <a href="{{ route('gallery') }}" class="inline-block mt-6 text-gold-500 hover:text-gold-400 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> View All Images
            </a>
            @endif
        </div>
        @endif
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 bg-black/95 z-50 hidden items-center justify-center p-4">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gold-500 text-4xl transition-colors z-10">
        <i class="fas fa-times"></i>
    </button>
    
    <button onclick="previousImage()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gold-500 text-3xl transition-colors hidden lg:block">
        <i class="fas fa-chevron-left"></i>
    </button>
    
    <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gold-500 text-3xl transition-colors hidden lg:block">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <div class="max-w-6xl w-full">
        <img id="lightbox-image" src="" alt="" class="w-full h-auto max-h-[80vh] object-contain rounded">
        <div id="lightbox-info" class="text-center mt-6">
            <h3 id="lightbox-title" class="text-white text-2xl font-serif mb-2"></h3>
            <p id="lightbox-description" class="text-gray-400"></p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentImageIndex = 0;
const images = {!! json_encode($galleryItems->map(function($item) {
    return [
        'url' => asset('storage/' . $item->image_path),
        'title' => $item->title ?? '',
        'description' => $item->description ?? ''
    ];
})->values()) !!};

function openLightbox(url, title, description) {
    currentImageIndex = images.findIndex(img => img.url === url);
    showLightboxImage();
    document.getElementById('lightbox-modal').classList.remove('hidden');
    document.getElementById('lightbox-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox-modal').classList.add('hidden');
    document.getElementById('lightbox-modal').classList.remove('flex');
    document.body.style.overflow = '';
}

function showLightboxImage() {
    const img = images[currentImageIndex];
    document.getElementById('lightbox-image').src = img.url;
    document.getElementById('lightbox-title').textContent = img.title || '';
    document.getElementById('lightbox-description').textContent = img.description || '';
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    showLightboxImage();
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    showLightboxImage();
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('lightbox-modal');
    if (!modal.classList.contains('hidden')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') previousImage();
        if (e.key === 'ArrowRight') nextImage();
    }
});

// Close on backdrop click
document.getElementById('lightbox-modal').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
</script>
@endpush
