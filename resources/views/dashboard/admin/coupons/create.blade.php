@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('admin_content')
    <div class="min-h-screen bg-bg-light p-6 md:p-8">
        @include('components.breadcrumbs', [
            'links' => [
                'Coupons' => route('coupons.index'),
                'Create' => null
            ]
        ])

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('coupons.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create New Coupon</h1>
                        <p class="mt-1 text-sm text-gray-600">Define a new discount coupon for your store</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('coupons.index') }}" class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                    View All Coupons
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-full mx-auto bg-white p-8 rounded-lg shadow-lg">
            <form action="{{ route('coupons.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Coupon Code</label>
                        <input type="text" name="code" id="code" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required placeholder="e.g., SUMMER20">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Discount Type</label>
                        <select name="type" id="type" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                    <div>
                        <label for="value" class="block text-sm font-semibold text-gray-700 mb-2">Discount Value</label>
                        <input type="number" name="value" id="value" step="0.01" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required placeholder="e.g., 10.00 or 20">
                    </div>
                    <div>
                        <label for="usage_limit" class="block text-sm font-semibold text-gray-700 mb-2">Usage Limit (optional)</label>
                        <input type="number" name="usage_limit" id="usage_limit" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="e.g., 100">
                    </div>
                    <div>
                        <label for="expires_at" class="block text-sm font-semibold text-gray-700 mb-2">Expires At (optional)</label>
                        <input type="date" name="expires_at" id="expires_at" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    </div>
                    <div>
                        <label for="max_discount_amount" class="block text-sm font-semibold text-gray-700 mb-2">Max Discount Amount (for percentage, optional)</label>
                        <input type="number" name="max_discount_amount" id="max_discount_amount" step="0.01" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="e.g., 50.00">
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Create Coupon</button>
                </div>
            </form>
        </div>
    </div>
@endsection
