@extends('layouts.admin')

@section('title', 'Role Details | Admin Panel')

@section('page-title', $role->display_name)

@section('header-actions')
<a href="{{ route('admin.roles.edit', $role->id) }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm shadow-lg">
    <i class="fas fa-edit sm:mr-2"></i> <span class="hidden sm:inline">Edit Role</span>
</a>
@endsection

@section('content')

<!-- Role Information -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <h3 class="text-white font-medium mb-4 pb-3 border-b border-white/10">Role Information</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <p class="text-gray-400 text-sm mb-1">Role Name</p>
            <p class="text-white font-medium">{{ $role->name }}</p>
        </div>
        
        <div>
            <p class="text-gray-400 text-sm mb-1">Display Name</p>
            <p class="text-white font-medium">{{ $role->display_name }}</p>
        </div>
        
        <div>
            <p class="text-gray-400 text-sm mb-1">Status</p>
            @if($role->is_active)
            <span class="badge badge-confirmed">Active</span>
            @else
            <span class="badge badge-cancelled">Inactive</span>
            @endif
        </div>
    </div>
    
    @if($role->description)
    <div class="mt-4">
        <p class="text-gray-400 text-sm mb-1">Description</p>
        <p class="text-gray-300">{{ $role->description }}</p>
    </div>
    @endif
</div>

<!-- Permissions -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <h3 class="text-white font-medium mb-4 pb-3 border-b border-white/10">Assigned Permissions ({{ $role->permissions->count() }})</h3>
    
    @if($permissionsByGroup->count() > 0)
        @foreach($permissionsByGroup as $group => $permissions)
        <div class="mb-6 last:mb-0">
            <h4 class="text-gold-400 font-medium mb-3 capitalize">{{ ucwords(str_replace('_', ' ', $group)) }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($permissions as $permission)
                <div class="bg-dark-800/50 p-3 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-green-400 mt-1"></i>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $permission->display_name }}</p>
                            @if($permission->description)
                            <p class="text-gray-500 text-xs mt-0.5">{{ $permission->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-shield-alt text-4xl mb-3 block opacity-50"></i>
            <p>No permissions assigned to this role</p>
        </div>
    @endif
</div>

<!-- Assigned Staff -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6">
    <h3 class="text-white font-medium mb-4 pb-3 border-b border-white/10">Staff Members ({{ $role->staff->count() }})</h3>
    
    @if($role->staff->count() > 0)
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Position</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($role->staff as $member)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($member->profile_image)
                            <img src="{{ asset('storage/' . $member->profile_image) }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->first_name . ' ' . $member->last_name) }}&background=EAB308&color=000" class="w-8 h-8 rounded-full">
                            @endif
                            <span class="text-white">{{ $member->first_name }} {{ $member->last_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $member->email }}</td>
                    <td class="px-6 py-4">{{ $member->position ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($member->is_active)
                        <span class="badge badge-confirmed">Active</span>
                        @else
                        <span class="badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.staff.show', $member->id) }}" class="text-blue-400 hover:text-blue-300" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-users text-4xl mb-3 block opacity-50"></i>
        <p>No staff members assigned to this role</p>
    </div>
    @endif
</div>

@endsection
