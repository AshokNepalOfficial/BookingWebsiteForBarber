@extends('layouts.frontend')

@section('title', 'Home | JB Unisex Salon')

@section('content')

<!-- HERO SECTION -->
<section id="home" class="relative h-screen flex items-center bg-hero-pattern bg-cover bg-center bg-fixed">
    <div class="max-w-7xl mx-auto px-4 w-full pt-20">
        <div class="max-w-3xl">
            <p class="text-gold-500 tracking-[0.3em] text-xs font-bold uppercase mb-4 animate-up">Premium Barbershop Experience</p>
            <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl text-white leading-none mb-2 animate-up delay-100">Where Style Meets</h1>
            <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl text-gold-500 font-bold mb-8 animate-up delay-200">Tradition</h1>
            <p class="text-gray-300 text-lg md:text-xl font-light leading-relaxed mb-10 max-w-lg animate-up delay-300">
                Precision cuts, hot towel shaves, and a whiskey on the rocks. Experience the ritual of refined grooming.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 animate-up delay-300">
                <button onclick="openBooking()" class="bg-gold-500 hover:bg-gold-400 text-dark-900 font-bold py-3 px-8 rounded-sm transition-all">
                    Book Your Appointment
                </button>
                <a href="#services" class="border border-white hover:border-gold-500 hover:text-gold-500 text-white font-medium py-3 px-8 rounded-sm transition-all text-center">
                    View Menu
                </a>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES SHOWCASE -->
<section id="services" class="py-24 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="font-serif text-4xl text-white mb-4">Our Services</h2>
            <div class="h-0.5 w-20 bg-gold-500 mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="bg-dark-800 p-8 border border-white/5 hover:border-gold-500/50 transition-all group">
                <i class="fas {{ $service->icon ?? 'fa-cut' }} text-4xl text-gray-600 group-hover:text-gold-500 mb-6 transition-colors"></i>
                <h3 class="font-serif text-2xl mb-2">{{ $service->title }}</h3>
                <p class="text-gold-500 font-bold text-lg mb-4">{!! $formatPrice($service->price) !!}</p>
                <p class="text-gray-400 text-sm">{{ $service->sub_title }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- REVIEWS -->
<section id="reviews" class="py-20 bg-dark-800 border-y border-white/5">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <i class="fas fa-quote-left text-4xl text-gold-500 opacity-50 mb-6"></i>
        <h2 class="font-serif text-3xl md:text-4xl text-white leading-tight mb-8">
            "Best barbershop in the city. The attention to detail is unmatched and the atmosphere is pure class."
        </h2>
        <div class="text-gold-500 mb-2">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
        <p class="text-white font-bold tracking-widest uppercase">Jonathan S.</p>
    </div>
</section>

<!-- GALLERY SECTION -->
<section id="gallery" class="py-24 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="font-serif text-4xl text-white mb-4">Our Gallery</h2>
            <div class="h-0.5 w-20 bg-gold-500 mx-auto mb-4"></div>
            <p class="text-gray-400 max-w-2xl mx-auto">Experience the artistry and craftsmanship through our collection of signature styles and transformations.</p>
        </div>
        
        @if($galleryItems->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-10">
            @foreach($galleryItems->take(8) as $item)
            <div class="group relative overflow-hidden aspect-square bg-dark-800 border border-white/5 hover:border-gold-500/50 transition-all cursor-pointer">
                <img src="{{ asset('storage/' . $item->image_path) }}" 
                     alt="{{ $item->title }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        @if($item->title)
                        <h3 class="text-white font-medium text-sm mb-1">{{ $item->title }}</h3>
                        @endif
                        @if($item->category)
                        <p class="text-gold-500 text-xs uppercase tracking-wider">{{ $item->category }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center">
            <a href="{{ route('gallery') }}" class="inline-block bg-gold-500 hover:bg-gold-400 text-dark-900 font-bold py-3 px-8 rounded-sm transition-all">
                View Full Gallery
            </a>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-images text-4xl text-gray-600 mb-4 block"></i>
            <p class="text-gray-400">Gallery coming soon...</p>
        </div>
        @endif
    </div>
</section>

@endsection
