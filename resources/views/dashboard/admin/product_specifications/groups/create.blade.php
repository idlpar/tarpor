@extends('layouts.admin')

@section('title', 'Create Product Specification Group')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.breadcrumbs', [
            'links' => [
                'Product Specification Groups' => route('admin.product_specifications.groups.index'),
                'Add New' => null
            ]
        ])

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create Product Specification Group</h1>
                <p class="text-sm text-gray-500 mt-1">Define a new group for organizing product specifications.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <!-- View All Groups Button -->
                <a href="{{ route('admin.product_specifications.groups.index') }}" class="flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View All Groups
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-6 py-5 text-base text-green-700" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.product_specifications.groups.store') }}" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column -->
                <div class="w-full lg:w-9/12">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Group Name:</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            @error('description')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-full lg:w-3/12 sticky top-6">
                    <!-- Publish Card -->
                    <div class="mb-6 bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Publish</h3>
                        <div class="pt-4 border-t text-sm border-gray-200 flex gap-4">
                            <button type="submit" id="saveButton" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover-effect">Save</button>
                            <button type="submit" name="save_exit" value="1" id="saveExitButton" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover-effect">Save & Exit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
