@extends('layouts.admin')

@section('title', 'Products Management')

@section('admin_content')
    @include('components.breadcrumbs', [
        'links' => [
            'Products' => null
        ]
    ])
    <div class="container mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
                <p class="text-sm text-gray-500 mt-1">Total {{ $products->total() }} products found</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <!-- Quick Actions -->
                <div class="relative">
                    <button id="quickActionsButton" class="flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                        Quick Actions
                    </button>
                    <div id="quickActionsMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                        <div class="p-1">
                            <a href="{{ route('products.import') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import Products
                            </a>
                            <a href="#" id="exportProductsButton" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export Products
                            </a>
                            <a href="{{ route('products.bulk-edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Bulk Edit
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filter Toggle -->
                <button id="filterToggleButton" class="flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </button>

                <!-- Sort Dropdown -->
                <div class="relative">
                    <select id="sortSelect" class="appearance-none bg-white border border-gray-300 text-gray-700 px-4 py-2 pr-8 rounded-lg hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_at:desc">Newest First</option>
                        <option value="created_at:asc">Oldest First</option>
                        <option value="name:asc">Name (A-Z)</option>
                        <option value="name:desc">Name (Z-A)</option>
                        <option value="price:asc">Price (Low to High)</option>
                        <option value="price:desc">Price (High to Low)</option>
                        <option value="stock:asc">Stock (Low to High)</option>
                        <option value="stock:desc">Stock (High to Low)</option>
                        <option value="sales:desc">Best Selling</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>

                <!-- Manage Product Attributes Button -->
                <a href="{{ route('product_attributes.index') }}" class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.827 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.827 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.827-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.827-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Manage Product Attributes
                </a>
                <!-- Add Product Button -->
                <a href="{{ route('products.create') }}" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Product
                </a>
            </div>
        </div>

        <!-- Filters Panel -->
        <div id="filterSection" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="searchInput" placeholder="Name, SKU, description..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="categoryFilter" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @foreach($category->children as $child)
                                <option value="{{ $child->id }}">— {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <!-- Brand Filter -->
                <div>
                    <label for="brandFilter" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <select id="brandFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                    <select id="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="simple">Simple</option>
                        <option value="variable">Variable</option>
                        <option value="digital">Digital</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Statuses</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <!-- Stock Status Filter -->
                <div>
                    <label for="stockStatusFilter" class="block text-sm font-medium text-gray-700 mb-1">Stock Status</label>
                    <select id="stockStatusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Statuses</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="backorder">On Backorder</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="priceMin" placeholder="Min" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <span class="text-gray-500">to</span>
                        <input type="number" id="priceMax" placeholder="Max" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Added</label>
                    <div class="flex items-center gap-2">
                        <input type="date" id="dateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <span class="text-gray-500">to</span>
                        <input type="date" id="dateTo" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button id="applyFiltersButton" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Apply Filters
                    </button>
                    <button id="clearFiltersButton" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors">
                        Clear
                    </button>
                </div>
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

        <!-- Products Table -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <!-- Table Header with Bulk Actions -->
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="selectAllCheckbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="selectAllCheckbox" class="ml-2 text-sm text-gray-700">Select all</label>

                    <div id="bulkActions" class="hidden ml-4">
                        <select id="bulkActionSelect" class="mr-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Bulk Actions</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Set to Draft</option>
                            <option value="archive">Archive</option>
                            <option value="delete">Delete</option>
                            <option value="update-categories">Update Categories</option>
                            <option value="update-tags">Update Tags</option>
                        </select>
                        <button id="applyBulkAction" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                            Apply
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-500">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                            <!-- Checkbox column -->
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                            Image
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                            Product
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            SKU
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Categories
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <!-- Checkbox -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </td>

                            <!-- Image -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $product->thumbnail_url ?? asset('images/default-product.png') }}" alt="{{ $product->name }}">
                                </div>
                            </td>

                            <!-- Product Name and Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-blue-600 hover:underline">
                                                {{ $product->name }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($product->brand)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-800">
                                                {{ $product->brand->name }}
                                            </span>
                                            @endif
                                            @if($product->type === 'variable')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-100 text-purple-800 ml-1">
                                                Variable ({{ $product->variants_count }} variants)
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- SKU -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    {{ $product->sku ?? 'N/A' }}
                                    @if($product->sku)
                                        <button data-copy-sku="{{ $product->sku }}" class="ml-1 text-gray-400 hover:text-gray-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>

                            <!-- Categories -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @forelse($product->categories->take(2) as $category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $category->name }}
                                    </span>
                                    @empty
                                        <span class="text-xs text-gray-500">Uncategorized</span>
                                    @endforelse
                                    @if($product->categories->count() > 2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        +{{ $product->categories->count() - 2 }} more
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Price -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    @if($product->sale_price)
                                        <span class="font-medium">{{ format_taka($product->sale_price) }}</span>
                                        <span class="text-xs text-gray-500 line-through">{{ format_taka($product->price) }}</span>
                                    @else
                                        <span class="font-medium">{{ format_taka($product->price) }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Stock -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($product->type === 'variable')
                                    <div class="flex flex-col items-center">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ $product->total_stock }} total
                                    </span>
                                        <span class="text-xs text-gray-500 mt-1">{{ $product->variants_count }} variants</span>
                                    </div>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($product->stock_quantity > 10) bg-green-100 text-green-800
                                    @elseif($product->stock_quantity > 0) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $product->stock_quantity }}
                                </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($product->status === 'published') bg-green-100 text-green-800
                                @elseif($product->status === 'draft') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($product->status) }}
                            </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('products.show.frontend', $product->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-600 custom-tooltip-trigger" data-tooltip="View on Frontend">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900 custom-tooltip-trigger" data-tooltip="Edit Product">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($product->type === 'variable')
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('products.variants.edit', $product->id) }}" class="text-purple-600 hover:text-purple-900 custom-tooltip-trigger" data-tooltip="Manage Variants">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form inline-block" onsubmit="confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Product">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No products found</h3>
                                    <p class="text-sm">Try adjusting your search or filter to find what you're looking for.</p>
                                    <a href="{{ route('products.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Add New Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between sm:px-6">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $products->firstItem() }}</span> to <span class="font-medium">{{ $products->lastItem() }}</span> of <span class="font-medium">{{ $products->total() }}</span> results
                            </p>
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')

<script>
    function confirmDelete(event) {
        event.preventDefault(); // Prevent the form from submitting immediately
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            focusCancel: true // Focus on the cancel button by default
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit(); // Submit the form if confirmed
            }
        });
    }
</script>
@endpush
