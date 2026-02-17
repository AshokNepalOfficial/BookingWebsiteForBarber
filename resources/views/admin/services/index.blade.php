@extends('layouts.admin')

@section('title', 'Services Management | Admin Panel')

@section('page-title', 'Services Management')

@section('header-actions')
@if($canAccess('services'))
<a href="{{ route('admin.services.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Add Service</span>
</a>
@endif
@endsection

@section('content')

<!-- Services Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($services ?? [] as $service)
    <div class="bg-dark-900 rounded-lg border border-white/5 p-6 hover:border-gold-500/50 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <h3 class="font-serif text-xl text-white mb-1">{{ $service->title }}</h3>
                <p class="text-gray-400 text-sm">{{ $service->sub_title }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($service->is_active)
                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded border border-green-500/30">Active</span>
                @else
                <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded border border-red-500/30">Inactive</span>
                @endif
            </div>
        </div>

        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Price:</span>
                <span class="text-gold-500 font-bold text-lg">{!! $formatPrice($service->price) !!}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Duration:</span>
                <span class="text-white">{{ $service->duration_minutes }} minutes</span>
            </div>
            @if($service->icon)
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Icon:</span>
                <span class="text-white"><i class="fas {{ $service->icon }}"></i> {{ $service->icon }}</span>
            </div>
            @endif
        </div>

        <div class="flex gap-2 pt-4 border-t border-white/10">
            <a href="{{ route('admin.services.edit', $service->id) }}" 
               class="flex-1 text-center bg-dark-800 border border-gold-500 text-gold-500 py-2 rounded hover:bg-gold-500 hover:text-dark-900 transition-colors text-sm">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            
            <form action="{{ route('admin.services.toggleStatus', $service->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-dark-800 border border-white/20 text-gray-400 py-2 rounded hover:border-gold-500 hover:text-gold-500 transition-colors text-sm">
                    <i class="fas fa-power-off mr-1"></i> {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>

            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-dark-800 border border-red-500/30 text-red-400 py-2 px-3 rounded hover:bg-red-500 hover:text-white transition-colors text-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <i class="fas fa-cut text-4xl text-gray-600 mb-4 block"></i>
        <p class="text-gray-400">No services found</p>
        <a href="{{ route('admin.services.create') }}" class="inline-block mt-4 bg-gold-500 text-dark-900 px-6 py-2 rounded font-bold hover:bg-gold-600 transition-colors">
            Add Your First Service
        </a>
    </div>
    @endforelse
</div>

@endsection
