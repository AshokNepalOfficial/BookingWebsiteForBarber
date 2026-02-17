@extends('layouts.admin')

@section('title', 'Visitor Analytics | Admin Panel')

@section('page-title', 'Visitor Analytics')

@section('content')

<!-- Filters -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.visitors') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input-dark">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input-dark">
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold px-6 py-2 rounded w-full">
                <i class="fas fa-filter mr-2"></i> Apply Filter
            </button>
        </div>
    </form>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-400 text-sm">Total Visits</p>
            <i class="fas fa-eye text-blue-500"></i>
        </div>
        <h3 class="text-2xl font-bold text-white">{{ number_format($stats['total_visits']) }}</h3>
    </div>

    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-400 text-sm">Unique Visitors</p>
            <i class="fas fa-users text-green-500"></i>
        </div>
        <h3 class="text-2xl font-bold text-white">{{ number_format($stats['unique_visitors']) }}</h3>
    </div>

    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-400 text-sm">Mobile Visits</p>
            <i class="fas fa-mobile-alt text-purple-500"></i>
        </div>
        <h3 class="text-2xl font-bold text-white">{{ number_format($stats['mobile_visits']) }}</h3>
    </div>

    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-gray-400 text-sm">Desktop Visits</p>
            <i class="fas fa-desktop text-gold-500"></i>
        </div>
        <h3 class="text-2xl font-bold text-white">{{ number_format($stats['desktop_visits']) }}</h3>
    </div>
</div>

<!-- Device & Browser Breakdown -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Device Stats -->
    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <h4 class="text-white font-medium mb-4">Device Breakdown</h4>
        <div class="space-y-3">
            @foreach($deviceStats as $device)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-400 capitalize">{{ $device->device_type ?? 'Unknown' }}</span>
                    <span class="text-sm text-white font-medium">{{ number_format($device->count) }}</span>
                </div>
                <div class="w-full bg-dark-800 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($device->count / $stats['total_visits']) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Browser Stats -->
    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <h4 class="text-white font-medium mb-4">Top Browsers</h4>
        <div class="space-y-3">
            @foreach($browserStats as $browser)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-400">{{ $browser->browser ?? 'Unknown' }}</span>
                    <span class="text-sm text-white font-medium">{{ number_format($browser->count) }}</span>
                </div>
                <div class="w-full bg-dark-800 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($browser->count / $stats['total_visits']) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Platform Stats -->
    <div class="bg-dark-900 border border-white/5 rounded-lg p-6">
        <h4 class="text-white font-medium mb-4">Operating Systems</h4>
        <div class="space-y-3">
            @foreach($platformStats as $platform)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-gray-400">{{ $platform->platform ?? 'Unknown' }}</span>
                    <span class="text-sm text-white font-medium">{{ number_format($platform->count) }}</span>
                </div>
                <div class="w-full bg-dark-800 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ ($platform->count / $stats['total_visits']) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Visitor Logs -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="px-6 py-4 border-b border-white/5">
        <h3 class="text-white font-medium">Recent Visitors</h3>
    </div>
    
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[1000px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">IP Address</th>
                    <th class="px-6 py-3">Device</th>
                    <th class="px-6 py-3">Browser</th>
                    <th class="px-6 py-3">Platform</th>
                    <th class="px-6 py-3">URL</th>
                    <th class="px-6 py-3">Visit Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $visitor)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4 text-white font-mono text-xs">{{ $visitor->ip_address }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded capitalize {{ $visitor->device_type == 'mobile' ? 'bg-purple-500/20 text-purple-400' : ($visitor->device_type == 'tablet' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400') }}">
                            {{ $visitor->device_type ?? 'Unknown' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $visitor->browser ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $visitor->platform ?? 'Unknown' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-blue-400 text-xs truncate block max-w-xs">{{ Str::limit($visitor->url, 50) }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $visitor->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-4 block"></i>
                        <p>No visitor data found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($visitors->hasPages())
<div class="mt-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-400">
            Showing {{ $visitors->firstItem() }} to {{ $visitors->lastItem() }} of {{ $visitors->total() }} visitors
        </div>
        <div class="flex items-center gap-4">
            <form method="GET" action="{{ route('admin.reports.visitors') }}" class="flex items-center gap-2">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                <label class="text-sm text-gray-400">Per page:</label>
                <select name="per_page" onchange="this.form.submit()" class="bg-dark-800 border border-white/10 text-white text-sm rounded px-3 py-1.5 focus:border-gold-500 focus:outline-none">
                    <option value="25" {{ request('per_page', 50) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page', 50) == 100 ? 'selected' : '' }}>100</option>
                    <option value="200" {{ request('per_page', 50) == 200 ? 'selected' : '' }}>200</option>
                </select>
            </form>
            {{ $visitors->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endif

@endsection
