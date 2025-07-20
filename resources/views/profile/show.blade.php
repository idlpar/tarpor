@extends('layouts.app')

@push('styles')
    <style>
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

@section('title', 'My Profile')

@section('content')
    @include('components.breadcrumbs', [
        'links' => [
            'Home' => '/',
            'My Profile' => null
        ]
    ])
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">My Profile</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage your personal information and account settings</p>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-8 p-4 rounded-md bg-green-50 border-l-4 border-green-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="bg-gradient-to-r from-sky-200 via-sky-50 to-sky-100 shadow-lg rounded-xl overflow-hidden border border-gray-200">
                    <div class="bg-gradient-to-r from-[var(--primary)] to-[var(--primary-dark)] px-6 py-4">
                        <h2 class="text-xl font-semibold text-white">Profile Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center text-center mb-6">
                            <div class="relative mb-4">
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/avatar/default-avatar.jpg') }}"
                                     alt="User Avatar" class="w-24 h-24 rounded-full border-4 border-white shadow-md">
                                @if($user->profile_photo)
                                    <form action="{{ route('profile.avatar.destroy', $user) }}" method="POST" class="absolute -bottom-2 -right-2 delete-avatar-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 bg-red-500 rounded-full text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                                <span class="ml-3 text-sm text-gray-700">{{ $user->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-3 text-sm text-gray-700 capitalize">{{ $user->role }}</span>
                            </div>
                            @if($user->address)
                                <div class="flex items-start">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-3 text-sm text-gray-700">
                                        {{ $user->street_address ?? '' }}<br>
                                        {{ $user->union ? $user->union . ', ' : '' }}
                                        {{ $user->upazila }}, {{ $user->district }}<br>
                                        {{ $user->division }}, {{ $user->postal_code }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main Content Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Update Profile Form -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                        <div class="bg-gradient-to-r from-[var(--primary)] to-[var(--primary-dark)] px-6 py-4">
                            <h2 class="text-xl font-semibold text-white">Update Profile</h2>
                        </div>
                        <div class="p-6 bg-gradient-to-r from-blue-200 via-blue-100 to-blue-100">
                            <form action="{{ route('profile.update') }}" method="POST" class="update-profile-form">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                                   class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror">
                                        </div>
                                        @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                </svg>
                                            </div>
                                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                                   class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 @enderror">
                                        </div>
                                        @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                                </svg>
                                            </div>
                                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                                   class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full pl-10 py-2 sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 @enderror">
                                        </div>
                                        @error('phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Update Avatar Form -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                        <div class="bg-gradient-to-r from-[var(--primary)] to-[var(--primary-dark)] px-6 py-4">
                            <h2 class="text-xl font-semibold text-white">Update Avatar</h2>
                        </div>
                        <div class="p-6 bg-gradient-to-r from-amber-50 via-amber-100 to-amber-200">
                            <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="update-avatar-form">
                                @csrf
                                @method('PUT')

                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/avatar/default-avatar.jpg') }}"
                                             alt="Current Avatar" class="h-20 w-20 rounded-full border-2 border-gray-200">
                                    </div>
                                    <div class="flex-1 w-full">
                                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Upload New Avatar</label>
                                        <div class="flex items-center">
                                            <input type="file" name="avatar" id="avatar"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[var(--primary)] file:text-white hover:file:bg-[var(--primary-dark)] @error('avatar') border-red-300 @enderror">
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF (Max: 2MB)</p>
                                        @error('avatar')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="mt-6 pt-5 border-t border-gray-200 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                                        Update Avatar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Update Address Form -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                        <div class="bg-gradient-to-r from-[var(--primary)] to-[var(--primary-dark)] px-6 py-4">
                            <h2 class="text-xl font-semibold text-white">Update Address</h2>
                        </div>
                        <div class="p-6 bg-gradient-to-r from-green-50 via-green-100 to-green-200">
                            <form action="{{ route('profile.address.update') }}" method="POST" class="update-address-form">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Division -->
                                    <div>
                                        <label for="division" class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                                        <input type="text" name="division" id="division" value="{{ old('division', $user->division) }}"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full p-2 sm:text-sm border-gray-300 rounded-md @error('division') border-red-300 @enderror">
                                        @error('division')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- District -->
                                    <div>
                                        <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                                        <input type="text" name="district" id="district" value="{{ old('district', $user->district) }}"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full p-2 sm:text-sm border-gray-300 rounded-md @error('district') border-red-300 @enderror">
                                        @error('district')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Upazila -->
                                    <div>
                                        <label for="upazila" class="block text-sm font-medium text-gray-700 mb-1">Upazila</label>
                                        <input type="text" name="upazila" id="upazila" value="{{ old('upazila', $user->upazila) }}"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full p-2 sm:text-sm border-gray-300 rounded-md @error('upazila') border-red-300 @enderror">
                                        @error('upazila')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Union -->
                                    <div>
                                        <label for="union" class="block text-sm font-medium text-gray-700 mb-1">Union (Optional)</label>
                                        <input type="text" name="union" id="union" value="{{ old('union', $user->union) }}"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full p-2 sm:text-sm border-gray-300 rounded-md @error('union') border-red-300 @enderror">
                                        @error('union')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Street Address -->
                                    <div class="md:col-span-2">
                                        <label for="street_address" class="block text-sm font-medium text-gray-700 mb-1">Street Address (Optional)</label>
                                        <input type="text" name="street_address" id="street_address" value="{{ old('street_address', $user->street_address) }}"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full p-2 sm:text-sm border-gray-300 rounded-md @error('street_address') border-red-300 @enderror">
                                        @error('street_address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Postal Code -->
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full p-2 sm:text-sm border-gray-300 rounded-md @error('postal_code') border-red-300 @enderror">
                                        @error('postal_code')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                                        Update Address
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Update Profile Form
            const profileForm = document.querySelector('.update-profile-form');
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Confirm Profile Update',
                    html: `
                        <div class="text-left">
                            <p class="mb-4 text-gray-700">You are about to update your profile with the following details:</p>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span id="swal-name" class="font-medium"></span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span id="swal-email" class="font-medium"></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span id="swal-phone" class="font-medium"></span>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Please verify all information before proceeding.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update Profile',
                    cancelButtonText: 'Cancel',
                    focusCancel: true,
                    customClass: {
                        popup: 'rounded-xl border border-gray-200 shadow-xl',
                        title: 'text-2xl font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4',
                        confirmButton: 'bg-[var(--primary)] hover:bg-[var(--primary-dark)] px-4 py-2 rounded-md font-medium shadow-sm',
                        cancelButton: 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-md font-medium shadow-sm mr-2',
                    },
                    didOpen: () => {
                        document.getElementById('swal-name').textContent = document.getElementById('name').value || '(empty)';
                        document.getElementById('swal-email').textContent = document.getElementById('email').value || '(empty)';
                        document.getElementById('swal-phone').textContent = document.getElementById('phone').value || '(empty)';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        profileForm.submit();
                    }
                });
            });

            // Update Avatar Form
            const avatarForm = document.querySelector('.update-avatar-form');
            avatarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const fileInput = document.getElementById('avatar');
                const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : '(no file selected)';
                Swal.fire({
                    title: 'Confirm Avatar Update',
                    html: `
                        <div class="text-left">
                            <p class="mb-4 text-gray-700">You are about to update your avatar with the following file:</p>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span id="swal-avatar" class="font-medium">${fileName}</span>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Please verify the file before proceeding.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update Avatar',
                    cancelButtonText: 'Cancel',
                    focusCancel: true,
                    customClass: {
                        popup: 'rounded-xl border border-gray-200 shadow-xl',
                        title: 'text-2xl font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4',
                        confirmButton: 'bg-[var(--primary)] hover:bg-[var(--primary-dark)] px-4 py-2 rounded-md font-medium shadow-sm',
                        cancelButton: 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-md font-medium shadow-sm mr-2',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        avatarForm.submit();
                    }
                });
            });

            // Update Address Form
            const addressForm = document.querySelector('.update-address-form');
            addressForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Confirm Address Update',
                    html: `
                        <div class="text-left">
                            <p class="mb-4 text-gray-700">You are about to update your address with the following details:</p>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span id="swal-division" class="font-medium"></span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span id="swal-district" class="font-medium"></span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span id="swal-upazila" class="font-medium"></span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span id="swal-union" class="font-medium"></span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span id="swal-street_address" class="font-medium"></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span id="swal-postal_code" class="font-medium"></span>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Please verify all information before proceeding.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update Address',
                    cancelButtonText: 'Cancel',
                    focusCancel: true,
                    customClass: {
                        popup: 'rounded-xl border border-gray-200 shadow-xl',
                        title: 'text-2xl font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4',
                        confirmButton: 'bg-[var(--primary)] hover:bg-[var(--primary-dark)] px-4 py-2 rounded-md font-medium shadow-sm',
                        cancelButton: 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-md font-medium shadow-sm mr-2',
                    },
                    didOpen: () => {
                        document.getElementById('swal-division').textContent = document.getElementById('division').value || '(empty)';
                        document.getElementById('swal-district').textContent = document.getElementById('district').value || '(empty)';
                        document.getElementById('swal-upazila').textContent = document.getElementById('upazila').value || '(empty)';
                        document.getElementById('swal-union').textContent = document.getElementById('union').value || '(empty)';
                        document.getElementById('swal-street_address').textContent = document.getElementById('street_address').value || '(empty)';
                        document.getElementById('swal-postal_code').textContent = document.getElementById('postal_code').value || '(empty)';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        addressForm.submit();
                    }
                });
            });

            // Delete Avatar Form
            const deleteAvatarForms = document.querySelectorAll('.delete-avatar-form');
            deleteAvatarForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        html: `
                            <div class="text-left">
                                <p class="mb-4 text-gray-700">You are about to delete your profile avatar. This action cannot be undone.</p>
                                <p class="mt-4 text-sm text-red-500">Please confirm to proceed with deletion.</p>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Delete Avatar',
                        cancelButtonText: 'Cancel',
                        focusCancel: true,
                        customClass: {
                            popup: 'rounded-xl border border-gray-200 shadow-xl',
                            title: 'text-2xl font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4',
                            confirmButton: 'bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md font-medium shadow-sm',
                            cancelButton: 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-md font-medium shadow-sm mr-2',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
