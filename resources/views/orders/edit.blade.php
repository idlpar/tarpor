@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --primary: #008080;
            --primary-dark: #006666;
        }
        .swal2-container {
            background-color: rgba(33, 37, 41, 0.75) !important;
        }
        .swal2-popup {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #212529;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
                confirmButtonColor: '#008080',
                timer: 3000,
                timerProgressBar: true
            });
            @endif

            // Optional: Dynamic total price update
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const totalPriceSpan = document.getElementById('total-price');
            const productPrices = @json($products->pluck('price', 'id')->toArray());

            function updateTotalPrice() {
                const productId = productSelect.value;
                const quantity = parseInt(quantityInput.value) || 1;
                const price = productPrices[productId] || 0;
                const total = (price * quantity).toFixed(2);
                totalPriceSpan.textContent = `$${total}`;
            }

            productSelect.addEventListener('change', updateTotalPrice);
            quantityInput.addEventListener('input', updateTotalPrice);
            updateTotalPrice(); // Initial calculation
        });
    </script>
@endpush

@section('title', 'Edit Order')

@section('content')
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-8">Edit Order #{{ $order->id }}</h1>

            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <form action="{{ route('orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                            <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm">
                                @foreach ($products as $productItem)
                                    <option value="{{ $productItem->id }}" {{ $productItem->id == $order->product_id ? 'selected' : '' }}>
                                        {{ $productItem->name }} (${{ number_format($productItem->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" id="quantity" name="quantity" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" value="{{ old('quantity', $order->quantity) }}">
                            @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="address" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)] sm:text-sm" value="{{ old('address', $order->address) }}">
                            @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Price</label>
                            <p class="mt-1 text-sm text-gray-900" id="total-price">${{ number_format($order->total_price, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[var(--primary)] text-white font-semibold rounded-md hover:bg-[var(--primary-dark)]">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
