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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '{!! implode("<br>", $errors->all()) !!}',
                background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
                confirmButtonColor: '#008080',
                focusConfirm: false,
                focusCancel: true
            });
            @endif
        });
    </script>
@endpush

@section('title', 'Place New Order')

    @section('content')
        <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                    <div class="mb-4 md:mb-0">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Place New Order</h1>
                        <p class="mt-1 text-sm text-gray-600">Select a product and provide order details</p>
                    </div>
                    <div>
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700 transition duration-150">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Orders
                        </a>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                            <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring focus:ring-[var(--primary)] focus:ring-opacity-50 sm:text-sm">
                                <option value="">Select a product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (${{ number_format($product->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring focus:ring-[var(--primary)] focus:ring-opacity-50 sm:text-sm" required>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Delivery Address</label>
                            <textarea id="address" name="address" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring focus:ring-[var(--primary)] focus:ring-opacity-50 sm:text-sm" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[var(--primary)] text-white font-semibold rounded-md hover:bg-[var(--primary-dark)] transition duration-150">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    @endsection
