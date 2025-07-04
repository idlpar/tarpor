<div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
    <div class="aspect-w-1 aspect-h-1 bg-gray-200 overflow-hidden">
        @if($product->thumbnail)
            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-48 object-cover group-hover:opacity-75 transition-opacity duration-200">
        @elseif($product->images && count($product->images) > 0)
            <img src="{{ asset('storage/' . $product->images[0]) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-48 object-cover group-hover:opacity-75 transition-opacity duration-200">
        @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
    </div>
    <div class="p-4">
        <h3 class="text-sm font-medium text-gray-900">
            <a href="{{ route('products.show.frontend', $product->slug) }}">
                <span aria-hidden="true" class="absolute inset-0"></span>
                {{ Str::limit($product->name, 50) }}
            </a>
        </h3>
        <div class="mt-2 flex justify-between items-center">
            <p class="text-sm font-semibold text-gray-900">
                @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-gray-500 text-xs line-through">${{ number_format($product->price, 2) }}</span>
                @else
                    ${{ number_format($product->price, 2) }}
                @endif
            </p>
            @if($product->is_featured)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
            @endif
        </div>
        <div class="mt-2">
            @if($product->stock_quantity > 0)
                <p class="text-xs text-green-600">In Stock</p>
            @else
                <p class="text-xs text-red-600">Out of Stock</p>
            @endif
        </div>
    </div>
</div>
