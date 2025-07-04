@extends('layouts.app')

@section('title', 'Shop')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        @include('components.breadcrumbs', ['links' => ['Home' => route('home')], 'title' => 'Shop'])

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full md:w-1/4 lg:w-1/5">
                <div class="bg-white p-4 rounded-lg shadow-md sticky top-4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Filters</h2>

                    <!-- Categories Filter -->
                    <div class="mb-6 border-b border-gray-200 pb-2">
                        <button class="flex justify-between items-center w-full text-lg font-semibold text-gray-700 mb-1 filter-toggle" data-target="#categories-content">
                            Categories
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="categories-content" class="filter-content {{ request('category') ? '' : 'hidden' }}">
                            <input type="text" placeholder="Search categories..." class="category-search-input w-full px-3 py-2 border border-gray-300 rounded-md mb-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <ul class="category-list space-y-1 max-h-48 overflow-y-auto custom-scrollbar">
                                @foreach($categories as $category)
                                    <li class="category-item">
                                        <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                                           class="text-gray-600 hover:text-blue-600 {{ request('category') == $category->slug ? 'text-blue-600 font-medium' : '' }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @if(count($categories) > 5)
                                <button class="show-more-categories text-blue-600 hover:underline text-sm mt-2">Show More</button>
                            @endif
                        </div>
                    </div>

                    <!-- Brands Filter -->
                    <div class="mb-6 border-b border-gray-200 pb-2">
                        <button class="flex justify-between items-center w-full text-lg font-semibold text-gray-700 mb-1 filter-toggle" data-target="#brands-content">
                            Brands
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="brands-content" class="filter-content {{ request('brand') ? '' : 'hidden' }}">
                            <input type="text" placeholder="Search brands..." class="brand-search-input w-full px-3 py-2 border border-gray-300 rounded-md mb-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <ul class="brand-list space-y-1 max-h-48 overflow-y-auto custom-scrollbar">
                                @foreach($brands as $brand)
                                    <li class="brand-item">
                                        <a href="{{ route('shop.index', array_merge(request()->except('page'), ['brand' => $brand->slug])) }}"
                                           class="text-gray-600 hover:text-blue-600 {{ request('brand') == $brand->slug ? 'text-blue-600 font-medium' : '' }}">
                                            {{ $brand->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @if(count($brands) > 5)
                                <button class="show-more-brands text-blue-600 hover:underline text-sm mt-2">Show More</button>
                            @endif
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-6 border-b border-gray-200 pb-2">
                        <button class="flex justify-between items-center w-full text-lg font-semibold text-gray-700 mb-1 filter-toggle" data-target="#price-content">
                            Price Range
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="price-content" class="filter-content {{ (request('min_price') || request('max_price')) ? '' : 'hidden' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">BDT 0</span>
                                <span class="text-sm text-gray-600">BDT {{ number_format($maxPrice, 2) }}</span>
                            </div>
                            <input type="range" min="0" max="{{ $maxPrice }}"
                                   value="{{ request('min_price', 0) }},{{ request('max_price', $maxPrice) }}"
                                   class="w-full range-slider" id="priceRange">
                            <div class="flex justify-between mt-2">
                                <input type="number" name="min_price" id="minPrice" value="{{ request('min_price', 0) }}"
                                       class="w-24 p-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                <input type="number" name="max_price" id="maxPrice" value="{{ request('max_price', $maxPrice) }}"
                                       class="w-24 p-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-6">
                        <button class="flex justify-between items-center w-full text-lg font-semibold text-gray-700 mb-1 filter-toggle" data-target="#sort-content">
                            Sort By
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="sort-content" class="filter-content {{ request('sort') ? '' : 'hidden' }}">
                            <select id="sortSelect" class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                    </div>

                    <button id="applyFilters" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 shadow-md">
                        Apply Filters
                    </button>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="w-full md:w-3/4 lg:w-4/5">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Shop All Products</h1>
                    <div class="relative">
                        <form action="{{ route('shop.search') }}" method="GET" class="flex items-center">
                            <input type="text" name="q" placeholder="Search products..."
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ request()->get('q') }}">
                            <button type="submit" class="absolute left-3 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Active Filters Display -->
                @if(request()->has('category') || request()->has('brand') || request()->has('min_price') || request()->has('sort'))
                    <div class="mb-6 flex flex-wrap gap-2 items-center p-3 bg-gray-100 rounded-lg shadow-sm">
                        <span class="text-sm font-semibold text-gray-700 mr-2">Active Filters:</span>
                        @if(request()->has('category'))
                            <span class="bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center">
                            Category: {{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }}
                            <a href="{{ route('shop.index', array_except(request()->query(), 'category')) }}" class="ml-2 text-blue-800 hover:text-blue-900 font-bold">
                                &times;
                            </a>
                        </span>
                        @endif
                        @if(request()->has('brand'))
                            <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-sm flex items-center">
                            Brand: {{ $brands->firstWhere('slug', request('brand'))->name ?? request('brand') }}
                            <a href="{{ route('shop.index', array_except(request()->query(), 'brand')) }}" class="ml-2 text-green-800 hover:text-green-900 font-bold">
                                &times;
                            </a>
                        </span>
                        @endif
                        @if(request()->has('min_price') || request()->has('max_price'))
                            <span class="bg-purple-200 text-purple-800 px-3 py-1 rounded-full text-sm flex items-center">
                            Price: BDT {{ request('min_price', 0) }} - BDT {{ request('max_price', $maxPrice) }}
                            <a href="{{ route('shop.index', array_except(request()->query(), ['min_price', 'max_price'])) }}" class="ml-2 text-purple-800 hover:text-purple-900 font-bold">
                                &times;
                            </a>
                        </span>
                        @endif
                        @if(request('sort') && request('sort') !== 'newest')
                            <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-sm flex items-center">
                            Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}
                            <a href="{{ route('shop.index', array_except(request()->query(), 'sort')) }}" class="ml-2 text-yellow-800 hover:text-yellow-900 font-bold">
                                &times;
                            </a>
                        </span>
                        @endif
                        <a href="{{ route('shop.index') }}" class="ml-auto text-blue-600 hover:underline text-sm font-medium">
                            Clear All Filters
                        </a>
                    </div>
                @endif

                <!-- Product Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 group">
                                <div class="relative h-36 sm:h-40 md:h-48 overflow-hidden">
                                    <a href="{{ route('products.show.frontend', $product->slug) }}">
                                        <img src="{{ $product->thumbnail_url ?? asset('images/placeholder-product.png') }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    </a>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">
                                        SALE
                                    </span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">
                                        FEATURED
                                    </span>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h3 class="text-base font-semibold text-gray-900 mb-1 truncate">
                                        <a href="{{ route('products.show.frontend', $product->slug) }}" class="hover:text-blue-600 transition-colors duration-200">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    @if($product->brand)
                                        <p class="text-xs text-gray-500 mb-2">Brand: {{ $product->brand->name }}</p>
                                    @endif

                                    <div class="flex items-center mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                        <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count }})</span>
                                    </div>

                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-lg font-bold text-gray-800">BDT {{ number_format($product->sale_price, 2) }}</span>
                                                <span class="text-sm text-gray-500 line-through ml-1">BDT {{ number_format($product->price, 2) }}</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-800">BDT {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs">
                                            @if($product->stock_quantity > 0)
                                                <span class="text-green-600 font-medium">In Stock</span>
                                            @else
                                                <span class="text-red-600 font-medium">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-2">
                                        <button class="w-full bg-blue-600 text-white py-2 px-3 rounded-md text-sm font-semibold hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                            Add to Cart
                                        </button>
                                        <button class="w-full bg-green-500 text-white py-2 px-3 rounded-md text-sm font-semibold hover:bg-green-600 transition-colors duration-200 shadow-sm">
                                            Buy Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-lg shadow-md">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-4 text-xl font-medium text-gray-900">No products found</h3>
                        <p class="mt-2 text-gray-600">Try adjusting your search or filter to find what you're looking for.</p>
                        <div class="mt-6">
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-5 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                Clear All Filters
                            </a>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Filter Toggles
                document.querySelectorAll('.filter-toggle').forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.dataset.target;
                        const targetContent = document.querySelector(targetId);
                        const icon = this.querySelector('svg');

                        if (targetContent.classList.contains('hidden')) {
                            targetContent.classList.remove('hidden');
                            icon.classList.remove('rotate-0');
                            icon.classList.add('rotate-180');
                        } else {
                            targetContent.classList.add('hidden');
                            icon.classList.remove('rotate-180');
                            icon.classList.add('rotate-0');
                        }
                    });
                });

                // Category and Brand Search/Show More
                function setupFilterList(containerSelector, searchInputSelector, itemListSelector, showMoreButtonSelector, initialItemsToShow = 5) {
                    const container = document.querySelector(containerSelector);
                    if (!container) return;

                    const searchInput = container.querySelector(searchInputSelector);
                    const itemList = container.querySelector(itemListSelector);
                    const items = Array.from(itemList.children);
                    const showMoreButton = container.querySelector(showMoreButtonSelector);

                    function updateVisibility() {
                        let visibleCount = 0;
                        items.forEach((item, index) => {
                            const text = item.textContent.toLowerCase();
                            const searchTerm = searchInput.value.toLowerCase();
                            if (text.includes(searchTerm)) {
                                item.style.display = '';
                                visibleCount++;
                            } else {
                                item.style.display = 'none';
                            }
                        });

                        if (showMoreButton) {
                            if (visibleCount > initialItemsToShow) {
                                showMoreButton.style.display = '';
                                if (!itemList.classList.contains('expanded')) {
                                    items.forEach((item, index) => {
                                        if (index >= initialItemsToShow && item.style.display !== 'none') {
                                            item.style.display = 'none';
                                        }
                                    });
                                }
                            } else {
                                showMoreButton.style.display = 'none';
                            }
                        }
                    }

                    if (searchInput) {
                        searchInput.addEventListener('input', updateVisibility);
                    }

                    if (showMoreButton) {
                        showMoreButton.addEventListener('click', function() {
                            itemList.classList.toggle('expanded');
                            if (itemList.classList.contains('expanded')) {
                                this.textContent = 'Show Less';
                                items.forEach(item => {
                                    if (item.style.display === 'none') {
                                        item.style.display = '';
                                    }
                                });
                            } else {
                                this.textContent = 'Show More';
                                updateVisibility(); // Re-apply initial visibility
                            }
                        });
                    }

                    // Initial setup
                    updateVisibility();
                    if (showMoreButton && items.length > initialItemsToShow) {
                        showMoreButton.textContent = 'Show More';
                    }
                }

                setupFilterList('#categories-content', '.category-search-input', '.category-list', '.show-more-categories', 5);
                setupFilterList('#brands-content', '.brand-search-input', '.brand-list', '.show-more-brands', 5);

                // Price range slider (assuming noUiSlider is available or will be added)
                const priceRange = document.getElementById('priceRange');
                const minPriceInput = document.getElementById('minPrice');
                const maxPriceInput = document.getElementById('maxPrice');

                if (priceRange && typeof noUiSlider !== 'undefined') {
                    noUiSlider.create(priceRange, {
                        start: [{{ request('min_price', 0) }}, {{ request('max_price', $maxPrice) }}],
                        connect: true,
                        range: {
                            'min': 0,
                            'max': {{ $maxPrice }}
                        },
                        tooltips: true,
                        format: {
                            to: function(value) {
                                return Math.round(value);
                            },
                            from: function(value) {
                                return Number(value);
                            }
                        }
                    });

                    priceRange.noUiSlider.on('update', function(values, handle) {
                        const [min, max] = values;
                        minPriceInput.value = Math.round(min);
                        maxPriceInput.value = Math.round(max);
                    });

                    minPriceInput.addEventListener('change', function() {
                        priceRange.noUiSlider.set([this.value, null]);
                    });

                    maxPriceInput.addEventListener('change', function() {
                        priceRange.noUiSlider.set([null, this.value]);
                    });
                } else if (priceRange) {
                    // Fallback for basic range input if noUiSlider is not present
                    minPriceInput.addEventListener('input', function() {
                        priceRange.value = `${this.value},${maxPriceInput.value}`;
                    });
                    maxPriceInput.addEventListener('input', function() {
                        priceRange.value = `${minPriceInput.value},${this.value}`;
                    });
                }

                // Apply Filters Button
                document.getElementById('applyFilters').addEventListener('click', function() {
                    const url = new URL(window.location.href);
                    const params = new URLSearchParams(url.search);

                    // Clear existing filters that will be reapplied
                    params.delete('category');
                    params.delete('brand');
                    params.delete('min_price');
                    params.delete('max_price');
                    params.delete('sort');
                    params.delete('page'); // Reset pagination on filter change

                    // Add new filters
                    const selectedCategory = document.querySelector('#categories-content a.text-blue-600');
                    if (selectedCategory) {
                        params.set('category', selectedCategory.href.split('category=')[1].split('&')[0]);
                    }

                    const selectedBrand = document.querySelector('#brands-content a.text-blue-600');
                    if (selectedBrand) {
                        params.set('brand', selectedBrand.href.split('brand=')[1].split('&')[0]);
                    }

                    if (minPriceInput.value !== '0' || maxPriceInput.value !== '{{ $maxPrice }}') {
                        params.set('min_price', minPriceInput.value);
                        params.set('max_price', maxPriceInput.value);
                    }

                    const sortSelect = document.getElementById('sortSelect');
                    if (sortSelect.value !== 'newest') {
                        params.set('sort', sortSelect.value);
                    }

                    window.location.href = url.pathname + '?' + params.toString();
                });

                // Handle category/brand link clicks to apply filter immediately
                document.querySelectorAll('#categories-content a, #brands-content a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent default link behavior
                        const url = new URL(this.href);
                        const params = new URLSearchParams(url.search);
                        params.delete('page'); // Reset pagination
                        window.location.href = url.pathname + '?' + params.toString();
                    });
                });

                // Initial state for filter toggles based on active filters
                document.querySelectorAll('.filter-content').forEach(contentDiv => {
                    const toggleButton = contentDiv.previousElementSibling;
                    const icon = toggleButton.querySelector('svg');
                    const hasActiveFilter = contentDiv.querySelectorAll('a.text-blue-600').length > 0 ||
                                            (contentDiv.id === 'price-content' && (minPriceInput.value !== '0' || maxPriceInput.value !== '{{ $maxPrice }}')) ||
                                            (contentDiv.id === 'sort-content' && document.getElementById('sortSelect').value !== 'newest');

                    if (hasActiveFilter) {
                        contentDiv.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        contentDiv.classList.add('hidden');
                        icon.classList.add('rotate-0');
                    }
                });

                // Quick View Modal functionality
                document.querySelectorAll('.quick-view-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.dataset.productId;
                        // In a real application, you would fetch product details via AJAX here
                        // For now, we'll just show a placeholder
                        const quickViewModal = document.getElementById('quick-view-modal');
                        const quickViewContent = document.getElementById('quick-view-content');

                        quickViewContent.innerHTML = `
                            <h2 class="text-2xl font-bold mb-4">Product Quick View</h2>
                            <p>Loading details for product ID: ${productId}...</p>
                            <!-- More detailed product info would go here -->
                        `;
                        quickViewModal.classList.remove('hidden');
                        quickViewModal.classList.add('flex');
                    });
                });

                document.getElementById('quick-view-close').addEventListener('click', function() {
                    document.getElementById('quick-view-modal').classList.add('hidden');
                    document.getElementById('quick-view-modal').classList.remove('flex');
                });

                // Close modal when clicking outside
                document.getElementById('quick-view-modal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                        this.classList.remove('flex');
                    }
                });
            });
        </script>
        <style>
            /* Custom scrollbar for filter lists */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>
    @endpush
