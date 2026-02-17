@extends('layouts.admin')

@section('title', 'Edit Role | Admin Panel')

@section('page-title', 'Edit Role: ' . $role->display_name)

@section('content')

<div class="max-w-4xl">
    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg text-white font-medium mb-4 pb-3 border-b border-white/10">Role Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Role Name *</label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                               class="input-dark" placeholder="e.g., manager">
                        @error('name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Lowercase, no spaces (used internally)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Display Name *</label>
                        <input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required
                               class="input-dark" placeholder="e.g., Manager">
                        @error('display_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="2" class="input-dark" placeholder="Brief description of this role">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                        <span class="text-sm text-gray-300">Role is active</span>
                    </label>
                </div>
            </div>

            <!-- Permissions -->
            <div>
                <h3 class="text-lg text-white font-medium mb-4 pb-3 border-t border-white/10 pt-6">Assign Permissions</h3>
                
                @php
                    $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                @endphp

                @foreach($permissions as $group => $groupPermissions)
                <div class="mb-6">
                    <h4 class="text-md text-gold-400 font-medium mb-3 capitalize">{{ ucwords(str_replace('_', ' ', $group)) }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-dark-800/50 p-4 rounded-lg">
                        @foreach($groupPermissions as $permission)
                        <label class="flex items-start gap-3 cursor-pointer hover:bg-dark-700/50 p-2 rounded transition-colors">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                   {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}
                                   class="w-4 h-4 mt-1 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                            <div>
                                <span class="text-sm text-white font-medium">{{ $permission->display_name }}</span>
                                @if($permission->description)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $permission->description }}</p>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @error('permissions')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Update Role
            </button>
            <a href="{{ route('admin.roles.index') }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
