@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                    <div class="divide-y divide-gray-200">
                        @php $total = 0 @endphp
                        @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity'] @endphp
                            <div class="flex items-center py-4">
                                <div class="flex-shrink-0 w-24 h-24 rounded-md overflow-hidden">
                                    <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover object-center">
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $details['name'] }}</h3>
                                            <p class="text-sm text-gray-600">{{ $details['attributes'] }}</p>
                                        </div>
                                        <button class="text-red-600 hover:text-red-800 remove-from-cart" data-id="{{ $id }}">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <p class="text-gray-900 font-medium">BDT {{ number_format($details['price'], 2) }}</p>
                                        <div class="ml-4 flex items-center border border-gray-300 rounded-md">
                                            <button class="px-2 py-1 text-gray-600 hover:bg-gray-100 rounded-l-md update-quantity" data-id="{{ $id }}" data-action="minus">-</button>
                                            <input type="text" value="{{ $details['quantity'] }}" class="w-12 text-center border-l border-r border-gray-300 focus:outline-none" readonly>
                                            <button class="px-2 py-1 text-gray-600 hover:bg-gray-100 rounded-r-md update-quantity" data-id="{{ $id }}" data-action="plus">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h2>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-700">Subtotal:</p>
                        <p class="text-gray-900 font-medium">BDT {{ number_format($total, 2) }}</p>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <p class="text-lg font-semibold text-gray-800">Total:</p>
                            <p class="text-lg font-semibold text-gray-800">BDT {{ number_format($total, 2) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="mt-6 w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 text-center block">Proceed to Checkout</a>
                </div>
            </div>
        @else
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-600 text-lg">Your cart is empty.</p>
                <a href="{{ route('shop.index') }}" class="mt-4 inline-block bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Continue Shopping</a>
            </div>
        @endif
    </div>

    <form id="update-cart-form" action="{{ route('cart.update') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" id="update-cart-id">
        <input type="hidden" name="quantity" id="update-cart-quantity">
    </form>

    <form id="remove-from-cart-form" action="{{ route('cart.remove') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="id" id="remove-cart-id">
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.update-quantity').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const action = this.dataset.action;
                    const quantityInput = this.closest('div').querySelector('input');
                    let quantity = parseInt(quantityInput.value);

                    if (action === 'plus') {
                        quantity++;
                    } else if (action === 'minus' && quantity > 1) {
                        quantity--;
                    }

                    quantityInput.value = quantity;

                    document.getElementById('update-cart-id').value = id;
                    document.getElementById('update-cart-quantity').value = quantity;
                    document.getElementById('update-cart-form').submit();
                });
            });

            document.querySelectorAll('.remove-from-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    document.getElementById('remove-cart-id').value = id;
                    document.getElementById('remove-from-cart-form').submit();
                });
            });
        });
    </script>
@endpush
