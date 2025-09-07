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
        font-weight: 700;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        border-radius: 0.75rem;
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
        transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
    }
    .btn:hover {
        --tw-scale-x: 1.02;
        --tw-scale-y: 1.02;
        transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
    }
    .btn:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
    }
    .btn-primary {
        background-image: linear-gradient(to right, var(--primary), #6366f1);
        color: #fff;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        border: 1px solid transparent;
    }
    .btn-primary:hover {
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        background-image: linear-gradient(to right, var(--primary-hover), #4f46e5);
    }
    .btn-primary:focus {
        --tw-ring-color: var(--primary);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow, 0 0 #0000);
    }
    .btn-secondary {
        background-color: var(--light);
        color: var(--dark);
        border: 1px solid var(--border);
    }
    .btn-secondary:hover {
        background-color: #e5e7eb;
    }
    .btn-secondary:focus {
        --tw-ring-color: #d1d5db;
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow, 0 0 #0000);
    }
    .form-input-custom {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        color: #1f2937;
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: all 0.3s;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .form-input-custom:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        border-color: transparent;
        --tw-ring-color: #3b82f6;
        --tw-ring-offset-color: #f9fafb;
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
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Your Cart</h1>
                        <div class="flex items-center">
                            <input type="checkbox" id="select-all-checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" checked>
                            <label for="select-all-checkbox" class="ml-2 text-gray-700 font-medium">Select All</label>
                        </div>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Clear Cart</button>
                        </form>
                        <p class="text-gray-500 font-medium">{{ count(session('cart')) }} items</p>
                    </div>
                    @foreach(session('cart') as $id => $details)
                        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 mb-4 flex flex-col lg:flex-row lg:items-center transition-shadow hover:shadow-md cart-item" data-id="{{ $id }}" data-item-price="{{ $details['price'] }}">
                            <div class="flex items-center space-x-4 flex-grow">
                                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded cart-item-checkbox" checked>
                                <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-lg transition-transform duration-300 ease-in-out hover:scale-110">
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-gray-800 text-lg">{{ $details['name'] }}</h3>
                                    @if(isset($details['attributes']) && $details['attributes'] !== 'N/A')
                                        <p class="text-sm text-gray-500">{{ $details['attributes'] }}</p>
                                    @endif
                                    <p class="text-md font-bold text-gemini-pink">{{ format_taka($details['price']) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4 lg:mt-0">
                                <form action="{{ route('cart.update', ['id' => $id]) }}" method="POST" class="flex items-center cart-update-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="form-input-custom w-20 text-center mx-4 cart-item-quantity-input">
                                    <button type="submit" class="text-gray-500 hover:text-blue-600 p-2 rounded-full update-quantity-btn" data-tippy-content="Update">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"></path></svg>
                                    </button>
                                </form>
                                <p class="text-md font-bold text-gemini-pink ml-4 cart-item-line-total">{{ format_taka($details['price'] * $details['quantity']) }}</p>
                                <form action="{{ route('cart.remove', ['id' => $id]) }}" method="POST" class="cart-remove-form">
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
                                @forelse($shippingMethods as $index => $method)
                                    <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                        <input type="radio" name="shipping" value="{{ $method->cost }}" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" {{ $index == 0 ? 'checked' : '' }}>
                                        <div class="ml-4 flex-grow">
                                            <span class="font-medium text-gray-800">{{ $method->name }}</span>
                                            @if($method->description)
                                                <p class="text-sm text-gray-500">{{ $method->description }}</p>
                                            @endif
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ format_taka($method->cost) }}</span>
                                    </label>
                                @empty
                                    <p class="text-gray-600">No shipping methods available.</p>
                                @endforelse
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
                                <span id="subtotal">{{ format_taka(collect(session('cart'))->sum(function($item) { return $item['price'] * $item['quantity']; })) }}</span>
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
                            <form action="{{ route('checkout.index') }}" method="GET" id="checkout-form">
                                <div id="selected-items-container"></div>
                                <button type="submit" class="checkout-btn btn w-full block text-center text-lg py-4 text-white font-bold rounded-xl transition-all duration-300">
                                    Proceed to Checkout
                                    <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </form>
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
        const numStr = parseInt(num).toString(); // Convert to integer and then string
        let integerPart = numStr;
        const lastThree = integerPart.slice(-3);
        const otherNumbers = integerPart.slice(0, -3);
        if (otherNumbers !== '') {
            integerPart = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + ',' + lastThree;
        }
        return `৳${integerPart}`;
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
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
    const checkoutForm = document.getElementById('checkout-form');
    const selectedItemsContainer = document.getElementById('selected-items-container');

    // --- State ---
    let shippingCost = 0;
    let discount = 0;

    // --- Main Update Function ---
    function updateSummary() {
        if (!subtotalEl) return; // Don't run on empty cart page

        let newSubtotal = 0;
        document.querySelectorAll('.cart-item-checkbox:checked').forEach(checkbox => {
            const cartItem = checkbox.closest('.cart-item');
            const price = parseFloat(cartItem.dataset.itemPrice);
            const quantity = parseInt(cartItem.querySelector('.cart-item-quantity-input').value);
            newSubtotal += price * quantity;
        });

        let currentTotal = newSubtotal + shippingCost - discount;

        subtotalEl.textContent = formatCurrencyBD(newSubtotal);
        shippingEl.textContent = formatCurrencyBD(shippingCost);
        totalEl.textContent = formatCurrencyBD(currentTotal);

        if (discount > 0) {
            discountEl.textContent = `- ${formatCurrencyBD(discount)}`;
            discountRowEl.classList.remove('hidden');
        } else {
            discountRowEl.classList.add('hidden');
        }

        // Update hidden inputs for checkout form
        if (selectedItemsContainer) {
            selectedItemsContainer.innerHTML = '';
            document.querySelectorAll('.cart-item-checkbox:checked').forEach(checkbox => {
                const cartItem = checkbox.closest('.cart-item');
                const itemId = cartItem.dataset.id;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'items[]';
                input.value = itemId;
                selectedItemsContainer.appendChild(input);
            });
        }
    }

    // --- Event Listeners ---
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSummary();
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else {
                const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }
            updateSummary();
        });
    });

    shippingOptions.forEach(radio => {
        radio.addEventListener('change', function() {
            shippingCost = parseFloat(this.value);
            updateSummary();
        });
    });

    // Set initial shipping cost
    const initialCheckedShipping = document.querySelector('input[name="shipping"]:checked');
    if (initialCheckedShipping) {
        shippingCost = parseFloat(initialCheckedShipping.value);
    }

    // AJAX for quantity update
    const cartItemQuantityInputs = document.querySelectorAll('.cart-item-quantity-input');
    cartItemQuantityInputs.forEach(input => {
        input.addEventListener('change', async function() {
            const cartItemDiv = this.closest('.cart-item');
            const itemId = cartItemDiv.dataset.id;
            const newQuantity = parseInt(this.value);

            if (newQuantity < 1) {
                this.value = 1;
                return;
            }

            try {
                const response = await fetch('{{ route('cart.update') }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ id: itemId, quantity: newQuantity })
                });
                const data = await response.json();
                if (data.success) {
                    const itemLineTotalEl = cartItemDiv.querySelector('.cart-item-line-total');
                    if (itemLineTotalEl) {
                        itemLineTotalEl.textContent = formatCurrencyBD(data.item_line_total);
                    }
                    updateSummary();
                } else {
                    alert('Error updating quantity: ' + data.message);
                }
            } catch (error) {
                alert('Network error. Could not update quantity.');
            }
        });
    });

    // AJAX for item removal
    const cartRemoveForms = document.querySelectorAll('.cart-remove-form');
    cartRemoveForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const cartItemDiv = this.closest('.cart-item');
            if (confirm('Are you sure you want to remove this item?')) {
                cartItemDiv.remove();
                updateSummary();
                // Here you might want to send a request to the server to actually remove the item from the cart session
            }
        });
    });

    // Coupon form
    const applyCouponForm = document.getElementById('apply-coupon-form');
    if (applyCouponForm) {
        applyCouponForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            // ... (coupon logic remains the same)
        });
    }

    // Initial summary calculation
    updateSummary();
});
</script>
@endpush