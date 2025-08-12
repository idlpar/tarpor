@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="text-sm text-gray-500 mb-6">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Home</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 67.254c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('shop.index') }}" class="text-blue-600 hover:text-blue-800">Shop</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 67.254c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li>
                    <span>{{ $product->name }}</span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Images & Details (Left/Main Column) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image Gallery -->
                    <div>
                        <div class="relative mb-4">
                            <img id="mainProductImage" src="{{ $product->thumbnail_url ?? asset('images/default-product.jpg') }}" alt="{{ $product->name }}" class="w-full h-96 object-contain rounded-lg shadow-sm cursor-zoom-in" data-full-src="{{ $product->thumbnail_url ?? asset('images/default-product.jpg') }}">
                            <!-- Zoom overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-opacity duration-300 flex items-center justify-center opacity-0 hover:opacity-100 cursor-zoom-in" id="zoomOverlay">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m0 0h-3"></path></svg>
                            </div>
                        </div>
                        @if ($product->gallery_images->isNotEmpty())
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($product->gallery_images as $image_url)
                                    <img src="{{ $image_url }}" alt="{{ $product->name }} thumbnail" class="w-full h-24 object-cover rounded-md cursor-pointer border-2 border-transparent hover:border-blue-500 transition-colors duration-200 thumbnail-image" data-src="{{ $image_url }}" data-full-src="{{ str_replace('/thumb/', '/', $image_url) }}">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                        <p class="text-gray-600 text-sm mb-4">Brand: <a href="#" class="text-blue-600 hover:underline">{{ $product->brand->name ?? 'N/A' }}</a></p>

                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                            <span class="text-sm text-gray-500 ml-2">({{ $product->reviews_count }} reviews)</span>
                        </div>

                        <div class="mb-4">
                            <p id="product-price" class="text-xl font-bold text-gray-900">{{ format_taka($product->price) }}</p>
                            <p id="product-sale-price" class="text-sm text-gray-500 line-through"></p>
                        </div>

                        <p class="text-gray-700 mb-4">{!! $product->short_description !!}</p>

                        <!-- Tags -->
                        @if($product->tags->isNotEmpty())
                            <div class="mb-4">
                                <span class="font-semibold text-gray-700">Tags: </span>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($product->tags as $tag)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ Str::upper($tag->name) === $tag->name ? $tag->name : Str::ucfirst($tag->name) }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Variant Selection -->
                        @if ($product->type === 'variable' && $product->variants->isNotEmpty())
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Select Variant:</h3>
                                <div id="variant-options" class="flex flex-wrap gap-2">
                                    @foreach ($product->variants as $variant)
                                        <div class="variant-option-wrapper">
                                            <input type="radio" name="variant_id" id="variant-{{ $variant->id }}" value="{{ $variant->id }}" class="sr-only variant-radio"
                                                   data-price="{{ $variant->sale_price ?? $variant->price }}"
                                                   data-stock="{{ $variant->stock_quantity }}"
                                                   data-stock-status="{{ $variant->stock_status }}"
                                                   @if ($variant->stock_status === 'out_of_stock') disabled @endif>
                                            <label for="variant-{{ $variant->id }}" class="variant-label cursor-pointer block border border-gray-300 rounded-md p-3 text-center transition-all duration-200">
                                                <span class="variant-name text-sm font-medium text-gray-800">
                                                    {{ $variant->attributes_list }}
                                                </span>
                                                <span class="variant-price text-xs text-gray-500 block mt-1">
                                                    {{ format_taka($variant->sale_price ?? $variant->price) }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Stock Status -->
                        <div class="mb-4">
                            <span class="font-semibold text-gray-700">Availability: </span>
                            <span id="stock-status-display" class="font-medium">
                                @if ($product->type === 'simple')
                                    @if($product->stock_quantity > 0)
                                        <span class="text-green-600">In Stock ({{ $product->stock_quantity }} items)</span>
                                    @else
                                        <span class="text-red-600">Out of Stock</span>
                                    @endif
                                @else
                                    <span class="text-gray-600">Select a variant to see availability</span>
                                @endif
                            </span>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="flex items-center mb-6">
                            <label for="quantity" class="font-semibold text-gray-700 mr-3">Quantity:</label>
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button id="decrement-quantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-md" @if (($product->type === 'simple' && $product->stock_quantity <= 0) || $product->type === 'variable') disabled @endif>-</button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-16 text-center border-l border-r border-gray-300 focus:outline-none" @if (($product->type === 'simple' && $product->stock_quantity <= 0) || $product->type === 'variable') disabled @endif>
                                <button id="increment-quantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-md" @if (($product->type === 'simple' && $product->stock_quantity <= 0) || $product->type === 'variable') disabled @endif>+</button>
                            </div>
                        </div>

                        <div id="action-buttons" class="hidden md:flex space-x-4 mb-6">
                            <button type="button" id="add-to-cart-btn" class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200" @if($product->type === 'simple' && $product->stock_quantity <= 0) disabled @endif>
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Add to Cart
                            </button>
                            <button type="button" id="buy-now-btn" class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200" @if($product->type === 'simple' && $product->stock_quantity <= 0) disabled @endif>
                                Buy Now
                            </button>
                            <button type="button" class="add-to-wishlist bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200" data-product-id="{{ $product->id }}">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </div>
                        <div id="action-buttons-mobile" class="flex space-x-4 mb-6 md:hidden">
                            <button type="button" id="add-to-cart-btn-mobile" class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200" @if($product->type === 'simple' && $product->stock_quantity <= 0) disabled @endif>
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            <button type="button" id="buy-now-btn-mobile" class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200" @if($product->type === 'simple' && $product->stock_quantity <= 0) disabled @endif>
                                <i class="fas fa-bolt"></i> Buy Now
                            </button>
                            <button type="button" class="add-to-wishlist bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200" data-product-id="{{ $product->id }}">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </div>

                        <!-- Share Buttons -->
                        <div class="flex items-center space-x-3 text-gray-600">
                            <span>Share:</span>
                            <a href="#" class="hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="hover:text-red-600"><i class="fab fa-pinterest-p"></i></a>
                            <a href="#" class="hover:text-green-600"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Product Details Tabs -->
                <div class="mt-10">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button class="tab-button border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none active-tab" data-tab="description">Description</button>
                            <button class="tab-button border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" data-tab="technical">Technical Details</button>
                            <button class="tab-button border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" data-tab="shipping">Shipping</button>
                            <button class="tab-button border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" data-tab="reviews">Reviews ({{ $product->reviews_count }})</button>
                        </nav>
                    </div>

                    <div id="tab-content" class="mt-6">
                        <div id="description" class="tab-pane active-pane prose max-w-none text-gray-700">
                            {!! $product->description !!}
                        </div>
                        <div id="technical" class="tab-pane hidden prose max-w-none text-gray-700">
                            <h3 class="text-lg font-semibold mb-2">Technical Specifications</h3>
                            @if($product->attributes)
                                <ul class="list-disc list-inside">
                                    @foreach(json_decode($product->attributes, true) as $key => $value)
                                        <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No technical details available.</p>
                            @endif
                            <h3 class="text-lg font-semibold mt-4 mb-2">Dimensions</h3>
                            <ul class="list-disc list-inside">
                                <li><strong>Weight:</strong> {{ $product->weight ?? 'N/A' }}</li>
                                <li><strong>Length:</strong> {{ $product->length ?? 'N/A' }}</li>
                                <li><strong>Width:</strong> {{ $product->width ?? 'N/A' }}</li>
                                <li><strong>Height:</strong> {{ $product->height ?? 'N/A' }}</li>
                            </ul>
                        </div>
                        <div id="shipping" class="tab-pane hidden prose max-w-none text-gray-700">
                            <h3 class="text-lg font-semibold mb-2">Shipping Information</h3>
                            <p>Standard shipping usually takes 3-5 business days. Express shipping options are available at checkout.</p>
                            <p>Please note that shipping times may vary based on your location and product availability.</p>
                            <p>For international shipping, additional customs duties and taxes may apply.</p>
                        </div>
                        <div id="reviews" class="tab-pane hidden">
                            <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>
                            @if ($product->reviews->isNotEmpty())
                                <div class="space-y-6">
                                    @foreach ($product->reviews as $review)
                                        <div class="border-b pb-4">
                                            <div class="flex items-center mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3.921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784.57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                                                @endfor
                                                <span class="ml-2 text-sm text-gray-600 font-semibold">{{ $review->user->name ?? 'Anonymous' }}</span>
                                                <span class="ml-2 text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <p class="text-gray-700">{{ $review->comment ?? 'No comment provided.' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600">No reviews yet. Be the first to review this product!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right Column) -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Similar Products / Cross-Sell -->
                @if ($product->relatedProducts->isNotEmpty())
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Related Products</h3>
                        <div class="space-y-4">
                            @foreach ($product->relatedProducts as $relatedProduct)
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('products.show.frontend', $relatedProduct->slug) }}">
                                        <img src="{{ $relatedProduct->thumbnail_url ?? asset('images/default-product.jpg') }}" alt="{{ $relatedProduct->name }}" class="w-20 h-20 object-cover rounded-md">
                                    </a>
                                    <div>
                                        <a href="{{ route('products.show.frontend', $relatedProduct->slug) }}" class="text-gray-800 hover:text-blue-600 font-medium">{{ $relatedProduct->name }}</a>
                                        <p class="text-gray-600">{{ format_taka($relatedProduct->price) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($product->crossSellingProducts->isNotEmpty())
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Cross-Selling Products</h3>
                        <div class="space-y-4">
                            @foreach ($product->crossSellingProducts as $crossSellingProduct)
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('products.show.frontend', $crossSellingProduct->slug) }}">
                                        <img src="{{ $crossSellingProduct->thumbnail_url ?? asset('images/default-product.jpg') }}" alt="{{ $crossSellingProduct->name }}" class="w-20 h-20 object-cover rounded-md">
                                    </a>
                                    <div>
                                        <a href="{{ route('products.show.frontend', $crossSellingProduct->slug) }}" class="text-gray-800 hover:text-blue-600 font-medium">{{ $crossSellingProduct->name }}</a>
                                        <p class="text-gray-600">{{ format_taka($crossSellingProduct->price) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recently Viewed Products (Placeholder) -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Recently Viewed</h3>
                    <div class="space-y-4 text-gray-500">
                        <p>No recently viewed products.</p>
                        <!-- Example structure for dynamic content -->
                        {{--
                        <div class="flex items-center space-x-4">
                            <a href="#">
                                <img src="https://via.placeholder.com/80" alt="Product Name" class="w-20 h-20 object-cover rounded-md">
                            </a>
                            <div>
                                <a href="#" class="text-gray-800 hover:text-blue-600 font-medium">Another Product</a>
                                <p class="text-gray-600">$49.99</p>
                            </div>
                        </div>
                        --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productType = '{{ $product->type }}';
        const productId = {{ $product->id }};

        // Pixel ViewContent Events
        const productData = {
            id: '{{ $product->id }}',
            name: '{{ $product->name }}',
            price: {{ $product->price }},
            category: '{{ $product->categories->first()->name ?? 'N/A' }}'
        };

        // Meta Pixel ViewContent
        if (typeof fbq === 'function') {
            fbq('track', 'ViewContent', {
                content_ids: [productData.id],
                content_name: productData.name,
                content_type: 'product',
                value: productData.price,
                currency: 'BDT'
            });
        }

        // Google Tag ViewContent
        if (typeof gtag === 'function') {
            gtag('event', 'view_item', {
                items: [{
                    item_id: productData.id,
                    item_name: productData.name,
                    price: productData.price,
                    currency: 'BDT',
                    item_category: productData.category
                }]
            });
        }

        // TikTok Pixel ViewContent
        if (typeof ttq === 'object' && typeof ttq.track === 'function') {
            ttq.track('ViewContent', {
                content_id: productData.id,
                content_name: productData.name,
                content_type: 'product',
                value: productData.price,
                currency: 'BDT'
            });
        }

        const mainProductImage = document.getElementById('mainProductImage');
        const thumbnailImages = document.querySelectorAll('.thumbnail-image');
        const quantityInput = document.getElementById('quantity');
        const incrementButton = document.getElementById('increment-quantity');
        const decrementButton = document.getElementById('decrement-quantity');
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');
        const variantRadios = document.querySelectorAll('.variant-radio');
        const priceDisplay = document.getElementById('product-price');
        const salePriceDisplay = document.getElementById('product-sale-price');
        const stockStatusDisplay = document.getElementById('stock-status-display');
        const actionButtons = document.getElementById('action-buttons');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const buyNowBtn = document.getElementById('buy-now-btn');
        const addToCartBtnMobile = document.getElementById('add-to-cart-btn-mobile');
        const buyNowBtnMobile = document.getElementById('buy-now-btn-mobile');

        // --- IMAGE GALLERY ---
        thumbnailImages.forEach(thumbnail => {
            thumbnail.addEventListener('click', function () {
                mainProductImage.src = this.dataset.src;
            });
        });

        // --- TABS ---
        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                tabButtons.forEach(btn => btn.classList.remove('active-tab', 'border-blue-500', 'text-blue-600'));
                tabPanes.forEach(pane => pane.classList.add('hidden'));
                this.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                document.getElementById(this.dataset.tab).classList.remove('hidden');
            });
        });

        // --- QUANTITY SELECTOR ---
        function updateQuantity(change) {
            const currentValue = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.max);
            const min = parseInt(quantityInput.min);
            let newValue = currentValue + change;

            if (newValue > max) newValue = max;
            if (newValue < min) newValue = min;

            quantityInput.value = newValue;
        }

        if (incrementButton && decrementButton && quantityInput) {
            incrementButton.addEventListener('click', () => updateQuantity(1));
            decrementButton.addEventListener('click', () => updateQuantity(-1));
            quantityInput.addEventListener('change', () => {
                updateQuantity(0);
            });
        }

        // --- VARIANT SELECTION ---
        function updateProductDisplay(radio) {
            if (!radio) return;

            const price = radio.dataset.price;
            const stock = parseInt(radio.dataset.stock);
            const stockStatus = radio.dataset.stockStatus;

            priceDisplay.textContent = `৳${parseInt(price)}`;
            salePriceDisplay.textContent = '';

            if (stockStatus === 'in_stock') {
                stockStatusDisplay.innerHTML = `<span class="text-green-600">In Stock (${stock} items)</span>`;
                actionButtons.classList.remove('hidden');
            } else if (stockStatus === 'out_of_stock') {
                stockStatusDisplay.innerHTML = `<span class="text-red-600">Out of Stock</span>`;
                actionButtons.classList.add('hidden');
            } else {
                stockStatusDisplay.innerHTML = `<span class="text-yellow-600">Backorder</span>`;
                actionButtons.classList.remove('hidden');
            }

            quantityInput.max = stock;
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = 1;
            }

            const isOutOfStock = (stockStatus === 'out_of_stock');
            addToCartBtn.disabled = isOutOfStock;
            buyNowBtn.disabled = isOutOfStock;
            quantityInput.disabled = isOutOfStock;
            incrementButton.disabled = isOutOfStock;
            decrementButton.disabled = isOutOfStock;
        }

        variantRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                updateProductDisplay(this);
            });
        });

        // --- INITIAL STATE ---
        if (productType === 'variable') {
            const firstSelectedVariant = document.querySelector('.variant-radio:checked');
            if (firstSelectedVariant) {
                updateProductDisplay(firstSelectedVariant);
            }
        }

        // --- CART ACTIONS ---
        function addToCart(buttonElement, isBuyNow = false) {
            const selectedVariantRadio = document.querySelector('.variant-radio:checked');
            const variantId = productType === 'variable' ? (selectedVariantRadio ? selectedVariantRadio.value : null) : null;

            if (productType === 'variable' && !variantId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select a variant first!',
                });
                return;
            }

            const quantity = parseInt(quantityInput.value);
            const action = isBuyNow ? 'buy_now' : 'add_to_cart';
            const originalText = buttonElement.innerHTML;
            buttonElement.innerHTML = `
                <svg class="w-4 h-4 animate-spin text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                ${isBuyNow ? 'Redirecting...' : 'Adding...'}
            `;
            buttonElement.disabled = true;

            // Fetch product details for pixel events
            fetch('/products/' + productId + '/brief')
                .then(response => response.json())
                .then(productBrief => {
                    const itemPrice = productBrief.price;

                    // Meta Pixel AddToCart
                    if (typeof fbq === 'function') {
                        fbq('track', 'AddToCart', {
                            content_ids: [productBrief.id],
                            content_name: productBrief.name,
                            content_type: 'product',
                            value: itemPrice * quantity,
                            currency: 'BDT'
                        });
                    }

                    // Google Tag AddToCart
                    if (typeof gtag === 'function') {
                        gtag('event', 'add_to_cart', {
                            items: [{
                                item_id: productBrief.id,
                                item_name: productBrief.name,
                                price: itemPrice,
                                currency: 'BDT',
                                quantity: quantity
                            }]
                        });
                    }

                    // TikTok Pixel AddToCart
                    if (typeof ttq === 'object' && typeof ttq.track === 'function') {
                        ttq.track('AddToCart', {
                            content_id: productBrief.id,
                            content_name: productBrief.name,
                            content_type: 'product',
                            value: itemPrice * quantity,
                            currency: 'BDT'
                        });
                    }

                    // Proceed with adding to cart via AJAX
                    return fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ product_id: productId, quantity: quantity, variant_id: variantId, action: action })
                    });
                })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (isBuyNow) {
                        window.location.href = '{{ route('checkout.index') }}';
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Added to Cart!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    updateCartCount(data.cart_count);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message || 'Could not add to cart.',
                    });
                }
            })
            .catch(error => {
                console.error('Error with cart action:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred.',
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

        addToCartBtn.addEventListener('click', function() {
            addToCart(this, false);
        });

        buyNowBtn.addEventListener('click', function() {
            addToCart(this, true);
        });

        if (addToCartBtnMobile) {
            addToCartBtnMobile.addEventListener('click', function() {
                addToCart(this, false);
            });
        }

        if (buyNowBtnMobile) {
            buyNowBtnMobile.addEventListener('click', function() {
                addToCart(this, true);
            });
        }

        // --- IMAGE ZOOM ---
        const zoomOverlay = document.getElementById('zoomOverlay');
        const modal = document.createElement('div');
        modal.id = 'imageZoomModal';
        modal.classList.add('fixed', 'inset-0', 'bg-black', 'bg-opacity-75', 'flex', 'items-center', 'justify-center', 'z-50', 'hidden');
        modal.innerHTML = `
            <div class="relative">
                <button id="closeZoomModal" class="absolute top-2 right-2 text-white text-3xl font-bold leading-none hover:text-gray-300">&times;</button>
                <img src="" alt="Product Zoom" class="max-w-full max-h-screen object-contain" id="zoomedImage">
            </div>
        `;
        document.body.appendChild(modal);

        const zoomedImage = document.getElementById('zoomedImage');
        const closeZoomModal = document.getElementById('closeZoomModal');

        function openZoomModal(src) {
            zoomedImage.src = src;
            modal.classList.remove('hidden');
        }

        function closeZoomModalFunc() {
            modal.classList.add('hidden');
            zoomedImage.src = ''; // Clear image source
        }

        zoomOverlay.addEventListener('click', function() {
            const fullSrc = mainProductImage.dataset.fullSrc;
            openZoomModal(fullSrc);
        });

        thumbnailImages.forEach(thumbnail => {
            thumbnail.addEventListener('click', function () {
                mainProductImage.src = this.dataset.src;
                mainProductImage.dataset.fullSrc = this.dataset.fullSrc; // Update full-src for main image
            });
        });

        closeZoomModal.addEventListener('click', closeZoomModalFunc);
        modal.addEventListener('click', function(event) {
            if (event.target === modal) { // Close only if clicking on the overlay, not the image
                closeZoomModalFunc();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeZoomModalFunc();
            }
        });
    });
</script>
<!-- Font Awesome for social share icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('styles')
<style>
    .variant-radio:checked + .variant-label {
        border-color: #3b82f6; /* blue-500 */
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    .variant-radio:disabled + .variant-label {
        cursor: not-allowed;
        background-color: #f3f4f6; /* gray-100 */
        opacity: 0.7;
    }
    .variant-radio:disabled + .variant-label .variant-name {
        text-decoration: line-through;
    }
</style>
@endpush
