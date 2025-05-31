@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full md:w-1/4 lg:w-1/5">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">Filters</h2>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Categories</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                       class="text-gray-600 hover:text-primary-500 {{ request('category') == $category->slug ? 'text-primary-500 font-medium' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Brands -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Brands</h3>
                        <ul class="space-y-2">
                            @foreach($brands as $brand)
                                <li>
                                    <a href="{{ route('shop.index', ['brand' => $brand->slug]) }}"
                                       class="text-gray-600 hover:text-primary-500 {{ request('brand') == $brand->slug ? 'text-primary-500 font-medium' : '' }}">
                                        {{ $brand->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Price Range</h3>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">BDT 0</span>
                            <span class="text-sm text-gray-600">BDT {{ number_format($maxPrice, 2) }}</span>
                        </div>
                        <input type="range" min="0" max="{{ $maxPrice }}"
                               value="{{ request('min_price', 0) }},{{ request('max_price', $maxPrice) }}"
                               class="w-full range-slider" id="priceRange">
                        <div class="flex justify-between mt-2">
                            <input type="number" name="min_price" id="minPrice" value="{{ request('min_price', 0) }}"
                                   class="w-20 p-1 border rounded text-sm">
                            <input type="number" name="max_price" id="maxPrice" value="{{ request('max_price', $maxPrice) }}"
                                   class="w-20 p-1 border rounded text-sm">
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Sort By</h3>
                        <select id="sortSelect" class="w-full p-2 border rounded">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    <button id="applyFilters" class="w-full bg-primary-500 text-white py-2 rounded hover:bg-primary-600">
                        Apply Filters
                    </button>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="w-full md:w-3/4 lg:w-4/5">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Shop</h1>
                    <div class="relative">
                        <form action="{{ route('shop.search') }}" method="GET">
                            <input type="text" name="q" placeholder="Search products..."
                                   class="pl-10 pr-4 py-2 border rounded-lg w-64"
                                   value="{{ request()->get('q') }}">
                            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </form>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request()->has('category') || request()->has('brand') || request()->has('min_price'))
                    <div class="mb-4 flex flex-wrap gap-2">
                        @if(request()->has('category'))
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm flex items-center">
                            {{ $categories->firstWhere('slug', request('category'))->name }}
                            <a href="{{ route('shop.index', array_except(request()->query(), 'category')) }}" class="ml-1 text-gray-500 hover:text-gray-700">
                                &times;
                            </a>
                        </span>
                        @endif
                        @if(request()->has('brand'))
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm flex items-center">
                            {{ $brands->firstWhere('slug', request('brand'))->name }}
                            <a href="{{ route('shop.index', array_except(request()->query(), 'brand')) }}" class="ml-1 text-gray-500 hover:text-gray-700">
                                &times;
                            </a>
                        </span>
                        @endif
                        @if(request()->has('min_price') || request()->has('max_price'))
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm flex items-center">
                            BDT {{ request('min_price', 0) }} - BDT {{ request('max_price', $maxPrice) }}
                            <a href="{{ route('shop.index', array_except(request()->query(), ['min_price', 'max_price'])) }}" class="ml-1 text-gray-500 hover:text-gray-700">
                                &times;
                            </a>
                        </span>
                        @endif
                    </div>
                @endif

                <!-- Product Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-md transition-shadow duration-300">
                                <div class="relative">
                                    <a href="{{ route('product.view', $product->slug) }}">
                                        <img src="{{ $product->thumbnail ?? asset('images/placeholder-product.png') }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-48 object-cover">
                                    </a>
                                    @if($product->sale_price)
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        SALE
                                    </span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="absolute top-2 left-2 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        FEATURED
                                    </span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-1">
                                        <a href="{{ route('product.view', $product->slug) }}"
                                           class="font-semibold text-gray-800 hover:text-primary-500 line-clamp-2">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->brand)
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                            {{ $product->brand->name }}
                                        </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count }})</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($product->sale_price)
                                                <span class="text-lg font-bold text-gray-800">BDT {{ number_format($product->sale_price, 2) }}</span>
                                                <span class="text-sm text-gray-500 line-through ml-1">BDT {{ number_format($product->price, 2) }}</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-800">BDT {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="text-sm">
                                            @if($product->stock_status == 'in_stock')
                                                <span class="text-green-600">In Stock</span>
                                            @elseif($product->stock_status == 'out_of_stock')
                                                <span class="text-red-600">Out of Stock</span>
                                            @else
                                                <span class="text-blue-600">Backorder</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 flex justify-between">
                                        <button class="add-to-wishlist text-gray-400 hover:text-red-500"
                                                data-product-id="{{ $product->id }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </button>
                                        <button class="add-to-cart bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded text-sm"
                                                data-product-id="{{ $product->id }}">
                                            Add to Cart
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
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                        <p class="mt-1 text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                        <div class="mt-6">
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600">
                                Clear filters
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
                // Price range slider
                const priceRange = document.getElementById('priceRange');
                const minPrice = document.getElementById('minPrice');
                const maxPrice = document.getElementById('maxPrice');

                if (priceRange) {
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
                        minPrice.value = min;
                        maxPrice.value = max;
                    });

                    minPrice.addEventListener('change', function() {
                        priceRange.noUiSlider.set([this.value, null]);
                    });

                    maxPrice.addEventListener('change', function() {
                        priceRange.noUiSlider.set([null, this.value]);
                    });
                }

                // Apply filters
                document.getElementById('applyFilters').addEventListener('click', function() {
                    const url = new URL(window.location.href);
                    const params = new URLSearchParams(url.search);

                    // Clear existing filters
                    params.delete('category');
                    params.delete('brand');
                    params.delete('min_price');
                    params.delete('max_price');
                    params.delete('sort');

                    // Add new filters
                    if (document.getElementById('minPrice').value > 0 ||
                        document.getElementById('maxPrice').value < {{ $maxPrice }}) {
                        params.set('min_price', document.getElementById('minPrice').value);
                        params.set('max_price', document.getElementById('maxPrice').value);
                    }

                    const sortSelect = document.getElementById('sortSelect');
                    if (sortSelect.value !== 'newest') {
                        params.set('sort', sortSelect.value);
                    }

                    window.location.href = url.pathname + '?' + params.toString();
                });

                // Add to cart
                document.querySelectorAll('.add-to-cart').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-product-id');
                        // Implement your cart functionality here
                        console.log('Add to cart:', productId);
                    });
                });

                // Add to wishlist
                document.querySelectorAll('.add-to-wishlist').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-product-id');
                        // Implement your wishlist functionality here
                        console.log('Add to wishlist:', productId);
                    });
                });
            });
        </script>
    @endpush
@endsection
