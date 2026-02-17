@extends('layouts.admin')

@section('title', 'CMS - Frontend Content | Admin Panel')

@section('page-title', 'Frontend Content Management')

@section('content')

<div class="mb-6 bg-blue-500/10 border border-blue-500/30 rounded-lg p-4">
    <div class="flex items-start gap-3">
        <i class="fas fa-info-circle text-blue-400 text-xl mt-0.5"></i>
        <div>
            <p class="text-blue-400 font-medium mb-1">Content Management System</p>
            <p class="text-gray-400 text-sm">Edit website content without touching code. Changes appear immediately on the frontend.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($sections as $key => $name)
    <div class="bg-dark-900 rounded-lg border border-white/5 p-6 hover:border-gold-500/50 transition-all group">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-serif text-xl text-white mb-2">{{ $name }}</h3>
                <p class="text-gray-400 text-sm">
                    @if(isset($contents[$key]))
                        {{ $contents[$key]->count() }} field(s)
                    @else
                        Not configured
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-gold-500/10 flex items-center justify-center group-hover:bg-gold-500/20 transition-colors">
                <i class="fas {{ 
                    $key === 'hero' ? 'fa-home' : 
                    ($key === 'about' ? 'fa-info-circle' : 
                    ($key === 'services_intro' ? 'fa-cut' : 
                    ($key === 'testimonials' ? 'fa-quote-left' : 
                    ($key === 'contact' ? 'fa-phone' : 
                    ($key === 'footer' ? 'fa-bars' : 'fa-file-alt'))))) 
                }} text-gold-500 text-xl"></i>
            </div>
        </div>

        <!-- Preview -->
        @if(isset($contents[$key]) && $contents[$key]->count() > 0)
        <div class="bg-dark-800 rounded p-3 mb-4 text-xs">
            @foreach($contents[$key]->take(2) as $content)
            <div class="mb-2 last:mb-0">
                <span class="text-gray-500">{{ ucfirst(str_replace('_', ' ', $content->key)) }}:</span>
                <span class="text-gray-300 ml-1">
                    @if($content->type === 'image')
                        <i class="fas fa-image text-gold-500"></i> Image uploaded
                    @else
                        {{ Str::limit($content->value, 30) }}
                    @endif
                </span>
            </div>
            @endforeach
            @if($contents[$key]->count() > 2)
            <p class="text-gray-600 text-xs mt-2">+{{ $contents[$key]->count() - 2 }} more</p>
            @endif
        </div>
        @endif

        <a href="{{ route('admin.cms.edit', $key) }}" 
           class="block w-full bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold py-2 px-4 rounded text-center transition-colors">
            <i class="fas fa-edit mr-2"></i> Edit Content
        </a>
    </div>
    @endforeach
</div>

@endsection
