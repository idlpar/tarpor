@extends('layouts.admin')
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

@section('title', 'Edit User')

@section('admin_content')
    <section class="bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-full mx-auto">
            @include('components.breadcrumbs', [
                'links' => [
                    'Users' => route('users.index'),
                    'Edit User' => null
                ]
            ])
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <a href="{{ route('users.index') }}" class="mr-4 text-blue-gray-400 hover:text-amber-600 transition duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit User</h1>
                            <p class="mt-1 text-sm text-gray-600">Update user details and permissions</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' :
                           ($user->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $user->verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $user->verified_at ? 'Verified' : 'Unverified' }}
                    </span>
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

            <!-- Form Card -->
            <div class="bg-teal-50 shadow-lg rounded-xl overflow-hidden border border-blue-gray-200">
                <!-- User Profile Header -->
                <div class="bg-teal-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-white font-bold text-xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                            <p class="text-sm text-white text-opacity-90">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-6 bg-gradient-to-b from-teal-50 to-teal-100">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                           class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full pl-10 py-2 sm:text-sm border-blue-gray-300 rounded-md @error('name') border-red-300 @enderror">
                                </div>
                                @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                           class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full pl-10 py-2 sm:text-sm border-blue-gray-300 rounded-md @error('email') border-red-300 @enderror">
                                </div>
                                @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-blue-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="password" name="password" id="password"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full pl-10 py-2 sm:text-sm border-blue-gray-300 rounded-md @error('password') border-red-300 @enderror">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-600">Leave blank to keep current password</p>
                                    @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-blue-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                               class="focus:ring-[var(--primary)] focus:border-[var(--primary)] block w-full pl-10 py-2 sm:text-sm border-blue-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Role Field -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">User Role</label>
                                <select name="role" id="role"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-blue-gray-300 focus:outline-none focus:ring-[var(--primary)] focus:border-[var(--primary)] sm:text-sm rounded-md @error('role') border-red-300 @enderror">
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }} class="text-purple-800">Admin</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }} class="text-blue-800">Staff</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }} class="text-green-800">User</option>
                                </select>
                                @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 pt-5 border-t border-blue-gray-200 flex justify-end space-x-3">
                            <a href="{{ route('users.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-teal-300 text-sm font-medium rounded-md text-teal-700 bg-teal-50 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action="{{ route('users.update', $user) }}"]');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // stop normal submit

                Swal.fire({
                    title: 'Confirm User Update',
                    html: `
            <div class="text-left">
                <p class="mb-4 text-gray-700">You are about to update the user with the following details:</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="swal-role" class="font-medium"></span>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500">Please verify all information before proceeding.</p>
            </div>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Update User',
                    cancelButtonText: 'Cancel',
                    focusCancel: true, // Set default focus to Cancel
                    customClass: {
                        popup: 'rounded-xl border border-gray-200 shadow-xl',
                        title: 'text-2xl font-bold text-gray-800 border-b border-gray-200 pb-4 mb-4',
                        confirmButton: 'bg-[var(--primary)] hover:bg-[var(--primary-dark)] px-4 py-2 rounded-md font-medium shadow-sm',
                        cancelButton: 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-md font-medium shadow-sm mr-2',
                    },
                    didOpen: () => {
                        document.getElementById('swal-name').textContent = document.getElementById('name').value || '(empty)';
                        document.getElementById('swal-email').textContent = document.getElementById('email').value || '(empty)';
                        const roleSelect = document.getElementById('role');
                        document.getElementById('swal-role').textContent = roleSelect.options[roleSelect.selectedIndex].text || '(empty)';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

