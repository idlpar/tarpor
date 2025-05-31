@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Search Results for "{{ $query }}"</h1>
            <p class="text-gray-600">{{ $products->total() }} results found</p>
        </div>

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
                        </div>
                        <div class="p-4">
                            <a href="{{ route('product.view', $product->slug) }}"
                               class="font-semibold text-gray-800 hover:text-primary-500 line-clamp-2 mb-1">
                                {{ $product->name }}
                            </a>

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
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->appends(['q' => $query])->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-gray-500">Try different search terms or browse our categories</p>
                <div class="mt-6">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-500 hover:bg-primary-600">
                        Browse Shop
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
