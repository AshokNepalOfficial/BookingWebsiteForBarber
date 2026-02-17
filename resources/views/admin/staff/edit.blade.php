@extends('layouts.admin')

@section('title', 'Edit Staff Member | Admin Panel')

@section('page-title', 'Edit Staff Member')

@section('content')

<div class="max-w-4xl">
    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data" class="bg-dark-900 rounded-lg border border-white/5 p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg text-white font-medium mb-4 pb-3 border-b border-white/10">Personal Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required
                               class="input-dark" placeholder="John">
                        @error('first_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required
                               class="input-dark" placeholder="Doe">
                        @error('last_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $staff->email) }}" required
                               class="input-dark" placeholder="john.doe@example.com">
                        @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Phone *</label>
                        <input type="text" name="phone_no" value="{{ old('phone_no', $staff->phone_no) }}" required
                               class="input-dark" placeholder="+1 (555) 123-4567">
                        @error('phone_no')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Role & Position -->
            <div>
                <h3 class="text-lg text-white font-medium mb-4 pb-3 border-t border-white/10 pt-6">Role & Position</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">User Type *</label>
                        <select name="role" required class="input-dark">
                            <option value="">-- Select User Type --</option>
                            <option value="admin" {{ old('role', $staff->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ old('role', $staff->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="receptionist" {{ old('role', $staff->role) == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                            <option value="staff" {{ old('role', $staff->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="barber" {{ old('role', $staff->role) == 'barber' ? 'selected' : '' }}>Barber</option>
                        </select>
                        @error('role')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Permission Role (Optional)</label>
                        <select name="role_id" class="input-dark">
                            <option value="">-- Select Permission Role --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $staff->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Position</label>
                        <input type="text" name="position" value="{{ old('position', $staff->position) }}"
                               class="input-dark" placeholder="e.g., Senior Barber, Manager">
                        @error('position')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div>
                <h3 class="text-lg text-white font-medium mb-4 pb-3 border-t border-white/10 pt-6">Employment Details</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Hourly Rate</label>
                        <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate', $staff->hourly_rate) }}"
                               class="input-dark" placeholder="25.00">
                        @error('hourly_rate')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Hire Date</label>
                        <input type="date" name="hire_date" value="{{ old('hire_date', $staff->hire_date ? $staff->hire_date->format('Y-m-d') : '') }}"
                               class="input-dark">
                        @error('hire_date')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="can_accept_bookings" value="1" {{ old('can_accept_bookings', $staff->can_accept_bookings) ? 'checked' : '' }}
                               class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                        <span class="text-sm text-gray-300">Can Accept Bookings (for barbers/service providers)</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $staff->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-gold-500 bg-dark-700 border-gray-600 rounded focus:ring-gold-500">
                        <span class="text-sm text-gray-300">Active Status</span>
                    </label>
                </div>
            </div>

            <!-- Authentication -->
            <div>
                <h3 class="text-lg text-white font-medium mb-4 pb-3 border-t border-white/10 pt-6">Authentication</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                    <input type="password" name="password"
                           class="input-dark" placeholder="Leave blank to keep current password">
                    <p class="text-xs text-gray-500 mt-1">Only fill this if you want to change the password</p>
                    @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Profile Image -->
            <div>
                <h3 class="text-lg text-white font-medium mb-4 pb-3 border-t border-white/10 pt-6">Profile Image</h3>
                
                @if($staff->profile_image)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Current Image</label>
                    <img src="{{ asset('storage/' . $staff->profile_image) }}" alt="{{ $staff->first_name }}" class="w-32 h-32 rounded-lg object-cover border border-white/10">
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">{{ $staff->profile_image ? 'Change' : 'Upload' }} Profile Image</label>
                    <input type="file" name="profile_image" accept="image/*"
                           class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gold-500 file:text-dark-900 hover:file:bg-gold-600">
                    @error('profile_image')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Bio -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Bio</label>
                <textarea name="bio" rows="3" class="input-dark" placeholder="Brief description about the staff member">{{ old('bio', $staff->bio) }}</textarea>
                @error('bio')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-8 pt-6 border-t border-white/10">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded transition-colors">
                <i class="fas fa-save mr-2"></i> Update Staff Member
            </button>
            <a href="{{ route('admin.staff.show', $staff->id) }}" class="bg-dark-800 hover:bg-dark-700 text-gray-400 hover:text-white px-6 py-2 rounded transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
