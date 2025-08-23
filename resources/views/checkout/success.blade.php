@extends('layouts.app')

@section('title', 'Order Confirmation & Voucher')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-xl p-8 print:shadow-none print:p-0 printable-voucher print-only">
                <!-- Voucher Header -->
                <div class="flex justify-between items-center border-b pb-4 mb-6 print:border-b-2 print:border-gray-800">
                    <div class="text-left">
                        <h1 class="text-3xl font-bold text-gray-800">Order Voucher</h1>
                        <p class="text-sm text-gray-600">#{{ $order->short_id }}</p>
                    </div>
                    <div class="text-right">
                        <!-- Replace with your actual logo -->
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
                        <p class="text-gray-700"><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-gray-700"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
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
                                        <td class="py-3 px-6 text-right">{{ format_taka($item->price) }}</td>
                                        <td class="py-3 px-6 text-right">{{ format_taka($item->quantity * $item->price) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="flex justify-end mb-6">
                    <div class="w-full md:w-1/2">
                        @php
                            $subtotalFromItems = 0;
                            foreach ($order->orderItems as $item) {
                                $subtotalFromItems += $item->quantity * $item->price;
                            }
                        @endphp
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-700">Subtotal:</span>
                            <span class="font-medium">{{ format_taka($subtotalFromItems) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-700">Shipping:</span>
                            <span class="font-medium">{{ format_taka($order->delivery_charge ?? 0) }}</span>
                        </div>
                        @if($order->coupon_discount > 0)
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-700">Coupon Discount:</span>
                                <span class="font-medium text-red-500">- {{ format_taka($order->coupon_discount) }}</span>
                            </div>
                        @endif
                        @if($order->reward_discount > 0)
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-700">Reward Discount:</span>
                                <span class="font-medium text-red-500">- {{ format_taka($order->reward_discount) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-3 mt-2 border-t-2 border-gray-800 print:border-t-2 print:border-gray-800">
                            <span class="text-xl font-bold text-gray-900">Grand Total:</span>
                            <span class="text-xl font-bold text-blue-600">{{ format_taka($order->total_price) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Barcode/QR Code Placeholder -->
                <div class="text-center mb-6 print:hidden">
                    <p class="text-gray-600 text-sm mb-2">Scan for quick order lookup</p>
                    <div class="visible-print-block">
                        {!! QrCode::size(150)->generate(route('order.success', ['short_id' => $order->short_id])) !!}
                    </div>
                </div>

                <!-- Thank You Message -->
                <div class="text-center text-gray-700 text-lg mb-6">
                    <p>Thank you for your purchase! We appreciate your business.</p>
                </div>

                <!-- Print Button -->
                <div class="text-center print:hidden">
                    <button id="print-voucher-btn" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-lg font-semibold">
                        <i class="fas fa-print mr-2"></i> Print Voucher
                    </button>
                    <button id="print-sticker-btn" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 text-lg font-semibold ml-4">
                        <i class="fas fa-sticky-note mr-2"></i> Print Sticker
                    </button>
                </div>
            </div> <!-- Close printable-voucher -->

            <!-- Printable Sticker Content (Hidden by default) -->
            <div class="printable-sticker hidden print-only">
                <h1 class="text-center">Shipping Label</h1>
                <div class="address">
                    <p><strong>To:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                    <p>{{ $order->address->street_address ?? 'N/A' }}</p>
                    <p>{{ $order->address->union ?? 'N/A' }}, {{ $order->address->upazila ?? 'N/A' }}</p>
                    <p>{{ $order->address->district ?? 'N/A' }} - {{ $order->address->postal_code ?? 'N/A' }}</p>
                    <p>Phone: {{ $order->address->phone ?? 'N/A' }}</p>
                </div>
                <div class="order-details">
                    <p><strong>Order ID:</strong> #{{ $order->short_id }}</p>
                    <p><strong>Items:</strong></p>
                    <ul>
                        @foreach($order->orderItems as $item)
                            <li>{{ $item->quantity }} x {{ Str::limit($item->product->name ?? 'N/A', 20) }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="qr-code">
                    {!! QrCode::size(100)->generate(route('order.success', ['short_id' => $order->short_id])) !!}
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printVoucherBtn = document.getElementById('print-voucher-btn');
            const printStickerBtn = document.getElementById('print-sticker-btn');

            function printContent(type) {
                document.body.classList.add('printing-' + type);
                window.print();
                document.body.classList.remove('printing-' + type);
            }

            if (printVoucherBtn) {
                printVoucherBtn.addEventListener('click', function() {
                    printContent('voucher');
                });
            }

            if (printStickerBtn) {
                printStickerBtn.addEventListener('click', function() {
                    printContent('sticker');
                });
            }

            const orderData = {
                id: '{{ $order->id }}',
                total: {{ $order->total_price ?? 0 }},
                currency: 'BDT',
                items: [
                    @foreach($order->orderItems as $item)
                        {
                            item_id: '{{ $item->product_id ?? 'N/A' }}',
                            item_name: '{{ Str::limit($item->product->name ?? 'N/A', 30) }}',
                            price: {{ $item->price ?? 0 }},
                            quantity: {{ $item->quantity ?? 0 }}
                        },
                    @endforeach
                ]
            };

            // Meta Pixel Purchase
            if (typeof fbq === 'function') {
                fbq('track', 'Purchase', {
                    value: orderData.total,
                    currency: orderData.currency,
                    content_ids: orderData.items.map(item => item.item_id),
                    content_type: 'product_group'
                });
            }

            // Google Tag Purchase
            if (typeof gtag === 'function') {
                gtag('event', 'purchase', {
                    transaction_id: orderData.id,
                    value: orderData.total,
                    currency: orderData.currency,
                    items: orderData.items.map(item => ({
                        item_id: item.item_id,
                        item_name: item.item_name,
                        price: item.price,
                        quantity: item.quantity
                    }))
                });
            }

            // TikTok Pixel Purchase
            if (typeof ttq === 'object' && typeof ttq.track === 'function') {
                ttq.track('CompletePayment', {
                    content_id: orderData.items.map(item => item.item_id).join(','),
                    content_type: 'product',
                    value: orderData.total,
                    currency: orderData.currency
                });
            }
        });
    </script>
@endpush
