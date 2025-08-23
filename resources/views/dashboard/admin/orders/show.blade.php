@extends('layouts.admin')

@push('styles')
    <style>
        :root {
            --primary: #005f73;
            --primary-light: #0a9396;
            --primary-dark: #001219;
            --secondary: #ee9b00;
            --accent: #bb3e03;
        }

        .order-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(229, 231, 235, 0.7);
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-pending {
            background: linear-gradient(to right, #f97316, #ef4444);
            color: white;
        }

        .badge-processing {
            background: linear-gradient(to right, #3b82f6, #6366f1);
            color: white;
        }

        .badge-shipped {
            background: linear-gradient(to right, #8b5cf6, #d946ef);
            color: white;
        }

        .badge-delivered {
            background: linear-gradient(to right, #10b981, #14b8a6);
            color: white;
        }

        .badge-cancelled {
            background: linear-gradient(to right, #64748b, #475569);
            color: white;
        }

        .detail-card {
            background: linear-gradient(135deg, #ffffff 0%, #e9f0f7 100%);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(200, 210, 220, 0.4);
        }

        .action-btn {
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .divider {
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.1), transparent);
            margin: 1.5rem 0;
        }

        .page-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .back-btn {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            z-index: 10;
        }
    </style>
@endpush

@section('title', 'Order Details')

@section('admin_content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
            @php
                $user = auth()->user();
                $routeName = in_array($user->role, ['admin', 'staff']) ? 'admin.orders.index' : 'orders.index';
            @endphp

                <!-- Back Button -->
            <a href="{{ route($routeName) }}" class="back-btn action-btn inline-flex items-center px-4 py-2 bg-green-300 border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-green-500">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>


            <!-- Header Section -->
            <div class="page-header">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-[var(--primary)] to-[var(--primary-light)] bg-clip-text text-transparent">
                            Order #{{ $order->id }}
                        </h1>
                        <p class="mt-2 text-gray-600">Order details and management</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="status-badge badge-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Order Card -->
            <div class="order-card overflow-hidden">
                <div class="p-6 md:p-8 bg-gradient-to-r from-blue-200 via-amber-50 to-teal-100">
                    <!-- Order Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Customer & Product Info -->
                        <div class="detail-card">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Customer & Products
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                                    <dd class="mt-1 text-base font-medium text-gray-900">
                                        {{ $order->user->name }}
                                        <span class="block text-sm font-normal text-gray-500 mt-1">{{ $order->user->email }}</span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Products</dt>
                                    <dd class="mt-1 text-base font-medium text-gray-900">
                                        @if($order->products->isNotEmpty())
                                            <ul class="space-y-2">
                                                @foreach($order->products as $product)
                                                    <li>
                                                        {{ $product->name }}
                                                        <span class="block text-sm font-normal text-gray-500 mt-1">
                                                            Quantity: {{ $product->pivot->quantity }} × {{ format_taka($product->pivot->price) }}
                                                            ({{ format_taka($product->pivot->price * $product->pivot->quantity) }})
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-sm text-gray-500">No products</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="detail-card">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Order Summary
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                                    <dd class="mt-1 text-2xl font-bold text-gray-900">{{ format_taka($order->total_price) }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                                    <dd class="mt-1 text-base text-gray-900">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-base text-gray-900">{{ $order->updated_at->format('F j, Y \a\t g:i A') }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="divider">

                    <!-- Shipping Information -->
                    <div class="detail-card">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Shipping Information
                        </h2>

                        @php
                            $address = json_decode($order->address);
                        @endphp
                        <div class="bg-gradient-to-r from-gray-50 to-white p-4 rounded-lg border border-gray-200 grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Label</dt>
                                <dd class="mt-1 text-base font-medium text-gray-900">{{ $address->label ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-base font-medium text-gray-900">{{ $address->phone ?? 'N/A' }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-base font-medium text-gray-900">
                                    {{ $address->street_address ?? '' }}, {{ $address->union ?? '' }}, {{ $address->upazila ?? '' }}, {{ $address->district ?? '' }} - {{ $address->postal_code ?? '' }}
                                </dd>
                            </div>
                            @if(isset($address->note) && $address->note)
                            <div class="col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Note</dt>
                                <dd class="mt-1 text-base font-medium text-gray-900">{{ $address->note }}</dd>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-wrap gap-3 justify-end">
                        @if (auth()->user()->id === $order->user_id && $order->status === 'pending')
                            <a href="{{ route('orders.edit', $order) }}" class="action-btn inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[var(--primary)] to-[var(--primary-light)] text-white font-medium rounded-lg hover:from-[var(--primary-dark)] hover:to-[var(--primary)]">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Order
                            </a>
                        @endif

                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                            <a href="{{ route('admin.orders.edit', $order) }}" class="action-btn inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[var(--primary-light)] to-cyan-500 text-white font-medium rounded-lg hover:from-[var(--primary)] hover:to-[var(--primary-light)]">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Admin Edit
                            </a>

                            <form id="deleteForm" action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete()" class="action-btn inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 text-white font-medium rounded-lg hover:from-red-700 hover:to-rose-700">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Order
                                </button>
                            </form>

                            @if($order->status !== 'delivered' && $order->status !== 'cancelled' && $nextStatus)
                                <form id="statusUpdateForm" action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                                    <button type="submit" class="action-btn inline-flex items-center px-5 py-2.5 bg-green-500 text-white font-medium rounded-lg hover:bg-green-700">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Mark as {{ ucfirst($nextStatus) }}
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusForm = document.getElementById('statusUpdateForm');
            if (statusForm) {
                statusForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Update Status',
                        text: `Are you sure you want to mark this order as ${this.querySelector('input[name="status"]').value}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Yes, update it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form after confirmation
                            this.submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush
