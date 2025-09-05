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
            @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
                confirmButtonColor: '#008080',
            });
            @endif
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This order will be permanently deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#008080',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        background: 'linear-gradient(135deg, #f8f9fa, #e9ecef)',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            button.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush

@section('title', 'My Orders')

@section('content')
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">My Orders</h1>
                <p class="mt-1 text-sm text-gray-600">View all orders</p>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Order ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Products
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Total Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Total Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Ordered At
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-gradient-to-r from-blue-50 via-amber-50 to-green-50 divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-blue-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($order->products->isNotEmpty())
                                        <div>
                                            {{ $order->products->first()->name }}
                                            @if($order->products->count() > 1)
                                                + {{ $order->products->count() - 1 }} more
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-gray-500">No products</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->products->sum('pivot.quantity') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ format_taka($order->total_price) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                           ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' :
                                           ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                                           'bg-red-100 text-red-800'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->address }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        @can('view', $order)
                                            <a href="{{ route('orders.show', $order) }}" class="text-[var(--primary)] hover:text-[var(--primary-dark)]" title="View Order">
                                                <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('update', $order)
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('orders.edit', $order) }}" class="text-[var(--primary)] hover:text-[var(--primary-dark)]" title="Edit Order">
                                                <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('delete', $order)
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-btn text-red-600 hover:text-red-800" title="Delete Order">
                                                    <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                                    <p class="mt-1 text-sm text-gray-600">No orders have been placed yet.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($orders->hasPages())
                <div class="mt-8 bg-white rounded-lg shadow-xs border border-gray-100 overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-3 sm:px-6">
                        <div class="mb-4 sm:mb-0">
                            <p class="text-sm text-gray-600 font-medium">
                                Showing <span class="text-[var(--primary)]">{{ $orders->firstItem() }}</span>
                                to <span class="text-[var(--primary)]">{{ $orders->lastItem() }}</span>
                                of <span class="text-[var(--primary)]">{{ $orders->total() }}</span> results
                            </p>
                        </div>
                        <nav class="flex items-center space-x-1">
                            {{ $orders->links('components.ui.custom-pagination') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
