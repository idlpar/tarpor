@extends('layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    /* Vibrant but professional color palette */
    :root {
        --primary: #4f46e5;
        --primary-hover: #4338ca;
        --secondary: #f472b6;
        --accent: #10b981;
        --dark: #1f2937;
        --light: #f9fafb;
        --border: #e5e7eb;
    }

    .btn {
        @apply font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2;
    }
    .btn-primary {
        @apply bg-gradient-to-r from-[var(--primary)] to-[#6366f1] text-white shadow-lg hover:shadow-xl hover:from-[var(--primary-hover)] hover:to-[#4f46e5] focus:ring-[var(--primary)] border border-transparent;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }
    .btn-primary:hover {
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
    }
    .btn-secondary {
        @apply bg-[var(--light)] text-[var(--dark)] hover:bg-gray-200 focus:ring-gray-300 border border-[var(--border)];
    }
    .form-input-custom {
        @apply block w-full px-4 py-3 text-base text-gray-800 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm;
    }
    .form-input-custom:focus {
        @apply ring-offset-2 ring-offset-gray-100;
    }
    /* Checkout button enhancements */
    .checkout-btn {
        background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        position: relative;
        overflow: hidden;
    }
    .checkout-btn:hover {
        background: linear-gradient(135deg, var(--primary-hover) 0%, #6d28d9 100%);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        transform: translateY(-2px);
    }
    .checkout-btn:active {
        transform: translateY(0);
    }
    .checkout-btn::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to bottom right,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0) 45%,
            rgba(255, 255, 255, 0.3) 48%,
            rgba(255, 255, 255, 0.3) 52%,
            rgba(255, 255, 255, 0) 55%,
            rgba(255, 255, 255, 0) 100%
        );
        transform: rotate(30deg);
        transition: all 0.5s;
    }
    .checkout-btn:hover::after {
        left: 100%;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <!-- Breadcrumbs -->
        <nav class="text-sm font-medium text-gray-500 mb-8">
            <ol class="list-none p-0 inline-flex space-x-2 items-center">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Home</a></li>
                <li><svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                <li><span class="text-gray-800">Shopping Cart</span></li>
            </ol>
        </nav>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12">
                <!-- Cart Items -->
                <div class="lg:col-span-8">
                    <div class="flex justify-between items-end mb-6">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Your Cart</h1>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Clear Cart</button>
                        </form>
                        <p class="text-gray-500 font-medium">{{ count(session('cart')) }} items</p>
                    </div>
                    @foreach(session('cart') as $id => $details)
                        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-4 flex flex-col lg:flex-row lg:items-center transition-shadow hover:shadow-md">
                            <div class="flex items-center space-x-4 flex-grow">
                                <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-lg">
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-gray-800 text-lg">{{ $details['name'] }}</h3>
                                    @if(isset($details['attributes']) && $details['attributes'] !== 'N/A')
                                        <p class="text-sm text-gray-500">{{ $details['attributes'] }}</p>
                                    @endif
                                    <p class="text-md text-gray-800 font-bold">৳{{ number_format($details['price'], 2) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4 lg:mt-0">
                                <form action="{{ route('cart.update', ['id' => $id]) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="form-input-custom w-20 text-center mx-4">
                                    <button type="submit" class="text-gray-500 hover:text-blue-600 p-2 rounded-full" data-tippy-content="Update">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('cart.remove', ['id' => $id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full ml-2" data-tippy-content="Delete">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-xl shadow-md p-6 sticky top-28">
                        <h2 class="text-2xl font-bold text-gray-800 border-b pb-4 mb-6">Order Summary</h2>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Shipping Method</h3>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="shipping" value="50" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" checked>
                                    <div class="ml-4 flex-grow">
                                        <span class="font-medium text-gray-800">Standard Shipping</span>
                                        <p class="text-sm text-gray-500">4-5 business days</p>
                                    </div>
                                    <span class="font-semibold text-gray-800">৳50.00</span>
                                </label>
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="shipping" value="120" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-4 flex-grow">
                                        <span class="font-medium text-gray-800">Express Shipping</span>
                                        <p class="text-sm text-gray-500">1-2 business days</p>
                                    </div>
                                    <span class="font-semibold text-gray-800">৳120.00</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="coupon-code" class="block text-sm font-medium text-gray-700 mb-2">Have a coupon?</label>
                            <form id="apply-coupon-form" class="flex">
                                <input type="text" id="coupon-code" name="code" placeholder="Enter coupon code" class="form-input-coupon px-2 rounded-lg flex-grow">
                                <button type="submit" id="apply-coupon-btn" class="btn-apply-coupon ml-2">Apply</button>
                            </form>
                            <p id="coupon-feedback" class="text-sm mt-2"></p>
                        </div>
                        <div class="space-y-4 text-gray-600 font-medium border-t pt-6">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span id="subtotal">৳{{ number_format(collect(session('cart'))->sum(function($item) { return $item['price'] * $item['quantity']; }), 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span id="shipping">৳50.00</span>
                            </div>
                            <div id="discount-row" class="flex justify-between text-green-600 hidden">
                                <span>Discount</span>
                                <span id="discount"></span>
                            </div>
                            <div class="border-t border-gray-200 my-2"></div>
                            <div class="flex justify-between text-xl font-bold text-gray-900">
                                <span>Total</span>
                                <span id="total"></span>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('checkout.index') }}" class="checkout-btn btn w-full block text-center text-lg py-4 text-white font-bold rounded-xl transition-all duration-300">
                                Proceed to Checkout
                                <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Your Cart is Empty</h1>
                <p class="text-gray-600 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary p-2 rounded-lg">Continue Shopping</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/@popperjs/core@2"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    tippy('[data-tippy-content]');

    // --- Bangladeshi Currency Formatter ---
    function formatCurrencyBD(num) {
        const numStr = parseFloat(num).toFixed(2).toString();
        let [integerPart, decimalPart] = numStr.split('.');
        const lastThree = integerPart.slice(-3);
        const otherNumbers = integerPart.slice(0, -3);
        if (otherNumbers !== '') {
            integerPart = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + ',' + lastThree;
        }
        return `৳${integerPart}.${decimalPart}`;
    }

    // --- DOM Elements ---
    const subtotalEl = document.getElementById('subtotal');
    const shippingEl = document.getElementById('shipping');
    const discountRowEl = document.getElementById('discount-row');
    const discountEl = document.getElementById('discount');
    const totalEl = document.getElementById('total');
    const couponInput = document.getElementById('coupon-code');
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    const couponFeedbackEl = document.getElementById('coupon-feedback');
    const shippingOptions = document.querySelectorAll('input[name="shipping"]');

    // --- State ---
    let shippingCost = shippingEl ? parseFloat(shippingEl.textContent.replace(/[^0-9.-]+/g,"")) : 50;
    let discount = 0;

    // --- Main Update Function ---
    function updateSummary() {
        if (!subtotalEl) return; // Don't run on empty cart page

        const subtotal = parseFloat(subtotalEl.textContent.replace(/[^0-9,.-]+/g,"").replace(/,/g, ''));
        const total = subtotal + shippingCost - discount;

        subtotalEl.textContent = formatCurrencyBD(subtotal);
        shippingEl.textContent = formatCurrencyBD(shippingCost);
        totalEl.textContent = formatCurrencyBD(total);

        if (discount > 0) {
            discountEl.textContent = `- ${formatCurrencyBD(discount)}`;
            discountRowEl.classList.remove('hidden');
        } else {
            discountRowEl.classList.add('hidden');
        }
    }

    // Format all prices on the page initially
    document.querySelectorAll('[data-price]').forEach(el => {
        el.textContent = formatCurrencyBD(el.dataset.price);
    });
    if (subtotalEl) {
        const subtotalValue = parseFloat(subtotalEl.textContent.replace(/[^0-9.-]+/g,""));
        subtotalEl.textContent = formatCurrencyBD(subtotalValue);
    }
    document.querySelectorAll('span:not(#subtotal)').forEach(el => {
        const text = el.textContent;
        if (text.includes('৳')) {
            const num = parseFloat(text.replace(/[^0-9.-]+/g,""));
            if (!isNaN(num)) {
                el.textContent = formatCurrencyBD(num);
            }
        }
    });


    // --- Event Listeners ---
    shippingOptions.forEach(radio => {
        radio.addEventListener('change', function() {
            shippingCost = parseFloat(this.value);
            updateSummary();
        });
    });

    const applyCouponForm = document.getElementById('apply-coupon-form');
    if (applyCouponForm) {
        applyCouponForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const couponCode = couponInput.value.trim();

            try {
                const response = await fetch('{{ route('coupons.apply') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ code: couponCode })
                });

                if (!response.ok) {
                    // Attempt to parse JSON error, but fallback to text if not JSON
                    let errorMessage = 'An unknown error occurred.';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (jsonError) {
                        errorMessage = await response.text(); // Get raw text if not JSON
                    }
                    throw new Error(errorMessage);
                }

                const data = await response.json();

                discount = data.discount; // Assuming the backend returns the discount amount
                couponFeedbackEl.textContent = data.message || 'Coupon applied successfully!';
                couponFeedbackEl.className = 'text-sm mt-2 text-green-600 font-semibold';

            } catch (error) {
                console.error('Error applying coupon:', error);
                discount = 0;
                couponFeedbackEl.textContent = `Error: ${error.message || 'An unexpected error occurred.'}`;
                couponFeedbackEl.className = 'text-sm mt-2 text-red-500 font-semibold';
            }
            updateSummary();
        });
    }

    // Initial summary calculation
    updateSummary();
});
</script>
@endpush
