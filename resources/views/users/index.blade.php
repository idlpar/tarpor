@extends('layouts.admin')

@section('title', 'All Users')

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

@section('admin_content')
    @include('components.breadcrumbs', [
        'links' => [
            'Users' => null
        ]
    ])
    <section class="py-12 bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">User Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage all registered users and their permissions</p>
                </div>
                <div>
                    <a href="{{ route('users.create') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-[var(--primary)] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create New User
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-8 p-4 rounded-md bg-green-50 border-l-4 border-green-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
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

            <!-- Search and Filter Bar -->
            <div class="mb-6 bg-green-100 p-4 rounded-lg shadow-sm border border-blue-gray-200">
                <form method="GET" action="{{ route('users.index') }}" class="filter-form">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-gray-400" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-blue-gray-300 rounded-md py-2"
                                       placeholder="Search by name or email">
                            </div>
                        </div>

                        <!-- Role Filter -->
                        <div class="w-full md:w-48">
                            <label for="role" class="sr-only">Role</label>
                            <select id="role" name="role"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-blue-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="w-full md:w-48">
                            <label for="sort" class="sr-only">Sort By</label>
                            <select id="sort" name="sort"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-blue-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name
                                    (A-Z)
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name
                                    (Z-A)
                                </option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest
                                    First
                                </option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest
                                    First
                                </option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('users.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-teal-300 text-sm font-medium rounded-md text-teal-700 bg-teal-50 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 reset-filters">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-blue-gray-200">
                <div class="overflow-x-auto">
                    <table id="users-table" class="min-w-full divide-y divide-blue-gray-200">
                        <thead class="bg-green-100">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider sortable-column"
                                data-sort-by="name" data-sort-direction="asc">
                                <div class="flex items-center group cursor-pointer">
                                    Name
                                    <svg class="ml-1 h-4 w-4 text-blue-gray-400 group-hover:text-blue-gray-600 sort-icon"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider sortable-column"
                                data-sort-by="created_at" data-sort-direction="desc">
                                <div class="flex items-center group cursor-pointer">
                                    Created
                                    <svg class="ml-1 h-4 w-4 text-blue-gray-400 group-hover:text-blue-gray-600 sort-icon"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium font-bold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody
                            class="bg-gradient-to-r from-blue-50 via-amber-50 to-green-50 divide-y divide-blue-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-blue-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span
                                                class="text-indigo-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-sm font-medium text-gray-900">{{ $user->email }}</span>
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold
                                             {{ $user->verified_at ? 'text-green-600' : 'text-red-500' }}">
                                                @if ($user->verified_at)
                                                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Verified {{ $user->verified_at->diffForHumans() }}
                                                @else
                                                    <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4h2v2h-2v-2zm0-8h2v6h-2V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Not Verified
                                                @endif
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('users.show', $user) }}"
                                           class="text-green-600 hover:text-green-800 flex items-center transition duration-150 custom-tooltip-trigger" data-tooltip="View User">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-indigo-600 hover:text-amber-600 flex items-center transition duration-150 custom-tooltip-trigger" data-tooltip="Edit User">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                              class="inline-block delete-user-form" onsubmit="confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-amber-600 flex items-center transition duration-150 custom-tooltip-trigger"
                                                    data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-tooltip="Delete User">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-blue-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                                    <p class="mt-1 text-sm text-gray-600">Try adjusting your search or filter to find
                                        what you're looking for.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('users.index') }}"
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 reset-filters">
                                            Clear Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="mt-8 bg-white rounded-lg shadow-xs border border-gray-100 overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-3 sm:px-6">
                        <!-- Pagination Info -->
                        <div class="mb-4 sm:mb-0">
                            <p class="text-sm text-gray-600 font-medium">
                                Showing <span class="text-[var(--primary)]">{{ $users->firstItem() }}</span>
                                to <span class="text-[var(--primary)]">{{ $users->lastItem() }}</span>
                                of <span class="text-[var(--primary)]">{{ $users->total() }}</span> results
                            </p>
                        </div>

                        <!-- Pagination Links -->
                        <nav class="flex items-center space-x-1">
                            {{ $users->appends(request()->query())->onEachSide(1)->links('components.pagination.custom-pagination') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function confirmDelete(event) {
            event.preventDefault(); // Prevent the form from submitting immediately
            const button = event.target.querySelector('button[type="submit"]');
            const userName = button.getAttribute('data-name') || '(unknown)';
            const userEmail = button.getAttribute('data-email') || '(unknown)';
            Swal.fire({
                title: 'Are you sure?',
                html: `
                    <div class="text-left">
                        <p class="mb-4 text-gray-700">You are about to delete the following user:</p>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex items-center mb-2">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">${userName}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium">${userEmail}</span>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-red-500">This action cannot be undone.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete User',
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
                    event.target.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Filter Form
            const filterForm = document.querySelector('.filter-form');
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchValue = document.getElementById('search').value || '(empty)';
                const roleSelect = document.getElementById('role');
                const roleValue = roleSelect.options[roleSelect.selectedIndex]?.text || '(empty)';
                const sortSelect = document.getElementById('sort');
                const sortValue = sortSelect.options[sortSelect.selectedIndex]?.text || '(empty)';
                Swal.fire({
                    title: 'Confirm Filter Application',
                    html: `
                        <div class="text-left">
                            <p class="mb-4 text-gray-700">You are about to apply the following filters:</p>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                <span id="swal-search" class="font-medium">${searchValue}</span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span id="swal-role" class="font-medium">${roleValue}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span id="swal-sort" class="font-medium">${sortValue}</span>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Please verify the filters before proceeding.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Apply Filters',
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
                        filterForm.submit();
                    }
                });
            });

            // Reset Filters Link
            const resetLinks = document.querySelectorAll('.reset-filters');
            resetLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Confirm Filter Reset',
                        html: `
                            <div class="text-left">
                                <p class="mb-4 text-gray-700">You are about to reset all filters and display all users.</p>
                                <p class="mt-4 text-sm text-gray-500">This will clear search, role, and sort settings.</p>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Reset Filters',
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
                            window.location.href = link.getAttribute('href');
                        }
                    });
                });
            });

            // Client-side sorting logic
            const usersTable = document.getElementById('users-table');
            const tableBody = usersTable.querySelector('tbody');
            const sortableColumns = usersTable.querySelectorAll('.sortable-column');

            sortableColumns.forEach(header => {
                header.addEventListener('click', () => {
                    const sortBy = header.dataset.sortBy;
                    let sortDirection = header.dataset.sortDirection;

                    // Toggle sort direction
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                    header.dataset.sortDirection = sortDirection;

                    // Reset other sort icons
                    sortableColumns.forEach(col => {
                        if (col !== header) {
                            const otherIcon = col.querySelector('.sort-icon');
                            if (otherIcon) {
                                otherIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>'; // Default icon
                            }
                        }
                    });

                    // Update current sort icon
                    const currentIcon = header.querySelector('.sort-icon');
                    if (currentIcon) {
                        if (sortDirection === 'asc') {
                            currentIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>'; // Up arrow
                        } else {
                            currentIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>'; // Down arrow
                        }
                    }

                    const rows = Array.from(tableBody.querySelectorAll('tr'));

                    rows.sort((a, b) => {
                        let aValue, bValue;

                        if (sortBy === 'name') {
                            aValue = a.querySelector('td:nth-child(1) .text-sm.font-medium').textContent.trim().toLowerCase();
                            bValue = b.querySelector('td:nth-child(1) .text-sm.font-medium').textContent.trim().toLowerCase();
                        } else if (sortBy === 'created_at') {
                            // For date sorting, parse the date string
                            aValue = new Date(a.querySelector('td:nth-child(3)').textContent.trim());
                            bValue = new Date(b.querySelector('td:nth-child(3)').textContent.trim());
                        }

                        if (sortDirection === 'asc') {
                            return aValue > bValue ? 1 : -1;
                        } else {
                            return aValue < bValue ? 1 : -1;
                        }
                    });

                    // Re-append sorted rows
                    rows.forEach(row => tableBody.appendChild(row));
                });
            });
        });
    </script>
@endpush
