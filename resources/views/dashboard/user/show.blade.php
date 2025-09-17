@extends('layouts.user')
@section('title', 'Profile')
@section('page-content')
    <!-- Right Side Content -->
    <div class="w-full h-full bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-8 transition-all duration-300"
         :class="{ 'md:ml-16': isSidebarCollapsed, 'md:ml-14': !isSidebarCollapsed }">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 md:mb-8">
            <div class="flex items-center mb-4 md:mb-0">
                <button @click="isSidebarCollapsed = !isSidebarCollapsed"
                        class="p-2 rounded-lg hover:bg-teal-100/50 transition-colors md:mr-4"
                        :class="{ 'hidden': !isSidebarCollapsed }">
                    <x-sidebar.sidebar-toogle-right-icon class="w-5 h-5 text-gray-600" />
                </button>

                <!-- Title with decorative element -->
                <div class="relative">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 tracking-tight">
                        My Profile
                    </h1>
                    <div class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
                </div>
            </div>

            <!-- Breadcrumb with subtle animation -->
            <div class="flex items-center space-x-1 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-gray-700 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-blue-600 font-medium">My Profile</span>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="max-w-full mx-auto bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <!-- Profile Header with Gradient -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <div class="flex items-center">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                        <p class="text-blue-100">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="p-6 md:p-8">
                <!-- Unverified User Notification -->
                @if (Auth::check() && !Auth::user()->is_verified)
                    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 p-4 mb-8 rounded-r-lg flex items-start" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-bold">Action Required: Verify Your Identity</p>
                            <p class="text-sm">
                                Please verify your account to place orders and access all features.
                                <a href="{{ route('verify.otp.form') }}" class="text-blue-600 underline hover:text-blue-800 font-medium">
                                    Verify Now →
                                </a>
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-8 rounded-r-lg flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 p-4 mb-8 rounded-r-lg">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-bold">Please fix these errors:</h3>
                                <ul class="list-disc list-inside text-sm mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Profile Form -->
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div class="mb-10">
                        <h2 class="text-xl font-semibold mb-6 text-gray-800 flex items-center">
                            <span class="w-2 h-6 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></span>
                            Personal Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name ?? Auth::user()->name) }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('first_name')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name ?? '') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('last_name')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('email')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('phone')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="mb-10">
                        <h2 class="text-xl font-semibold mb-6 text-gray-800 flex items-center">
                            <span class="w-2 h-6 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></span>
                            Company Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Company / Organization <span class="text-red-500">*</span></label>
                                <input type="text" name="company" value="{{ old('company', Auth::user()->company ?? 'TARPOR') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('company')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                                <input type="text" name="address" value="{{ old('address', Auth::user()->address ?? '') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('address')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code <span class="text-red-500">*</span></label>
                                <input type="text" name="zip_code" value="{{ old('zip_code', Auth::user()->zip_code ?? '') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('zip_code')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                                <input type="text" name="city" value="{{ old('city', Auth::user()->city ?? '') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                                @error('city')
                                <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <input type="text" name="country" value="{{ old('country', Auth::user()->country ?? 'Bangladesh') }}"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 placeholder-gray-400">
                            @error('country')
                            <p class="absolute text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Newsletter Subscription -->
                    <div class="mb-10">
                        <h2 class="text-xl font-semibold mb-6 text-gray-800 flex items-center">
                            <span class="w-2 h-6 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full mr-3"></span>
                            Communication Preferences
                        </h2>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="newsletter" name="newsletter" type="checkbox" {{ old('newsletter', Auth::user()->newsletter ?? false) ? 'checked' : '' }}
                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="newsletter" class="font-medium text-gray-700">Subscribe to Newsletter</label>
                                <p class="text-gray-500">Receive marketing tips and updates about TARPOR</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-200 focus:ring-offset-2">
                            Update Profile
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <footer class="mt-12 pt-6 text-center text-gray-500 text-sm border-t border-gray-100">
                    © {{ date('Y') }} TARPOR. All rights reserved.
                </footer>
            </div>
        </div>
    </div>
@endsection
