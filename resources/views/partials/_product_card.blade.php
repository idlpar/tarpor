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
                 class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-90 product-card-image">
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
        <h3 class="text-sm font-semibold text-gray-900 mb-1.5 truncate">
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
