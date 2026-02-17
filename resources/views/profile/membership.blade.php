@extends('layouts.frontend_custom_2')
@section('title', 'My Membership | ' . ($setting('site_name') ?? 'JB Barber Unisex Salon'))

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
                        <a href="{{ route('profile.membership') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gold-500 text-dark-900 font-bold transition-all">
                            <i class="fas fa-crown w-5"></i> Membership
                        </a>
                        <a href="{{ route('profile.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
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
                <h1 class="text-3xl font-serif text-white mb-8">Membership Plans</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    @php
                        $availableMemberships = \App\Models\Membership::where('is_active', true)->get();
                    @endphp
                    @foreach($availableMemberships as $plan)
                    <div class="bg-dark-900 rounded-2xl border border-white/5 overflow-hidden hover:border-gold-500/30 transition-all flex flex-col">
                        <div class="p-8 border-b border-white/5">
                            <h3 class="text-2xl font-serif text-white mb-2">{{ $plan->name }}</h3>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-bold text-gold-500">{!! $formatPrice($plan->price) !!}</span>
                                <span class="text-gray-500 text-sm">/ {{ $plan->duration_days }} Days</span>
                            </div>
                        </div>
                        <div class="p-8 flex-1">
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center gap-3 text-gray-300">
                                    <i class="fas fa-check text-gold-500 text-sm"></i> {{ $plan->discount_percentage }}% Off on all services
                                </li>
                                @if($plan->benefits)
                                    @foreach(explode("\n", $plan->benefits) as $benefit)
                                    <li class="flex items-center gap-3 text-gray-300">
                                        <i class="fas fa-check text-gold-500 text-sm"></i> {{ $benefit }}
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                            <button class="w-full bg-white/5 hover:bg-gold-500 hover:text-dark-900 text-white font-bold py-3 rounded-xl transition-all border border-white/10 hover:border-gold-500">
                                Select Plan
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h2 class="text-2xl font-serif text-white mb-6">Membership History</h2>
                <div class="bg-dark-900 rounded-xl border border-white/5 overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-dark-700">
                                <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold">Plan</th>
                                <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold">Duration</th>
                                <th class="px-6 py-4 text-xs uppercase tracking-widest text-gray-400 font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($memberships as $um)
                            <tr class="text-gray-300">
                                <td class="px-6 py-4">
                                    <div class="text-white font-medium">{{ $um->membership->name }}</div>
                                    <div class="text-[10px] text-gray-500">Purchased on {{ $um->start_date->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $um->start_date->format('M d') }} - {{ $um->end_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 {{ $um->status === 'active' ? 'bg-green-500/10 text-green-500' : 'bg-gray-500/10 text-gray-500' }} text-[10px] rounded uppercase font-bold">
                                        {{ $um->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500 italic">No membership history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
