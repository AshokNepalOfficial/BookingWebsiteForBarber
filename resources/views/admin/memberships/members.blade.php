@extends('layouts.admin')

@section('title', 'Active Members | Admin Panel')

@section('page-title', 'Active Members')

@section('content')

<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[900px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">Member</th>
                    <th class="px-6 py-3">Membership Plan</th>
                    <th class="px-6 py-3">Start Date</th>
                    <th class="px-6 py-3">End Date</th>
                    <th class="px-6 py-3">Free Services</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userMemberships ?? [] as $userMembership)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($userMembership->user->first_name . ' ' . $userMembership->user->last_name) }}&background=EAB308&color=000" 
                                 class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-medium text-white">{{ $userMembership->user->first_name }} {{ $userMembership->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $userMembership->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-white font-medium">{{ $userMembership->membership->membership_name }}</p>
                            <p class="text-xs text-gray-500">${{ number_format($userMembership->membership->price, 2) }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $userMembership->start_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        {{ $userMembership->end_date->format('M d, Y') }}
                        @if($userMembership->end_date->isPast())
                        <span class="block text-xs text-red-400 mt-1">Expired</span>
                        @elseif($userMembership->end_date->diffInDays(now()) <= 7)
                        <span class="block text-xs text-yellow-400 mt-1">Expiring Soon</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full font-bold">
                            {{ $userMembership->remaining_free_services ?? 0 }} / {{ $userMembership->membership->free_services_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($userMembership->status === 'active')
                        <span class="badge badge-confirmed">Active</span>
                        @elseif($userMembership->status === 'expired')
                        <span class="badge badge-cancelled">Expired</span>
                        @elseif($userMembership->status === 'cancelled')
                        <span class="badge badge-cancelled">Cancelled</span>
                        @else
                        <span class="badge badge-pending">{{ ucfirst($userMembership->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.customers.show', $userMembership->user_id) }}" 
                               class="text-blue-400 hover:text-blue-300" title="View Customer">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($userMembership->status === 'active' && $userMembership->end_date->diffInDays(now()) <= 30)
                            <form action="{{ route('admin.memberships.renew', $userMembership->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-400 hover:text-green-300" title="Renew">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </form>
                            @endif
                            @if($userMembership->status === 'active')
                            <form action="{{ route('admin.memberships.cancel', $userMembership->id) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this membership?')">
                                @csrf
                                <button type="submit" class="text-red-400 hover:text-red-300" title="Cancel">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-4 block"></i>
                        <p>No active members found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if(isset($userMemberships) && $userMemberships->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $userMemberships->links() }}
    </div>
</div>
@endif

@endsection
