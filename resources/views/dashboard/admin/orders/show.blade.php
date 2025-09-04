@extends('layouts.admin')

@section('title', 'Order Details')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
@endpush

@section('admin_content')
    <div class="container mx-auto">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-700 p-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-700 p-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('components.breadcrumbs', [
            'links' => [
                'Orders' => route('admin.orders.index'),
                'Order Details' => null
            ]
        ])

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('admin.orders.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Order Overview</h1>
                        <p class="mt-1 text-sm text-gray-600">Detailed view of order #{{ $order->short_id }}</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                    View All Orders
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-xl p-8 print:shadow-none print:p-0 printable-voucher">
            <!-- Voucher Header -->
            <div class="flex justify-between items-center border-b pb-4 mb-6 print:border-b-2 print:border-gray-800">
                <div class="text-left">
                    <h1 class="text-3xl font-bold text-gray-800">Order Voucher</h1>
                    <p class="text-sm text-gray-600">#{{ $order->short_id }}</p>
                </div>
                <div class="text-right">
                    <img src="/logos/logo.svg" alt="Company Logo" class="h-8 w-auto inline-block">
                </div>
            </div>

                <!-- Order & Customer Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="text-left">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Order Information</h2>
                        <p class="text-gray-700"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i A') }}</p>
                        <p class="text-gray-700"><strong>Status:</strong> <span class="font-medium text-blue-600">{{ ucfirst($order->status) }}</span></p>
                        <p class="text-gray-700"><strong>Payment Status:</strong> <span class="font-medium text-green-600">Cash on Delivery</span></p>
                    </div>
                    <div class="text-left">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Customer Information</h2>
                        <p class="text-gray-700"><strong>Name:</strong> @if($order->user_id) {{ $order->user->name }} @else {{ $order->address->first_name }} {{ $order->address->last_name }} @endif</p>
                        <p class="text-gray-700"><strong>Email:</strong> @if($order->user_id) {{ $order->user->email ?? 'N/A' }} @else {{ $order->address->email ?? 'N/A' }} @endif</p>
                        <p class="text-gray-700"><strong>Phone:</strong> {{ $order->address->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="mb-6 text-left">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Shipping Address</h2>
                    <p class="text-gray-700">{{ $order->address->street_address ?? 'N/A' }}</p>
                    <p class="text-gray-700">{{ $order->address->union ?? 'N/A' }}, {{ $order->address->upazila ?? 'N/A' }}</p>
                    <p class="text-gray-700">{{ $order->address->district ?? 'N/A' }} - {{ $order->address->postal_code ?? 'N/A' }}</p>
                    @if($order->address->note)
                        <p class="text-gray-700 mt-2"><strong>Note:</strong> {{ Str::limit($order->address->note, 100) }}</p>
                    @endif
                </div>

                <!-- Order Items Table -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Items Ordered</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal print:bg-gray-200">
                                    <th class="py-3 px-6 text-left">SL</th>
                                    <th class="py-3 px-6 text-left">Product</th>
                                    <th class="py-3 px-6 text-center">Qty</th>
                                    <th class="py-3 px-6 text-right">Price</th>
                                    <th class="py-3 px-6 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm font-light">
                                @foreach($order->orderItems as $item)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 print:border-gray-400">
                                        <td class="py-3 px-6 text-left">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ Str::limit($item->product->name ?? 'N/A', 30) }}</td>
                                        <td class="py-3 px-6 text-center">{{ $item->quantity }}</td>
                                        <td class="py-3 px-6 text-right">{{ format_taka($item->price, '') }}</td>
                                        <td class="py-3 px-6 text-right">{{ format_taka($item->quantity * $item->price, '') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-between items-start mb-6">
                    <div class="flex-shrink-0">
                        <p class="text-sm text-gray-600 mb-2">Scan to view order details:</p>
                        {!! QrCode::size(120)->generate(route('order.success', $order->short_id)) !!}
                    </div>
                    <div class="w-full md:w-1/2">
                        @php
                            $subtotalFromItems = 0;
                            foreach ($order->orderItems as $item) {
                                $subtotalFromItems += $item->quantity * $item->price;
                            }
                        @endphp
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-700">Subtotal:</span>
                            <span class="font-medium">{{ format_taka($subtotalFromItems, '') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-700">Shipping:</span>
                            <span class="font-medium">{{ format_taka($order->delivery_charge ?? 0, '') }}</span>
                        </div>
                        @if($order->coupon_discount > 0)
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-700">Coupon Discount:</span>
                                <span class="font-medium text-red-500">- {{ format_taka($order->coupon_discount, '') }}</span>
                            </div>
                        @endif
                        @if($order->reward_discount > 0)
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-700">Reward Discount:</span>
                                <span class="font-medium text-red-500">- {{ format_taka($order->reward_discount, '') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-3 mt-2 border-t-2 border-gray-800 print:border-t-2 print:border-gray-800">
                            <span class="text-xl font-bold text-gray-900">Grand Total:</span>
                            <span class="text-xl font-bold text-blue-600">{{ format_taka($order->total_price) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap gap-3 justify-end">
                <button onclick="printVoucher()" class="action-btn inline-flex items-center px-5 py-2.5 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                    <a href="{{ route('admin.orders.edit', $order) }}" class="action-btn inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[var(--primary-light)] to-cyan-500 text-white font-medium rounded-lg hover:from-[var(--primary)] hover:to-[var(--primary-light)]">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Admin Edit
                    </a>

                    <form id="deleteForm" action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()" class="action-btn inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 text-white font-medium rounded-lg hover:from-red-700 hover:to-rose-700">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Order
                        </button>
                    </form>

                    @if($order->status !== 'delivered' && $order->status !== 'cancelled' && $nextStatus)
                        <form id="statusUpdateForm" action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <button type="submit" class="action-btn inline-flex items-center px-5 py-2.5 bg-green-500 text-white font-medium rounded-lg hover:bg-green-700">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mark as {{ ucfirst($nextStatus) }}
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function printVoucher() {
            window.print();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const statusForm = document.getElementById('statusUpdateForm');
            if (statusForm) {
                statusForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Update Status',
                        text: `Are you sure you want to mark this order as ${this.querySelector('input[name="status"]').value}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Yes, update it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form after confirmation
                            this.submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush
