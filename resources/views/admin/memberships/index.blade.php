@extends('layouts.admin')

@section('title', 'Memberships | Admin Panel')

@section('page-title', 'Memberships Management')

@section('header-actions')
@if($canAccess('memberships'))
<a href="{{ route('admin.memberships.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Create Membership Plan</span>
</a>
@endif
@endsection

@section('content')

<!-- Membership Plans Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($memberships ?? [] as $membership)
    <div class="bg-dark-900 rounded-lg border border-white/5 p-6 hover:border-gold-500/50 transition-all">
        <div class="text-center mb-6">
            <h3 class="font-serif text-2xl text-white mb-2">{{ $membership->name ?? 'Membership Plan' }}</h3>
            <div class="text-4xl font-bold text-gold-500 mb-2">
                {!! $formatPrice($membership->price ?? 0) !!}
            </div>
            <p class="text-gray-400 text-sm">{{ $membership->duration_days ?? 30 }} days</p>
        </div>

        <div class="space-y-3 mb-6">
            @if($membership->description)
            <p class="text-gray-400 text-sm">{{ $membership->description }}</p>
            @endif
            
            <div class="flex items-center gap-2 text-sm">
                <i class="fas fa-check text-green-400"></i>
                <span class="text-gray-300">{{ $membership->discount_percentage ?? 0 }}% discount on services</span>
            </div>
            
            <div class="flex items-center gap-2 text-sm">
                <i class="fas fa-check text-green-400"></i>
                <span class="text-gray-300">{{ $membership->free_services ?? 0 }} free services</span>
            </div>
        </div>

        <div class="pt-4 border-t border-white/10">
            <div class="flex items-center justify-between text-sm mb-4">
                <span class="text-gray-400">Active Members:</span>
                <span class="font-bold text-white">{{ $membership->active_members_count ?? 0 }}</span>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.memberships.edit', $membership->id) }}" 
                   class="flex-1 text-center bg-dark-800 border border-gold-500 text-gold-500 py-2 rounded hover:bg-gold-500 hover:text-dark-900 transition-colors text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <i class="fas fa-crown text-4xl text-gray-600 mb-4 block"></i>
        <p class="text-gray-400">No membership plans found</p>
        <a href="{{ route('admin.memberships.create') }}" class="inline-block mt-4 bg-gold-500 text-dark-900 px-6 py-2 rounded font-bold hover:bg-gold-600 transition-colors">
            Create First Plan
        </a>
    </div>
    @endforelse
</div>

@endsection
