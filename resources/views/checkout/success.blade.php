@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
    <div class="container mx-auto px-4 py-8 text-center">
        <h1 class="text-4xl font-bold text-green-600 mb-4">Thank You for Your Order!</h1>
        <p class="text-lg text-gray-700 mb-8">Your order #{{ $order->id }} has been successfully placed.</p>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Order Details</h2>
            <div class="text-left space-y-2">
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Total Amount:</strong> BDT {{ number_format($order->total, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i A') }}</p>
            </div>

            <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-3">Items Ordered:</h3>
            <ul class="text-left space-y-2">
                @foreach($order->products as $item)
                    <li>{{ $item->pivot->quantity }} x {{ $item->name ?? 'N/A' }} (BDT {{ number_format($item->pivot->price, 2) }} each)</li>
                @endforeach
            </ul>

            <div class="mt-8">
                <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200">Continue Shopping</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderData = {
                id: '{{ $order->id }}',
                total: {{ $order->total }},
                currency: 'BDT',
                items: [
                    @foreach($order->products as $item)
                        {
                            item_id: '{{ $item->id ?? 'N/A' }}',
                            item_name: '{{ $item->name ?? 'N/A' }}',
                            price: {{ $item->pivot->price }},
                            quantity: {{ $item->pivot->quantity }}
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
