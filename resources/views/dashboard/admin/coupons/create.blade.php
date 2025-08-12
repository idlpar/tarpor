@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.breadcrumbs', [
            'links' => [
                'Coupons' => route('coupons.index'),
                'Create' => null
            ]
        ])

        <x-ui.page-header title="Create New Coupon" description="Define a new discount coupon for your store.">
            <a href="{{ route('coupons.index') }}" class="ml-4 flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View All Coupons
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        <form action="{{ route('coupons.store') }}" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column -->
                <div class="w-full lg:w-9/12">
                    <x-ui.content-card class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    </x-ui.content-card>
                </div>

                <!-- Right Column -->
                <div class="w-full lg:w-3/12 sticky top-6">
                    <x-ui.content-card class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Publish</h3>
                        <div class="pt-4 border-t border-gray-200 flex gap-4">
                            <button type="submit" id="saveButton" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors text-sm">Save</button>
                            <button type="submit" name="save_exit" value="1" id="saveExitButton" class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm">Save & Exit</button>
                        </div>
                    </x-ui.content-card>
                </div>
            </div>
        </form>
    </div>
@endsection