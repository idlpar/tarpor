@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
@endpush

@section('admin_content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Sales Today -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="bg-blue-500 text-white p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sales Today</p>
                    <p class="text-2xl font-bold text-gray-800">৳{{ number_format($salesToday, 2) }}</p>
                </div>
            </div>
        </div>
        <!-- Sales This Week -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="bg-green-500 text-white p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sales This Week</p>
                    <p class="text-2xl font-bold text-gray-800">৳{{ number_format($salesThisWeek, 2) }}</p>
                </div>
            </div>
        </div>
        <!-- Sales This Month -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="bg-yellow-500 text-white p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sales This Month</p>
                    <p class="text-2xl font-bold text-gray-800">৳{{ number_format($salesThisMonth, 2) }}</p>
                </div>
            </div>
        </div>
        <!-- Orders Today -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="bg-red-500 text-white p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Orders Today</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $ordersToday }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Sales (Last 7 Days)</h2>
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <!-- New Customers Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">New Customers (Last 7 Days)</h2>
            <div class="chart-container">
                <canvas id="customersChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Orders -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Recent Orders</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Order ID</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Customer</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Total</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr class="border-b">
                                <td class="py-3 px-4 text-sm"><a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:underline">#{{ $order->id }}</a></td>
                                <td class="py-3 px-4 text-sm">{{ $order->user->name }}</td>
                                <td class="py-3 px-4 text-sm">৳{{ number_format($order->total_price, 2) }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @switch($order->status)
                                            @case('pending') bg-yellow-200 text-yellow-800 @break
                                            @case('processing') bg-blue-200 text-blue-800 @break
                                            @case('shipped') bg-green-200 text-green-800 @break
                                            @case('delivered') bg-gray-200 text-gray-800 @break
                                            @case('cancelled') bg-red-200 text-red-800 @break
                                        @endswitch">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">No recent orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Top Selling Products -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Top Selling Products</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Product</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">SKU</th>
                            <th class="text-right py-3 px-4 font-semibold text-sm">Quantity Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topSellingProducts as $product)
                            <tr class="border-b">
                                <td class="py-3 px-4 text-sm"><a href="{{ route('products.show', $product->id) }}" class="text-blue-500 hover:underline">{{ $product->name }}</a></td>
                                <td class="py-3 px-4 text-sm">{{ $product->sku }}</td>
                                <td class="py-3 px-4 text-sm text-right">{{ $product->quantity_sold ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">No product sales data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesLast7Days);
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: Object.keys(salesData),
            datasets: [{
                label: 'Sales',
                data: Object.values(salesData),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Customers Chart
    const customersCtx = document.getElementById('customersChart').getContext('2d');
    const customersData = @json($newCustomersLast7Days);
    new Chart(customersCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(customersData),
            datasets: [{
                label: 'New Customers',
                data: Object.values(customersData),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush