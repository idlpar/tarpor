@extends('layouts.admin')

@push('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: #ffedd5;
            color: #9a3412;
        }

        .badge-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-shipped {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .badge-delivered {
            background-color: #dcfce7;
            color: #166534;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background-color: #f9fafb;
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .disabled-pagination-link {
            background-color: #e0e0e0; /* Lighter gray background */
            color: #a0a0a0; /* Muted text color */
            cursor: not-allowed;
            pointer-events: none;
            border: 1px solid #d0d0d0; /* Lighter border */
        }

        .action-btn {
            color: #4b5563;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            color: #111827;
            transform: scale(1.1);
        }

        .date-range-picker {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
        }

        .date-range-picker.active {
            max-height: 200px;
            opacity: 1;
            padding-top: 0.5rem;
        }

        .time-frame-indicator {
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .new-order-btn {
            height: 42px;
            white-space: nowrap;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 0.5rem;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
@endpush

@section('admin_content')

        <div class="container mx-auto px-4 py-4">
            @include('components.ui.breadcrumbs', [
            'links' => [
                'Orders' => null
            ]
        ])

        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order Management</h1>
                    <p class="mt-1 text-sm text-gray-600">View and manage customer orders</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <div class="relative">
                        <form method="GET" action="{{ route('admin.orders.index') }}" id="order-filter-form">
                            <select id="time-frame-filter" name="time_frame"
                                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="all" {{ $filters['time_frame'] == 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="daily" {{ $filters['time_frame'] == 'daily' ? 'selected' : '' }}>Today</option>
                                <option value="yesterday" {{ $filters['time_frame'] == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                <option value="weekly" {{ $filters['time_frame'] == 'weekly' ? 'selected' : '' }}>This Week</option>
                                <option value="monthly" {{ $filters['time_frame'] == 'monthly' ? 'selected' : '' }}>This Month</option>
                                <option value="yearly" {{ $filters['time_frame'] == 'yearly' ? 'selected' : '' }}>This Year</option>
                                <option value="custom" {{ $filters['start_date'] && $filters['end_date'] ? 'selected' : '' }}>Custom Range</option>
                            </select>
                            <div id="date-range-picker" class="date-range-picker {{ $filters['start_date'] && $filters['end_date'] ? 'active' : '' }}">
                                <div class="flex gap-2 mt-2">
                                    <input type="date" name="start_date" value="{{ $filters['start_date'] }}"
                                           class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <input type="date" name="end_date" value="{{ $filters['end_date'] }}"
                                           class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>

                            <select name="status"
                                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md mt-3">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ $filters['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $filters['status'] == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $filters['status'] == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $filters['status'] == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </form>
                    </div>

                    <a href="{{ route('admin.orders.create') }}"
                       class="new-order-btn inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        New Order
                    </a>
                </div>
            </div>

            <!-- Time Frame Indicator -->
            <div class="time-frame-indicator mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">
                        @if($filters['start_date'] && $filters['end_date'])
                            Showing orders from {{ \Carbon\Carbon::parse($filters['start_date'])->format('M j, Y') }}
                            to {{ \Carbon\Carbon::parse($filters['end_date'])->format('M j, Y') }}
                        @else
                            @switch($filters['time_frame'])
                                @case('daily') Today's Orders @break
                                @case('yesterday') Yesterday's Orders @break
                                @case('weekly') This Week's Orders @break
                                @case('monthly') This Month's Orders @break
                                @case('yearly') This Year's Orders @break
                                @default All Orders
                            @endswitch
                        @endif
                    </h2>
                    <span class="text-sm text-gray-500">Last updated: {{ now()->format('g:i A') }}</span>
                </div>
            </div>

            <!-- Stats Cards Placeholder -->
            <div id="stats-cards-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <!-- Spinners or placeholders will go here -->
            </div>

            <!-- Orders Table Placeholder -->
            <div id="orders-table-container" class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                </div>
                <div id="orders-table-body" class="table-responsive">
                    <!-- Spinner or table content will go here -->
                </div>
                <div id="pagination-container" class="px-6 py-4 border-t border-gray-200">
                    <!-- Pagination will go here -->
                </div>
            </div>

            <!-- Spinner for loading data -->
            <div id="loading-spinner" class="text-center py-8" style="display: none;">
                <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-gray-600">Loading orders...</p>
            </div>
        </div>


    <!-- Modal for order items -->
    <div id="orderItemsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Order Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Shipping Information</h3>
                    <p><strong>Method:</strong> <span id="shippingMethod"></span></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Discount Information</h3>
                    <p><strong>Coupon:</strong> <span id="couponCode"></span></p>
                </div>
            </div>

            <hr class="my-6">

            <div>
                <h3 class="text-lg font-semibold mb-2">Products Ordered</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(event) {
            event.preventDefault(); // Prevent the form from submitting immediately
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                focusCancel: true // Focus on the cancel button by default
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Submit the form if confirmed
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const timeFrameFilter = document.getElementById('time-frame-filter');
            const dateRangePicker = document.getElementById('date-range-picker');
            const orderFilterForm = document.getElementById('order-filter-form');
            const statsCardsContainer = document.getElementById('stats-cards-container');
            const ordersTableBody = document.getElementById('orders-table-body');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingSpinner = document.getElementById('loading-spinner');

            // Helper function to format currency (simple version)                                                                                                                    │
            function formatTaka(amount) {
                return parseFloat(amount).toFixed(2);
            }

            // Function to fetch and render order data
            async function fetchOrderData(page = 1) {
                loadingSpinner.style.display = 'block';
                statsCardsContainer.innerHTML = ''; // Clear previous stats
                ordersTableBody.innerHTML = ''; // Clear previous orders
                paginationContainer.innerHTML = ''; // Clear previous pagination

                const formData = new FormData(orderFilterForm);
                const params = new URLSearchParams();
                for (const pair of formData.entries()) {
                    params.append(pair[0], pair[1]);
                }
                params.append('page', page);

                try {
                    const response = await fetch(`${orderFilterForm.action}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest' // Laravel expects this header for ajax() check
                        }
                    });
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    renderStats(data.stats);
                    renderOrdersTable(data.orders.data);
                    renderPagination(data.orders);

                } catch (error) {
                    console.error('Error fetching order data:', error);
                    // Display an error message to the user
                    statsCardsContainer.innerHTML = '<p class="text-red-500">Failed to load data. Please try again.</p>';
                    ordersTableBody.innerHTML = '<p class="text-red-500">Failed to load data. Please try again.</p>';
                } finally {
                    loadingSpinner.style.display = 'none';
                }
            }

            // Function to render stats cards
            function renderStats(stats) {
                statsCardsContainer.innerHTML = `
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending</p>
                                <div class="mt-1">
                                    <p class="text-xl font-semibold">${stats.pendingOrders} orders</p>
                                    <p class="text-sm text-gray-600">${formatTaka(stats.pendingAmount ?? 0)}</p>
                                </div>
                            </div>
                            <div class="bg-yellow-100 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Processing</p>
                                <div class="mt-1">
                                    <p class="text-xl font-semibold">${stats.processingOrders} orders</p>
                                    <p class="text-sm text-gray-600">${formatTaka(stats.processingAmount ?? 0)}</p>
                                </div>
                            </div>
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Shipped</p>
                                <div class="mt-1">
                                    <p class="text-xl font-semibold">${stats.shippedOrders} orders</p>
                                    <p class="text-sm text-gray-600">${formatTaka(stats.shippedAmount ?? 0)}</p>
                                </div>
                            </div>
                            <div class="bg-indigo-100 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Delivered</p>
                                <div class="mt-1">
                                    <p class="text-xl font-semibold">${stats.deliveredOrders} orders</p>
                                    <p class="text-sm text-gray-600">${formatTaka(stats.deliveredAmount ?? 0)}</p>
                                </div>
                            </div>
                            <div class="bg-green-100 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Function to render orders table
            function renderOrdersTable(orders) {
                let tableHtml = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Details</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                if (orders.length === 0) {
                    tableHtml += `
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900">No orders found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new order.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.orders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            New Order
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    orders.forEach(order => {
                        const userHtml = order.user ? `
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <a href="/users/${order.user.id}">
                                    <span class="text-gray-600 font-medium">${order.user.name.charAt(0).toUpperCase()}</span>
                                </a>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">
                                    <a href="/users/${order.user.id}">${order.user.name}</a>
                                </div>
                                <div class="text-sm text-gray-500">${order.user.email}</div>
                            </div>
                        ` : `
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <span class="text-gray-600 font-medium">G</span>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">${order.address && order.address.first_name ? order.address.first_name + ' ' + order.address.last_name : 'Guest'}</div>
                                ${order.address && order.address.email ? `<div class="text-sm text-gray-500">${order.address.email}</div>` : ''}
                            </div>
                        `;

                        tableHtml += `
                            <tr>
                                <td class="font-medium">#${order.id}</td>
                                <td>
                                    <div class="flex items-center">
                                        ${userHtml}
                                    </div>
                                </td>
                                <td>
                                    <button class="text-blue-600 hover:underline" onclick='showOrderItems(${JSON.stringify(order)})'>
                                        View Details
                                    </button>
                                </td>
                                <td class="font-medium">${formatTaka(order.total_price)}</td>
                                <td>
                                    <span class="status-badge badge-${order.status}">
                                        ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                    </span>
                                </td>
                                <td class="text-sm text-gray-500">
                                    ${new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end items-center space-x-2">
                                        <a href="/admin/orders/${order.id}" class="action-btn custom-tooltip-trigger" data-tooltip="View Order">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a href="/admin/orders/${order.id}/edit" class="action-btn custom-tooltip-trigger" data-tooltip="Edit Order">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="/admin/orders/${order.id}" method="POST" class="inline" onsubmit="confirmDelete(event)">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="action-btn text-red-600 hover:text-red-800 custom-tooltip-trigger" data-tooltip="Delete Order">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }

                tableHtml += `
                        </tbody>
                    </table>
                `;
                ordersTableBody.innerHTML = tableHtml;
            }

            // Function to render pagination
            function renderPagination(paginationData) {
                if (paginationData.last_page > 1) {
                    let paginationHtml = `
                        <div class="flex flex-col sm:flex-row items-center justify-between">
                            <div class="text-sm text-gray-500 mb-4 sm:mb-0">
                                Showing <span class="font-medium">${paginationData.from}</span> to <span class="font-medium">${paginationData.to}</span> of <span class="font-medium">${paginationData.total}</span> results
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    `;

                    paginationData.links.forEach(link => {
                        if (link.url) {
                            const pageNum = new URL(link.url).searchParams.get('page') || 1;
                            paginationHtml += `
                                <a href="#" data-page="${pageNum}" class="${link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md">
                                    ${link.label.replace(/&laquo; Previous/, 'Previous').replace(/Next &raquo;/, 'Next')}
                                </a>
                            `;
                        } else {
                            paginationHtml += `
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 cursor-default rounded-md disabled-pagination-link">
                                    ${link.label.replace(/&laquo; Previous/, 'Previous').replace(/Next &raquo;/, 'Next')}
                                </span>
                            `;
                        }
                    });

                    paginationHtml += `
                                </nav>
                            </div>
                        </div>
                    `;
                    paginationContainer.innerHTML = paginationHtml;

                    // Add event listeners for pagination links
                    paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            fetchOrderData(this.dataset.page);
                        });
                    });
                }
            }

            // Initial fetch of data when the page loads
            fetchOrderData();

            // Time frame filter functionality
            if (timeFrameFilter && dateRangePicker && orderFilterForm) {
                timeFrameFilter.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        dateRangePicker.classList.add('active');
                    } else {
                        dateRangePicker.classList.remove('active');
                        fetchOrderData(); // Fetch data on change
                    }
                });
            }

            // Submit form on date range change
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const startDate = document.querySelector('input[name="start_date"]').value;
                    const endDate = document.querySelector('input[name="end_date"]').value;
                    if (startDate && endDate) {
                        fetchOrderData(); // Fetch data on change
                    }
                });
            });

            // Status filter functionality
            const statusFilter = document.querySelector('select[name="status"]');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    fetchOrderData(); // Fetch data on change
                });
            }
        });

        function showOrderItems(order) {
            const modal = document.getElementById('orderItemsModal');
            const shippingMethodSpan = document.getElementById('shippingMethod');
            const couponCodeSpan = document.getElementById('couponCode');
            const tbody = document.getElementById('orderItemsTbody');

            shippingMethodSpan.textContent = order.shipping_method ? order.shipping_method.name : 'N/A';
            couponCodeSpan.textContent = order.coupon ? order.coupon.code : 'N/A';

            tbody.innerHTML = '';
            order.order_items.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.product.name}</td>
                        <td>${item.quantity}</td>
                        <td class="text-right">${item.price}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            modal.style.display = "block";
        }

        function closeModal() {
            const modal = document.getElementById('orderItemsModal');
            modal.style.display = "none";
        }
    </script>
@endpush
