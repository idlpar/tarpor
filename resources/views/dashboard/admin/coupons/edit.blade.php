@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('admin_content')
    <div class="min-h-screen bg-bg-light p-6 md:p-8">
        @include('components.breadcrumbs', [
            'links' => [
                'Coupons' => route('coupons.index'),
                'Edit' => null
            ]
        ])

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

        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Coupon</h2>

            <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-text-dark">Coupon Code</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2" required>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-text-dark">Discount Type</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2" required>
                            <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </select>
                    </div>
                    <div>
                        <label for="value" class="block text-sm font-medium text-text-dark">Discount Value</label>
                        <input type="number" name="value" id="value" value="{{ old('value', $coupon->value) }}" step="0.01" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2" required>
                    </div>
                    <div>
                        <label for="usage_limit" class="block text-sm font-medium text-text-dark">Usage Limit (optional)</label>
                        <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2">
                    </div>
                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-text-dark">Expires At (optional)</label>
                        <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2">
                    </div>
                    <div>
                        <label for="max_discount_amount" class="block text-sm font-medium text-text-dark">Max Discount Amount (for percentage, optional)</label>
                        <input type="number" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" step="0.01" class="mt-1 block w-full rounded-md border-input-border bg-input-bg text-text-dark shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 p-2">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">Update Coupon</button>
                </div>
            </form>
        </div>
    </div>
@endsection
