@extends('layouts.app')

@push('styles')
    <style>
        :root {
            --primary: #4F46E5;  /* Vibrant indigo */
            --primary-light: #6366F1;
            --primary-dark: #4338CA;
            --secondary: #10B981; /* Emerald green */
            --accent: #F59E0B;    /* Amber */
            --danger: #EF4444;    /* Red */
            --light-bg: #F8FAFC;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .order-edit-container {
            background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .form-card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: white;
            padding: 1.5rem 2rem;
        }

        .form-header h1 {
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
        }

        .form-body {
            padding: 2rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #E5E7EB;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-color: white;
        }

        .form-input:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            outline: none;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }

        .product-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            padding: 1rem;
            border-radius: 0.75rem;
            background-color: #F9FAFB;
            margin-bottom: 1rem;
            border: 2px dashed #E5E7EB;
            transition: all 0.2s ease;
        }

        .product-row:hover {
            border-color: var(--primary-light);
            background-color: #F0F5FF;
        }

        .product-row select, .product-row input {
            flex: 1;
            min-width: 200px;
            background-color: white;
        }

        .remove-btn {
            background-color: var(--danger);
            color: white;
            padding: 0.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            width: 2.5rem;
            height: 2.5rem;
        }

        .remove-btn:hover {
            background-color: #DC2626;
            transform: scale(1.05);
        }

        .add-product-btn {
            background-color: var(--secondary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .add-product-btn:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
        }

        .cancel-btn {
            background-color: white;
            color: #4B5563;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: 2px solid #E5E7EB;
            transition: all 0.2s ease;
        }

        .cancel-btn:hover {
            background-color: #F3F4F6;
            border-color: #D1D5DB;
        }

        .submit-btn {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .delete-btn {
            background-color: var(--danger);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .delete-btn:hover {
            background-color: #DC2626;
            transform: translateY(-1px);
        }

        .error-message {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .price-display {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
        }

        .total-label {
            color: #6B7280;
        }

        .order-summary {
            background-color: #F0F5FF;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary);
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
            background-color: #FFEDD5;
            color: #9A3412;
        }

        .badge-processing {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .badge-shipped {
            background-color: #E0E7FF;
            color: #4338CA;
        }

        .badge-delivered {
            background-color: #DCFCE7;
            color: #166534;
        }

        .badge-cancelled {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        @media (max-width: 768px) {
            .product-row {
                flex-direction: column;
                align-items: stretch;
            }

            .product-row select,
            .product-row input {
                width: 100%;
            }
        }
    </style>
@endpush

@section('title', 'Edit Order #'.$order->id)

@section('content')
    <div class="order-edit-container">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="form-card">
                <div class="form-header">
                    <h1>Edit Order #{{ $order->id }}</h1>
                </div>

                <div class="form-body">
                    <form id="editOrderForm" action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Order Summary -->
                        <div class="order-summary">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Current Order</h3>
                                    @if($order->products->isNotEmpty())
                                        @foreach($order->products as $product)
                                            <p class="text-sm text-gray-900 mt-1">
                                                {{ $product->name }} × {{ $product->pivot->quantity }} ({{ format_taka($product->pivot->price * $product->pivot->quantity) }})
                                            </p>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 mt-1">No products</p>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <span class="status-badge badge-{{ $order->status }} mr-3">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="text-sm font-medium text-white bg-[var(--primary)] px-3 py-1 rounded-full">
                                        {{ format_taka($order->total_price) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Products Section -->
                            <div>
                                <label class="form-label">Products</label>
                                <div id="products-container" class="mt-2">
                                    @foreach(old('products', $orderProducts) as $index => $product)
                                        <div class="product-row" data-index="{{ $index }}">
                                            <select name="products[{{ $index }}][product_id]" class="form-select">
                                                @foreach ($products as $productOption)
                                                    <option value="{{ $productOption->id }}"
                                                            data-price="{{ $productOption->price }}"
                                                        {{ $productOption->id == ($product['product_id'] ?? '') ? 'selected' : '' }}>
                                                        {{ $productOption->name }} ({{ format_taka($productOption->price) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="number" name="products[{{ $index }}][quantity]"
                                                   min="1" value="{{ $product['quantity'] ?? 1 }}"
                                                   class="form-input">
                                            <button type="button" class="remove-btn {{ $index === 0 && count(old('products', $orderProducts)) === 1 ? 'hidden' : '' }}"
                                                    onclick="removeProductRow(this)">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="add-product-btn" onclick="addProductRow()">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Another Product
                                </button>
                                @error('products')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                                @error('products.*.product_id')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                                @error('products.*.quantity')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                                <div id="total-price" class="price-display">
                                    <span class="total-label">Estimated Total:</span>
                                    <span id="total-amount">{{ format_taka($order->total_price) }}</span>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div>
                                <label for="address" class="form-label">Shipping Address</label>
                                <input type="text" id="address" name="address" class="form-input"
                                       value="{{ old('address', $order->address) }}">
                                @error('address')
                                <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('orders.index') }}" class="cancel-btn">Cancel</a>
                            <div class="flex gap-3">
                                @can('delete', $order)
                                    <button type="button" onclick="confirmDelete()" class="delete-btn">
                                        Delete Order
                                    </button>
                                @endcan
                                <button type="button" onclick="confirmSubmit()" class="submit-btn">Update Order</button>
                            </div>
                        </div>
                    </form>
                </div>
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
        // Add new product row
        function addProductRow() {
            const container = document.getElementById('products-container');
            const index = container.querySelectorAll('.product-row').length;

            const row = document.createElement('div');
            row.className = 'product-row';
            row.dataset.index = index;

            row.innerHTML = `
                <select name="products[${index}][product_id]" class="form-select">
                    @foreach ($products as $product)
            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} ({{ format_taka($product->price) }})
                        </option>
                    @endforeach
            </select>
            <input type="number" name="products[${index}][quantity]" min="1" value="1" class="form-input">
                <button type="button" class="remove-btn" onclick="removeProductRow(this)">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            container.appendChild(row);
            updateRemoveButtons();
            attachProductChangeListeners(row);
            calculateTotal();
        }

        // Remove product row
        function removeProductRow(button) {
            button.closest('.product-row').remove();
            updateProductIndices();
            updateRemoveButtons();
            calculateTotal();
        }

        // Update product indices after removal
        function updateProductIndices() {
            const rows = document.querySelectorAll('.product-row');
            rows.forEach((row, index) => {
                row.dataset.index = index;
                const select = row.querySelector('select');
                const input = row.querySelector('input');
                select.name = `products[${index}][product_id]`;
                input.name = `products[${index}][quantity]`;
            });
        }

        // Show/hide remove buttons appropriately
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.product-row');
            const removeButtons = document.querySelectorAll('.remove-btn');
            removeButtons.forEach(button => {
                button.classList.toggle('hidden', rows.length === 1);
            });
        }

        // Calculate total order amount
        function calculateTotal() {
            const rows = document.querySelectorAll('.product-row');
            let total = 0;

            rows.forEach(row => {
                const select = row.querySelector('select');
                const selectedOption = select.options[select.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const quantity = parseFloat(row.querySelector('input').value) || 0;
                total += price * quantity;
            });

            document.getElementById('total-amount').textContent = `${total.toFixed(2)}`;
        }

        // Attach event listeners to product inputs
        function attachProductChangeListeners(row) {
            const select = row.querySelector('select');
            const input = row.querySelector('input');

            select.addEventListener('change', calculateTotal);
            input.addEventListener('input', calculateTotal);
        }

        // Confirm delete action with SweetAlert
        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        // Confirm submit action with SweetAlert
        function confirmSubmit() {
            Swal.fire({
                title: 'Update Order',
                text: "Are you sure you want to update this order?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, update it!',
                background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('editOrderForm').submit();
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Success/error messages
            @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
                confirmButtonColor: '#4F46E5',
                timer: 3000,
                timerProgressBar: true
            });
            @endif
            @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
                confirmButtonColor: '#4F46E5',
            });
            @endif

            // Set up listeners for initial rows
            const productItems = document.querySelectorAll('.product-row');
            productItems.forEach(item => {
                attachProductChangeListeners(item);
            });

            // Calculate initial total
            calculateTotal();

            // Update remove buttons
            updateRemoveButtons();
        });
    </script>
@endpush
