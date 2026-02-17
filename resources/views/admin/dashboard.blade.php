@extends('layouts.admin')

@section('title', 'Dashboard | Admin Panel')

@section('page-title', 'Dashboard Overview')

@section('header-actions')
<a href="{{ url('/') }}" target="_blank" class="bg-gold-500 hover:bg-gold-600 text-dark-900 font-bold text-xs sm:text-sm py-2 px-3 sm:px-4 rounded-sm whitespace-nowrap shadow-lg">
    <i class="fas fa-external-link-alt sm:mr-2"></i> <span class="hidden sm:inline">Visit Site</span>
</a>
@endsection

@section('content')

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Today's Revenue</p>
        <h3 class="text-2xl font-bold text-white mt-1">{!! $formatPrice($todayRevenue ?? 0) !!}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
         <p class="text-gray-400 text-xs uppercase">Pending Requests</p>
         <h3 class="text-2xl font-bold text-gold-500 mt-1">{{ $pendingBookings ?? 0 }}</h3>
    </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
        <p class="text-gray-400 text-xs uppercase">Total Customers</p>
        <h3 class="text-2xl font-bold text-blue-400 mt-1">{{ $totalCustomers ?? 0 }}</h3>
   </div>
    <div class="bg-dark-900 p-6 rounded-lg border border-white/5">
         <p class="text-gray-400 text-xs uppercase">Active Memberships</p>
         <h3 class="text-2xl font-bold text-white mt-1">{{ $activeMemberships ?? 0 }}</h3>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-dark-900 rounded-lg border border-white/5 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-white/5 flex justify-between items-center">
        <h3 class="font-serif text-lg text-white">Recent Bookings</h3>
        <a href="{{ route('admin.bookings.index') }}" class="text-gold-500 text-sm hover:text-gold-400">View All →</a>
    </div>
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full text-sm text-left text-gray-400 min-w-[900px]">
            <thead class="text-xs text-gray-500 uppercase bg-dark-700">
                <tr>
                    <th class="px-6 py-3">Client</th>
                    <th class="px-6 py-3">Service</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings ?? [] as $booking)
                <tr class="border-b border-white/5 hover:bg-dark-800 transition-colors">
                    <td class="px-6 py-4 font-medium text-white">
                        {{ $booking->user->first_name }} {{ $booking->user->last_name }}
                    </td>
                    <td class="px-6 py-4">
                        @foreach($booking->services as $service)
                            {{ $service->title }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="px-6 py-4">
                        {{ $booking->appointment_date->format('M d, Y') }} at {{ $booking->appointment_time }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-gold-500 hover:text-gold-400">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        No bookings found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Time-Based Revenue Chart -->
<div class="bg-dark-900 rounded-lg border border-white/5 p-6">
    <h3 class="font-serif text-lg text-white mb-4">Today's Revenue by Hour</h3>
    <canvas id="revenueChart" class="w-full" style="max-height: 300px;"></canvas>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    const hourlyData = @json($hourlyRevenue ?? []);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hourlyData.map(d => d.hour),
            datasets: [{
                label: 'Revenue',
                data: hourlyData.map(d => d.revenue),
                backgroundColor: 'rgba(212, 175, 55, 0.8)',
                borderColor: 'rgba(212, 175, 55, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    padding: 12,
                    titleColor: '#D4AF37',
                    bodyColor: '#fff',
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: Rs ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9CA3AF',
                        callback: function(value) {
                            return 'Rs ' + value;
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        color: '#9CA3AF'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
