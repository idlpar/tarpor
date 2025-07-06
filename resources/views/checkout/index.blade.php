@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Billing Information</h2>
                <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                            <input type="text" name="state" id="state" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="zip" class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
                            <input type="text" name="zip" id="zip" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="country" id="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">Place Order</button>
                    </div>
                </form>
            </div>
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h2>
                <div class="space-y-4">
                    @php $total = 0 @endphp
                    @if(session('cart'))
                        @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity'] @endphp
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-800 font-medium">{{ $details['name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $details['attributes'] }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $details['quantity'] }}</p>
                                </div>
                                <p class="text-gray-800 font-medium">BDT {{ number_format($details['price'] * $details['quantity'], 2) }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="border-t border-gray-200 mt-4 pt-4">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-gray-800">Subtotal</p>
                        <p class="text-lg font-semibold text-gray-800">BDT {{ number_format($total, 2) }}</p>
                    </div>
                    @if(session('coupon'))
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-gray-800">Coupon ({{ session('coupon')['code'] }})</p>
                            <p class="text-red-600">- BDT {{ number_format(session('coupon')['discount'], 2) }}</p>
                        </div>
                    @endif
                    @if(session('rewards'))
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-gray-800">Reward Points</p>
                            <p class="text-red-600">- BDT {{ number_format(session('rewards')['discount'], 2) }}</p>
                        </div>
                    @endif
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xl font-bold text-gray-900">Total</p>
                        <p class="text-xl font-bold text-gray-900">BDT {{ number_format($total - (session('coupon.discount', 0) + session('rewards.discount', 0)), 2) }}</p>
                    </div>
                </div>
                <div class="mt-8">
                    <form action="{{ route('coupons.apply') }}" method="POST">
                        @csrf
                        <div class="flex">
                            <input type="text" name="code" placeholder="Enter Coupon Code" class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="submit" class="bg-gray-800 text-white py-2 px-4 rounded-r-md font-semibold hover:bg-gray-700 transition-colors duration-200">Apply</button>
                        </div>
                    </form>
                </div>
                <div class="mt-4">
                    <form action="{{ route('rewards.apply') }}" method="POST">
                        @csrf
                        <div class="flex">
                            <input type="number" name="points" placeholder="Enter Reward Points" class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="submit" class="bg-gray-800 text-white py-2 px-4 rounded-r-md font-semibold hover:bg-gray-700 transition-colors duration-200">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
