@extends('layouts.app')

@section('head')
    <!-- SEO Meta Tags -->
    <title>{{ $product->name }} | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $product->seo->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="keywords" content="{{ $product->seo->meta_keywords ?? ($product->categories->isNotEmpty() ? $product->categories->pluck('name')->implode(', ') : $product->name) }}">
    <meta property="og:title" content="{{ $product->seo->og_title ?? $product->name }}">
    <meta property="og:description" content="{{ $product->seo->og_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image" content="{{ asset(optional($product->seo)->og_image ?? $product->thumbnail_url ?? 'images/default-product.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->seo->twitter_title ?? $product->name }}">
    <meta name="twitter:description" content="{{ $product->seo->twitter_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="twitter:image" content="{{ asset($product->seo->twitter_image ?? $product->thumbnail_url ?? 'images/default-product.jpg') }}">
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <!-- Breadcrumbs -->
        <div class="container mx-auto px-4">
            @include('components.breadcrumbs', ['links' => $breadcrumbs])
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 mt-6">
            <!-- Header with Actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $product->trashed() ? 'bg-red-100 text-red-800' :
                               ($product->status === 'published' ? 'bg-green-100 text-green-800' :
                               ($product->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ $product->trashed() ? 'Trashed' : ucfirst($product->status) }}
                        </span>
                        @if($product->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Featured
                        </span>
                        @endif
                    </div>
                </div>

                @canany(['update', 'delete'], $product)
                    <div class="flex flex-wrap gap-3">
                        @if($product->status === 'published')
                            <a href="{{ route('products.show.frontend', $product->slug) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                View on Frontend
                            </a>
                        @endif
                        @can('update', $product)
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Edit
                            </a>
                        @endcan

                        @can('delete', $product)
                            @if (!$product->trashed())
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('products.restore', $product->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-13.357-2M9 9H4.582A8.001 8.001 0 0120.581 11m-8.581 2H20"/>
                                        </svg>
                                        Restore
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>
                @endcanany
            </div>

            <!-- Product Details Card -->
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <!-- Product Images & Basic Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 border-b">
                    <!-- Image Gallery -->
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center h-96">
                            <img src="{{ $product->thumbnail_url }}"
                                 alt="{{ $product->name }}"
                                 class="max-h-full max-w-full object-contain"
                                 id="main-product-image"
                                 loading="lazy">
                        </div>

                        @if ($product->gallery_images->isNotEmpty())
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($product->gallery_images as $image)
                                    <div class="bg-gray-50 rounded-md overflow-hidden h-24 cursor-pointer product-thumbnail">
                                        <img src="{{ $image }}"
                                             alt="{{ $product->name }} image"
                                             class="w-full h-full object-cover"
                                             loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-900">Product Information</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                    <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                        {{ $product->sku ?? 'N/A' }}
                                        @if($product->sku)
                                            <button data-copy-sku class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->barcode ?? 'N/A' }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-gray-900">Pricing</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">
                                        {{ format_taka($product->price ?? 0) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sale Price</dt>
                                    <dd class="mt-1 text-sm font-medium
                                        {{ $product->sale_price ? 'text-green-600' : 'text-gray-900' }}">
                                        {{ $product->sale_price ? format_taka($product->sale_price) : 'N/A' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $product->cost_price ? format_taka($product->cost_price) : 'N/A' }}
                                    </dd>
                                </div>
                                @if($product->discount)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Discount</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ $product->discount }}%
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-gray-900">Inventory</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->stock_quantity }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800' :
                                               ($product->stock_status === 'out_of_stock' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Inventory Tracking</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $product->inventory_tracking ? 'Enabled' : 'Disabled' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Views</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->views ?? 0 }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Categories & Tags -->
                        <div class="space-y-2">
                            <h3 class="text-sm font-medium text-gray-900">Classification</h3>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Brand</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->brand->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Categories</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if ($product->categories->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($product->categories as $category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $category->name }}
                                        </span>
                                            @endforeach
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tags</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if ($product->tags && $product->tags->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($product->tags as $tag)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $tag->name }}
                                        </span>
                                            @endforeach
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="p-6 space-y-8">
                    <!-- Description -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Dimensions</h3>
                            <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                <div class="border-b border-gray-100 pb-1">
                                    <dt class="text-xs text-gray-500">Weight</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->weight ?? 'N/A' }}</dd>
                                </div>
                                <div class="border-b border-gray-100 pb-1">
                                    <dt class="text-xs text-gray-500">Length</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->length ?? 'N/A' }}</dd>
                                </div>
                                <div class="border-b border-gray-100 pb-1">
                                    <dt class="text-xs text-gray-500">Width</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->width ?? 'N/A' }}</dd>
                                </div>
                                <div class="border-b border-gray-100 pb-1">
                                    <dt class="text-xs text-gray-500">Height</dt>
                                    <dd class="text-sm text-gray-900">{{ $product->height ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Attributes</h3>
                            @if ($product->attributes && is_array($product->attributes) && count($product->attributes) > 0)
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    @foreach ($product->attributes as $key => $value)
                                        <div class="border-b border-gray-100 pb-1">
                                            <dt class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                            <dd class="text-sm text-gray-900">{{ $value }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @else
                                <div class="bg-gray-50 rounded p-3">
                                    <p class="text-xs text-gray-600">N/A</p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Collections</h3>
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $productCollections = collect($product->product_collections ?? []);
                                @endphp
                                @if ($productCollections->isNotEmpty())
                                    @foreach($productCollections as $collection)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst(str_replace('_', ' ', $collection)) }}
                                    </span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Labels</h3>
                            <div class="flex flex-wrap gap-1">
                                @if(collect($product->labels)->isNotEmpty())
                                    @foreach($product->labels as $label)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ ucfirst($label->name) }}
                                    </span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- SEO Metadata -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-900">SEO Metadata</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->meta_title ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->meta_description ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Meta Keywords</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->meta_keywords ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Canonical URL</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->canonical_url ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">OG Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->og_title ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">OG Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->og_description ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">OG Image</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if (optional($product->seo)->og_image)
                                            <img src="{{ asset($product->seo->og_image) }}" alt="OG Image" class="w-32 h-auto rounded-md">
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Twitter Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->twitter_title ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Twitter Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->twitter_description ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Twitter Image</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if (optional($product->seo)->twitter_image)
                                            <img src="{{ asset($product->seo->twitter_image) }}" alt="Twitter Image" class="w-32 h-auto rounded-md">
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Schema Markup</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($product->seo && $product->seo->schema_markup)
                                            <div class="bg-gray-100 p-2 rounded overflow-x-auto">
                                                <pre class="text-xs">{{ json_encode(json_decode($product->seo->schema_markup), JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Robots</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->robots ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Customer Reviews ({{ $product->reviews_count }})</h2>
                    @if($product->reviews_count > 0)
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-500 mr-2">Average Rating:</span>
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                    </svg>
                                @endfor
                                <span class="ml-1 text-sm font-medium text-gray-900">{{ number_format($product->average_rating, 1) }}/5</span>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($product->reviews->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($product->reviews as $review)
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="flex items-center mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">{{ $review->user->name ?? 'Anonymous' }} - {{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-gray-700">{{ $review->comment ?? 'No comment provided.' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Be the first to review this product.</p>
                    </div>
                @endif
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Related Products</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                <a href="{{ route('products.show', $relatedProduct->id) }}" class="block">
                                    <div class="bg-gray-50 h-48 flex items-center justify-center p-4">
                                        <img
                                            src="{{ asset($relatedProduct->thumbnail_url ?? 'images/default-product.jpg') }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="max-h-full max-w-full object-contain"
                                            loading="lazy"
                                        >
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $relatedProduct->name }}</h3>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ format_taka($relatedProduct->price) }}
                                            </span>
                                            @if($relatedProduct->sale_price)
                                                <span class="text-xs line-through text-gray-500">
                                                {{ format_taka($relatedProduct->sale_price) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Delete confirmation with SweetAlert2
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        focusCancel: true,
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200',
                            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 mr-3'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Copy SKU functionality
            document.querySelectorAll('[data-copy-sku]').forEach(button => {
                button.addEventListener('click', function() {
                    const sku = '{{ $product->sku }}';
                    navigator.clipboard.writeText(sku).then(() => {
                        const originalText = button.innerHTML;
                        button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                        setTimeout(() => {
                            button.innerHTML = originalText;
                        }, 2000);
                    });
                });
            });

            // Image gallery interaction
            const mainImage = document.getElementById('main-product-image');
            const thumbnails = document.querySelectorAll('.product-thumbnail img');

            if (thumbnails.length > 0) {
                // Set the first thumbnail as active initially
                thumbnails[0].parentElement.classList.add('ring-2', 'ring-blue-500');

                thumbnails.forEach(thumb => {
                    thumb.parentElement.addEventListener('click', function() {
                        mainImage.src = thumb.src;
                        // Remove active class from all thumbnails
                        thumbnails.forEach(t => t.parentElement.classList.remove('ring-2', 'ring-blue-500'));
                        // Add active class to the clicked thumbnail
                        this.classList.add('ring-2', 'ring-blue-500');
                    });
                });
            }
        });
    </script>
@endpush