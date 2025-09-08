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

        /* Hide spinner from number inputs */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .product-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
                        @foreach(array_reverse(session('cart'), true) as $id => $details)
                            <div class="bg-white rounded-xl shadow-sm p-4 mb-4 grid grid-cols-12 items-center gap-4 transition-shadow hover:shadow-md cart-item" data-id="{{ $id }}" data-item-price="{{ $details['price'] }}">
                                <div class="col-span-1">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded cart-item-checkbox" checked>
                                </div>
                                <div class="col-span-2">
                                    <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-20 h-20 object-cover rounded-lg">
                                </div>
                                <div class="col-span-3">
                                    <h3 class="font-semibold text-gray-800 text-md">{{ $details['name'] }}</h3>
                                    @if(isset($details['attributes']) && $details['attributes'] !== 'N/A')
                                        <p class="text-sm text-gray-500">{{ $details['attributes'] }}</p>
                                    @endif
                                </div>
                                <div class="col-span-2 text-right">
                                    <p class="text-md font-bold text-gemini-pink">{{ format_taka($details['price']) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center justify-center">
                                        <form action="{{ route('cart.update', ['id' => $id]) }}" method="POST" class="flex items-center cart-update-form">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="quantity-change-btn p-1 border rounded-l-md" data-change="-1">-</button>
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-12 text-center border-t border-b focus:outline-none p-1 cart-item-quantity-input">
                                            <button type="button" class="quantity-change-btn p-1 border rounded-r-md" data-change="1">+</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-span-1 text-right">
                                    <p class="text-md text-gray-800 cart-item-line-total">{{ format_taka($details['price'] * $details['quantity']) }}</p>
                                </div>
                                <div class="col-span-1 text-right">
                                    <form action="{{ route('cart.remove', ['id' => $id]) }}" method="POST" class="cart-remove-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-full" data-tippy-content="Delete">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Right Column -->
                    <div class="lg:col-span-4">
                        <div class="bg-white rounded-xl shadow-md p-6 sticky top-28">
                            <h2 class="text-2xl font-bold text-gray-800 border-b pb-4 mb-6">Order Summary</h2>
                            <div class="space-y-4 text-gray-600 font-medium">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold">Subtotal</span>
                                    <span id="subtotal" class="text-lg font-semibold">{{ format_taka(collect(session('cart'))->sum(function($item) { return $item['price'] * $item['quantity']; })) }}</span>
                                </div>
                                <div class="border-t border-gray-200 my-2"></div>
                                <div class="flex justify-between text-xl font-bold text-gray-900">
                                    <span class="text-2xl font-bold">Total</span>
                                    <span id="total" class="text-2xl font-bold"></span>
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

                <!-- Frequently Bought Together -->
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Frequently Bought Together</h2>
                    <div id="frequently-bought-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                        @include('partials.product_card', ['products' => $frequentlyBought])
                    </div>
                    <div class="text-center mt-8">
                        <button id="load-more-frequently-bought" class="btn btn-secondary">Load More</button>
                    </div>
                </div>

                <!-- You Might Also Like -->
                <div class="mt-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">You Might Also Like</h2>
                    <div id="might-also-like-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                        @include('partials.product_card', ['products' => $mightAlsoLike])
                    </div>
                    <div class="text-center mt-8">
                        <button id="load-more-might-also-like" class="btn btn-secondary">Load More</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            const totalEl = document.getElementById('total');
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
            const checkoutForm = document.getElementById('checkout-form');
            const selectedItemsContainer = document.getElementById('selected-items-container');

            // --- State ---

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

                let currentTotal = newSubtotal;

                subtotalEl.textContent = formatCurrencyBD(newSubtotal);
                totalEl.textContent = formatCurrencyBD(currentTotal);

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
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                    if (selectedItems.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please select at least one item to checkout!',
                        });
                    }
                });
            }

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

            // AJAX for quantity update
            const cartItemQuantityInputs = document.querySelectorAll('.cart-item-quantity-input');
            // Debounce function to limit API calls
            function debounce(func, delay) {
                let timeout;
                return function(...args) {
                    const context = this;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), delay);
                };
            }

            // Debounced version of the quantity update logic
            const debouncedUpdateQuantity = debounce(async function(input) {
                const cartItemDiv = input.closest('.cart-item');
                const itemId = cartItemDiv.dataset.id;
                const newQuantity = parseInt(input.value);

                if (newQuantity < 1) {
                    input.value = 1;
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

                    if (!response.ok) {
                        console.error('Response not OK:', response);
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server responded with an error.');
                    }

                    const data = await response.json();
                    if (data.success) {
                        const itemLineTotalEl = cartItemDiv.querySelector('.cart-item-line-total');
                        if (itemLineTotalEl) {
                            itemLineTotalEl.textContent = formatCurrencyBD(data.item_line_total);
                        }
                        updateSummary();
                    } else {
                        console.error('Error updating cart item quantity:', data.message);
                        alert('Error updating quantity: ' + data.message);
                    }
                } catch (error) {
                    console.error('Network error updating cart item quantity:', error);
                    alert('Network error. Could not update quantity. Check console for details.');
                }
            }, 500); // Debounce with a 500ms delay

            cartItemQuantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    debouncedUpdateQuantity(this);
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

            // Quantity change buttons
            document.querySelectorAll('.quantity-change-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const change = parseInt(this.dataset.change);
                    const input = this.closest('form').querySelector('.cart-item-quantity-input');
                    const currentValue = parseInt(input.value);
                    let newValue = currentValue + change;

                    if (newValue < 1) {
                        newValue = 1;
                    }
                    input.value = newValue;

                    // Trigger the change event to update the cart
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                });
            });

            // Initial summary calculation
            updateSummary();

            // Load More functionality
            let frequentlyBoughtSkip = 6;
            let mightAlsoLikeSkip = 6;

            document.getElementById('load-more-frequently-bought').addEventListener('click', function() {
                fetchMoreProducts('frequently_bought', frequentlyBoughtSkip, 'frequently-bought-container', this);
                frequentlyBoughtSkip += 6;
            });

            document.getElementById('load-more-might-also-like').addEventListener('click', function() {
                fetchMoreProducts('might_also_like', mightAlsoLikeSkip, 'might-also-like-container', this);
                mightAlsoLikeSkip += 6;
            });

            async function fetchMoreProducts(type, skip, containerId, button) {
                button.disabled = true;
                button.textContent = 'Loading...';

                try {
                    const response = await fetch(`{{ route('cart.index') }}?type=${type}&skip=${skip}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();

                    if (data.html.trim() === '') {
                        button.textContent = 'No More Products';
                        button.disabled = true;
                    } else {
                        document.getElementById(containerId).insertAdjacentHTML('beforeend', data.html);
                        button.disabled = false;
                        button.textContent = 'Load More';
                    }
                } catch (error) {
                    console.error('Error fetching more products:', error);
                    button.disabled = false;
                    button.textContent = 'Load More';
                }
            }
        });
    </script>
@endpush
