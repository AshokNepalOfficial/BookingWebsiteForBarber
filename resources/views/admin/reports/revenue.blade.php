@extends('layouts.admin')

@section('title', 'Revenue Reports | Admin Panel')

@section('page-title', 'Revenue Reports')

@section('content')

<!-- Filters -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.revenue') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Period</label>
            <select name="period" class="input-dark">
                <option value="hourly" {{ $period == 'hourly' ? 'selected' : '' }}>Hourly</option>
                <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>
        
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

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-gradient-to-br from-green-500/10 to-green-600/5 border border-green-500/20 rounded-lg p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-400 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Total Revenue</p>
                <h3 class="text-3xl font-bold text-white">{!! $formatPrice($totalRevenue) !!}</h3>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/5 border border-blue-500/20 rounded-lg p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-receipt text-blue-400 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Total Transactions</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalTransactions) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    @if($period == 'hourly')
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Hour</th>
                    @elseif($period == 'daily')
                    <th class="px-6 py-3">Date</th>
                    @elseif($period == 'monthly')
                    <th class="px-6 py-3">Month</th>
                    @else
                    <th class="px-6 py-3">Year</th>
                    @endif
                    <th class="px-6 py-3">Transactions</th>
                    <th class="px-6 py-3">Revenue</th>
                    <th class="px-6 py-3">Average</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    @if($period == 'hourly')
                    <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-white">{{ sprintf('%02d:00', $row->hour) }} - {{ sprintf('%02d:59', $row->hour) }}</td>
                    @elseif($period == 'daily')
                    <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                    @elseif($period == 'monthly')
                    <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::create($row->year, $row->month)->format('F Y') }}</td>
                    @else
                    <td class="px-6 py-4 text-white">{{ $row->year }}</td>
                    @endif
                    <td class="px-6 py-4 text-white font-medium">{{ number_format($row->count) }}</td>
                    <td class="px-6 py-4 text-green-400 font-bold">{!! $formatPrice($row->total) !!}</td>
                    <td class="px-6 py-4 text-gray-300">{!! $formatPrice($row->total / $row->count) !!}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-chart-line text-4xl mb-4 block"></i>
                        <p>No revenue data found for selected period</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            <tfoot class="bg-dark-800">
                <tr class="border-t border-white/10">
                    <td class="px-6 py-4 font-bold text-white" colspan="{{ $period == 'hourly' ? 2 : 1 }}">TOTAL</td>
                    <td class="px-6 py-4 font-bold text-white">{{ number_format($totalTransactions) }}</td>
                    <td class="px-6 py-4 font-bold text-green-400">{!! $formatPrice($totalRevenue) !!}</td>
                    <td class="px-6 py-4 font-bold text-gray-300">{!! $formatPrice($totalRevenue / max($totalTransactions, 1)) !!}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
