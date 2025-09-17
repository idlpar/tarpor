@extends('layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <div class="bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <nav class="text-sm font-medium text-gray-500 mb-8">
                <ol class="list-none p-0 inline-flex space-x-2 items-center">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition-colors">Home</a></li>
                    <li><svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                    <li><span class="text-gray-800">Shopping Cart</span></li>
                </ol>
            </nav>

            @if(session('cart') && count(session('cart')) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12">
                    <div class="lg:col-span-8">
                        <div class="flex justify-between items-center mb-6">
                            <!-- Left: Title -->
                            <h1 class="text-3xl font-bold text-gray-900">Your Cart</h1>

                            <!-- Center: Select All -->
                            <div class="flex-1 flex justify-center">
                                <div class="flex items-center">
                                    <input type="checkbox" id="select-all"
                                           class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary" checked>
                                    <label for="select-all" class="ml-2 text-sm font-medium text-gray-700">Select all</label>
                                </div>
                            </div>

                            <!-- Right: Empty Cart -->
                            <form action="{{ route('cart.clear') }}" method="POST" id="empty-cart-form"
                                  class="flex items-center">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-800">
                                    <!-- SVG Trash Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Empty Cart
                                </button>
                            </form>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="bg-white rounded-lg shadow-sm">
                            <div class="hidden md:grid grid-cols-12 gap-4 p-4 border-b font-semibold text-gray-600 text-sm">
                                <div class="col-span-1"></div>
                                <div class="col-span-5">Product</div>
                                <div class="col-span-2 text-center">Quantity</div>
                                <div class="col-span-2 text-center">Total</div>
                                <div class="col-span-2 text-right">Action</div>
                            </div>
                            <div id="cart-items" class="divide-y divide-gray-200">
                                @foreach(session('cart') as $id => $details)
                                    <div class="grid grid-cols-12 gap-4 items-center p-4 cart-item" data-id="{{ $id }}" data-price="{{ $details['price'] }}">
                                        <div class="col-span-1 flex justify-center">
                                            <input type="checkbox" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary item-checkbox" checked>
                                        </div>
                                        <div class="col-span-11 md:col-span-5 flex items-center gap-4">
                                            <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-20 h-20 object-cover rounded-md">
                                            <div>
                                                <h3 class="font-semibold text-gray-800">{{ Str::limit($details['name'], 40) }}</h3>
                                                @if(isset($details['attributes']) && $details['attributes'] !== 'N/A')
                                                    <p class="text-sm text-gray-500">{{ $details['attributes'] }}</p>
                                                @endif
                                                <p class="text-md font-bold text-gemini-pink mt-2">{{ format_taka($details['price']) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-span-6 md:col-span-2 flex items-center justify-center">
                                            <div class="flex items-center border border-gray-300 rounded-md">
                                                <button class="quantity-btn p-2 text-gray-600 hover:bg-gray-100 rounded-l-md" data-action="decrement">-</button>
                                                <span class="quantity-text w-12 text-center border-l border-r border-gray-300">{{ $details['quantity'] }}</span>
                                                <button class="quantity-btn p-2 text-gray-600 hover:bg-gray-100 rounded-r-md" data-action="increment">+</button>
                                            </div>
                                        </div>
                                        <div class="col-span-6 md:col-span-2 flex items-center justify-end">
                                            <p class="text-md font-bold text-gray-800 item-total">{{ format_taka($details['price'] * $details['quantity']) }}</p>
                                        </div>
                                        <div class="col-span-6 md:col-span-2 flex items-center justify-end">
                                            <button class="remove-item-btn text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4">
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-28">
                            <h2 class="text-xl font-bold text-gray-800 border-b pb-4 mb-6">Order Summary</h2>
                            <div class="space-y-4 text-gray-700">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span id="summary-subtotal" class="font-semibold"></span>
                                </div>
                                <div class="border-t border-gray-200 my-2"></div>
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span id="summary-total"></span>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('checkout.index') }}" id="checkout-btn" class="block text-center w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-md transition-colors">
                                    Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">You Might Also Like</h2>
                    <div id="recommendations-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                        @foreach($mightAlsoLike as $product)
                            @include('partials._product_card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="text-center mt-8">
                        <button id="load-more-recommendations" class="bg-white text-primary border border-primary hover:bg-gray-100 font-bold py-2 px-4 rounded-md">Load More</button>
                    </div>
                </div>

            @else
                <div class="text-center py-16">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Your Cart is Empty</h1>
                    <p class="text-gray-600 mb-8">Start adding some products to see them here!</p>
                    <a href="{{ route('shop.index') }}" class="bg-primary text-white font-bold py-3 px-6 rounded-md hover:bg-primary-dark transition-colors">Continue Shopping</a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function formatCurrency(amount) {
                return `৳${parseInt(amount).toLocaleString('en-IN')}`;
            }

            function updateSummary() {
                let subtotal = 0;
                document.querySelectorAll('.cart-item').forEach(item => {
                    if (item.querySelector('.item-checkbox').checked) {
                        const price = parseFloat(item.dataset.price);
                        const quantity = parseInt(item.querySelector('.quantity-text').textContent);
                        subtotal += price * quantity;
                    }
                });

                document.getElementById('summary-subtotal').textContent = formatCurrency(subtotal);
                document.getElementById('summary-total').textContent = formatCurrency(subtotal);
            }

            let debounceTimer;
            function updateCartItem(id, quantity) {
                const item = document.querySelector(`.cart-item[data-id="${id}"]`);
                item.querySelector('.quantity-text').textContent = quantity;
                const price = parseFloat(item.dataset.price);
                item.querySelector('.item-total').textContent = formatCurrency(price * quantity);

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    updateSummary();
                    fetch('{{ route('cart.update') }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id, quantity })
                    });
                }, 1000);
            }

            document.getElementById('cart-items').addEventListener('click', e => {
                const cartItem = e.target.closest('.cart-item');
                if (!cartItem) return;

                const id = cartItem.dataset.id;

                if (e.target.classList.contains('quantity-btn')) {
                    const action = e.target.dataset.action;
                    const quantityEl = cartItem.querySelector('.quantity-text');
                    let quantity = parseInt(quantityEl.textContent);
                    if (action === 'increment') {
                        quantity++;
                    } else if (action === 'decrement' && quantity > 1) {
                        quantity--;
                    }
                    updateCartItem(id, quantity);
                }

                if (e.target.classList.contains('remove-item-btn')) {
                    cartItem.remove();
                    updateSummary();
                    fetch('{{ route('cart.remove') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id })
                    });
                }

                if (e.target.classList.contains('item-checkbox')) {
                    updateSummary();
                }
            });

            document.getElementById('select-all').addEventListener('change', e => {
                document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
                updateSummary();
            });

            document.getElementById('checkout-btn').addEventListener('click', e => {
                const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.closest('.cart-item').dataset.id);
                if (selectedItems.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'No items selected',
                        text: 'Please select items to checkout.'
                    });
                } else {
                    const checkoutUrl = new URL(e.target.href);
                    checkoutUrl.searchParams.set('items', selectedItems.join(','));
                    e.target.href = checkoutUrl.toString();
                }
            });

            function initializeZoom() {
                mediumZoom('.product-card-image', {
                    margin: 24,
                    background: 'rgba(255, 255, 255, 0.8)'
                });
            }

            initializeZoom();

            let skip = 6;
            document.getElementById('load-more-recommendations').addEventListener('click', function() {
                fetch(`{{ route('cart.index') }}?type=might_also_like&skip=${skip}`)
                    .then(response => response.json())
                    .then(data => {
                        const recommendationsContainer = document.getElementById('recommendations-container');
                        recommendationsContainer.insertAdjacentHTML('beforeend', data.html);
                        skip += 6;
                        initializeZoom(); // Re-initialize zoom on new elements
                    });
            });

            // Initial summary calculation
            if (document.getElementById('summary-subtotal')) {
                updateSummary();
            }
        });
    </script>
@endpush
