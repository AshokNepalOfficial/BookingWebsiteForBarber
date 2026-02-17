@extends('layouts.frontend_custom_2')
@section('title', 'My Transactions | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

@section('content')
<div class="pt-32 pb-20 bg-dark-800 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden sticky top-32">
                    <div class="p-6 text-center border-b border-white/5">
                        <div class="w-20 h-20 bg-gold-500 rounded-full mx-auto flex items-center justify-center text-dark-900 text-3xl font-bold mb-4">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-serif text-white">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>
                    <nav class="p-4 space-y-1">
                        <a href="{{ route('profile.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-th-large w-5"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.bookings') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="far fa-calendar-alt w-5"></i> My Bookings
                        </a>
                        <a href="{{ route('profile.membership') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-crown w-5"></i> Membership
                        </a>
                        <a href="{{ route('profile.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gold-500 text-dark-900 font-bold transition-all">
                            <i class="fas fa-history w-5"></i> Transactions
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-user-edit w-5"></i> Edit Profile
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <h1 class="text-3xl font-serif text-white mb-8">Transaction History</h1>

                <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-dark-700">
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold">Transaction</th>
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold">Amount</th>
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold">Method</th>
                                    <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($transactions as $tx)
                                <tr class="text-gray-300">
                                    <td class="px-6 py-4">
                                        <div class="text-white font-medium">{{ ucfirst($tx->transaction_type) }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $tx->created_at->format('M d, Y h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-white">
                                        {!! $formatPrice($tx->amount) !!}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ ucfirst($tx->payment_method) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 {{ $tx->verification_status === 'verified' ? 'bg-green-500/10 text-green-500' : 'bg-yellow-500/10 text-yellow-500' }} text-[10px] rounded uppercase font-bold">
                                            {{ $tx->verification_status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">No transactions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
