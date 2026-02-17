@extends('layouts.admin')

@section('title', 'Transactions | Admin Panel')

@section('page-title', 'Transactions')

@section('content')

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Total Revenue</p>
        <h3 class="text-2xl font-bold text-gold-500 mt-1">{!! $formatPrice($totalRevenue ?? 0) !!}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
         <p class="text-gray-400 text-xs uppercase">Pending Verification</p>
         <h3 class="text-2xl font-bold text-gold-500 mt-1">{{ $pendingCount ?? 0 }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">This Month</p>
        <h3 class="text-2xl font-bold text-white mt-1">{!! $formatPrice($monthlyRevenue ?? 0) !!}</h3>
   </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
         <p class="text-gray-400 text-xs uppercase">Today</p>
         <h3 class="text-2xl font-bold text-white mt-1">{!! $formatPrice($todayRevenue ?? 0) !!}</h3>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[1000px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Payment Method</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions ?? [] as $transaction)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4">#{{ $transaction->id }}</td>
                    <td class="px-6 py-4 font-medium text-white">
                        {{ $transaction->user->first_name ?? '' }} {{ $transaction->user->last_name ?? '' }}
                    </td>
                    <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type ?? '')) }}</td>
                    <td class="px-6 py-4 font-bold text-gold-500">{!! $formatPrice($transaction->amount ?? 0) !!}</td>
                    <td class="px-6 py-4">{{ ucfirst($transaction->payment_method ?? '') }}</td>
                    <td class="px-6 py-4">
                        <span class="badge badge-{{ $transaction->verification_status ?? 'pending' }}">
                            {{ ucfirst($transaction->verification_status ?? 'Pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-dollar-sign text-4xl mb-4 block"></i>
                        <p>No transactions found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if(isset($transactions) && $transactions->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $transactions->links() }}
    </div>
</div>
@endif

@endsection
