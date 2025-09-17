@extends('layouts.user')
@section('title', 'Dashboard')
@section('page-content')
    <!-- Right Side Content -->
    <div class="w-full h-full bg-gray-100 p-2 md:p-8 transition-all duration-300"
         :class="{ 'md:ml-16': isSidebarCollapsed, 'md:ml-14': !isSidebarCollapsed }">
        <!-- Second Toggle Button on Right Side -->
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <div class="flex justify-between items-center">
                <button @click="isSidebarCollapsed = !isSidebarCollapsed"
                        class="p-2 rounded-lg hover:bg-teal-100 md:mr-4"
                        :class="{ 'hidden': !isSidebarCollapsed }">
                    <x-sidebar.sidebar-toogle-right-icon />
                </button>
                <!-- Title on the Left -->
                <h1 class="text-lg lg:text-3xl font-bold text-gray-800">My Profile</h1>
            </div>

            <!-- Breadcrumb on the Right -->
            <div class="text-xs lg:text-sm text-gray-500 flex items-center">
                <a href="{{ route('home') }}" class="hover:text-gray-700">Home</a>  →  
                <a href="#" class="hover:text-gray-700">My Profile</a>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="max-w-full mx-auto bg-white px-8 py-8 rounded-lg shadow-lg">
            <!-- Unverified User Notification -->
            @if (Auth::check() && !Auth::user()->is_verified)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Action Required: Verify Your Identity</p>
                    <p>
                        Please verify your account to place orders and access all features.
                        <a href="{{ route('verify.otp.form') }}" class="text-blue-600 underline hover:text-blue-800">
                            Verify Now
                        </a>
                    </p>
                </div>
            @endif

            <!-- Profile Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Personal Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700">First Name <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">Mohammad Parvar-Uri-Rahman</p>
                    </div>
                    <div>
                        <label class="block text-gray-700">Last Name <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">Pisu</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">admin@support.com</p>
                    </div>
                    <div>
                        <label class="block text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">+860 1551 805527</p>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Company Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700">Company / Organization <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">TARPOR</p>
                    </div>
                    <div>
                        <label class="block text-gray-700">Street Address <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">House Of, East Midlands, Uttara</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700">ZIP Code <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">1230</p>
                    </div>
                    <div>
                        <label class="block text-gray-700">City <span class="text-red-500">*</span></label>
                        <p class="text-gray-600">Dinka</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700">Country <span class="text-red-500">*</span></label>
                    <p class="text-gray-600">Bangladesh</p>
                </div>
            </div>

            <!-- Newsletter Subscription -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Newsletter Subscription</h2>
                <div class="flex items-center">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600">
                    <label class="ml-2 text-gray-700">Send me marketing tips and updates about Brovo.</label>
                </div>
            </div>

            <!-- Action Button -->
            <div class="text-center">
                <button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none">
                    Update My Profile
                </button>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 py-6 text-center text-gray-500 text-sm border-t border-gray-200">
            © {{ date('Y') }} TARPOR. All rights reserved.
        </footer>
    </div>
@endsection
