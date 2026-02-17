@extends('layouts.admin')

@section('title', 'Staff Details | Admin Panel')

@section('page-title', 'Staff Member Details')

@section('header-actions')
<div class="flex gap-2">
    <a href="{{ route('admin.staff.edit', $staff->id) }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
        <i class="fas fa-edit sm:mr-2"></i> <span class="hidden sm:inline">Edit</span>
    </a>
    <a href="{{ route('admin.staff.index') }}" class="bg-dark-700 hover:bg-dark-600 text-white font-medium text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap">
        <i class="fas fa-arrow-left sm:mr-2"></i> <span class="hidden sm:inline">Back</span>
    </a>
</div>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Staff Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-br from-gold-500/20 to-dark-800 p-6 text-center">
                @if($staff->profile_image)
                <img src="{{ asset('storage/' . $staff->profile_image) }}" 
                     alt="{{ $staff->first_name }}" 
                     class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-gold-500/30 object-cover">
                @else
                <div class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-gold-500/30 bg-dark-700 flex items-center justify-center">
                    <i class="fas fa-user text-4xl text-gray-500"></i>
                </div>
                @endif
                
                <h2 class="text-2xl font-serif text-white mb-1">{{ $staff->first_name }} {{ $staff->last_name }}</h2>
                <p class="text-gray-400 text-sm mb-3">{{ $staff->position ?? 'Staff Member' }}</p>
                
                <div class="flex items-center justify-center gap-2">
                    @if($staff->is_active)
                    <span class="badge badge-confirmed">Active</span>
                    @else
                    <span class="badge badge-cancelled">Inactive</span>
                    @endif
                    
                    @if($staff->can_accept_bookings)
                    <span class="badge badge-new">Accepting Bookings</span>
                    @endif
                </div>
            </div>

            <!-- Contact Info -->
            <div class="p-6 border-t border-white/5">
                <h3 class="text-xs font-bold text-gray-500 uppercase mb-4">Contact Information</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope text-gold-500 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="text-white text-sm">{{ $staff->email }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <i class="fas fa-phone text-gold-500 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="text-white text-sm">{{ $staff->phone_no }}</p>
                        </div>
                    </div>
                    
                    @if($staff->hire_date)
                    <div class="flex items-center gap-3">
                        <i class="fas fa-calendar-check text-gold-500 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Hire Date</p>
                            <p class="text-white text-sm">{{ $staff->hire_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($staff->hourly_rate)
                    <div class="flex items-center gap-3">
                        <i class="fas fa-dollar-sign text-gold-500 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Hourly Rate</p>
                            <p class="text-white text-sm">${{ number_format($staff->hourly_rate, 2) }}/hr</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Role & Permissions -->
            <div class="p-6 border-t border-white/5">
                <h3 class="text-xs font-bold text-gray-500 uppercase mb-4">Role & Access</h3>
                
                @if($staff->roleDetails)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white font-medium">{{ $staff->roleDetails->display_name }}</span>
                        <span class="text-xs text-gray-500">{{ $staff->roleDetails->permissions->count() }} permissions</span>
                    </div>
                    <p class="text-xs text-gray-400">{{ $staff->roleDetails->description }}</p>
                </div>

                <a href="{{ route('admin.roles.show', $staff->roleDetails->id) }}" class="text-gold-500 hover:text-gold-400 text-sm">
                    <i class="fas fa-shield-alt mr-1"></i> View Full Permissions
                </a>
                @else
                <p class="text-gray-500 text-sm">No role assigned</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Staff Stats & Activity -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold text-gray-500 uppercase">Total Bookings</h3>
                    <i class="fas fa-calendar text-blue-500 text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-white">{{ $stats['total_bookings'] }}</p>
            </div>

            <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold text-gray-500 uppercase">Pending</h3>
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-white">{{ $stats['pending_bookings'] }}</p>
            </div>

            <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold text-gray-500 uppercase">Completed</h3>
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-white">{{ $stats['completed_bookings'] }}</p>
            </div>
        </div>

        <!-- Bio Section -->
        @if($staff->bio)
        <div class="bg-dark-900 rounded-lg border border-white/5 p-6">
            <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">About</h3>
            <p class="text-gray-300 leading-relaxed">{{ $staff->bio }}</p>
        </div>
        @endif

        <!-- Recent Bookings -->
        <div class="bg-dark-900 rounded-lg border border-white/5">
            <div class="p-6 border-b border-white/5">
                <h3 class="text-lg font-serif text-white">Recent Bookings</h3>
            </div>
            
            <div class="overflow-x-auto custom-scroll">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                        <tr>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Time</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff->bookings as $booking)
                        <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-white font-medium">{{ $booking->user->first_name }} {{ $booking->user->last_name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $booking->appointment_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4">{{ $booking->appointment_time }}</td>
                            <td class="px-6 py-4">
                                <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-400 hover:text-blue-300">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-4 block"></i>
                                <p>No bookings assigned to this staff member yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <form action="{{ route('admin.staff.toggleStatus', $staff->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-dark-700 hover:bg-dark-600 text-white font-medium text-sm py-2 px-4 rounded-sm">
                    <i class="fas fa-power-off mr-2"></i>
                    {{ $staff->is_active ? 'Deactivate' : 'Activate' }} Staff
                </button>
            </form>

            <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-medium text-sm py-2 px-4 rounded-sm">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Staff
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
