
@extends('layouts.app')

@section('content')
    <section class="py-12 bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">User Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage all registered users and their permissions</p>
                </div>
                <div>
                    <a href="{{ route('users.create') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-[var(--primary)] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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

            <!-- Search and Filter Bar -->
            <div class="mb-6 bg-teal-50 p-4 rounded-lg shadow-sm border border-blue-gray-200">
                <form method="GET" action="{{ route('users.index') }}">
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-blue-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
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
                            <select id="role" name="role" class="block w-full pl-3 pr-10 py-2 text-base border-blue-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="w-full md:w-48">
                            <label for="sort" class="sr-only">Sort By</label>
                            <select id="sort" name="sort" class="block w-full pl-3 pr-10 py-2 text-base border-blue-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 border border-teal-300 text-sm font-medium rounded-md text-teal-700 bg-teal-50 hover:bg-teal-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-blue-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-gray-200">
                        <thead class="bg-teal-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => request('sort') == 'name_asc' ? 'name_desc' : 'name_asc'])) }}" class="flex items-center group">
                                    Name
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-blue-gray-400 group-hover:text-blue-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if(request('sort') == 'name_asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                                        @elseif(request('sort') == 'name_desc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        @endif
                                    </svg>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => request('sort') == 'newest' ? 'oldest' : 'newest'])) }}" class="flex items-center group">
                                    Created
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-blue-gray-400 group-hover:text-blue-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if(request('sort') == 'newest')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @elseif(request('sort') == 'oldest')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        @endif
                                    </svg>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-gradient-to-r from-blue-50 via-teal-100 to-green-50 divide-y divide-blue-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-blue-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-500">Verified {{ $user->email_verified_at?->diffForHumans() ?? 'Not verified' }}</div>
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
                                    <div class="flex items-center justify-end space-x-4">
                                        <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-amber-600 flex items-center transition duration-150">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-amber-600 flex items-center transition duration-150">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-blue-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                                    <p class="mt-1 text-sm text-gray-600">Try adjusting your search or filter to find what you're looking for.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[var(--primary)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                <div class="mt-8 px-4 py-3 flex items-center justify-between border-t border-blue-gray-200 sm:px-6 bg-white rounded-b-lg">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-600">
                                Showing
                                <span class="font-medium">{{ $users->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $users->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $users->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

