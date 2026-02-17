@extends('layouts.admin')

@section('title', 'Staff Management | Admin Panel')

@section('page-title', 'Staff Management')

@section('header-actions')
@if(Auth::user()->role === 'admin')
<a href="{{ route('admin.staff.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Add Staff</span>
</a>
@endif
@endsection

@section('content')

<!-- Staff Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[900px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">Staff Member</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Hourly Rate</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff ?? [] as $member)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($member->profile_image)
                            <img src="{{ asset('storage/' . $member->profile_image) }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->first_name . ' ' . $member->last_name) }}&background=EAB308&color=000" 
                                 class="w-10 h-10 rounded-full">
                            @endif
                            <div>
                                <p class="font-medium text-white">{{ $member->first_name }} {{ $member->last_name }}</p>
                                @if($member->position)
                                <p class="text-xs text-gray-500">{{ $member->position }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $member->email }}</td>
                    <td class="px-6 py-4">{{ $member->phone_no }}</td>
                    <td class="px-6 py-4">
                        @if($member->roleDetails)
                        <span class="px-2 py-1 text-xs rounded bg-blue-500/20 text-blue-400">
                            {{ $member->roleDetails->display_name }}
                        </span>
                        @else
                        <span class="text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($member->hourly_rate)
                        <span class="text-white font-medium">{!! $formatPrice($member->hourly_rate) !!}/hr</span>
                        @else
                        <span class="text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($member->is_active)
                        <span class="badge badge-confirmed">Active</span>
                        @else
                        <span class="badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.staff.show', $member->id) }}" 
                               class="text-blue-400 hover:text-blue-300" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.staff.edit', $member->id) }}" 
                               class="text-gold-500 hover:text-gold-400" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.staff.toggleStatus', $member->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-gray-300" title="Toggle Status">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-user-tie text-4xl mb-4 block"></i>
                        <p>No staff members found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if(isset($staff) && $staff->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $staff->links() }}
    </div>
</div>
@endif

@endsection
