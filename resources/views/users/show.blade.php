@extends('layouts.admin')

@section('title', $user->name . ' | User Details')

@section('admin_content')
    @include('components.breadcrumbs', [
        'links' => [
            'Users' => route('users.index'),
            $user->name => null
        ]
    ])
    <section class="py-12 bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <a href="{{ route('users.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">User Details: {{ $user->name }}</h1>
                            <p class="mt-1 text-sm text-gray-600">Detailed information about {{ $user->name }}</p>
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

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="bg-gradient-to-r from-[var(--primary)] to-[var(--primary-dark)] px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">User Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Role</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ ucfirst($user->role) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Registered On</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                        @if($user->phone)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Phone</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->phone }}</p>
                            </div>
                        @endif
                        @if($user->profile_photo)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Avatar</p>
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="User Avatar" class="mt-1 w-24 h-24 rounded-full object-cover">
                            </div>
                        @endif
                    </div>

                    @if($user->division || $user->district || $user->upazila || $user->union || $user->street_address || $user->postal_code)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Address Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($user->division)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Division</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->division }}</p>
                                    </div>
                                @endif
                                @if($user->district)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">District</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->district }}</p>
                                    </div>
                                @endif
                                @if($user->upazila)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Upazila</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->upazila }}</p>
                                    </div>
                                @endif
                                @if($user->union)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Union</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->union }}</p>
                                    </div>
                                @endif
                                @if($user->street_address)
                                    <div class="md:col-span-2">
                                        <p class="text-sm font-medium text-gray-500">Street Address</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->street_address }}</p>
                                    </div>
                                @endif
                                @if($user->postal_code)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Postal Code</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->postal_code }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-3">
                        
                        <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Edit User
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block delete-user-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm-1 3a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                Delete User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteForms = document.querySelectorAll('.delete-user-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusConfirm: false
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