@extends('layouts.admin')

@section('title', 'Roles & Permissions | Admin Panel')

@section('page-title', 'Roles & Permissions Management')

@section('header-actions')
<a href="{{ route('admin.roles.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Add Role</span>
</a>
@endsection

@section('content')

<!-- Roles Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[800px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">Role Name</th>
                    <th class="px-6 py-3">Description</th>
                    <th class="px-6 py-3">Permissions</th>
                    <th class="px-6 py-3">Staff Members</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles ?? [] as $role)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-white">{{ $role->display_name }}</p>
                            <p class="text-xs text-gray-500">{{ $role->name }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-400">{{ Str::limit($role->description ?? 'No description', 50) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded bg-blue-500/20 text-blue-400">
                            {{ $role->permissions_count }} Permissions
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-white font-medium">{{ $role->staff_count }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($role->is_active)
                        <span class="badge badge-confirmed">Active</span>
                        @else
                        <span class="badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.roles.show', $role->id) }}" 
                               class="text-blue-400 hover:text-blue-300" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.roles.edit', $role->id) }}" 
                               class="text-gold-500 hover:text-gold-400" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-user-shield text-4xl mb-4 block"></i>
                        <p>No roles found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if(isset($roles) && $roles->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $roles->links() }}
    </div>
</div>
@endif

@endsection
