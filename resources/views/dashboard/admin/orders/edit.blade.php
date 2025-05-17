@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --primary: #008080;
            --primary-light: #00a3a3;
            --primary-dark: #006666;
            --primary-bg: #e6f2f2;
            --danger: #dc3545;
            --danger-light: #f8d7da;
            --danger-dark: #c82333;
            --success: #28a745;
            --success-light: #d4edda;
        }

        .form-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(229, 231, 235, 0.8);
        }

        .form-header {
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
            background: linear-gradient(to right, #f8fafc, #f1f5f9);
        }

        .form-input {
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 128, 128, 0.1);
        }

        .btn-primary {
            transition: all 0.2s ease;
            background-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            transition: all 0.2s ease;
            background-color: white;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: #cbd5e0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-pending {
            background-color: #ffedd5;
            color: #9a3412;
        }

        .badge-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-shipped {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .badge-delivered {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .order-summary {
            background-color: var(--primary-bg);
            border-radius: 0.5rem;
            border-left: 4px solid var(--primary);
        }

        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            border-radius: 0.25rem;
            background-color: #f9fafb;
        }

        .product-select {
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.25rem;
            border: 1px solid #e2e8f0;
        }

        .add-product-btn {
            margin-top: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: white;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
        }

        .add-product-btn:hover {
            background-color: var(--primary-dark);
        }

        .remove-product-btn {
            margin-left: 0.5rem;
            color: var(--danger);
            cursor: pointer;
        }

        .total-price-display {
            font-size: 1.25rem;
            font-weight: bold;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
@endpush

@section('title', 'Edit Order #'.$order->id)

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Order #{{ $order->id }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Update order details and status</p>
                </div>
                <div class="flex items-center">
                    <span class="status-badge badge-{{ $order->status }} mr-3">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ format_taka($order->total_price) }}
                    </span>
                </div>
            </div>

            <div class="form-container overflow-hidden">
                <div class="form-header px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Order Information</h2>
                </div>

                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Order Summary -->
                    <div class="order-summary p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Current Order</h3>
                                @foreach($order->products as $product)
                                    <p class="text-sm text-gray-900 mt-1">
                                        {{ $product->name }} × {{ $product->pivot->quantity }} ({{ format_taka($product->pivot->price * $product->pivot->quantity) }})
                                    </p>
                                @endforeach
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ format_taka($order->total_price) }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $order->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:form-grid gap-6">
                        <!-- User Selection -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                            <select id="user_id" name="user_id" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $order->user_id ? 'selected' : '' }}>
                                        {{ $user->name }} &lt;{{ $user->email }}&gt;
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Products -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Products</label>
                            <div id="products-container">
                                @foreach(old('products', $order->products->isEmpty() ? [['product_id' => '', 'quantity' => 1]] : $order->products->map(function($product) {
                                    return ['product_id' => $product->id, 'quantity' => $product->pivot->quantity];
                                })->toArray()) as $index => $product)
                                    <div class="product-item" data-index="{{ $index }}">
                                        <select name="products[{{ $index }}][product_id]" class="product-select" required>
                                            <option value="">Select a product</option>
                                            @foreach ($products as $productOption)
                                                <option value="{{ $productOption->id }}"
                                                        data-price="{{ $productOption->price }}"
                                                    {{ $productOption->id == ($product['product_id'] ?? '') ? 'selected' : '' }}>
                                                    {{ $productOption->name }} ({{ format_taka($productOption->price) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="products[{{ $index }}][quantity]" min="1"
                                               value="{{ $product['quantity'] ?? 1 }}"
                                               class="ml-2 w-20 form-input" required>
                                        @if($index > 0)
                                            <button type="button" class="remove-product-btn" onclick="removeProduct(this)">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-product-btn" class="add-product-btn">
                                + Add Another Product
                            </button>
                            <div id="total-price" class="total-price-display">
                                Total: {{ format_taka($order->total_price) }}
                            </div>
                            @error('products')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address', $order->address) }}"
                                   class="form-input mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm">
                            @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 pt-5 border-t border-gray-200 flex justify-between">
                        <a href="{{ route('admin.orders.index') }}" class="btn-secondary inline-flex items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <div class="flex space-x-3">
                            @can('delete', $order)
                                <button type="button" onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Delete Order
                                </button>
                            @endcan
                            <button type="submit" class="btn-primary inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                                Update Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Delete Order #{{ $order->id }}?',
                text: "This action cannot be undone. Are you sure you want to delete this order?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: 'white',
                customClass: {
                    title: 'text-lg font-medium text-gray-900',
                    popup: 'rounded-xl shadow-xl border border-gray-100'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        // Add new product row
        document.getElementById('add-product-btn').addEventListener('click', function() {
            const container = document.getElementById('products-container');
            const index = container.children.length;

            const productItem = document.createElement('div');
            productItem.className = 'product-item';
            productItem.dataset.index = index;

            productItem.innerHTML = `
                <select name="products[${index}][product_id]" class="product-select" required>
                    <option value="">Select a product</option>
                    @foreach ($products as $product)
            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} ({{ format_taka($product->price) }})
                        </option>
                    @endforeach
            </select>
            <input type="number" name="products[${index}][quantity]" min="1" value="1"
                    class="ml-2 w-20 form-input" required>
                <button type="button" class="remove-product-btn" onclick="removeProduct(this)">
                    Remove
                </button>
            `;

            container.appendChild(productItem);
            updateTotalPrice();
        });

        // Remove product row
        function removeProduct(button) {
            const productItem = button.closest('.product-item');
            productItem.remove();

            // Reindex remaining products
            const container = document.getElementById('products-container');
            Array.from(container.children).forEach((item, index) => {
                item.dataset.index = index;
                item.querySelector('select').name = `products[${index}][product_id]`;
                item.querySelector('input').name = `products[${index}][quantity]`;
            });

            updateTotalPrice();
        }

        // Calculate total price
        function updateTotalPrice() {
            const productItems = document.querySelectorAll('.product-item');
            let total = 0;

            productItems.forEach(item => {
                const select = item.querySelector('select');
                const selectedOption = select.options[select.selectedIndex];
                const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
                const quantity = parseFloat(item.querySelector('input').value) || 0;
                total += price * quantity;
            });

            document.getElementById('total-price').textContent = `Total: ${formatTaka(total)}`;
        }

        // Format as Bangladeshi Taka
        function formatTaka(amount) {
            // This is a simplified version - you might want to implement the full logic
            return '৳ ' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        // Listen for changes to update total price
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('products-container');

            container.addEventListener('change', function(e) {
                if (e.target.matches('select, input')) {
                    updateTotalPrice();
                }
            });

            container.addEventListener('input', function(e) {
                if (e.target.matches('input')) {
                    updateTotalPrice();
                }
            });

            // Initial calculation
            updateTotalPrice();
        });
    </script>
@endpush
