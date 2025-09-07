@extends('layouts.admin')

@section('title', 'Products Management')

@push('styles')
    <style>
        .highlight-row {
            animation: highlight 5s ease-out forwards;
        }

        @keyframes highlight {
            0% { background-color: #e6ffed; } /* Light green */
            100% { background-color: transparent; } /* Fade to transparent */
        }
    </style>
@endpush

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

        <!-- Products Table Placeholder -->
        <div id="products-table-container" class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div id="products-table-body" class="overflow-x-auto">
                <!-- Product table content will be loaded here via AJAX -->
            </div>
            <div id="pagination-container" class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between sm:px-6">
                <!-- Pagination will be loaded here via AJAX -->
            </div>
        </div>

        <!-- Spinner for loading data -->
        <div id="loading-spinner" class="text-center py-8" style="display: none;">
            <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-24 w-24 mx-auto">
            <p class="mt-2 text-gray-600">Loading products...</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const productsTableBody = document.getElementById('products-table-body');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingSpinner = document.getElementById('loading-spinner');
        const filterToggleButton = document.getElementById('filterToggleButton');
        const filterSection = document.getElementById('filterSection');
        const applyFiltersButton = document.getElementById('applyFiltersButton');
        const clearFiltersButton = document.getElementById('clearFiltersButton');
        const sortSelect = document.getElementById('sortSelect');

        // Filter elements
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

        // Helper function to format currency
        function formatTaka(amount) {
            const numAmount = parseFloat(amount);
            if (isNaN(numAmount)) {
                return '৳ 0';
            }
            const formattedAmount = numAmount.toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
                useGrouping: true
            });
            return `৳ ${formattedAmount}`;
        }

        // Function to fetch and render product data
        async function fetchProductData(page = 1) {
            loadingSpinner.style.display = 'block';
            productsTableBody.innerHTML = ''; // Clear previous products
            paginationContainer.innerHTML = ''; // Clear previous pagination

            const params = new URLSearchParams();
            params.append('page', page);

            // Append filter values
            if (searchInput.value) params.append('search', searchInput.value);
            if (categoryFilter.value) params.append('category_id', categoryFilter.value);
            if (brandFilter.value) params.append('brand_id', brandFilter.value);
            if (typeFilter.value) params.append('type', typeFilter.value);
            if (statusFilter.value) params.append('status', statusFilter.value);
            if (stockStatusFilter.value) params.append('stock_status', stockStatusFilter.value);
            if (priceMin.value) params.append('price_min', priceMin.value);
            if (priceMax.value) params.append('price_max', priceMax.value);
            if (dateFrom.value) params.append('date_from', dateFrom.value);
            if (dateTo.value) params.append('date_to', dateTo.value);
            if (sortSelect.value) params.append('sort', sortSelect.value);

            try {
                const response = await fetch(`{{ route('products.index') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                renderProductsTable(data.products.data);
                renderPagination(data.products);

            } catch (error) {
                console.error('Error fetching product data:', error);
                productsTableBody.innerHTML = '<p class="text-red-500 px-6 py-4">Failed to load products. Please try again.</p>';
            } finally {
                loadingSpinner.style.display = 'none';
            }
        }

        // Function to render products table
        function renderProductsTable(products) {
            let tableHtml = `
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8"></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Image</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
            `;

            if (products.length === 0) {
                tableHtml += `
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
                `;
            } else {
                products.forEach(product => {
                    const thumbnailUrl = product.thumbnail_url || '{{ asset('images/default-product.png') }}';
                    const brandHtml = product.brand ? `<span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-800">${product.brand.name}</span>` : '';
                    const variableTypeHtml = product.type === 'variable' ? `<span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-100 text-purple-800 ml-1">Variable (${product.variants_count} variants)</span>` : '';
                    const skuHtml = product.sku ? `
                        <div class="flex items-center">
                            ${product.sku}
                            <button data-copy-sku="${product.sku}" class="ml-1 text-gray-400 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                    ` : 'N/A';

                    let categoriesHtml = '';
                    if (product.categories && product.categories.length > 0) {
                        product.categories.slice(0, 2).forEach(category => {
                            categoriesHtml += `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">${category.name}</span>`;
                        });
                        if (product.categories.length > 2) {
                            categoriesHtml += `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">+${product.categories.length - 2} more</span>`;
                        }
                    } else {
                        categoriesHtml = `<span class="text-xs text-gray-500">Uncategorized</span>`;
                    }

                    const priceHtml = product.sale_price ? `
                        <span class="font-medium text-gemini-pink">${formatTaka(product.sale_price)}</span>
                        <span class="text-xs text-gray-500 line-through">${formatTaka(product.price)}</span>
                    ` : `
                        <span class="font-medium">${formatTaka(product.price)}</span>
                    `;

                    let stockClass = '';
                    let stockMainText = '';
                    let stockSubText = '';

                    if (product.type === 'variable') {
                        if (product.variants_count === 0) {
                            stockClass = 'bg-gray-100 text-gray-800'; // Neutral color
                            stockMainText = 'No Variants';
                            stockSubText = 'Configure variants'; // Suggestion
                        } else if (product.total_stock > 0) {
                            stockClass = 'bg-blue-100 text-blue-800';
                            stockMainText = `${product.total_stock} in stock`;
                            stockSubText = `${product.variants_count} variants`;
                        } else {
                            stockClass = 'bg-red-100 text-red-800';
                            stockMainText = 'Out of Stock';
                            stockSubText = `${product.variants_count} variants`;
                        }
                    } else {
                        if (product.stock_quantity > 10) {
                            stockClass = 'bg-green-100 text-green-800';
                        } else if (product.stock_quantity > 0) {
                            stockClass = 'bg-yellow-100 text-yellow-800';
                        } else {
                            stockClass = 'bg-red-100 text-red-800';
                        }
                        stockMainText = product.stock_quantity;
                    }

                    const statusClass = product.status === 'published' ? 'bg-green-100 text-green-800' :
                                        product.status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800';

                    tableHtml += `
                        <tr class="hover:bg-gray-50" data-id="${product.id}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="product_ids[]" value="${product.id}" class="product-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-md object-cover" src="${thumbnailUrl}" alt="${product.name}">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="/products/${product.id}" class="hover:text-blue-600 hover:underline">
                                                ${product.name}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            ${brandHtml}
                                            ${variableTypeHtml}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${skuHtml}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    ${categoriesHtml}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    ${priceHtml}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full ${stockClass}">
                                        ${stockMainText}
                                    </span>
                                    ${stockSubText ? `<span class="text-xs text-gray-500 mt-1">${stockSubText}</span>` : ''}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                    ${product.status.charAt(0).toUpperCase() + product.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="/products/${product.slug}" target="_blank" class="text-gray-400 hover:text-gray-600 custom-tooltip-trigger" data-tooltip="View on Frontend">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="/products/${product.id}/edit" class="text-blue-600 hover:text-blue-900 custom-tooltip-trigger" data-tooltip="Edit Product">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    ${product.type === 'variable' ? `
                                        <span class="text-gray-300">|</span>
                                        <a href="/products/${product.id}/variants/edit" class="text-purple-600 hover:text-purple-900 custom-tooltip-trigger" data-tooltip="Manage Variants">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </a>
                                    ` : ''}
                                    <span class="text-gray-300">|</span>
                                    <form action="/products/${product.id}" method="POST" class="delete-form inline-block" onsubmit="confirmDelete(event)">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="text-red-600 hover:text-red-900 custom-tooltip-trigger" data-tooltip="Delete Product">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }

            tableHtml += `
                    </tbody>
                </table>
            `;
            productsTableBody.innerHTML = tableHtml;

            // Add event listeners for copy SKU buttons
            productsTableBody.querySelectorAll('[data-copy-sku]').forEach(button => {
                button.addEventListener('click', function() {
                    const sku = this.dataset.copySku;
                    navigator.clipboard.writeText(sku).then(() => {
                        // Optional: Show a tooltip or temporary message
                        console.log('SKU copied:', sku);
                    }).catch(err => {
                        console.error('Failed to copy SKU:', err);
                    });
                });
            });
        }

        // Function to render pagination
        function renderPagination(paginationData) {
            if (paginationData.last_page > 1) {
                let paginationHtml = `
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between sm:px-6">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">${paginationData.from}</span> to <span class="font-medium">${paginationData.to}</span> of <span class="font-medium">${paginationData.total}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                `;

                paginationData.links.forEach(link => {
                    if (link.url) {
                        const pageNum = new URL(link.url).searchParams.get('page') || 1;
                        paginationHtml += `
                            <a href="#" data-page="${pageNum}" class="${link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md">
                                ${link.label.replace(/&laquo; Previous/, 'Previous').replace(/Next &raquo;/, 'Next')}
                            </a>
                        `;
                    } else {
                        paginationHtml += `
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 cursor-default rounded-md disabled-pagination-link">
                                ${link.label.replace(/&laquo; Previous/, 'Previous').replace(/Next &raquo;/, 'Next')}
                            </span>
                        `;
                    }
                });

                paginationHtml += `
                                </nav>
                            </div>
                        </div>
                    </div>
                `;
                paginationContainer.innerHTML = paginationHtml;

                // Add event listeners for pagination links
                paginationContainer.querySelectorAll('a[data-page]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        fetchProductData(this.dataset.page);
                    });
                });
            } else {
                paginationContainer.innerHTML = ''; // Hide pagination if only one page
            }
        }

        // Initial fetch of data when the page loads
        fetchProductData();

        // Filter toggle functionality
        filterToggleButton.addEventListener('click', function() {
            filterSection.classList.toggle('hidden');
        });

        // Apply Filters button
        applyFiltersButton.addEventListener('click', function() {
            fetchProductData();
        });

        // Clear Filters button
        clearFiltersButton.addEventListener('click', function() {
            searchInput.value = '';
            categoryFilter.value = '';
            brandFilter.value = '';
            typeFilter.value = '';
            statusFilter.value = '';
            stockStatusFilter.value = '';
            priceMin.value = '';
            priceMax.value = '';
            dateFrom.value = '';
            dateTo.value = '';
            sortSelect.value = 'created_at:desc'; // Reset sort to default
            fetchProductData();
        });

        // Sort select change
        sortSelect.addEventListener('change', function() {
            fetchProductData();
        });

        // Optional: Add debounce for search input to avoid too many requests
        let searchTimeout;
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchProductData();
            }, 500); // Fetch after 500ms of no typing
        });

        // Other filter changes (category, brand, type, status, stock status, price, date)
        // These will trigger fetchProductData on change
        categoryFilter.addEventListener('change', fetchProductData);
        brandFilter.addEventListener('change', fetchProductData);
        typeFilter.addEventListener('change', fetchProductData);
        statusFilter.addEventListener('change', fetchProductData);
        stockStatusFilter.addEventListener('change', fetchProductData);

        // Price and Date inputs - fetch on change if both min/max or from/to are filled
        [priceMin, priceMax, dateFrom, dateTo].forEach(input => {
            input.addEventListener('change', function() {
                // Only fetch if both min/max or from/to are filled, or if one is cleared
                if ((priceMin.value && priceMax.value) || (!priceMin.value && !priceMax.value)) {
                    fetchProductData();
                }
                if ((dateFrom.value && dateTo.value) || (!dateFrom.value && !dateTo.value)) {
                    fetchProductData();
                }
            });
        });

        // Quick Actions Menu Toggle
        const quickActionsButton = document.getElementById('quickActionsButton');
        const quickActionsMenu = document.getElementById('quickActionsMenu');

        if (quickActionsButton && quickActionsMenu) {
            quickActionsButton.addEventListener('click', function() {
                quickActionsMenu.classList.toggle('hidden');
            });

            // Close the dropdown if the user clicks outside of it
            document.addEventListener('click', function(event) {
                if (!quickActionsButton.contains(event.target) && !quickActionsMenu.contains(event.target)) {
                    quickActionsMenu.classList.add('hidden');
                }
            });
        }

        // Export Products Button (if needed to be AJAX)
        const exportProductsButton = document.getElementById('exportProductsButton');
        if (exportProductsButton) {
            exportProductsButton.addEventListener('click', function(e) {
                e.preventDefault();
                // You might want to collect current filters and pass them to the export route
                const params = new URLSearchParams();
                if (searchInput.value) params.append('search', searchInput.value);
                // ... add other filters ...
                window.location.href = `{{ route('products.export') }}?${params.toString()}`;
            });
        }

        // Select All Checkbox and Bulk Actions
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const bulkActionsDiv = document.getElementById('bulkActions');
        const applyBulkActionButton = document.getElementById('applyBulkAction');
        const bulkActionSelect = document.getElementById('bulkActionSelect');

        function updateBulkActionsVisibility() {
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkActionsDiv.classList.remove('hidden');
            } else {
                bulkActionsDiv.classList.add('hidden');
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateBulkActionsVisibility();
            });
        }

        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionsVisibility);
        });

        if (applyBulkActionButton) {
            applyBulkActionButton.addEventListener('click', async function() {
                const action = bulkActionSelect.value;
                const selectedProductIds = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);

                if (!action || selectedProductIds.length === 0) {
                    Swal.fire('Warning', 'Please select an action and at least one product.', 'warning');
                    return;
                }

                let additionalData = {};
                if (action === 'update-categories') {
                    const { value: categoryIds } = await Swal.fire({
                        title: 'Select Categories',
                        input: 'select',
                        inputOptions: {
                            @foreach($categories as $category)
                                '{{ $category->id }}': '{{ $category->name }}',
                                @foreach($category->children as $child)
                                    '{{ $child->id }}': '— {{ $child->name }}',
                                @endforeach
                            @endforeach
                        },
                        inputPlaceholder: 'Select categories',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to select at least one category!';
                            }
                        }
                    });
                    if (categoryIds) {
                        additionalData.category_ids = [categoryIds]; // Swal returns single value for select
                    } else {
                        return; // User cancelled
                    }
                } else if (action === 'update-tags') {
                    const { value: tagsInput } = await Swal.fire({
                        title: 'Enter Tags (comma-separated)',
                        input: 'text',
                        inputPlaceholder: 'e.g., new, popular, sale',
                        showCancelButton: true,
                    });
                    if (tagsInput !== undefined) {
                        additionalData.tags = tagsInput.split(',').map(tag => tag.trim()).filter(tag => tag.length > 0);
                    } else {
                        return; // User cancelled
                    }
                }

                Swal.fire({
                    title: 'Applying Bulk Action...',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch(`{{ route('products.bulk-action') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            action: action,
                            ids: selectedProductIds,
                            ...additionalData
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Swal.fire('Success', result.message, 'success');
                        fetchProductData(); // Re-fetch data after bulk action
                    } else {
                        Swal.fire('Error', result.message || 'An error occurred.', 'error');
                    }
                } catch (error) {
                    console.error('Bulk action error:', error);
                    Swal.fire('Error', 'An error occurred during the bulk action.', 'error');
                }
            });
        }

        // Highlight product row if redirected from edit
        const highlightProductId = {{ session('highlight_product_id') ?? 'null' }};
        if (highlightProductId) {
            const checkTableInterval = setInterval(() => {
                const productRow = productsTableBody.querySelector(`tr[data-id="${highlightProductId}"]`);
                if (productRow) {
                    clearInterval(checkTableInterval);
                    productRow.classList.add('highlight-row');
                    setTimeout(() => {
                        productRow.classList.remove('highlight-row');
                    }, 5000);
                }
            }, 100);
        }
    });
</script>
@endpush
