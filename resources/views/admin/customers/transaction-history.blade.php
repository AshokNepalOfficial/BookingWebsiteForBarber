@extends('layouts.admin')
@section('title', 'Transaction History - ' . $customer->first_name . ' ' . $customer->last_name)
@section('page-title', 'Transaction History')

@section('content')

<!-- Customer Info Card -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gold-500/20 flex items-center justify-center">
                <i class="fas fa-user text-gold-500 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-serif text-white mb-1">
                    {{ $customer->first_name }} {{ $customer->last_name }}
                    @if($customer->walkin_token)
                    <span class="text-sm font-mono text-gold-500 ml-2">
                        <i class="fas fa-ticket-alt"></i> {{ $customer->walkin_token }}
                    </span>
                    @endif
                </h2>
                <p class="text-gray-400">{{ $customer->email }}</p>
            </div>
        </div>
        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
        </a>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-800 border-b border-white/5">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Payment Method</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Reference</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($transactions as $transaction)
            <tr class="hover:bg-dark-800/50">
                <td class="px-6 py-4 text-white font-mono">#{{ $transaction->id }}</td>
                <td class="px-6 py-4 text-gray-300">
                    <div class="font-medium">{{ $transaction->created_at->format('M d, Y') }}</div>
                    <div class="text-sm text-gray-500">{{ $transaction->created_at->format('h:i A') }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-300">
                        {{ ucwords(str_replace('_', ' ', $transaction->transaction_type)) }}
                    </span>
                    @if($transaction->booking)
                    <div class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-link mr-1"></i> Booking #{{ $transaction->booking_id }}
                    </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-white font-semibold">
                    {!! $formatPrice($transaction->amount) !!}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded bg-dark-800 text-gray-300">
                        {{ ucfirst($transaction->payment_method) }}
                    </span>
                    @if($transaction->is_offline)
                    <span class="ml-1 text-xs text-gray-500">(Offline)</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($transaction->transaction_reference)
                    <span class="text-sm font-mono text-gray-300">{{ $transaction->transaction_reference }}</span>
                    @else
                    <span class="text-xs text-gray-600">N/A</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded
                        {{ $transaction->verification_status === 'verified' ? 'bg-green-500/20 text-green-400' : '' }}
                        {{ $transaction->verification_status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                        {{ $transaction->verification_status === 'rejected' ? 'bg-red-500/20 text-red-400' : '' }}">
                        {{ ucfirst($transaction->verification_status) }}
                    </span>
                    @if($transaction->verified_at && $transaction->verifier)
                    <div class="text-xs text-gray-500 mt-1">
                        by {{ $transaction->verifier->first_name }}
                    </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                       class="text-blue-400 hover:text-blue-300">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-3 block"></i>
                    No transactions found for this customer.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($transactions->hasPages())
<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    @include('partials.per-page-selector')
    <div class="flex-1 flex justify-center sm:justify-end">
        {{ $transactions->links() }}
    </div>
</div>
@endif

@endsection
