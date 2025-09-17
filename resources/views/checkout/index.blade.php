@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="text-sm font-medium text-gray-500 mb-8">
                <ol class="list-none p-0 inline-flex space-x-2 items-center">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Home</a></li>
                    <li><svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                    <li><a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Shopping Cart</a></li>
                    <li><svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                    <li><span class="text-gray-800">Checkout</span></li>
                </ol>
            </nav>
            <!-- Header with subtle gradient -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-2 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-700">
                    Secure Checkout
                </h1>
                <p class="text-lg text-gray-600">Complete your purchase with confidence</p>
            </div>

            <div class="lg:grid lg:grid-cols-3 lg:gap-8 max-w-screen-xl mx-auto">
                <!-- Billing Information - Elegant Card -->
                <div class="lg:col-span-2">
                    <!-- Address Management Section -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 mb-8 transform transition-all hover:shadow-2xl">
                        <div class="flex items-center mb-6">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Billing Information</h2>
                        </div>

                        @guest
                            <!-- Guest Checkout Form -->
                            <form action="{{ route('checkout.placeOrder') }}" method="POST" id="guest-checkout-form">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- First Name -->
                                    <div class="space-y-1">
                                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" name="first_name" id="first_name"
                                                   class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300 @error('first_name') border-red-500 @enderror"
                                                   value="{{ old('first_name') }}" required>
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('first_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Last Name -->
                                    <div class="space-y-1">
                                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="last_name" id="last_name"
                                               class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300 @error('last_name') border-red-500 @enderror"
                                               value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="md:col-span-2 space-y-1">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="email" name="email" id="email"
                                                   class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300 @error('email') border-red-500 @enderror"
                                                   value="{{ old('email') }}" required>
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                <!-- Phone Number -->
                                    <div class="md:col-span-2 space-y-1">
                                        <label for="guest_phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" name="phone" id="guest_phone"
                                                   class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-500 @error('phone') border-red-500 @enderror"
                                                   value="{{ old('phone') }}" required>
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Street Address -->
                                    <div class="md:col-span-2 space-y-1">
                                        <label for="guest_street_address" class="block text-sm font-medium text-gray-700">House, Street, Area <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" name="street_address" id="guest_street_address"
                                                   class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-500 @error('street_address') border-red-500 @enderror"
                                                   value="{{ old('street_address') }}" required>
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('street_address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Union/Village -->
                                    <div>
                                        <label for="guest_union" class="block text-sm font-medium text-gray-700">Union/Village <span class="text-red-500">*</span></label>
                                        <input type="text" name="union" id="guest_union" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500 @error('union') border-red-500 @enderror" value="{{ old('union') }}" required>
                                        @error('union')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Thana/Upazila -->
                                    <div>
                                        <label for="guest_upazila" class="block text-sm font-medium text-gray-700">Thana/Upazila <span class="text-red-500">*</span></label>
                                        <input type="text" name="upazila" id="guest_upazila" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500 @error('upazila') border-red-500 @enderror" value="{{ old('upazila') }}" required>
                                        @error('upazila')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- District -->
                                    <div>
                                        <label for="guest_district_autocomplete" class="block text-sm font-medium text-gray-700">District <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" id="guest_district_autocomplete" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500 @error('district') border-red-500 @enderror" value="{{ old('district') }}" required>
                                            <input type="hidden" name="district" id="guest_district_hidden" value="{{ old('district') }}">
                                            <div id="guest_district_suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                                        </div>
                                        @error('district')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Postal Code -->
                                    <div>
                                        <label for="guest_postal_code" class="block text-sm font-medium text-gray-700">Postal Code <span class="text-red-500">*</span></label>
                                        <input type="text" name="postal_code" id="guest_postal_code" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500 @error('postal_code') border-red-500 @enderror" value="{{ old('postal_code') }}" required>
                                        @error('postal_code')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Note -->
                                    <div class="md:col-span-2">
                                        <label for="guest_note" class="block text-sm font-medium text-gray-700">Note or Specific Instruction</label>
                                        <textarea name="note" id="guest_note" rows="3" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500">{{ old('note') }}</textarea>
                                    </div>
                                    <input type="hidden" name="shipping_option" id="guest_shipping_option_hidden" value="{{ $deliveryCharge }}">
                                </div>
                            </form>
                        @else
                            <!-- Address Management for Logged-in Users -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Saved Addresses</h3>
                                <div id="saved-addresses-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($addresses as $address)
                                        <div class="border rounded-lg p-4 flex flex-col justify-between address-item {{ $defaultAddress && $defaultAddress->id === $address->id ? 'bg-blue-50 border-blue-500' : 'border-gray-200' }}" data-address-id="{{ $address->id }}">
                                            <div>
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-medium text-gray-900">{{ $address->label }}</p>
                                                <p class="text-gray-600 text-sm mt-1">Phone: {{ $address->phone }}</p>
                                                    @if($address->is_default)
                                                        <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full">Default</span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-700 text-sm">{{ $address->street_address }}, {{ $address->union }}, {{ $address->upazila }}, {{ $address->district }}, {{ $address->postal_code }}</p>
                                            </div>
                                            <div class="flex space-x-2 mt-4">
                                                <button type="button" class="use-address-btn w-full px-3 py-2 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition-colors" data-address-id="{{ $address->id }}">Deliver to this Address</button>
                                                <button type="button" class="edit-address-btn px-3 py-2 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600 transition-colors" data-address-id="{{ $address->id }}"><i class="fas fa-edit"></i></button>
                                                @if(!$address->is_default)
                                                    <button type="button" class="make-default-btn px-3 py-2 bg-gray-200 text-gray-800 rounded-md text-sm hover:bg-gray-300 transition-colors" data-address-id="{{ $address->id }}"><i class="fas fa-star"></i></button>
                                                @endif
                                                <button type="button" class="delete-address-btn px-3 py-2 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 transition-colors" data-address-id="{{ $address->id }}"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-600 md:col-span-2">No saved addresses. Please add one.</p>
                                    @endforelse
                                </div>
                                <button type="button" id="add-new-address-btn" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    @if($addresses->isEmpty())
                                        Add New Address
                                    @else
                                        Add Another Address
                                    @endif
                                </button>
                            </div>
                        @endguest
                    </div>

                    <!-- Address Form (Initially Hidden) -->
                    <div id="address-form-container" class="hidden bg-white rounded-2xl shadow-xl p-8 border border-gray-100 mb-8 transform transition-all hover:shadow-2xl">
                        <div class="flex items-center mb-6">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900" id="address-form-title">Add New Address</h2>
                        </div>

                        <form id="ajax-address-form" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="address-form-method" value="POST">
                            <input type="hidden" name="address_id" id="address-form-id">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="label" class="block text-sm font-medium text-gray-700">Label</label>
                                    <select name="label" id="address-label" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500">
                                        <option value="Home" selected>Home</option>
                                        <option value="Work">Work</option>
                                    </select>
                                    <p class="text-red-500 text-sm mt-1" id="error-label"></p>
                                </div>

                                <div class="md:col-span-2 space-y-1">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" name="phone" id="phone"
                                               class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-500 @error('phone') border-red-500 @enderror"
                                               value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2 space-y-1">
                                    <label for="street_address" class="block text-sm font-medium text-gray-700">House, Street, Area <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" name="street_address" id="street_address"
                                               class="w-full pl-10 pr-4 py-3 text-gray-900 bg-white border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-500 @error('street_address') border-red-500 @enderror"
                                               value="{{ old('street_address') }}" required>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('street_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="address-union" class="block text-sm font-medium text-gray-700">Union/Village <span class="text-red-500">*</span></label>
                                    <input type="text" name="union" id="address-union" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500" required>
                                    <p class="text-red-500 text-sm mt-1" id="error-union"></p>
                                </div>

                                <div>
                                    <label for="address-upazila" class="block text-sm font-medium text-gray-700">Thana/Upazila <span class="text-red-500">*</span></label>
                                    <input type="text" name="upazila" id="address-upazila" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500" required>
                                    <p class="text-red-500 text-sm mt-1" id="error-upazila"></p>
                                </div>

                                <div>
                                    <label for="address-district" class="block text-sm font-medium text-gray-700">District <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="text" id="address-district-autocomplete" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500" required>
                                        <input type="hidden" name="district" id="address-district">
                                        <div id="address-district-suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                                    </div>
                                    <p class="text-red-500 text-sm mt-1" id="error-district"></p>
                                </div>

                                <div>
                                    <label for="address-postal_code" class="block text-sm font-medium text-gray-700">Postal Code <span class="text-red-500">*</span></label>
                                    <input type="text" name="postal_code" id="address-postal_code" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500" required>
                                    <p class="text-red-500 text-sm mt-1" id="error-postal_code"></p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="note" class="block text-sm font-medium text-gray-700">Note or Specific Instruction</label>
                                    <textarea name="note" id="note" rows="3" class="mt-1 block w-full rounded-md border border-gray-400 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-3 px-4 text-gray-900 hover:border-gray-500"></textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_default_checkbox" id="is_default_checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="is_default_checkbox" class="ml-2 block text-sm text-gray-900">Set as default address</label>
                                        <input type="hidden" name="is_default" id="address-is_default" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" id="cancel-address-form-btn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                                <button type="submit" id="save-address-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Save Address</button>
                            </div>
                        </form>
                    </div>

                    <!-- Order Submission Form -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transform transition-all hover:shadow-2xl">
                        @guest
                            <!-- Submit Button for Guest Checkout -->
                            <div class="mt-10">
                                <button type="submit" form="guest-checkout-form"
                                        class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Place Your Order
                                </button>
                            </div>
                        @else
                            <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="selected_address_id" id="selected-address-id" value="{{ $defaultAddress->id ?? '' }}">

                                <!-- New hidden fields for address details -->
                                <input type="hidden" name="phone" id="checkout-phone">
                                <input type="hidden" name="street_address" id="checkout-street-address">
                                <input type="hidden" name="district" id="checkout-district">
                                <input type="hidden" name="upazila" id="checkout-upazila">
                                <input type="hidden" name="union" id="checkout-union">
                                <input type="hidden" name="postal_code" id="checkout-postal-code">
                                <input type="hidden" name="note" id="checkout-note">
                                <!-- End new hidden fields -->

                                <!-- Submit Button -->
                                <div class="mt-10">
                                    <button type="submit"
                                            class="w-full py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Place Your Order
                                    </button>
                                </div>
                            </form>
                        @endguest
                    </div>
                </div>

                <!-- Order Summary - Elegant Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transform transition-all hover:shadow-2xl">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-2 rounded-full mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Order Summary</h2>
                    </div>

                    <div class="space-y-4">
                        @php $subtotal = 0 @endphp
                        @if(!empty($cart))
                            @foreach($cart as $id => $details)
                                @php $subtotal += $details['price'] * $details['quantity'] @endphp
                                <div class="flex justify-between items-center py-4 border-b border-gray-100 last:border-0 group">
                                    <div class="flex items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden mr-4">
                                            <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-gray-800 font-medium group-hover:text-blue-600 transition-colors">{{ $details['name'] }}</p>
                                            @if(isset($details['attributes']) && $details['attributes'] !== 'N/A')
                                                <p class="text-sm text-gray-500">{{ $details['attributes'] }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400">Qty: {{ $details['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-800 font-medium">{{ format_taka($details['price'] * $details['quantity']) }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">Your cart is empty.</p>
                        @endif
                    </div>

                    <!-- Shipping Method -->
                    <div class="mb-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Shipping Method</h3>
                        <div class="space-y-3">
                            @foreach($shippingMethods as $method)
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="shipping_option" value="{{ $method->cost }}" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" {{ $deliveryCharge == $method->cost ? 'checked' : '' }}>
                                    <div class="ml-4 flex-grow">
                                        <span class="font-medium text-gray-800">{{ $method->name }}</span>
                                        <p class="text-sm text-gray-500">{{ $method->description }}</p>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ format_taka($method->cost) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Coupon Code -->
                    <div class="mb-6">
                        <label for="coupon-code" class="block text-sm font-medium text-gray-700 mb-2">Have a coupon?</label>
                        <form id="apply-coupon-form" class="flex w-full">
                            <input type="text" id="coupon-code" name="code" placeholder="Enter coupon code" class="flex-grow px-3 py-2 text-gray-900 bg-white border border-gray-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:border-gray-300" value="{{ $coupon['code'] ?? '' }}">
                            <button type="submit" id="apply-coupon-btn" class="bg-gray-800 text-white py-2 px-4 rounded-r-lg font-medium hover:bg-gray-700 transition-colors duration-200 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Apply
                            </button>
                        </form>
                        <p id="coupon-feedback" class="text-sm mt-2 @if(isset($coupon) && !empty($coupon)) text-green-600 @endif">{{ isset($coupon) && !empty($coupon) ? 'Coupon ' . $coupon['code'] . ' applied!' : '' }}</p>
                    </div>

                    <!-- Order Totals -->
                    <div class="mt-8 space-y-3">
                        <div class="flex justify-between py-3">
                            <p class="text-gray-600">Subtotal</p>
                            <p class="text-gray-900 font-medium" id="summary-subtotal">{{ format_taka($subtotal) }}</p>
                        </div>

                        <div class="flex justify-between py-3" id="summary-delivery-charge-row">
                            <p class="text-gray-600">Shipping</p>
                            <p class="text-gray-900 font-medium" id="summary-delivery-charge">{{ format_taka($deliveryCharge) }}</p>
                        </div>

                        <div class="flex justify-between py-3 bg-blue-50 -mx-4 px-4 rounded-lg {{ (isset($coupon) && !empty($coupon)) ? '' : 'hidden' }}" id="summary-coupon-display">
                            <p class="text-gray-600" id="coupon-display-text">Coupon ({{ $coupon['code'] ?? '' }})</p>
                            <p class="text-red-500 font-medium" id="coupon-discount-display">- {{ format_taka($coupon['discount'] ?? 0) }}
                                <button type="button" id="remove-coupon-btn" class="ml-2 text-red-400 hover:text-red-600 focus:outline-none">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </p>
                        </div>

                        <div class="flex justify-between pt-4 mt-4 border-t border-gray-200">
                            <p class="text-lg font-bold text-gray-900">Total</p>
                            @php
                                $finalTotal = $subtotal - ($coupon['discount'] ?? 0) + $deliveryCharge;
                                if ($finalTotal < 0) $finalTotal = 0;
                            @endphp
                            <p class="text-xl font-bold text-blue-600" id="order-total">{{ format_taka($finalTotal) }}</p>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-wrap justify-center gap-4">
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <p class="text-xs text-gray-500 mt-1">Secure Payment</p>
                            </div>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-xs text-gray-500 mt-1">Low Cost Shipping</p>
                            </div>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-gray-500 mt-1">Money Back</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pixel InitiateCheckout Events
            const checkoutValue = parseFloat("{{ $subtotal - ($coupon['discount'] ?? 0) + $deliveryCharge }}");
            const currency = 'BDT';

            // Meta Pixel InitiateCheckout
            if (typeof fbq === 'function') {
                fbq('track', 'InitiateCheckout', {
                    value: checkoutValue,
                    currency: currency
                });
            }

            // Google Tag InitiateCheckout
            if (typeof gtag === 'function') {
                gtag('event', 'begin_checkout', {
                    value: checkoutValue,
                    currency: currency,
                    items: [
                            @foreach($cart as $id => $details)
                        {
                            item_id: '{{ $id }}',
                            item_name: '{{ $details['name'] }}',
                            price: {{ $details['price'] }},
                            quantity: {{ $details['quantity'] }}
                        },
                        @endforeach
                    ]
                });
            }

            // TikTok Pixel InitiateCheckout
            if (typeof ttq === 'object' && typeof ttq.track === 'function') {
                ttq.track('InitiateCheckout', {
                    value: checkoutValue,
                    currency: currency
                });
            }

            // Function to format currency (assuming BDT)
            function formatCurrency(amount) {
                return `৳${parseInt(amount)}`;
            }

            let allDistricts = [];

            // Load all districts once
            async function loadAllDistricts() {
                try {
                    const response = await fetch('/api/districts');
                    allDistricts = await response.json();
                } catch (error) {
                    console.error('Error loading districts:', error);
                }
            }

            // Function to get district name by ID
            function getDistrictNameById(districtId) {
                const district = allDistricts.find(d => d.id == districtId);
                return district ? `${district.name} (${district.bn_name})` : districtId;
            }

            // Initial values from Blade
            let subtotal = parseFloat("{{ $subtotal }}"); // Use $subtotal from PHP
            let couponCode = "{{ $coupon['code'] ?? '' }}"; // Store the coupon code
            let couponDiscount = parseFloat("{{ $coupon['discount'] ?? 0 }}");
            let deliveryCharge = parseFloat("{{ $deliveryCharge ?? 0 }}");

            // DOM Elements
            const orderTotalEl = document.getElementById('order-total');
            const summarySubtotalEl = document.getElementById('summary-subtotal');
            const summaryDeliveryChargeEl = document.getElementById('summary-delivery-charge');
            const summaryCouponDisplayEl = document.getElementById('summary-coupon-display');
            const couponCodeInput = document.getElementById('coupon-code');
            const couponFeedbackEl = document.getElementById('coupon-feedback');
            const applyCouponForm = document.getElementById('apply-coupon-form');

            function updateOrderSummary() {
                let currentTotal = subtotal - couponDiscount + deliveryCharge;
                if (currentTotal < 0) currentTotal = 0;

                orderTotalEl.textContent = formatCurrency(currentTotal);
                summarySubtotalEl.textContent = formatCurrency(subtotal);
                summaryDeliveryChargeEl.textContent = formatCurrency(deliveryCharge);

                if (couponDiscount > 0) {
                    summaryCouponDisplayEl.classList.remove('hidden');
                    document.getElementById('coupon-display-text').textContent = `Coupon (${couponCode})`;
                    document.getElementById('coupon-discount-display').innerHTML = `- ${formatCurrency(couponDiscount)}
                        <button type="button" id="remove-coupon-btn" class="ml-2 text-red-400 hover:text-red-600 focus:outline-none">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>`;
                } else {
                    summaryCouponDisplayEl.classList.add('hidden');
                    couponFeedbackEl.textContent = ''; // Clear feedback when no coupon
                    couponFeedbackEl.className = 'text-sm mt-2';
                }
            }

            // Event listener for coupon removal (delegated to document)
            document.addEventListener('click', function(event) {
                // Use closest to handle clicks on the button or its SVG icon
                const removeButton = event.target.closest('#remove-coupon-btn');
                if (removeButton) {
                    fetch('{{ route('coupons.remove') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                couponDiscount = 0;
                                couponCode = ''; // Clear coupon code
                                couponCodeInput.value = ''; // Clear coupon input field
                                updateOrderSummary();
                                // Use a less intrusive notification if possible in a real app
                                couponFeedbackEl.textContent = data.message;
                                couponFeedbackEl.className = 'text-sm mt-2 text-green-600';
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error removing coupon:', error);
                            alert('Failed to remove coupon.');
                        });
                }
            });

            // Event listener for shipping option change
            document.querySelectorAll('input[name="shipping_option"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    deliveryCharge = parseFloat(this.value);
                    updateOrderSummary();

                    // Update the hidden shipping option input for guest form if it exists
                    const guestShippingOptionHidden = document.getElementById('guest_shipping_option_hidden');
                    if (guestShippingOptionHidden) {
                        guestShippingOptionHidden.value = this.value;
                    }

                    Swal.fire({
                        iconHtml: '<svg class="w-12 h-12 sm:w-16 sm:h-16 text-blue-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17H5a2 2 0 00-2 2h16a2 2 0 002-2v-3a2 2 0 00-2-2H6a2 2 0 00-2 2v3h15zM19 17V9a2 2 0 00-2-2H5a2 2 0 00-2 2v8h16z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9V5a2 2 0 00-2-2H5a2 2 0 00-2 2v4"></path></svg>',
                        title: this.nextElementSibling.querySelector('span').textContent + ' Selected',
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            popup: 'swal2-popup-custom p-4 sm:p-6',
                            title: 'swal2-title-custom text-lg sm:text-xl',
                            htmlContainer: 'swal2-html-container-custom',
                        },
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                });
            });


            // Event listener for coupon application form submission
            if (applyCouponForm) {
                applyCouponForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const code = couponCodeInput.value.trim();

                    try {
                        const response = await fetch('{{ route('coupons.apply') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ code: code })
                        });

                        if (!response.ok) {
                            let errorMessage = 'An unknown error occurred.';
                            try {
                                const errorData = await response.json();
                                errorMessage = errorData.message || errorMessage;
                            } catch (jsonError) {
                                errorMessage = await response.text();
                            }
                            throw new Error(errorMessage);
                        }

                        const data = await response.json();
                        couponDiscount = data.discount;
                        couponCode = code; // Store the applied coupon code
                        couponFeedbackEl.textContent = data.message || 'Coupon applied successfully!';
                        couponFeedbackEl.className = 'text-sm mt-2 text-green-600 font-semibold';
                        updateOrderSummary();

                    } catch (error) {
                        console.error('Error applying coupon:', error);
                        couponDiscount = 0;
                        couponCode = ''; // Clear coupon code on error
                        updateOrderSummary(); // Update summary to remove old discount
                        couponFeedbackEl.textContent = error.message; // Display the new error message
                        couponFeedbackEl.className = 'text-sm mt-2 text-red-500 font-semibold';
                        return;
                    }
                });
            }

            // Initial call to update summary on page load
            updateOrderSummary();

            // Client-side validation for shipping method
            const checkoutForm = document.getElementById('checkout-form');
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    const selectedShippingOption = document.querySelector('input[name="shipping_option"]:checked');
                    if (!selectedShippingOption) {
                        e.preventDefault(); // Prevent form submission
                        Swal.fire({
                            icon: 'warning',
                            title: 'Shipping Method Required',
                            text: 'Please select a shipping method to proceed with your order.',
                            confirmButtonColor: '#3085d6',
                        });
                    } else {
                        // Create a hidden input for the selected shipping option and append it to the form
                        const hiddenShippingInput = document.createElement('input');
                        hiddenShippingInput.type = 'hidden';
                        hiddenShippingInput.name = 'shipping_option';
                        hiddenShippingInput.value = selectedShippingOption.value;
                        checkoutForm.appendChild(hiddenShippingInput);
                    }
                });
            }

            @guest
                // Guest Address Fields and Autocomplete
                const guestPhoneInput = document.getElementById('guest_phone');
                const guestDistrictAutocompleteInput = document.getElementById('guest_district_autocomplete');
                const guestDistrictHiddenInput = document.getElementById('guest_district_hidden');
                const guestDistrictSuggestionsContainer = document.getElementById('guest_district_suggestions');
                const guestStreetAddressInput = document.getElementById('guest_street_address');
                const guestUnionInput = document.getElementById('guest_union');
                const guestUpazilaInput = document.getElementById('guest_upazila');
                const guestPostalCodeInput = document.getElementById('guest_postal_code');
                const guestNoteInput = document.getElementById('guest_note');

                // Load districts immediately for guests
                loadAllDistricts();

                // Autocomplete logic for guest district
                if (guestDistrictAutocompleteInput) {
                    guestDistrictAutocompleteInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase();
                        guestDistrictSuggestionsContainer.innerHTML = '';

                        if (query.length > 0) {
                            const filteredDistricts = allDistricts.filter(district =>
                                district.name.toLowerCase().includes(query) ||
                                district.bn_name.toLowerCase().includes(query)
                            );

                            filteredDistricts.forEach(district => {
                                const div = document.createElement('div');
                                div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100');
                                div.textContent = `${district.name} (${district.bn_name})`;
                                div.dataset.id = district.id;
                                div.dataset.name = district.name;
                                div.addEventListener('click', function() {
                                    guestDistrictAutocompleteInput.value = this.dataset.name;
                                    guestDistrictHiddenInput.value = this.dataset.id; // Update hidden input
                                    guestDistrictSuggestionsContainer.classList.add('hidden');
                                });
                                guestDistrictSuggestionsContainer.appendChild(div);
                            });
                            guestDistrictSuggestionsContainer.classList.remove('hidden');
                        } else {
                            guestDistrictSuggestionsContainer.classList.add('hidden');
                        }
                    });

                    // Hide suggestions when clicking outside
                    document.addEventListener('click', function(event) {
                        if (!guestDistrictAutocompleteInput.contains(event.target) && !guestDistrictSuggestionsContainer.contains(event.target)) {
                            guestDistrictSuggestionsContainer.classList.add('hidden');
                        }
                    });
                }

                // Phone number validation for guest form
                const guestCheckoutForm = document.getElementById('guest-checkout-form');
                if (guestCheckoutForm) {
                    guestCheckoutForm.addEventListener('submit', function(e) {
                        const phoneNumber = guestPhoneInput.value.trim();
                        if (phoneNumber.length !== 11 || !/^[0-9]+$/.test(phoneNumber)) {
                            e.preventDefault(); // Prevent form submission
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Phone number must be exactly 11 digits and contain only numbers.',
                                confirmButtonColor: '#d33',
                            });
                        }
                    });
                }

            @else
                // Registered User Address Management Logic
                const addAddressBtn = document.getElementById('add-new-address-btn');
                const addressFormContainer = document.getElementById('address-form-container');
                const cancelAddressFormBtn = document.getElementById('cancel-address-form-btn');
                const ajaxAddressForm = document.getElementById('ajax-address-form');
                const addressFormTitle = document.getElementById('address-form-title');
                const addressFormMethod = document.getElementById('address-form-method');
                const addressFormId = document.getElementById('address-form-id');
                const savedAddressesContainer = document.getElementById('saved-addresses-container');
                const selectedAddressIdInput = document.getElementById('selected-address-id');

                const addressFields = {
                    label: document.getElementById('address-label'),
                    phone: document.getElementById('phone'),
                    district_id: document.getElementById('address-district'), // Hidden input for ID
                    upazila: document.getElementById('address-upazila'),
                    union: document.getElementById('address-union'),
                    street_address: document.getElementById('street_address'),
                    postal_code: document.getElementById('address-postal_code'),
                    note: document.getElementById('note'),
                    is_default_checkbox: document.getElementById('is_default_checkbox'), // New checkbox
                    is_default_hidden: document.getElementById('address-is_default'), // New hidden input
                };

                const districtAutocompleteInput = document.getElementById('address-district-autocomplete'); // Visible input for text
                const districtSuggestionsContainer = document.getElementById('address-district-suggestions');

                // Store addresses from Blade for easy access
                const allUserAddresses = @json($addresses);

                function clearAddressForm() {
                    for (const key in addressFields) {
                        if (addressFields.hasOwnProperty(key)) {
                            const field = addressFields[key];
                            if (field) { // Check if field is not null
                                if (field.type === 'checkbox') {
                                    field.checked = false;
                                } else if (field.tagName === 'SELECT') {
                                    if (key !== 'label') {
                                        field.value = '';
                                    }
                                } else {
                                    field.value = '';
                                }
                            }
                        }
                    }
                    // Also clear the autocomplete input
                    if (districtAutocompleteInput) districtAutocompleteInput.value = '';
                    if (addressFormTitle) addressFormTitle.textContent = 'Add New Address';
                    if (addressFormMethod) addressFormMethod.value = 'POST';
                    if (addressFormId) addressFormId.value = '';
                    if (document.getElementById('save-address-btn')) document.getElementById('save-address-btn').textContent = 'Save Address';
                    clearValidationErrors();
                }

                function clearValidationErrors() {
                    document.querySelectorAll('[id^="error-"]').forEach(el => el.textContent = '');

                    // Remove red border from all input fields
                    for (const key in addressFields) {
                        if (addressFields.hasOwnProperty(key)) {
                            const field = addressFields[key];
                            if (field) {
                                field.classList.remove('border-red-500');
                            }
                        }
                    }
                    // Special case for district autocomplete
                    const districtInput = document.getElementById('address-district-autocomplete');
                    if (districtInput) {
                        districtInput.classList.remove('border-red-500');
                    }
                }

                function displayValidationErrors(errors) {
                    clearValidationErrors();
                    for (const field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            const errorElement = document.getElementById(`error-${field}`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                            }

                            // Add red border to the input field
                            const inputField = addressFields[field]; // Get the input element from addressFields
                            if (inputField) {
                                inputField.classList.add('border-red-500');
                            }
                        }
                    }
                }

                // Show/Hide Address Form
                if (addAddressBtn) {
                    addAddressBtn.addEventListener('click', function() {
                        addressFormContainer.classList.remove('hidden');
                        addAddressBtn.classList.add('hidden');
                        clearAddressForm();
                        loadAllDistricts(); // Load districts when form is opened
                    });
                }

                if (cancelAddressFormBtn) {
                    cancelAddressFormBtn.addEventListener('click', function() {
                        addressFormContainer.classList.add('hidden');
                        addAddressBtn.classList.remove('hidden');
                        clearAddressForm();
                    });
                }

                // Handle AJAX Address Form Submission
                if (ajaxAddressForm) {
                    ajaxAddressForm.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        // Phone number validation
                        const phoneNumber = addressFields.phone.value.trim();
                        if (phoneNumber.length !== 11 || !/^[0-9]+$/.test(phoneNumber)) {
                            displayValidationErrors({ phone: ['Phone number must be exactly 11 digits and contain only numbers.'] });
                            return;
                        }

                        const formData = new FormData(this);
                        const method = addressFormMethod.value;
                        const addressId = addressFormId.value;
                        let url = '{{ route('profile.addresses.store') }}';

                        if (method === 'PUT') {
                            url = `/profile/addresses/${addressId}`;
                        }
                        // Ensure district_id is sent correctly
                        formData.set('district', districtAutocompleteInput.value);
                        // Set is_default based on checkbox
                        formData.set('is_default', addressFields.is_default_checkbox.checked ? 1 : 0);

                        try {
                            const response = await fetch(url, {
                                method: 'POST', // Always POST for Laravel, _method handles PUT
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                                body: formData,
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                if (response.status === 422 && data.errors) {
                                    displayValidationErrors(data.errors);
                                } else {
                                    Swal.fire('Error', data.message || 'An unexpected error occurred.', 'error');
                                }
                                return;
                            }

                            Swal.fire('Success', data.message, 'success');
                            addressFormContainer.classList.add('hidden');
                            addAddressBtn.classList.remove('hidden');
                            clearAddressForm();
                            // Reload addresses or dynamically add/update them
                            fetchAddresses();

                        } catch (error) {
                            console.error('Error saving address:', error);
                            Swal.fire('Error', 'Failed to save address.', 'error');
                        }
                    });
                }

                // Fetch and Render Addresses
                async function fetchAddresses() {
                    try {
                        const response = await fetch('/api/user/addresses', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });
                        const data = await response.json();

                        if (data.success) {
                            savedAddressesContainer.innerHTML = '';
                            if (data.addresses.length === 0) {
                                savedAddressesContainer.innerHTML = '<p class="text-gray-600 md:col-span-2">No saved addresses. Please add one.</p>';
                                addAddressBtn.textContent = 'Add New Address';
                                selectedAddressIdInput.value = '';
                            } else {
                                addAddressBtn.innerHTML = '<i class="fas fa-plus mr-2"></i> Add Another Address';
                                data.addresses.forEach(address => {
                                    const isDefault = address.is_default;
                                    const isSelected = selectedAddressIdInput.value == address.id;
                                    const addressHtml = `
                                        <div class="border rounded-lg p-4 flex flex-col justify-between address-item ${isSelected ? 'bg-blue-50 border-blue-500' : 'border-gray-200'}" data-address-id="${address.id}">
                                            <div>
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-medium text-gray-900">${address.label}</p>
                                                    <p class="text-gray-600 text-sm mt-1">Phone: ${address.phone}</p>
                                                    ${isDefault ? '<span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full">Default</span>' : ''}
                                                </div>
                                                <p class="text-gray-700 text-sm">${address.street_address}, ${address.union}, ${address.upazila}, ${getDistrictNameById(address.district)}, ${address.postal_code}</p>
                                            </div>
                                            <div class="flex space-x-2 mt-4">
                                                <button type="button" class="use-address-btn w-full px-3 py-2 ${isSelected ? 'bg-blue-600' : 'bg-blue-500'} text-white rounded-md text-sm hover:bg-blue-600 transition-colors" data-address-id="${address.id}">${isSelected ? 'Selected' : 'Deliver to this Address'}</button>
                                                <button type="button" class="edit-address-btn px-3 py-2 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600 transition-colors" data-address-id="${address.id}"><i class="fas fa-edit"></i></button>
                                                ${!isDefault ? `<button type="button" class="make-default-btn px-3 py-2 bg-gray-200 text-gray-800 rounded-md text-sm hover:bg-gray-300 transition-colors" data-address-id="${address.id}"><i class="fas fa-star"></i></button>` : ''}
                                                <button type="button" class="delete-address-btn px-3 py-2 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 transition-colors" data-address-id="${address.id}"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    `;
                                    savedAddressesContainer.insertAdjacentHTML('beforeend', addressHtml);
                                });
                                // Ensure a default address is selected if available
                                if (data.defaultAddress && !selectedAddressIdInput.value) {
                                    selectedAddressIdInput.value = data.defaultAddress.id;
                                    highlightSelectedAddress(data.defaultAddress.id);
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching addresses:', error);
                        Swal.fire('Error', 'Failed to load addresses.', 'error');
                    }
                }

                // Highlight selected address
                function highlightSelectedAddress(addressId) {
                    document.querySelectorAll('.address-item').forEach(item => {
                        item.classList.remove('bg-blue-50', 'border-blue-500');
                        const useBtn = item.querySelector('.use-address-btn');
                        if (useBtn) { // Add null check
                            useBtn.textContent = 'Deliver to this Address';
                            useBtn.classList.remove('bg-blue-600');
                            useBtn.classList.add('bg-blue-500');
                        }
                    });
                    const selectedItem = document.querySelector(`.address-item[data-address-id="${addressId}"]`);
                    if (selectedItem) {
                        selectedItem.classList.add('bg-blue-50', 'border-blue-500');
                        const useBtn = selectedItem.querySelector('.use-address-btn');
                        if (useBtn) { // Add null check
                            useBtn.textContent = 'Selected';
                            useBtn.classList.remove('bg-blue-500');
                            useBtn.classList.add('bg-blue-600');
                        }

                        // Populate hidden fields in the checkout form
                        const selectedAddress = allUserAddresses.find(addr => addr.id == addressId);
                        if (selectedAddress) {
                            const checkoutPhone = document.getElementById('checkout-phone');
                            if (checkoutPhone) { // Add null check
                                checkoutPhone.value = selectedAddress.phone || '';
                            }
                            const checkoutStreetAddress = document.getElementById('checkout-street-address');
                            if (checkoutStreetAddress) { // Add null check
                                checkoutStreetAddress.value = selectedAddress.street_address || '';
                            }
                            const checkoutDistrict = document.getElementById('checkout-district');
                            if (checkoutDistrict) { // Add null check
                                checkoutDistrict.value = selectedAddress.district || '';
                            }
                            const checkoutUpazila = document.getElementById('checkout-upazila');
                            if (checkoutUpazila) { // Add null check
                                checkoutUpazila.value = selectedAddress.upazila || '';
                            }
                            const checkoutUnion = document.getElementById('checkout-union');
                            if (checkoutUnion) { // Add null check
                                checkoutUnion.value = selectedAddress.union || '';
                            }
                            const checkoutPostalCode = document.getElementById('checkout-postal-code');
                            if (checkoutPostalCode) { // Add null check
                                checkoutPostalCode.value = selectedAddress.postal_code || '';
                            }
                            const checkoutNote = document.getElementById('checkout-note');
                            if (checkoutNote) { // Add null check
                                checkoutNote.value = selectedAddress.note || '';
                            }
                        }
                    }
                }

                // Event delegation for Use, Make Default, Edit, Delete buttons
                if (savedAddressesContainer) {
                    savedAddressesContainer.addEventListener('click', async function(event) {
                        const target = event.target.closest('button');
                        if (!target) return;

                        const addressId = target.dataset.addressId;

                        // Use Address
                        if (target.classList.contains('use-address-btn')) {
                            selectedAddressIdInput.value = addressId;
                            highlightSelectedAddress(addressId);
                            Swal.fire('Address Selected', 'Your chosen address has been set for this order.', 'success');
                        }

                        // Make Default
                        if (target.classList.contains('make-default-btn')) {
                            try {
                                const response = await fetch(`/api/user/addresses/${addressId}/set-default`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                });
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Success', data.message, 'success');
                                    fetchAddresses(); // Re-fetch to update UI
                                } else {
                                    Swal.fire('Error', data.message || 'Failed to set default address.', 'error');
                                }
                            } catch (error) {
                                console.error('Error setting default address:', error);
                                Swal.fire('Error', 'Failed to set default address.', 'error');
                            }
                        }

                        // Edit Address
                        if (target.classList.contains('edit-address-btn')) {
                            await loadAllDistricts(); // Ensure districts are loaded before populating
                            try {
                                const response = await fetch(`/api/user/addresses/${addressId}`, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                });
                                const data = await response.json();

                                if (data.success && data.address) {
                                    const address = data.address;
                                    addressFormContainer.classList.remove('hidden');
                                    addAddressBtn.classList.add('hidden');
                                    addressFormTitle.textContent = 'Edit Address';
                                    addressFormMethod.value = 'PUT';
                                    addressFormId.value = address.id;
                                    document.getElementById('save-address-btn').textContent = 'Update Address';

                                    // Populate form fields
                                    addressFields.label.value = address.label || '';
                                    addressFields.street_address.value = address.street_address || '';
                                    addressFields.postal_code.value = address.postal_code || '';
                                    if (addressFields.is_default_checkbox) { // Add null check
                                        addressFields.is_default_checkbox.checked = address.is_default;
                                    }
                                    if (addressFields.is_default_hidden) { // Add null check
                                        addressFields.is_default_hidden.value = address.is_default ? 1 : 0;
                                    }
                                    addressFields.upazila.value = address.upazila || '';
                                    addressFields.union.value = address.union || '';
                                    addressFields.phone.value = address.phone || '';
                                    addressFields.note.value = address.note || '';

                                    // Populate district autocomplete and hidden ID
                                    if (districtAutocompleteInput) {
                                        districtAutocompleteInput.value = getDistrictNameById(address.district) || '';
                                    }
                                    if (addressFields.district_id) {
                                        addressFields.district_id.value = address.district || '';
                                    }

                                    clearValidationErrors();

                                } else {
                                    Swal.fire('Error', data.message || 'Failed to load address for editing.', 'error');
                                }
                            } catch (error) {
                                console.error('Error fetching address for edit:', error);
                                Swal.fire('Error', 'Failed to load address for editing.', 'error');
                            }
                        }

                        // Delete Address
                        if (target.classList.contains('delete-address-btn')) {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, delete it!',
                                focusCancel: true
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    try {
                                        const response = await fetch(`/profile/addresses/${addressId}`, {
                                            method: 'DELETE',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest',
                                            },
                                        });
                                        const data = await response.json();
                                        if (data.success) {
                                            Swal.fire(
                                                'Deleted!',
                                                'Your address has been deleted.',
                                                'success'
                                            )
                                            fetchAddresses(); // Re-fetch to update UI
                                        } else {
                                            Swal.fire('Error', data.message || 'Failed to delete address.', 'error');
                                        }
                                    } catch (error) {
                                        console.error('Error deleting address:', error);
                                        Swal.fire('Error', 'Failed to delete address.', 'error');
                                    }
                                }
                            })
                        }
                    });
                }

                // Initial fetch of addresses for authenticated users
                fetchAddresses().then(() => {
                    // After addresses are fetched and rendered, highlight the default one if it exists
                    const initialSelectedAddressId = selectedAddressIdInput.value;
                    if (initialSelectedAddressId) {
                        highlightSelectedAddress(initialSelectedAddressId);
                    }
                });
            @endguest
        });
    </script>
@endpush