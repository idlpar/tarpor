@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="text-sm font-medium text-gray-500 mb-8">
                <ol class="list-none p-0 inline-flex space-x-2 items-center">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Home</a></li>
                    <li><svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                    <li><a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Shopping Cart</a></li>
                    <li><svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                    <li><span class="text-gray-800">Checkout</span></li>
                </ol>
            </nav>
            <!-- Header with subtle gradient -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-2 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-700">
                    Secure Checkout
                </h1>
                <p class="text-lg text-gray-600">Complete your purchase with confidence</p>
            </div>

            <div class="lg:grid lg:grid-cols-3 lg:gap-8 max-w-screen-xl mx-auto">
                <!-- Billing Information - Elegant Card -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-8 border border-gray-100 mb-8 lg:mb-0 transform transition-all hover:shadow-2xl">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-2 rounded-full mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Billing Information</h2>
                    </div>

                    <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div class="space-y-1">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <div class="relative">
                                    <input type="text" name="first_name" id="first_name"
                                           class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                           required>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-1">
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name"
                                       class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                       required>
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2 space-y-1">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <div class="relative">
                                    <input type="email" name="email" id="email"
                                           class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                           required>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="md:col-span-2 space-y-1">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <div class="relative">
                                    <input type="text" name="phone" id="phone"
                                           class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                           required>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2 space-y-1">
                                <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
                                <div class="relative">
                                    <input type="text" name="address" id="address"
                                           class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                           required>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="space-y-1">
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="city" id="city"
                                       class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                       required>
                            </div>

                            <!-- State -->
                            <div class="space-y-1">
                                <label for="state" class="block text-sm font-medium text-gray-700">State/Province</label>
                                <input type="text" name="state" id="state"
                                       class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                       required>
                            </div>

                            <!-- ZIP -->
                            <div class="space-y-1">
                                <label for="zip" class="block text-sm font-medium text-gray-700">ZIP/Postal Code</label>
                                <input type="text" name="zip" id="zip"
                                       class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                       required>
                            </div>

                            <!-- Country -->
                            <div class="space-y-1">
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <div class="relative">
                                    <input type="text" name="country" id="country"
                                           class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300"
                                           required>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-10">
                            <button type="submit"
                                    class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Complete Secure Payment
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Summary - Elegant Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transform transition-all hover:shadow-2xl">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-2 rounded-full mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Order Summary</h2>
                    </div>

                    <div class="space-y-4">
                        @php $subtotal = 0 @endphp
                        @if(!empty($cart))
                            @foreach($cart as $id => $details)
                                @php $subtotal += $details['price'] * $details['quantity'] @endphp
                                <div class="flex justify-between items-center py-4 border-b border-gray-100 last:border-0 group">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden mr-4">
                                            <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-gray-800 font-medium group-hover:text-blue-600 transition-colors">{{ $details['name'] }}</p>
                                            @if(isset($details['attributes']) && $details['attributes'] !== 'N/A')
                                                <p class="text-sm text-gray-500">{{ $details['attributes'] }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400">Qty: {{ $details['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-800 font-medium">BDT {{ number_format($details['price'] * $details['quantity'], 2) }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">Your cart is empty.</p>
                        @endif
                    </div>

                    <!-- Shipping Method -->
                    <div class="mb-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Shipping Method</h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="shipping_option" value="50" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" {{ $deliveryCharge == 50 ? 'checked' : '' }}>
                                <div class="ml-4 flex-grow">
                                    <span class="font-medium text-gray-800">Standard Shipping</span>
                                    <p class="text-sm text-gray-500">4-5 business days</p>
                                </div>
                                <span class="font-semibold text-gray-800">৳50.00</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="shipping_option" value="120" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" {{ $deliveryCharge == 120 ? 'checked' : '' }}>
                                <div class="ml-4 flex-grow">
                                    <span class="font-medium text-gray-800">Express Shipping</span>
                                    <p class="text-sm text-gray-500">1-2 business days</p>
                                </div>
                                <span class="font-semibold text-gray-800">৳120.00</span>
                            </label>
                        </div>
                    </div>

                    <!-- Coupon Code -->
                    <div class="mb-6">
                        <label for="coupon-code" class="block text-sm font-medium text-gray-700 mb-2">Have a coupon?</label>
                        <form id="apply-coupon-form" class="flex">
                            <input type="text" id="coupon-code" name="code" placeholder="Enter coupon code" class="flex-grow px-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300" value="{{ $coupon['code'] ?? '' }}">
                            <button type="submit" id="apply-coupon-btn" class="bg-gray-800 text-white py-3 px-5 rounded-r-lg font-medium hover:bg-gray-700 transition-colors duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Apply
                            </button>
                        </form>
                        <p id="coupon-feedback" class="text-sm mt-2 @if(isset($coupon) && !empty($coupon)) text-green-600 @endif">{{ isset($coupon) && !empty($coupon) ? 'Coupon ' . $coupon['code'] . ' applied!' : '' }}</p>
                    </div>

                    <!-- Order Totals -->
                    <div class="mt-8 space-y-3">
                        <div class="flex justify-between py-3">
                            <p class="text-gray-600">Subtotal</p>
                            <p class="text-gray-900 font-medium" id="summary-subtotal">BDT {{ number_format($subtotal, 2) }}</p>
                        </div>

                        <div class="flex justify-between py-3" id="summary-delivery-charge-row">
                            <p class="text-gray-600">Shipping</p>
                            <p class="text-gray-900 font-medium" id="summary-delivery-charge">BDT {{ number_format($deliveryCharge, 2) }}</p>
                        </div>

                        <div class="flex justify-between py-3 bg-blue-50 -mx-4 px-4 rounded-lg {{ (isset($coupon) && !empty($coupon)) ? '' : 'hidden' }}" id="summary-coupon-display">
                            <p class="text-gray-600" id="coupon-display-text">Coupon ({{ $coupon['code'] ?? '' }})</p>
                            <p class="text-red-500 font-medium" id="coupon-discount-display">- BDT {{ number_format($coupon['discount'] ?? 0, 2) }}
                                <button type="button" id="remove-coupon-btn" class="ml-2 text-red-400 hover:text-red-600 focus:outline-none">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </p>
                        </div>

                        <div class="flex justify-between pt-4 mt-4 border-t border-gray-200">
                            <p class="text-lg font-bold text-gray-900">Total</p>
                            @php
                                $finalTotal = $subtotal - ($coupon['discount'] ?? 0) + $deliveryCharge;
                                if ($finalTotal < 0) $finalTotal = 0;
                            @endphp
                            <p class="text-xl font-bold text-blue-600" id="order-total">BDT {{ number_format($finalTotal, 2) }}</p>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap justify-center gap-4">
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <p class="text-xs text-gray-500 mt-1">Secure Payment</p>
                            </div>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-xs text-gray-500 mt-1">Low Cost Shipping</p>
                            </div>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-gray-500 mt-1">Money Back</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pixel InitiateCheckout Events
            const checkoutValue = parseFloat("{{ $subtotal - ($coupon['discount'] ?? 0) + $deliveryCharge }}");
            const currency = 'BDT';

            // Meta Pixel InitiateCheckout
            if (typeof fbq === 'function') {
                fbq('track', 'InitiateCheckout', {
                    value: checkoutValue,
                    currency: currency
                });
            }

            // Google Tag InitiateCheckout
            if (typeof gtag === 'function') {
                gtag('event', 'begin_checkout', {
                    value: checkoutValue,
                    currency: currency,
                    items: [
                        @foreach($cart as $id => $details)
                            {
                                item_id: '{{ $id }}',
                                item_name: '{{ $details['name'] }}',
                                price: {{ $details['price'] }},
                                quantity: {{ $details['quantity'] }}
                            },
                        @endforeach
                    ]
                });
            }

            // TikTok Pixel InitiateCheckout
            if (typeof ttq === 'object' && typeof ttq.track === 'function') {
                ttq.track('InitiateCheckout', {
                    value: checkoutValue,
                    currency: currency
                });
            }

            // Function to format currency (assuming BDT)
            function formatCurrency(amount) {
                return `BDT ${parseFloat(amount).toFixed(2)}`;
            }

            // Initial values from Blade
            let subtotal = parseFloat("{{ $subtotal }}"); // Use $subtotal from PHP
            let couponCode = "{{ $coupon['code'] ?? '' }}"; // Store the coupon code
            let couponDiscount = parseFloat("{{ $coupon['discount'] ?? 0 }}");
            let deliveryCharge = parseFloat("{{ $deliveryCharge ?? 0 }}");

            // DOM Elements
            const orderTotalEl = document.getElementById('order-total');
            const summarySubtotalEl = document.getElementById('summary-subtotal');
            const summaryDeliveryChargeEl = document.getElementById('summary-delivery-charge');
            const summaryCouponDisplayEl = document.getElementById('summary-coupon-display');
            const couponCodeInput = document.getElementById('coupon-code');
            const couponFeedbackEl = document.getElementById('coupon-feedback');
            const applyCouponForm = document.getElementById('apply-coupon-form');

            function updateOrderSummary() {
                let currentTotal = subtotal - couponDiscount + deliveryCharge;
                if (currentTotal < 0) currentTotal = 0;

                orderTotalEl.textContent = formatCurrency(currentTotal);
                summarySubtotalEl.textContent = formatCurrency(subtotal);
                summaryDeliveryChargeEl.textContent = formatCurrency(deliveryCharge);

                if (couponDiscount > 0) {
                    summaryCouponDisplayEl.classList.remove('hidden');
                    document.getElementById('coupon-display-text').textContent = `Coupon (${couponCode})`;
                    document.getElementById('coupon-discount-display').innerHTML = `- ${formatCurrency(couponDiscount)}
                        <button type="button" id="remove-coupon-btn" class="ml-2 text-red-400 hover:text-red-600 focus:outline-none">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>`;
                } else {
                    summaryCouponDisplayEl.classList.add('hidden');
                    couponFeedbackEl.textContent = ''; // Clear feedback when no coupon
                    couponFeedbackEl.className = 'text-sm mt-2';
                }
            }

            // Event listener for coupon removal (delegated to document)
            document.addEventListener('click', function(event) {
                // Use closest to handle clicks on the button or its SVG icon
                const removeButton = event.target.closest('#remove-coupon-btn');
                if (removeButton) {
                    fetch('{{ route('coupons.remove') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            couponDiscount = 0;
                            couponCode = ''; // Clear coupon code
                            couponCodeInput.value = ''; // Clear coupon input field
                            updateOrderSummary();
                            // Use a less intrusive notification if possible in a real app
                            couponFeedbackEl.textContent = data.message;
                            couponFeedbackEl.className = 'text-sm mt-2 text-green-600';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error removing coupon:', error);
                        alert('Failed to remove coupon.');
                    });
                }
            });

            // Event listener for shipping option change
            document.querySelectorAll('input[name="shipping_option"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    deliveryCharge = parseFloat(this.value);
                    fetch('{{ route('checkout.updateDeliveryCharge') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ delivery_charge: deliveryCharge })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateOrderSummary();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating delivery charge:', error);
                        alert('Failed to update delivery charge.');
                    });
                });
            });

            // Event listener for coupon application form submission
            if (applyCouponForm) {
                applyCouponForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const code = couponCodeInput.value.trim();

                    try {
                        const response = await fetch('{{ route('coupons.apply') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ code: code })
                        });

                        if (!response.ok) {
                            let errorMessage = 'An unknown error occurred.';
                            try {
                                const errorData = await response.json();
                                errorMessage = errorData.message || errorMessage;
                            } catch (jsonError) {
                                errorMessage = await response.text();
                            }
                            throw new Error(errorMessage);
                        }

                        const data = await response.json();
                        couponDiscount = data.discount;
                        couponCode = code; // Store the applied coupon code
                        couponFeedbackEl.textContent = data.message || 'Coupon applied successfully!';
                        couponFeedbackEl.className = 'text-sm mt-2 text-green-600 font-semibold';
                        updateOrderSummary();

                    } catch (error) {
                        console.error('Error applying coupon:', error);
                        couponDiscount = 0;
                        couponCode = ''; // Clear coupon code on error
                        updateOrderSummary(); // Update summary to remove old discount
                        couponFeedbackEl.textContent = error.message; // Display the new error message
                        couponFeedbackEl.className = 'text-sm mt-2 text-red-500 font-semibold';
                        return;
                    }
                });
            }

            // Initial call to update summary on page load
            updateOrderSummary();
        });
    </script>
@endpush