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
                            <img id="mainProductImage" src="{{ asset($product->thumbnail ?? 'images/default-product.jpg') }}" alt="{{ $product->name }}" class="w-full h-96 object-contain rounded-lg shadow-sm cursor-zoom-in">
                            <!-- Zoom overlay (optional, can be more complex with JS) -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-opacity duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m0 0h-3"></path></svg>
                            </div>
                        </div>
                        @if ($product->images && count(json_decode($product->images, true)) > 0)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach (json_decode($product->images, true) as $image)
                                    <img src="{{ asset($image) }}" alt="{{ $product->name }} thumbnail" class="w-full h-24 object-cover rounded-md cursor-pointer border-2 border-transparent hover:border-blue-500 transition-colors duration-200 thumbnail-image" data-src="{{ asset($image) }}">
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
                            <p class="text-xl font-bold text-gray-900" id="product-price">
                                BDT {{ number_format($product->final_price, 2) }}
                            </p>
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <p class="text-sm text-gray-500 line-through" id="original-price">
                                    BDT {{ number_format($product->price, 2) }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 line-through hidden" id="original-price"></p>
                            @endif
                        </div>

                        <p class="text-gray-700 mb-4">{!! $product->short_description !!}</p>

                        <!-- Stock Status -->
                        <div class="mb-4">
                            <span class="font-semibold text-gray-700">Availability: </span>
                            <span id="stock-status" class="font-medium">
                                @if($product->stock_quantity > 0)
                                    <span class="text-green-600">In Stock (<span id="stock-quantity">{{ $product->stock_quantity }}</span> items)</span>
                                @else
                                    <span class="text-red-600">Out of Stock</span>
                                @endif
                            </span>
                        </div>

                        <!-- Variant Selection -->
                        @if($product->variants->isNotEmpty())
                            <div class="mb-4">
                                @php
                                    $groupedAttributes = [];
                                    foreach ($product->variants as $variant) {
                                        foreach ($variant->attributeValues as $attributeValue) {
                                            $attributeName = $attributeValue->attribute->name;
                                            $attributeValueName = $attributeValue->value;
                                            if (!isset($groupedAttributes[$attributeName])) {
                                                $groupedAttributes[$attributeName] = [];
                                            }
                                            if (!in_array($attributeValueName, $groupedAttributes[$attributeName])) {
                                                $groupedAttributes[$attributeName][] = $attributeValueName;
                                            }
                                        }
                                    }
                                @endphp

                                @foreach($groupedAttributes as $attributeName => $attributeValues)
                                    <div class="mb-3">
                                        <label class="font-semibold text-gray-700 block mb-2">{{ $attributeName }}:</label>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($attributeValues as $value)
                                                <button type="button"
                                                        class="attribute-selector px-4 py-2 border border-gray-300 rounded-md text-sm hover:bg-gray-100 data-[selected=true]:bg-blue-600 data-[selected=true]:text-white data-[selected=true]:border-blue-600 transition-colors duration-200"
                                                        data-attribute-name="{{ $attributeName }}"
                                                        data-attribute-value="{{ $value }}">
                                                    {{ $value }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Quantity Selector -->
                        <div class="flex items-center mb-6">
                            <label for="quantity" class="font-semibold text-gray-700 mr-3">Quantity:</label>
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button id="decrement-quantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-md">-</button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-16 text-center border-l border-r border-gray-300 focus:outline-none">
                                <button id="increment-quantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-md">+</button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4 mb-6">
                            <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" id="selected-variant-id" value="{{ $product->variants->first()->id ?? '' }}">
                                <input type="hidden" name="quantity" id="cart-quantity" value="1">
                                <input type="hidden" name="action" value="add_to_cart">
                                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 014 0zM10 7v3m0 0v3m0-3h3m0 0h-3"></path></svg>
                                    Add to Cart
                                </button>
                            </form>
                            <form id="buy-now-form" action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="variant_id" id="buy-now-selected-variant-id" value="{{ $product->variants->first()->id ?? '' }}">
                                <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
                                <input type="hidden" name="action" value="buy_now">
                                <button type="submit" class="bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200">
                                    Buy Now
                                </button>
                            </form>
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

                            <div class="mt-8">
                                <h4 class="text-md font-semibold mb-3">Submit Your Review</h4>
                                @auth
                                    <form action="{{ route('products.reviews.store', $product->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="rating" class="block text-sm font-medium text-gray-700">Your Rating</label>
                                            <select id="rating" name="rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                <option value="5">5 Stars - Excellent</option>
                                                <option value="4">4 Stars - Very Good</option>
                                                <option value="3">3 Stars - Good</option>
                                                <option value="2">2 Stars - Fair</option>
                                                <option value="1">1 Star - Poor</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="comment" class="block text-sm font-medium text-gray-700">Your Review</label>
                                            <textarea id="comment" name="comment" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                        </div>
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Submit Review
                                        </button>
                                    </form>
                                @else
                                    <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to submit a review.</p>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right Column) -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Similar Products / Cross-Sell -->
                @if ($relatedProducts->isNotEmpty())
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Similar Products</h3>
                        <div class="space-y-4">
                            @foreach ($relatedProducts as $relatedProduct)
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('products.show.frontend', $relatedProduct->slug) }}">
                                        <img src="{{ asset($relatedProduct->thumbnail ?? 'images/default-product.jpg') }}" alt="{{ $relatedProduct->name }}" class="w-20 h-20 object-cover rounded-md">
                                    </a>
                                    <div>
                                        <a href="{{ route('products.show.frontend', $relatedProduct->slug) }}" class="text-gray-800 hover:text-blue-600 font-medium">{{ $relatedProduct->name }}</a>
                                        <p class="text-gray-600">BDT {{ number_format($relatedProduct->price, 2) }}</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Image Gallery
            const mainProductImage = document.getElementById('mainProductImage');
            const thumbnailImages = document.querySelectorAll('.thumbnail-image');

            thumbnailImages.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    mainProductImage.src = this.dataset.src;
                });
            });

            // Quantity Selector
            const quantityInput = document.getElementById('quantity');
            const incrementButton = document.getElementById('increment-quantity');
            const decrementButton = document.getElementById('decrement-quantity');

            if (quantityInput && incrementButton && decrementButton) {
                incrementButton.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue < parseInt(quantityInput.max)) {
                        quantityInput.value = currentValue + 1;
                    }
                    updateCartQuantity();
                });

                decrementButton.addEventListener('click', function() {
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue > parseInt(quantityInput.min)) {
                        quantityInput.value = currentValue - 1;
                    }
                    updateCartQuantity();
                });

                quantityInput.addEventListener('change', function() {
                    updateCartQuantity();
                });
            }

            function updateCartQuantity() {
                const currentQuantity = quantityInput.value;
                document.getElementById('cart-quantity').value = currentQuantity;
                document.getElementById('buy-now-quantity').value = currentQuantity;
            }

            // Tabs functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Deactivate all tabs and panes
                    tabButtons.forEach(btn => btn.classList.remove('active-tab', 'border-blue-500', 'text-blue-600'));
                    tabPanes.forEach(pane => pane.classList.add('hidden'));

                    // Activate clicked tab and corresponding pane
                    this.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                    const targetTab = this.dataset.tab;
                    document.getElementById(targetTab).classList.remove('hidden');
                });
            });

            // Activate the first tab by default
            if (tabButtons.length > 0) {
                tabButtons[0].classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                tabPanes[0].classList.remove('hidden');
            }

            // Variant Selection Logic
            const productVariants = @json($product->variants->keyBy('id'));
            const attributeSelectors = document.querySelectorAll('.attribute-selector');
            const selectedAttributes = {};
            const productPriceEl = document.getElementById('product-price');
            const originalPriceEl = document.getElementById('original-price');
            const stockStatusEl = document.getElementById('stock-status');
            const stockQuantityEl = document.getElementById('stock-quantity');
            const selectedVariantIdInput = document.getElementById('selected-variant-id');
            const buyNowSelectedVariantIdInput = document.getElementById('buy-now-selected-variant-id');
            const addToCartButton = document.querySelector('#add-to-cart-form button[type="submit"]');
            const buyNowButton = document.querySelector('#buy-now-form button[type="submit"]');

            // Initialize selected attributes based on the first variant if available
            @if($product->variants->isNotEmpty())
                const firstVariant = productVariants[{{ $product->variants->first()->id }}];
                firstVariant.attribute_values.forEach(attrVal => {
                    selectedAttributes[attrVal.attribute.name] = attrVal.value;
                });
                updateVariantDisplay(firstVariant);
            @else
                // For simple products, ensure buttons are enabled and variant_id is empty
                addToCartButton.disabled = false;
                buyNowButton.disabled = false;
                selectedVariantIdInput.value = '';
                buyNowSelectedVariantIdInput.value = '';
            @endif


            attributeSelectors.forEach(selector => {
                selector.addEventListener('click', function() {
                    const attributeName = this.dataset.attributeName;
                    const attributeValue = this.dataset.attributeValue;

                    // Deselect other buttons for the same attribute
                    document.querySelectorAll(`.attribute-selector[data-attribute-name="${attributeName}"]`).forEach(btn => {
                        btn.dataset.selected = 'false';
                    });

                    // Select the clicked button
                    this.dataset.selected = 'true';
                    selectedAttributes[attributeName] = attributeValue;

                    findMatchingVariant();
                });
            });

            function findMatchingVariant() {
                let matchedVariant = null;
                for (const variantId in productVariants) {
                    const variant = productVariants[variantId];
                    let isMatch = true;
                    const variantAttributeMap = {};
                    variant.attribute_values.forEach(attrVal => {
                        variantAttributeMap[attrVal.attribute.name] = attrVal.value;
                    });

                    for (const attrName in selectedAttributes) {
                        if (selectedAttributes[attrName] !== variantAttributeMap[attrName]) {
                            isMatch = false;
                            break;
                        }
                    }

                    if (isMatch) {
                        matchedVariant = variant;
                        break;
                    }
                }
                updateVariantDisplay(matchedVariant);
            }

            function updateVariantDisplay(variant) {
                if (variant) {
                    productPriceEl.textContent = `BDT ${formatCurrency(variant.final_price)}`;
                    if (variant.sale_price && variant.sale_price < variant.price) {
                        originalPriceEl.textContent = `BDT ${formatCurrency(variant.price)}`;
                        originalPriceEl.classList.remove('hidden');
                    } else {
                        originalPriceEl.classList.add('hidden');
                    }

                    stockQuantityEl.textContent = variant.stock_quantity;
                    if (variant.stock_quantity > 0) {
                        stockStatusEl.innerHTML = `<span class="text-green-600">In Stock (<span id="stock-quantity">${variant.stock_quantity}</span> items)</span>`;
                        quantityInput.max = variant.stock_quantity;
                        addToCartButton.disabled = false;
                        buyNowButton.disabled = false;
                    } else {
                        stockStatusEl.innerHTML = `<span class="text-red-600">Out of Stock</span>`;
                        quantityInput.max = 0;
                        quantityInput.value = 0;
                        addToCartButton.disabled = true;
                        buyNowButton.disabled = true;
                    }
                    selectedVariantIdInput.value = variant.id;
                    buyNowSelectedVariantIdInput.value = variant.id;
                } else {
                    // No matching variant found
                    productPriceEl.textContent = `BDT {{ number_format($product->final_price, 2) }}`; // Fallback to product base price
                    originalPriceEl.classList.add('hidden');
                    stockStatusEl.innerHTML = `<span class="text-red-600">No matching variant available</span>`;
                    stockQuantityEl.textContent = 0;
                    quantityInput.max = 0;
                    quantityInput.value = 0;
                    addToCartButton.disabled = true;
                    buyNowButton.disabled = true;
                    selectedVariantIdInput.value = ''; // Clear variant ID
                    buyNowSelectedVariantIdInput.value = '';
                }
                // Ensure quantity input doesn't exceed new max
                if (parseInt(quantityInput.value) > parseInt(quantityInput.max)) {
                    quantityInput.value = quantityInput.max > 0 ? 1 : 0;
                }
                updateCartQuantity();
            }

            function formatCurrency(amount) {
                return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }
        });
    </script>
    <!-- Font Awesome for social share icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
