@extends('layouts.admin')

@section('title', 'Products Management')

@section('admin_content')
    <div class="container mx-auto px-4 py-6">
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
                                    <a href="{{ route('products.show.frontend', $product->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-600" title="View on frontend">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($product->type === 'variable')
                                        <a href="{{ route('products.variants.edit', $product->id) }}" class="text-purple-600 hover:text-purple-900" title="Manage Variants">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
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
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM Content Loaded');

            // Delete confirmation
            const deleteForms = document.querySelectorAll('.delete-form');
            console.log('Delete Forms found:', deleteForms.length);
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    console.log('Delete form submitted, showing Swal confirmation.');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33', // Red for delete
                        cancelButtonColor: '#3085d6', // Blue for cancel
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true, // Puts cancel on the left, confirm on the right
                        focusConfirm: false // Ensures cancel is not the default focus
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('Delete confirmed, submitting form.');
                            form.submit();
                        } else {
                            console.log('Delete cancelled.');
                        }
                    });
                });
            });

            // Quick Actions Menu
            const quickActionsButton = document.getElementById('quickActionsButton');
            const quickActionsMenu = document.getElementById('quickActionsMenu');
            const exportProductsButton = document.getElementById('exportProductsButton');

            console.log('Quick Actions Button:', quickActionsButton);
            console.log('Quick Actions Menu:', quickActionsMenu);
            console.log('Export Products Button:', exportProductsButton);

            if (quickActionsButton) {
                quickActionsButton.addEventListener('click', () => {
                    quickActionsMenu.classList.toggle('hidden');
                    console.log('Quick Actions Button clicked. Menu hidden:', quickActionsMenu.classList.contains('hidden'));
                });
            }

            // Close quick actions menu when clicking outside
            document.addEventListener('click', (e) => {
                if (quickActionsButton && quickActionsMenu && !quickActionsButton.contains(e.target) && !quickActionsMenu.contains(e.target)) {
                    quickActionsMenu.classList.add('hidden');
                    console.log('Clicked outside quick actions menu. Menu hidden:', quickActionsMenu.classList.contains('hidden'));
                }
            });

            // Export Products functionality
            if (exportProductsButton) {
                exportProductsButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    const selectedIds = Array.from(productCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    // If no products are selected, export all products
                    const idsToExport = selectedIds.length > 0 ? selectedIds : null;

                    Swal.fire({
                        title: 'Exporting Products',
                        text: 'Please wait while your products are being exported...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route('products.export') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ product_ids: idsToExport })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = 'products.xlsx'; // Or get filename from response headers if available
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        Swal.close();
                        Swal.fire('Export Successful', 'Your products have been exported.', 'success');
                    })
                    .catch(error => {
                        console.error('Export error:', error);
                        Swal.fire('Export Failed', 'There was an error exporting your products.', 'error');
                    });
                });
            }

            // Filter Toggle
            const filterToggleButton = document.getElementById('filterToggleButton');
            const filterSection = document.getElementById('filterSection');
            console.log('Filter Toggle Button:', filterToggleButton);
            console.log('Filter Section:', filterSection);

            if (filterToggleButton) {
                filterToggleButton.addEventListener('click', () => {
                    filterSection.classList.toggle('hidden');
                    console.log('Filter Toggle Button clicked. Filter section hidden:', filterSection.classList.contains('hidden'));
                });
            }

            // Bulk Actions
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyBulkAction = document.getElementById('applyBulkAction');
            console.log('Select All Checkbox:', selectAllCheckbox);
            console.log('Product Checkboxes (count):', productCheckboxes.length);
            console.log('Bulk Actions Div:', bulkActions);
            console.log('Bulk Action Select:', bulkActionSelect);
            console.log('Apply Bulk Action Button:', applyBulkAction);


            // Select all checkbox functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', () => {
                    const isChecked = selectAllCheckbox.checked;
                    console.log('Select All Checkbox changed. Is checked:', isChecked);
                    productCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                        const row = checkbox.closest('tr');
                        if (isChecked) {
                            row.classList.add('bg-blue-50'); // Highlight selected row
                        } else {
                            row.classList.remove('bg-blue-50');
                        }
                    });
                    toggleBulkActions();
                });
            }

            // Individual checkbox functionality
            productCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    console.log('Individual checkbox changed. ID:', this.value, 'Is checked:', this.checked);
                    const row = this.closest('tr');
                    if (this.checked) {
                        row.classList.add('bg-blue-50'); // Highlight selected row
                    } else {
                        row.classList.remove('bg-blue-50');
                    }
                    const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
                    if (selectAllCheckbox) { // Check if selectAllCheckbox exists before accessing it
                        selectAllCheckbox.checked = allChecked;
                    }
                    toggleBulkActions();
                });
            });

            function toggleBulkActions() {
                const anyChecked = Array.from(productCheckboxes).some(cb => cb.checked);
                console.log('toggleBulkActions called. Any checked:', anyChecked);
                if (bulkActions) {
                    bulkActions.classList.toggle('hidden', !anyChecked);
                }
            }

            // Apply bulk action
            if (applyBulkAction) {
                applyBulkAction.addEventListener('click', () => {
                    const action = bulkActionSelect.value;
                    console.log('Apply Bulk Action button clicked. Action:', action);
                    if (!action) return;

                    const selectedIds = Array.from(productCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    if (selectedIds.length === 0) {
                        console.log('No products selected for bulk action.');
                        return;
                    }

                    let categoryIdsForBulk = [];
                    let tagsForBulk = [];

                    // Placeholder for getting categories/tags from a modal/input
                    if (action === 'update-categories') {
                        @php
                            $categoryOptions = [];
                            foreach ($categories as $category) {
                                $categoryOptions[] = ['id' => $category->id, 'name' => $category->name];
                                foreach ($category->children as $child) {
                                    $categoryOptions[] = ['id' => $child->id, 'name' => '&nbsp;&nbsp;&nbsp;— ' . $child->name];
                                }
                            }
                            $categoryOptionsJson = json_encode($categoryOptions);
                        @endphp
                        Swal.fire({
                            title: 'Update Categories',
                            html: '<select id="swal-categories" class="swal2-input" multiple></select>',
                            didOpen: () => {
                                const select = document.getElementById('swal-categories');
                                const options = {!! $categoryOptionsJson !!};
                                options.forEach(optionData => {
                                    const option = document.createElement('option');
                                    option.value = optionData.id;
                                    option.innerHTML = optionData.name;
                                    select.appendChild(option);
                                });
                            },
                            focusConfirm: false,
                            preConfirm: () => {
                                const selectedOptions = Array.from(document.getElementById('swal-categories').selectedOptions);
                                return selectedOptions.map(option => option.value);
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                categoryIdsForBulk = result.value;
                                if (categoryIdsForBulk.length === 0) {
                                    Swal.fire('Error', 'Please select at least one category.', 'error');
                                    return;
                                }
                                performBulkAction(action, selectedIds, categoryIdsForBulk, tagsForBulk);
                            }
                        });
                        return; // Exit to wait for Swal confirmation
                    } else if (action === 'update-tags') {
                        Swal.fire({
                            title: 'Update Tags',
                            input: 'text',
                            inputLabel: 'Enter tags separated by commas',
                            inputPlaceholder: 'tag1, tag2, tag3',
                            showCancelButton: true,
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'Please enter tags!';
                                }
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                tagsForBulk = result.value.split(',').map(tag => tag.trim());
                                performBulkAction(action, selectedIds, categoryIdsForBulk, tagsForBulk);
                            }
                        });
                        return; // Exit to wait for Swal confirmation
                    }

                    performBulkAction(action, selectedIds, categoryIdsForBulk, tagsForBulk);
                });
            }

            function performBulkAction(action, selectedIds, categoryIdsForBulk, tagsForBulk) {
                console.log('performBulkAction called with:', { action, selectedIds, categoryIdsForBulk, tagsForBulk });
                Swal.fire({
                    title: 'Confirm Bulk Action',
                    text: `You are about to ${action} ${selectedIds.length} product(s). Continue?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Red for confirm
                    cancelButtonColor: '#3085d6', // Blue for cancel
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true, // Puts cancel on the left, confirm on the right
                    focusConfirm: false // Ensures cancel is not the default focus
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form or make AJAX request
                        console.log(`Applying ${action} to:`, selectedIds);

                        fetch('{{ route('products.bulk-action') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                action: action,
                                ids: selectedIds,
                                // Add category_ids or tags if action is update-categories or update-tags
                                ...((action === 'update-categories' && categoryIdsForBulk) && { category_ids: categoryIdsForBulk }),
                                ...((action === 'update-tags' && tagsForBulk) && { tags: tagsForBulk })
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Success!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message || 'An error occurred.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                'An error occurred while processing your request.',
                                'error'
                            );
                        });
                    }
                });
            }

            // Copy SKU functionality
            document.querySelectorAll('[data-copy-sku]').forEach(button => {
                button.addEventListener('click', function() {
                    const sku = this.getAttribute('data-copy-sku');
                    navigator.clipboard.writeText(sku).then(() => {
                        const originalHTML = this.innerHTML;
                        this.innerHTML = `
                    <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                `;
                        setTimeout(() => {
                            this.innerHTML = originalHTML;
                        }, 2000);
                    });
                });
            });

            // Filter and Sort functionality
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const brandFilter = document.getElementById('brandFilter');
            const typeFilter = document.getElementById('typeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const stockStatusFilter = document.getElementById('stockStatusFilter');
            const priceMin = document.getElementById('priceMin');
            const priceMax = document.getElementById('priceMax');
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            const sortSelect = document.getElementById('sortSelect');
            const applyFiltersButton = document.getElementById('applyFiltersButton');
            const clearFiltersButton = document.getElementById('clearFiltersButton');

            console.log('Sort Select Element:', sortSelect);

            // Apply filters when sort select changes
            if (sortSelect) {
                sortSelect.addEventListener('change', applyFilters);
            }

            function applyFilters() {
                console.log('applyFilters function called.');
                const params = new URLSearchParams();

                // Add filters to params only if they have a value
                if (searchInput && searchInput.value) params.append('search', searchInput.value);
                if (categoryFilter && categoryFilter.value) params.append('category_id', categoryFilter.value);
                if (brandFilter && brandFilter.value) params.append('brand_id', brandFilter.value);
                if (typeFilter && typeFilter.value) params.append('type', typeFilter.value);
                if (statusFilter && statusFilter.value) params.append('status', statusFilter.value);
                if (stockStatusFilter && stockStatusFilter.value) params.append('stock_status', stockStatusFilter.value);
                if (priceMin && priceMin.value) params.append('price_min', priceMin.value);
                if (priceMax && priceMax.value) params.append('price_max', priceMax.value);
                if (dateFrom && dateFrom.value) params.append('date_from', dateFrom.value);
                if (dateTo && dateTo.value) params.append('date_to', dateTo.value);
                if (sortSelect && sortSelect.value) params.append('sort', sortSelect.value);

                console.log('Filter parameters:', params.toString());
                window.location.href = '{{ route('products.index') }}?' + params.toString();
            }

            // Apply filters when button is clicked
            if (applyFiltersButton) {
                applyFiltersButton.addEventListener('click', applyFilters);
            }

            // Apply filters when Enter is pressed in search
            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        console.log('Enter key pressed in search input.');
                        applyFilters();
                    }
                });
            }

            // Clear all filters
            if (clearFiltersButton) {
                clearFiltersButton.addEventListener('click', function() {
                    console.log('Clear Filters button clicked.');
                    if (searchInput) searchInput.value = '';
                    if (categoryFilter) categoryFilter.value = '';
                    if (brandFilter) brandFilter.value = '';
                    if (typeFilter) typeFilter.value = '';
                    if (statusFilter) statusFilter.value = '';
                    if (stockStatusFilter) stockStatusFilter.value = '';
                    if (priceMin) priceMin.value = '';
                    if (priceMax) priceMax.value = '';
                    if (dateFrom) dateFrom.value = '';
                    if (dateTo) dateTo.value = '';
                    if (sortSelect) sortSelect.value = 'created_at:desc'; // Reset sort to default
                    applyFilters();
                });
            }

            // Set initial filter values from URL
            const urlParams = new URLSearchParams(window.location.search);
            console.log('URL Params on load:', urlParams.toString());
            if (urlParams.has('search') && searchInput) searchInput.value = urlParams.get('search');
            if (urlParams.has('category_id') && categoryFilter) categoryFilter.value = urlParams.get('category_id');
            if (urlParams.has('brand_id') && brandFilter) brandFilter.value = urlParams.get('brand_id');
            if (urlParams.has('type') && typeFilter) typeFilter.value = urlParams.get('type');
            if (urlParams.has('status') && statusFilter) statusFilter.value = urlParams.get('status');
            if (urlParams.has('stock_status') && stockStatusFilter) stockStatusFilter.value = urlParams.get('stock_status');
            if (urlParams.has('price_min') && priceMin) priceMin.value = urlParams.get('price_min');
            if (urlParams.has('price_max') && priceMax) priceMax.value = urlParams.get('price_max');
            if (urlParams.has('date_from') && dateFrom) dateFrom.value = urlParams.get('date_from');
            if (urlParams.has('date_to') && dateTo) dateTo.value = urlParams.get('date_to');
            if (urlParams.has('sort') && sortSelect) sortSelect.value = urlParams.get('sort');

            // Show filter section if any filters are active
            if (urlParams.toString()) {
                if (filterSection) {
                    filterSection.classList.remove('hidden');
                    console.log('Filter section shown due to active filters.');
                }
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Custom styling for the pagination */
        .pagination {
            display: inline-flex;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .page-item {
            margin: 0;
        }

        .page-item:first-child .page-link {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .page-item:last-child .page-link {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #3b82f6;
            background-color: #fff;
            border: 1px solid #d1d5db;
        }

        .page-link:hover {
            z-index: 2;
            color: #2563eb;
            background-color: #f9fafb;
            border-color: #d1d5db;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .page-item.disabled .page-link {
            color: #9ca3af;
            pointer-events: none;
            background-color: #fff;
            border-color: #d1d5db;
        }
    </style>
@endpush
