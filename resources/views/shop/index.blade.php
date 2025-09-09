@extends('layouts.app')

@section('title', 'Shop')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Enhanced Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Home
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Shop</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Enhanced Sidebar Filters -->
            <aside class="w-full md:w-72 shrink-0">
                <div class="bg-white p-6 rounded-xl shadow-sm sticky top-4 border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Filters</h2>
                        @if(request()->except('page'))
                            <a href="{{ route('shop.index') }}" class="text-sm text-blue-600 hover:underline">Clear all</a>
                        @endif
                    </div>

                    <!-- Search Filter -->
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text" id="product-search-filter" placeholder="Search products..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex justify-between items-center cursor-pointer filter-toggle" data-target="#categories-content">
                            <span>Categories</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </h3>
                        <div id="categories-content" class="filter-content {{ request('category') ? '' : 'hidden' }}">
                            <div class="relative mb-3">
                                <input type="text" placeholder="Search categories..."
                                       class="category-search-input w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="absolute left-2.5 top-2.5 h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <ul class="category-list space-y-2 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                                @foreach($categories as $category)
                                    <li class="category-item">
                                        <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                                           class="flex items-center text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded {{ request('category') == $category->slug ? 'text-blue-600 font-medium bg-blue-50' : '' }}">
                                            <span class="truncate">{{ $category->name }}</span>
                                            <span class="ml-auto text-xs text-gray-500">{{ $category->products_count ?? '' }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Brands Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex justify-between items-center cursor-pointer filter-toggle" data-target="#brands-content">
                            <span>Brands</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </h3>
                        <div id="brands-content" class="filter-content {{ request('brand') ? '' : 'hidden' }}">
                            <div class="relative mb-3">
                                <input type="text" placeholder="Search brands..."
                                       class="brand-search-input w-full pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="absolute left-2.5 top-2.5 h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <ul class="brand-list space-y-2 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                                @foreach($brands as $brand)
                                    <li class="brand-item">
                                        <a href="{{ route('shop.index', array_merge(request()->except('page'), ['brand' => $brand->slug])) }}"
                                           class="flex items-center text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded {{ request('brand') == $brand->slug ? 'text-blue-600 font-medium bg-blue-50' : '' }}">
                                            <span class="truncate">{{ $brand->name }}</span>
                                            <span class="ml-auto text-xs text-gray-500">{{ $brand->products_count ?? '' }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex justify-between items-center cursor-pointer filter-toggle" data-target="#price-content">
                            <span>Price Range</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </h3>
                        <div id="price-content" class="filter-content {{ (request('min_price') || request('max_price')) ? '' : 'hidden' }}">
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>BDT 0</span>
                                    <span>{{ format_taka($maxPrice, '৳', false) }}</span>
                                </div>
                                <div class="relative h-1 bg-gray-200 rounded-full">
                                    <div class="absolute h-1 bg-blue-500 rounded-full" id="price-range-progress"></div>
                                </div>
                                <div class="relative">
                                    <input type="range" min="0" max="{{ $maxPrice }}" step="1"
                                           value="{{ request('min_price', 0) }}"
                                           class="absolute w-full h-1 opacity-0 cursor-pointer z-10"
                                           id="price-min">
                                    <input type="range" min="0" max="{{ $maxPrice }}" step="1"
                                           value="{{ request('max_price', $maxPrice) }}"
                                           class="absolute w-full h-1 opacity-0 cursor-pointer z-10"
                                           id="price-max">
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1">
                                    <label for="min-price-input" class="block text-xs text-gray-500 mb-1">Min</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-sm text-gray-400">৳</span>
                                        <input type="number" id="min-price-input" min="0" max="{{ $maxPrice }}"
                                               value="{{ request('min_price', 0) }}"
                                               class="w-full pl-10 pr-3 py-1.5 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label for="max-price-input" class="block text-xs text-gray-500 mb-1">Max</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-sm text-gray-400">৳</span>
                                        <input type="number" id="max-price-input" min="0" max="{{ $maxPrice }}"
                                               value="{{ request('max_price', $maxPrice) }}"
                                               class="w-full pl-10 pr-3 py-1.5 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex justify-between items-center cursor-pointer filter-toggle" data-target="#sort-content">
                            <span>Sort By</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </h3>
                        <div id="sort-content" class="filter-content {{ request('sort') ? '' : 'hidden' }}">
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="sort" value="newest" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request('sort') == 'newest' || !request('sort') ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Newest Arrivals</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="sort" value="price_asc" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Price: Low to High</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="sort" value="price_desc" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request('sort') == 'price_desc' ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Price: High to Low</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="sort" value="popular" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request('sort') == 'popular' ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Most Popular</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <button id="apply-filters" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Apply Filters
                    </button>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">
                            @if(request('category'))
                                {{ $categories->firstWhere('slug', request('category'))->name ?? 'Category' }} Products
                            @elseif(request('brand'))
                                {{ $brands->firstWhere('slug', request('brand'))->name ?? 'Brand' }} Products
                            @else
                                All Products
                            @endif
                            <span class="text-sm font-normal text-gray-500 ml-2">({{ $products->total() }} items)</span>
                        </h1>

                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <select id="mobile-sort" class="md:hidden block w-full pl-3 pr-8 py-2 text-base border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg appearance-none">
                                    <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Sort: Newest</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Sort: Price Low to High</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Sort: Price High to Low</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'checked' : '' }}>Sort: Most Popular</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                            <button id="mobile-filters-button" class="md:hidden flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-lg text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filters
                            </button>
                        </div>
                    </div>

                    <!-- Active Filters -->
                    @if(request()->except('page'))
                        <div class="flex flex-wrap items-center gap-2 mb-6">
                            <span class="text-sm font-medium text-gray-700">Filters:</span>
                            @if(request('category'))
                                <span class="inline-flex items-center bg-blue-50 text-blue-700 text-sm px-3 py-1 rounded-full">
                                    {{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }}
                                    <a href="{{ route('shop.index', array_except(request()->query(), 'category')) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-100">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </a>
                                </span>
                            @endif
                            @if(request('brand'))
                                <span class="inline-flex items-center bg-green-50 text-green-700 text-sm px-3 py-1 rounded-full">
                                    {{ $brands->firstWhere('slug', request('brand'))->name ?? request('brand') }}
                                    <a href="{{ route('shop.index', array_except(request()->query(), 'brand')) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-green-100">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </a>
                                </span>
                            @endif
                            @if(request('min_price') || request('max_price'))
                                <span class="inline-flex items-center bg-purple-50 text-purple-700 text-sm px-3 py-1 rounded-full">
                                    Price: {{ format_taka(request('min_price', 0), '৳', false) }} - {{ format_taka(request('max_price', $maxPrice), '৳', false) }}
                                    <a href="{{ route('shop.index', array_except(request()->query(), ['min_price', 'max_price'])) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-purple-100">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </a>
                                </span>
                            @endif
                            @if(request('sort') && request('sort') !== 'newest')
                                <span class="inline-flex items-center bg-yellow-50 text-yellow-700 text-sm px-3 py-1 rounded-full">
                                    Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}
                                    <a href="{{ route('shop.index', array_except(request()->query(), 'sort')) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-yellow-100">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </a>
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Product Grid -->
                    @if($products->count() > 0)
                        <div class="flex justify-end mb-4">
                            <div class="inline-flex rounded-md shadow-sm" role="group">
                                <button type="button" id="grid-4-cols" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                    4 Columns
                                </button>
                                <button type="button" id="grid-6-cols" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                    6 Columns
                                </button>
                            </div>
                        </div>
                        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($products as $product)
                                <div class="group relative bg-white border border-gray-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                    <!-- Product Labels -->
                                    <div class="absolute top-2 left-2 right-2 z-10 flex sm:justify-between">
                                        <div class="flex flex-col gap-1">
                                            @if($product->is_featured)
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full">
                                                    Featured
                                                </span>
                                            @endif
                                            @if($product->is_new)
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-green-600 rounded-full">
                                                    New
                                                </span>
                                            @endif
                                            @if($product->is_hot)
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-orange-600 rounded-full">
                                                    Hot
                                                </span>
                                            @endif
                                            @if($product->is_sale)
                                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-purple-600 rounded-full">
                                                    Sale
                                                </span>
                                            @endif
                                        </div>
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                                {{ round(100 - ($product->sale_price / $product->price * 100)) }}% OFF
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Product Image -->
                                    <div class="aspect-square bg-gray-50 relative overflow-hidden">
                                        <a href="{{ route('products.show.frontend', $product->slug) }}" class="block w-full h-full">
                                            <img src="{{ $product->thumbnail_url ?? asset('images/placeholder-product.png') }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-90">
                                        </a>
                                        <div class="absolute inset-0 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black bg-opacity-20">
                                            <button class="quick-view-btn p-2 rounded-full bg-white text-gray-800 hover:bg-blue-100 transition-colors duration-200 shadow-sm"
                                                    data-product-id="{{ $product->id }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                            <button class="add-to-wishlist p-2 rounded-full bg-white text-gray-800 hover:bg-red-100 transition-colors duration-200 shadow-sm"
                                                    data-product-id="{{ $product->id }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="p-3">
                                        @if($product->brand)
                                            <p class="text-xs text-gray-500 mb-1 truncate">{{ $product->brand->name }}</p>
                                        @endif
                                        <h3 class="text-sm font-semibold text-gray-900 mb-1.5">
                                            <a href="{{ route('products.show.frontend', $product->slug) }}" class="hover:text-blue-600 transition-colors duration-200 line-clamp-2" style="-webkit-line-clamp: 2;">
                                                {{ $product->name }}
                                            </a>
                                        </h3>

                                        <!-- Rating -->
                                        <div class="flex items-center mb-2">
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count }})</span>
                                        </div>

                                        <!-- Price -->
                                        <div class="mb-3">
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-lg font-bold text-gemini-pink">{{ format_taka($product->sale_price, '৳', false) }}</span>
                                                    <span class="text-sm text-gray-500 line-through">{{ format_taka($product->price, '৳', false) }}</span>
                                                </div>
                                            @else
                                                <span class="text-lg font-bold text-gemini-pink">{{ format_taka($product->price, '৳', false) }}</span>
                                            @endif
                                        </div>

                                        <!-- Stock & Buttons -->
                                        <div class="hidden md:flex gap-2">
                                            <button class="add-to-cart-btn w-1/2 bg-gray-100 hover:bg-[var(--primary)] text-gray-800 hover:text-white py-2 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-type="{{ $product->type }}"
                                                {{ ($product->type === 'simple' && $product->stock_status === 'out_of_stock') ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                Add to Cart
                                            </button>
                                            <button class="buy-now-btn w-1/2 bg-[var(--primary)] hover:bg-[var(--primary-dark)] text-white py-2 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-type="{{ $product->type }}"
                                                {{ ($product->type === 'simple' && $product->stock_status === 'out_of_stock') ? 'disabled' : '' }}>
                                                Buy Now
                                            </button>
                                        </div>
                                        <div class="flex gap-2 md:hidden">
                                            <button class="add-to-cart-btn w-1/2 bg-gray-100 hover:bg-blue-600 text-gray-800 hover:text-white py-2 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-type="{{ $product->type }}"
                                                {{ ($product->type === 'simple' && $product->stock_status === 'out_of_stock') ? 'disabled' : '' }}>
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                            <button class="buy-now-btn w-1/2 bg-[var(--primary)] hover:bg-[var(--primary-dark)] text-white py-2 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-type="{{ $product->type }}"
                                                {{ ($product->type === 'simple' && $product->stock_status === 'out_of_stock') ? 'disabled' : '' }}>
                                                <i class="fas fa-bolt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $products->appends(request()->query())->onEachSide(1)->links('vendor.pagination.tailwind') }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 flex items-center justify-center bg-blue-50 rounded-full mb-4">
                                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                            <p class="text-gray-500 mb-6">Try adjusting your search or filter to find what you're looking for.</p>
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear All Filters
                            </a>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Filters Sidebar -->
    <div id="mobile-filters" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex min-h-screen">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="mobile-filters-overlay"></div>
            <div class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white shadow-xl">
                <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                    <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:text-gray-500" id="mobile-filters-close">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Filters Content -->
                <div class="p-4">
                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-900 mb-3 flex justify-between items-center cursor-pointer mobile-filter-toggle" data-target="#mobile-categories-content">
                            <span>Categories</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </h3>
                        <div id="mobile-categories-content" class="mobile-filter-content {{ request('category') ? '' : 'hidden' }}">
                            <ul class="space-y-2">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                                           class="flex items-center text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded {{ request('category') == $category->slug ? 'text-blue-600 font-medium bg-blue-50' : '' }}">
                                            <span class="truncate">{{ $category->name }}</span>
                                            <span class="ml-auto text-xs text-gray-500">{{ $category->products_count ?? '' }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-900 mb-3 flex justify-between items-center cursor-pointer mobile-filter-toggle" data-target="#mobile-brands-content">
                            <span>Brands</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </h3>
                        <div id="mobile-brands-content" class="mobile-filter-content {{ request('brand') ? '' : 'hidden' }}">
                            <ul class="space-y-2">
                                @foreach($brands as $brand)
                                    <li>
                                        <a href="{{ route('shop.index', array_merge(request()->except('page'), ['brand' => $brand->slug])) }}"
                                           class="flex items-center text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded {{ request('brand') == $brand->slug ? 'text-blue-600 font-medium bg-blue-50' : '' }}">
                                            <span class="truncate">{{ $brand->name }}</span>
                                            <span class="ml-auto text-xs text-gray-500">{{ $brand->products_count ?? '' }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-900 mb-3 flex justify-between items-center cursor-pointer mobile-filter-toggle" data-target="#mobile-price-content">
                            <span>Price Range</span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </h3>
                        <div id="mobile-price-content" class="mobile-filter-content {{ (request('min_price') || request('max_price')) ? '' : 'hidden' }}">
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>BDT 0</span>
                                    <span>{{ format_taka($maxPrice, '৳', false) }}</span>
                                </div>
                                <div class="relative h-1 bg-gray-200 rounded-full">
                                    <div class="absolute h-1 bg-blue-500 rounded-full" id="mobile-price-range-progress"></div>
                                </div>
                                <div class="relative">
                                    <input type="range" min="0" max="{{ $maxPrice }}" step="1"
                                           value="{{ request('min_price', 0) }}"
                                           class="absolute w-full h-1 opacity-0 cursor-pointer z-10"
                                           id="mobile-price-min">
                                    <input type="range" min="0" max="{{ $maxPrice }}" step="1"
                                           value="{{ request('max_price', $maxPrice) }}"
                                           class="absolute w-full h-1 opacity-0 cursor-pointer z-10"
                                           id="mobile-price-max">
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1">
                                    <label for="mobile-min-price-input" class="block text-xs text-gray-500 mb-1">Min</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-sm text-gray-400">৳</span>
                                        <input type="number" id="mobile-min-price-input" min="0" max="{{ $maxPrice }}"
                                               value="{{ request('min_price', 0) }}"
                                               class="w-full pl-10 pr-3 py-1.5 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label for="mobile-max-price-input" class="block text-xs text-gray-500 mb-1">Max</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-sm text-gray-400">৳</span>
                                        <input type="number" id="mobile-max-price-input" min="0" max="{{ $maxPrice }}"
                                               value="{{ request('max_price', $maxPrice) }}"
                                               class="w-full pl-10 pr-3 py-1.5 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="mobile-apply-filters" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div id="quick-view-modal" class="fixed z-50 inset-0 overflow-y-auto hidden items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="relative bg-white rounded-lg max-w-6xl w-full max-h-[90vh] overflow-y-auto shadow-xl transform transition-all">
            <button type="button" class="absolute top-4 right-4 bg-white rounded-full p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500" id="quick-view-close">
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div id="quick-view-content" class="p-6">
                <!-- Product details will be loaded here via AJAX -->
                <div class="flex justify-center items-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
            </div>
            <template id="quick-view-product-template">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Image Gallery (Left Column) -->
                    <div class="md:col-span-1">
                        <div class="bg-gray-50 rounded-lg overflow-hidden mb-4">
                            <img src="" alt="Product" class="w-full h-auto object-contain" id="qv-main-image">
                        </div>
                        <div class="grid grid-cols-4 gap-2" id="qv-gallery-thumbnails"></div>
                    </div>

                    <!-- Product Info (Middle & Right Columns) -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Middle Column -->
                        <div>
                            <div class="pb-4 border-b border-gray-200 mb-4">
                                <h2 class="text-3xl font-bold text-gray-900 mb-2" id="qv-product-name"></h2>
                                <p class="text-gray-500 text-sm mb-2">Brand: <span id="qv-product-brand" class="font-medium text-gray-700"></span></p>
                                <div class="flex items-center" id="qv-product-rating"></div>
                            </div>

                            <div id="qv-product-short-description" class="text-gray-600 mb-6"></div>

                            <div class="mt-6">
                                 <h3 class="text-md font-semibold text-gray-800 mb-2">Description</h3>
                                 <div id="qv-product-description" class="prose max-w-none text-gray-600 text-sm"></div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div id="qv-variant-selection" class="mb-4 hidden">
                                    <h3 class="text-md font-semibold text-gray-800 mb-3">Select Variant:</h3>
                                    <div id="qv-variant-options" class="flex flex-wrap gap-3"></div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-3xl font-bold text-gemini-pink" id="qv-product-price"></p>
                                    <p class="text-md text-gray-500 line-through" id="qv-product-sale-price"></p>
                                </div>

                                <div class="mb-6">
                                    <div class="flex items-center">
                                        <span class="text-sm font-semibold text-gray-700 mr-2">Availability: </span>
                                        <span id="qv-stock-status-display" class="font-medium"></span>
                                    </div>
                                </div>

                                <form id="qv-add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" id="qv-product-id">
                                    <input type="hidden" name="variant_id" id="qv-selected-variant-id">

                                    <div class="flex items-center gap-3 mb-4">
                                        <label for="qv-quantity-input" class="text-sm font-medium text-gray-700">Quantity:</label>
                                        <div class="flex items-center border border-gray-300 rounded-md">
                                            <button type="button" id="qv-decrement-quantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-md">-</button>
                                            <input type="number" id="qv-quantity-input" value="1" min="1" class="w-16 text-center border-l border-r border-gray-300 focus:outline-none" name="quantity">
                                            <button type="button" id="qv-increment-quantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-md">+</button>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <button type="submit" name="action" value="add_to_cart" id="qv-add-to-cart-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md transition-colors duration-200">
                                            Add to Cart
                                        </button>
                                        <button type="submit" name="action" value="buy_now" id="qv-buy-now-btn" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-md transition-colors duration-200">
                                            Buy Now
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Filter Toggles
                function setupFilterToggles(prefix = '') {
                    document.querySelectorAll(`${prefix}.filter-toggle`).forEach(button => {
                        button.addEventListener('click', function() {
                            const targetId = this.dataset.target;
                            const targetContent = document.querySelector(targetId);
                            const icon = this.querySelector('svg');

                            if (targetContent.classList.contains('hidden')) {
                                targetContent.classList.remove('hidden');
                                icon.classList.add('rotate-180');
                            } else {
                                targetContent.classList.add('hidden');
                                icon.classList.remove('rotate-180');
                            }
                        });
                    });
                }

                setupFilterToggles();
                setupFilterToggles('.mobile-filter-toggle');

                // Mobile Filters
                const mobileFiltersButton = document.getElementById('mobile-filters-button');
                const mobileFilters = document.getElementById('mobile-filters');
                const mobileFiltersClose = document.getElementById('mobile-filters-close');
                const mobileFiltersOverlay = document.getElementById('mobile-filters-overlay');

                if (mobileFiltersButton) {
                    mobileFiltersButton.addEventListener('click', function() {
                        mobileFilters.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    });
                }

                if (mobileFiltersClose) {
                    mobileFiltersClose.addEventListener('click', function() {
                        mobileFilters.classList.add('hidden');
                        document.body.style.overflow = '';
                    });
                }

                if (mobileFiltersOverlay) {
                    mobileFiltersOverlay.addEventListener('click', function() {
                        mobileFilters.classList.add('hidden');
                        document.body.style.overflow = '';
                    });
                }

                // Price Range Slider
                function setupPriceRangeSlider(minInputId, maxInputId, minSliderId, maxSliderId, progressId, maxPrice) {
                    const minPriceInput = document.getElementById(minInputId);
                    const maxPriceInput = document.getElementById(maxInputId);
                    const minSlider = document.getElementById(minSliderId);
                    const maxSlider = document.getElementById(maxSliderId);
                    const progress = document.getElementById(progressId);

                    function updatePriceRange() {
                        const minVal = parseInt(minSlider.value);
                        const maxVal = parseInt(maxSlider.value);

                        // Update progress bar
                        const minPercent = (minVal / maxPrice) * 100;
                        const maxPercent = (maxVal / maxPrice) * 100;
                        progress.style.left = minPercent + '%';
                        progress.style.width = (maxPercent - minPercent) + '%';

                        // Update input fields
                        minPriceInput.value = minVal;
                        maxPriceInput.value = maxVal;
                    }

                    if (minSlider && maxSlider) {
                        minSlider.addEventListener('input', function() {
                            const minVal = parseInt(this.value);
                            const maxVal = parseInt(maxSlider.value);

                            if (minVal > maxVal) {
                                this.value = maxVal;
                            }
                            updatePriceRange();
                        });

                        maxSlider.addEventListener('input', function() {
                            const minVal = parseInt(minSlider.value);
                            const maxVal = parseInt(this.value);

                            if (maxVal < minVal) {
                                this.value = minVal;
                            }
                            updatePriceRange();
                        });

                        minPriceInput.addEventListener('change', function() {
                            let value = parseInt(this.value);
                            if (isNaN(value)) value = 0;
                            if (value < 0) value = 0;
                            if (value > maxPrice) value = maxPrice;

                            this.value = value;
                            minSlider.value = value;
                            updatePriceRange();
                        });

                        maxPriceInput.addEventListener('change', function() {
                            let value = parseInt(this.value);
                            if (isNaN(value)) value = maxPrice;
                            if (value < 0) value = 0;
                            if (value > maxPrice) value = maxPrice;

                            this.value = value;
                            maxSlider.value = value;
                            updatePriceRange();
                        });

                        // Initial setup
                        updatePriceRange();
                    }
                }

                setupPriceRangeSlider(
                    'min-price-input', 'max-price-input',
                    'price-min', 'price-max',
                    'price-range-progress',
                    {{ $maxPrice }}
                );

                setupPriceRangeSlider(
                    'mobile-min-price-input', 'mobile-max-price-input',
                    'mobile-price-min', 'mobile-price-max',
                    'mobile-price-range-progress',
                    {{ $maxPrice }}
                );

                // Apply Filters
                function applyFilters() {
                    const url = new URL(window.location.href);
                    const params = new URLSearchParams();

                    // Category
                    const selectedCategory = document.querySelector('#categories-content a.bg-blue-50');
                    if (selectedCategory) {
                        params.set('category', selectedCategory.href.split('category=')[1].split('&')[0]);
                    }

                    // Brand
                    const selectedBrand = document.querySelector('#brands-content a.bg-blue-50');
                    if (selectedBrand) {
                        params.set('brand', selectedBrand.href.split('brand=')[1].split('&')[0]);
                    }

                    // Price
                    const minPrice = document.getElementById('min-price-input').value;
                    const maxPrice = document.getElementById('max-price-input').value;
                    if (minPrice !== '0' || maxPrice !== '{{ $maxPrice }}') {
                        params.set('min_price', minPrice);
                        params.set('max_price', maxPrice);
                    }

                    // Sort
                    const sortOption = document.querySelector('input[name="sort"]:checked');
                    if (sortOption && sortOption.value !== 'newest') {
                        params.set('sort', sortOption.value);
                    }

                    // Preserve search query if exists
                    const searchQuery = new URLSearchParams(window.location.search).get('q');
                    if (searchQuery) {
                        params.set('q', searchQuery);
                    }

                    window.location.href = url.pathname + '?' + params.toString();
                }

                document.getElementById('apply-filters').addEventListener('click', applyFilters);
                document.getElementById('mobile-apply-filters').addEventListener('click', function() {
                    // Sync mobile price inputs with desktop before applying
                    document.getElementById('min-price-input').value = document.getElementById('mobile-min-price-input').value;
                    document.getElementById('max-price-input').value = document.getElementById('mobile-max-price-input').value;
                    applyFilters();
                });

                // Mobile Sort
                document.getElementById('mobile-sort')?.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    const params = new URLSearchParams(url.search);

                    if (this.value === 'newest') {
                        params.delete('sort');
                    } else {
                        params.set('sort', this.value);
                    }

                    window.location.href = url.pathname + '?' + params.toString();
                });

                // Quick View Modal
                document.querySelectorAll('.quick-view-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.dataset.productId;
                        openQuickViewModal(productId);
                    });
                });

                // Add to Cart from product card
                document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.dataset.productId;
                        const productType = this.dataset.productType;


                        if (productType === 'variable') {
                            openQuickViewModal(productId);
                        } else {
                            addToCart(productId, 1, this); // Simple product, add directly
                        }
                    });
                });

                // Buy Now from product card
                document.querySelectorAll('.buy-now-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.dataset.productId;
                        const productType = this.dataset.productType;


                        if (productType === 'variable') {
                            openQuickViewModal(productId, true); // Open quick view for buy now
                        } else {
                            buyNow(productId, 1, this); // Simple product, buy directly
                        }
                    });
                });

                function openQuickViewModal(productId, isBuyNow = false) {

                    const quickViewModal = document.getElementById('quick-view-modal');
                    const quickViewContent = document.getElementById('quick-view-content');
                    const qvAddToCartForm = document.getElementById('qv-add-to-cart-form');
                    const qvBuyNowBtn = document.getElementById('qv-buy-now-btn');

                    // Show loading state
                    quickViewContent.innerHTML = `
                        <div class="flex justify-center items-center h-64">
                            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                        </div>
                    `;

                    quickViewModal.classList.remove('hidden');
                    quickViewModal.classList.add('flex');
                    document.body.style.overflow = 'hidden';

                    // Fetch product details
                    fetch(`/api/products/${productId}/quick-view`)
                        .then(response => response.json())
                        .then(product => {

                            const template = document.getElementById('quick-view-product-template').content.cloneNode(true);

                            const qvProductName = template.querySelector('#qv-product-name');
                            const qvProductBrand = template.querySelector('#qv-product-brand');
                            const qvProductPrice = template.querySelector('#qv-product-price');
                            const qvProductSalePrice = template.querySelector('#qv-product-sale-price');
                            const qvProductShortDescription = template.querySelector('#qv-product-short-description');
                            const qvProductId = template.querySelector('#qv-product-id');
                            const qvMainImage = template.querySelector('#qv-main-image');
                            const qvGalleryThumbnails = template.querySelector('#qv-gallery-thumbnails');
                            const qvVariantSelection = template.querySelector('#qv-variant-selection');
                            const qvVariantOptions = template.querySelector('#qv-variant-options');
                            const qvSelectedVariantId = template.querySelector('#qv-selected-variant-id');
                            const qvQuantityInput = template.querySelector('#qv-quantity-input');
                            const qvStockStatusDisplay = template.querySelector('#qv-stock-status-display');
                            const qvDecrementQuantity = template.querySelector('#qv-decrement-quantity');
                            const qvIncrementQuantity = template.querySelector('#qv-increment-quantity');
                            const qvAddToCartForm = template.querySelector('#qv-add-to-cart-form');
                            const qvAddToCartBtn = template.querySelector('#qv-add-to-cart-btn');
                            const qvBuyNowBtn = template.querySelector('#qv-buy-now-btn');

                            qvProductName.textContent = product.name;
                            qvProductBrand.textContent = product.brand ? product.brand.name : 'N/A';
                            qvProductPrice.textContent = product.formatted_price;
                            if (product.formatted_sale_price) {
                                qvProductSalePrice.textContent = product.formatted_sale_price;
                                qvProductSalePrice.classList.remove('hidden');
                            } else {
                                qvProductSalePrice.classList.add('hidden');
                            }
                            qvProductShortDescription.innerHTML = product.short_description;
                            qvProductId.value = product.id;

                            const defaultImage = '{{ asset('images/placeholder-product.png') }}';
                            qvMainImage.src = product.thumbnail_url || defaultImage;

                            if (product.media && product.media.length > 0) {
                                qvGalleryThumbnails.innerHTML = '';
                                product.media.forEach(mediaItem => {
                                    const img = document.createElement('img');
                                    img.src = mediaItem.thumb_url;
                                    img.alt = product.name + ' thumbnail';
                                    img.classList.add('w-full', 'h-16', 'object-cover', 'rounded-md', 'cursor-pointer', 'border-2', 'border-transparent', 'hover:border-blue-500', 'transition-colors', 'duration-200', 'qv-thumbnail-image');
                                    img.dataset.src = mediaItem.url;
                                    qvGalleryThumbnails.appendChild(img);
                                });

                                template.querySelectorAll('.qv-thumbnail-image').forEach(thumb => {
                                    thumb.addEventListener('click', function() {
                                        qvMainImage.src = this.dataset.src;
                                    });
                                });
                            }

                            function updateQuickViewDisplay(selectedRadio) {

                                const formattedPrice = selectedRadio.dataset.formattedPrice;
                                const formattedSalePrice = selectedRadio.dataset.formattedSalePrice;
                                const stock = parseInt(selectedRadio.dataset.stock);
                                const stockStatus = selectedRadio.dataset.stockStatus;
                                const variantId = selectedRadio.value;

                                qvProductPrice.textContent = formattedSalePrice || formattedPrice;
                                if (formattedSalePrice) {
                                    qvProductSalePrice.textContent = formattedPrice;
                                    qvProductSalePrice.classList.remove('hidden');
                                } else {
                                    qvProductSalePrice.classList.add('hidden');
                                }

                                if (stockStatus === 'in_stock') {
                                    qvStockStatusDisplay.innerHTML = `<span class="text-green-600">In Stock (${stock} items)</span>`;
                                } else if (stockStatus === 'out_of_stock') {
                                    qvStockStatusDisplay.innerHTML = `<span class="text-red-600">Out of Stock</span>`;
                                } else {
                                    qvStockStatusDisplay.innerHTML = `<span class="text-yellow-600">Backorder</span>`;
                                }

                                qvQuantityInput.max = stock;
                                if (parseInt(qvQuantityInput.value) > stock) {
                                    qvQuantityInput.value = 1;
                                }

                                const isOutOfStock = (stockStatus === 'out_of_stock');
                                qvAddToCartBtn.disabled = isOutOfStock;
                                qvBuyNowBtn.disabled = isOutOfStock;
                                qvQuantityInput.disabled = isOutOfStock;
                                qvDecrementQuantity.disabled = isOutOfStock;
                                qvIncrementQuantity.disabled = isOutOfStock;

                                qvSelectedVariantId.value = variantId;
                            }

                            if (product.type === 'variable' && product.variants.length > 0) {
                                qvVariantSelection.classList.remove('hidden');
                                qvVariantOptions.innerHTML = '';

                                product.variants.forEach(variant => {
                                    const variantDiv = document.createElement('div');
                                    variantDiv.classList.add('variant-option-wrapper');
                                    variantDiv.innerHTML = `
                                        <input type="radio" name="qv_variant_id" id="qv-variant-${variant.id}" value="${variant.id}" class="sr-only qv-variant-radio"
                                            data-stock="${variant.stock_quantity}"
                                            data-stock-status="${variant.stock_status}"
                                            data-formatted-price="${variant.formatted_price}"
                                            data-formatted-sale-price="${variant.formatted_sale_price || ''}"
                                            ${variant.stock_status === 'out_of_stock' ? 'disabled' : ''}>
                                        <label for="qv-variant-${variant.id}" class="variant-label cursor-pointer block border border-gray-300 rounded-md p-3 text-center transition-all duration-200">
                                            <span class="variant-name text-sm font-medium text-gray-800">
                                                ${variant.attributes_list}
                                            </span>
                                            <span class="variant-price text-xs text-gray-500 block mt-1">
                                                ${variant.formatted_sale_price || variant.formatted_price}
                                            </span>
                                        </label>
                                    `;
                                    qvVariantOptions.appendChild(variantDiv);
                                });

                                template.querySelectorAll('.qv-variant-radio').forEach(radio => {
                                    radio.addEventListener('change', function() {
                                        updateQuickViewDisplay(this);
                                    });
                                });

                                const firstAvailableVariant = product.variants.find(v => v.stock_status !== 'out_of_stock') || product.variants[0];
                                if (firstAvailableVariant) {
                                    const firstAvailableRadio = template.querySelector(`#qv-variant-${firstAvailableVariant.id}`);
                                    firstAvailableRadio.checked = true;
                                    updateQuickViewDisplay(firstAvailableRadio);
                                }

                            } else {
                                qvVariantSelection.classList.add('hidden');
                                qvSelectedVariantId.value = '';
                                updateQuickViewDisplay({
                                    dataset: {
                                        stock: product.stock_quantity,
                                        stock_status: product.stock_status,
                                        formatted_price: product.formatted_price,
                                        formatted_sale_price: product.formatted_sale_price
                                    },
                                    value: ''
                                });
                            }

                            qvDecrementQuantity.addEventListener('click', () => {
                                if (parseInt(qvQuantityInput.value) > 1) {
                                    qvQuantityInput.value = parseInt(qvQuantityInput.value) - 1;
                                }
                            });
                            qvIncrementQuantity.addEventListener('click', () => {
                                const max = parseInt(qvQuantityInput.max) || 999;
                                if(parseInt(qvQuantityInput.value) < max) {
                                    qvQuantityInput.value = parseInt(qvQuantityInput.value) + 1;
                                }
                            });

                            qvAddToCartForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                const selectedProductId = product.id;
                                const selectedVariantId = qvSelectedVariantId.value || null;
                                const selectedQuantity = qvQuantityInput.value;
                                const action = e.submitter.value;

                                if (action === 'add_to_cart') {
                                    addToCart(selectedProductId, selectedQuantity, e.submitter, selectedVariantId);
                                } else if (action === 'buy_now') {
                                    buyNow(selectedProductId, selectedQuantity, e.submitter, selectedVariantId);
                                }
                                quickViewModal.classList.add('hidden');
                                document.body.style.overflow = '';
                            });

                            if (isBuyNow) {
                                qvBuyNowBtn.click();
                            }

                            quickViewContent.innerHTML = '';
                            quickViewContent.appendChild(template);
                        })
                        .catch(error => {

                            quickViewContent.innerHTML = '<p class="text-center text-red-500">Failed to load product details.</p>';
                        });
                }

                document.getElementById('quick-view-close').addEventListener('click', function() {
                    document.getElementById('quick-view-modal').classList.add('hidden');
                    document.getElementById('quick-view-modal').classList.remove('flex');
                    document.body.style.overflow = '';
                });

                // Close modal when clicking outside
                document.getElementById('quick-view-modal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                        this.classList.remove('flex');
                        document.body.style.overflow = '';
                    }
                });

                // Add to Cart functionality
                function addToCart(productId, quantity, buttonElement, variantId = null) {
                    const originalText = buttonElement.innerHTML;
                    buttonElement.innerHTML = `
                        <svg class="w-4 h-4 animate-spin text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Adding...
                    `;
                    buttonElement.disabled = true;

                    // Fly to cart animation
                    const productCard = buttonElement.closest('.group');
                    const productImage = productCard.querySelector('img');
                    const flyingImage = productImage.cloneNode();
                    const cartIcon = document.querySelector('.cart-icon'); // Assuming you have a cart icon with this class in your layout

                    if (cartIcon) {
                        flyingImage.style.position = 'fixed';
                        flyingImage.style.left = `${productImage.getBoundingClientRect().left}px`;
                        flyingImage.style.top = `${productImage.getBoundingClientRect().top}px`;
                        flyingImage.style.width = `${productImage.width}px`;
                        flyingImage.style.height = `${productImage.height}px`;
                        flyingImage.style.transition = 'all 1s ease-in-out';
                        flyingImage.style.zIndex = '9999';
                        document.body.appendChild(flyingImage);

                        setTimeout(() => {
                            flyingImage.style.left = `${cartIcon.getBoundingClientRect().left}px`;
                            flyingImage.style.top = `${cartIcon.getBoundingClientRect().top}px`;
                            flyingImage.style.width = '0px';
                            flyingImage.style.height = '0px';
                            flyingImage.style.opacity = '0';
                        }, 100);
                    }


                    fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ product_id: productId, quantity: quantity, variant_id: variantId, action: 'add_to_cart' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            setTimeout(() => {
                                if (cartIcon) flyingImage.remove();
                                let timerInterval
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    html: `
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-lg font-semibold">Product Added!</div>
                                                <div class="text-sm text-gray-500">Your item is in the cart.</div>
                                            </div>
                                        </div>
                                    `,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })
                                updateCartCount(data.cart_count);
                            }, 1000);
                        } else {
                            if (cartIcon) flyingImage.remove();
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message || 'Could not add to cart.',
                            });
                        }
                    })
                    .catch(error => {
                        if (cartIcon) flyingImage.remove();
                        console.error('Error adding to cart:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while adding to cart.',
                        });
                    })
                    .finally(() => {
                        setTimeout(() => {
                            buttonElement.innerHTML = originalText;
                            buttonElement.disabled = false;
                        }, 1000);
                    });
                }

                function buyNow(productId, quantity, buttonElement, variantId = null) {
                    const originalText = buttonElement.innerHTML;
                    buttonElement.innerHTML = `
                        <svg class="w-4 h-4 animate-spin text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Redirecting...
                    `;
                    buttonElement.disabled = true;

                    fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ product_id: productId, quantity: quantity, variant_id: variantId, action: 'buy_now' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCartCount(data.cart_count);
                            window.location.href = '{{ route('checkout.index') }}';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message || 'Could not add to cart.',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart for buy now:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing your request.',
                        });
                    })
                    .finally(() => {
                        buttonElement.innerHTML = originalText;
                        buttonElement.disabled = false;
                    });
                }

                function updateCartCount(count) {
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(element => {
                        element.textContent = count;
                        if (count > 0) {
                            element.classList.remove('hidden');
                        } else {
                            element.classList.add('hidden');
                        }
                    });
                }
            });
        </script>
        <style>
            /* Hide the arrows on number inputs */
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            /* Custom scrollbar */
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #a1a1a1;
            }

            /* Line clamp for product titles */
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* Smooth transitions */
            .transition-colors {
                transition-property: background-color, border-color, color, fill, stroke;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
                transition-duration: 150ms;
            }

            /* Focus styles */
            .focus\:ring-2:focus {
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
            }

            /* SweetAlert2 responsiveness */
            .swal2-popup {
                width: 90% !important; /* Occupy 90% of the viewport width */
                max-width: 400px; /* Max width for larger screens */
            }

            @media (max-width: 768px) {
                .swal2-popup {
                    width: 95% !important; /* Slightly wider on smaller screens */
                    margin: 0 10px; /* Add some margin to the sides */
                }
            }

            @media (max-width: 480px) {
                .swal2-popup {
                    width: 98% !important; /* Almost full width on very small screens */
                    margin: 0 5px; /* Minimal margin */
                }
            }

            /* Variant styles */
            .qv-variant-radio:checked + .variant-label {
                border-color: #3b82f6; /* blue-500 */
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
            }
            .qv-variant-radio:disabled + .variant-label {
                cursor: not-allowed;
                background-color: #f3f4f6; /* gray-100 */
                opacity: 0.7;
            }
            .qv-variant-radio:disabled + .variant-label .variant-name {
                text-decoration: line-through;
            }
        </style>
    @endpush
@endsection
