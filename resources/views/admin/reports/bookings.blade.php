@extends('layouts.admin')

@section('title', 'Booking Reports | Admin Panel')

@section('page-title', 'Booking Reports')

@section('content')

<!-- Filters -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.bookings') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Period</label>
            <select name="period" class="input-dark">
                <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
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

<!-- Bookings Table -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden">
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    @if($period == 'daily')
                    <th class="px-6 py-3">Date</th>
                    @else
                    <th class="px-6 py-3">Month</th>
                    @endif
                    <th class="px-6 py-3">Total Bookings</th>
                    <th class="px-6 py-3">Completed</th>
                    <th class="px-6 py-3">Pending</th>
                    <th class="px-6 py-3">Cancelled</th>
                    <th class="px-6 py-3">Completion Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    @if($period == 'daily')
                    <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                    @else
                    <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::create($row->year, $row->month)->format('F Y') }}</td>
                    @endif
                    <td class="px-6 py-4 text-white font-bold">{{ number_format($row->total) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded bg-green-500/20 text-green-400 font-medium">
                            {{ number_format($row->completed) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400 font-medium">
                            {{ number_format($row->pending) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded bg-red-500/20 text-red-400 font-medium">
                            {{ number_format($row->cancelled) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $rate = $row->total > 0 ? ($row->completed / $row->total) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="w-24 bg-dark-800 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400">{{ number_format($rate, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-calendar-alt text-4xl mb-4 block"></i>
                        <p>No booking data found for selected period</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            <tfoot class="bg-dark-800">
                <tr class="border-t border-white/10">
                    <td class="px-6 py-4 font-bold text-white">TOTAL</td>
                    <td class="px-6 py-4 font-bold text-white">{{ number_format($data->sum('total')) }}</td>
                    <td class="px-6 py-4 font-bold text-green-400">{{ number_format($data->sum('completed')) }}</td>
                    <td class="px-6 py-4 font-bold text-yellow-400">{{ number_format($data->sum('pending')) }}</td>
                    <td class="px-6 py-4 font-bold text-red-400">{{ number_format($data->sum('cancelled')) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $totalRate = $data->sum('total') > 0 ? ($data->sum('completed') / $data->sum('total')) * 100 : 0;
                        @endphp
                        <span class="text-white font-bold">{{ number_format($totalRate, 1) }}%</span>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
