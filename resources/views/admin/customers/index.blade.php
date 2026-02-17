@extends('layouts.admin')

@section('title', 'Customers Management | Admin Panel')

@section('page-title', 'Customers Management')

@section('header-actions')
@if($canAccess('customers'))
<a href="{{ route('admin.customers.create') }}" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Add Customer</span>
</a>
@endif
@endsection

@section('content')

<!-- Customers Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[900px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Total Bookings</th>
                    <th class="px-6 py-3">Loyalty Points</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers ?? [] as $customer)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->first_name . ' ' . $customer->last_name) }}&background=EAB308&color=000" 
                                 class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-medium text-white">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                                <p class="text-xs text-gray-500">ID: #{{ $customer->id }}</p>
                                @if($customer->walkin_token)
                                <p class="text-xs text-gold-500 font-mono mt-1">
                                    <i class="fas fa-ticket-alt mr-1"></i> {{ $customer->walkin_token }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $customer->email }}</td>
                    <td class="px-6 py-4">{{ $customer->phone_no }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-white">{{ $customer->bookings_count ?? 0 }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-gold-500/20 text-gold-400 rounded-full font-bold">
                                {{ $customer->loyalty_points ?? 0 }}
                            </span>
                            <span class="text-xs text-gray-500">/ 10</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($customer->is_guest)
                        <span class="badge badge-new">Guest</span>
                        @else
                        <span class="badge badge-confirmed">Registered</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" 
                               class="text-blue-400 hover:text-blue-300" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" 
                               class="text-gold-500 hover:text-gold-400" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-4 block"></i>
                        <p>No customers found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if(isset($customers) && $customers->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $customers->links() }}
    </div>
</div>
@endif

@endsection
