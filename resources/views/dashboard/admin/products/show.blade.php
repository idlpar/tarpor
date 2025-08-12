@extends('layouts.app')

@section('title', $product->name)

@section('head')
    <!-- SEO Meta Tags -->
    <title>{{ $product->name }} | {{ config('app.name') }}</title>
    <meta name="description"
          content="{{ $product->seo->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="keywords"
          content="{{ $product->seo->meta_keywords ?? ($product->categories->isNotEmpty() ? $product->categories->pluck('name')->implode(', ') : $product->name) }}">
    <meta property="og:title" content="{{ $product->seo->og_title ?? $product->name }}">
    <meta property="og:description"
          content="{{ $product->seo->og_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image"
          content="{{ asset(optional($product->seo)->og_image ?? $product->thumbnail_url ?? 'images/default-product.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->seo->twitter_title ?? $product->name }}">
    <meta name="twitter:description"
          content="{{ $product->seo->twitter_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="twitter:image"
          content="{{ asset($product->seo->twitter_image ?? $product->thumbnail_url ?? 'images/default-product.jpg') }}">

    <!-- Product Schema Markup -->
    @php
        $structuredData = [
            "@context" => "https://schema.org/",
            "@type" => "Product",
            "name" => $product->name,
            "image" => asset($product->thumbnail_url),
            "description" => strip_tags($product->description),
            "sku" => $product->sku,
            "brand" => [
                "@type" => "Brand",
                "name" => $product->brand->name ?? 'Generic'
            ],
            "offers" => [
                "@type" => "Offer",
                "url" => url()->current(),
                "priceCurrency" => config('app.currency', 'USD'),
                "price" => $product->sale_price ?? $product->price,
                "priceValidUntil" => now()->addYear()->format('Y-m-d'),
                "itemCondition" => "https://schema.org/NewCondition",
                "availability" => $product->stock_status === 'in_stock'
                    ? "https://schema.org/InStock"
                    : "https://schema.org/OutOfStock"
            ],
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => $product->average_rating ?? 4.5,
                "reviewCount" => $product->reviews_count ?? 0
            ]
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>


@endsection

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Breadcrumbs -->
        <div class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}"
                               class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Home
                            </a>
                        </li>
                        @foreach($breadcrumbs as $breadcrumb)
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ $breadcrumb['url'] }}"
                                       class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">{{ $breadcrumb['name'] }}</a>
                                </div>
                            </li>
                        @endforeach
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Main Product Section -->
        <div class="container mx-auto px-4 py-8">
            <!-- Product Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <div class="flex flex-wrap gap-2 items-center mt-2">
                        <!-- Status Badges -->
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {!! $product->trashed() ? 'bg-red-100 text-red-800' :
                               ($product->status === 'published' ? 'bg-green-100 text-green-800' :
                               ($product->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) !!}">
                            {{ $product->trashed() ? 'Trashed' : ucfirst($product->status) }}
                        </span>

                        <!-- Featured Badge -->
                        @if($product->is_featured)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Featured
                            </span>
                        @endif

                        <!-- Collection Badges -->
                        @if($product->collections->isNotEmpty())
                            @foreach($product->collections as $collection)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ $collection->name }}
                                </span>
                            @endforeach
                        @endif

                        <!-- Label Badges -->
                        @if($product->labels->isNotEmpty())
                            @foreach($product->labels as $label)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $label->name }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Admin Actions -->
                @canany(['update', 'delete'], $product)
                    <div class="flex flex-wrap gap-3">
                        @if($product->status === 'published')
                            <a href="{{ route('products.show.frontend', $product->slug) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View on Frontend
                            </a>
                        @endif

                        @can('update', $product)
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Edit
                            </a>
                        @endcan

                        @can('delete', $product)
                            @if (!$product->trashed())
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-13.357-2M9 9H4.582A8.001 8.001 0 0120.581 11m-8.581 2H20"/>
                                        </svg>
                                        Restore
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>
                @endcanany
            </div>

            <!-- Product Details -->
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <!-- Product Images & Basic Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6 border-b">
                    <!-- Image Gallery -->
                    <div class="space-y-4">
                        <!-- Main Image with Zoom -->
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center h-96 relative">
                            <div class="zoom-container" style="width: 100%; height: 100%; position: relative;">
                                <img src="{{ $product->thumbnail_url }}"
                                     alt="{{ $product->name }}"
                                     class="max-h-full max-w-full object-contain transition-opacity duration-300 cursor-zoom-in"
                                     id="main-product-image"
                                     data-zoom-image="{{ $product->thumbnail_url }}"
                                     loading="lazy">
                                @if($product->discount)
                                    <span
                                        class="absolute top-4 right-4 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        -{{ $product->discount }}%
                                    </span>
                                @endif
                            </div>

                            <!-- Video Button -->
                            @if($product->video_url)
                                <button type="button"
                                        class="absolute bottom-4 right-4 bg-white rounded-full p-3 shadow-lg hover:bg-gray-100 transition-colors video-modal-trigger"
                                        data-video-url="{{ $product->video_url }}">
                                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <!-- Thumbnail Gallery -->
                        @if ($product->media->isNotEmpty())
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($product->media as $media_item)
                                    <div class="bg-gray-50 rounded-md overflow-hidden h-24 cursor-pointer product-thumbnail transition-all duration-200 hover:ring-2 hover:ring-blue-500
                                        {{ $loop->first ? 'ring-2 ring-blue-500' : '' }}">
                                        <img src="{{ $media_item->url }}"
                                             alt="{{ $product->name }} image"
                                             class="w-full h-full object-cover"
                                             loading="lazy"
                                             data-zoom-image="{{ $media_item->url }}">
                                    </div>
                                @endforeach

                                <!-- Video Thumbnail -->
                                @if($product->video_url)
                                    <div
                                        class="bg-gray-50 rounded-md overflow-hidden h-24 cursor-pointer product-thumbnail transition-all duration-200 hover:ring-2 hover:ring-blue-500 video-modal-trigger"
                                        data-video-url="{{ $product->video_url }}">
                                        <div class="relative w-full h-full flex items-center justify-center bg-black">
                                            <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="absolute bottom-1 left-1 text-xs text-white">Video</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <!-- Rating & Reviews -->
                        <div class="flex items-center">
                            <div class="flex items-center mr-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg
                                        class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                    </svg>
                                @endfor
                                <span
                                    class="ml-2 text-sm font-medium text-gray-900">{{ number_format($product->average_rating, 1) }}</span>
                            </div>
                            <a href="#reviews"
                               class="text-sm font-medium text-blue-600 hover:text-blue-500">{{ $product->reviews_count }}
                                reviews</a>
                            <span class="mx-1 text-gray-300">|</span>
                            <a href="#reviews" class="text-sm font-medium text-blue-600 hover:text-blue-500">Write a
                                review</a>
                        </div>

                        <!-- Pricing Section -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-baseline gap-3">
                                @if($product->sale_price)
                                    <span class="text-3xl font-bold text-gray-900">
                                        {{ format_taka($product->sale_price) }}
                                    </span>
                                    <span class="text-xl line-through text-gray-500">
                                        {{ format_taka($product->price) }}
                                    </span>
                                @else
                                    <span class="text-3xl font-bold text-gray-900">
                                        {{ format_taka($product->price) }}
                                    </span>
                                @endif
                            </div>
                            @if($product->discount)
                                <div class="mt-1 text-sm text-green-600 font-medium">
                                    You save {{ format_taka($product->price - $product->sale_price) }}
                                    ({{ $product->discount }}%)
                                </div>
                            @endif

                            <!-- Price Match -->
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span>Found a lower price? We'll match it!</span>
                            </div>
                        </div>

                        <!-- Inventory Status -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-500 mr-2">Availability:</span>
                                <span class="text-sm font-medium
                                    {{ $product->stock_status === 'in_stock' ? 'text-green-600' :
                                       ($product->stock_status === 'out_of_stock' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                    @if($product->stock_quantity > 0)
                                        ({{ $product->stock_quantity }} available)
                                    @endif
                                </span>
                            </div>

                            <!-- Shipping Info -->
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span
                                    class="text-sm text-gray-500">Ships from {{ $product->warehouse ? $product->warehouse->name : 'our warehouse' }}</span>
                            </div>
                        </div>

                        <!-- Quick Details -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                    {{ $product->sku ?? 'N/A' }}
                                    @if($product->sku)
                                        <button data-copy-sku
                                                class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                        </button>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->barcode ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Brand</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->brand->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $product->cost_price ? format_taka($product->cost_price) : 'N/A' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Min Order</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->min_order_quantity ?? '1' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Max Order</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->max_order_quantity ?? 'No limit' }}</dd>
                            </div>
                            @if($product->tags->isNotEmpty())
                                <div class="col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Tags</dt>
                                    <dd class="mt-1 text-sm text-gray-900 flex flex-wrap gap-2">
                                        @foreach($product->tags as $tag)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ Str::upper($tag->name) === $tag->name ? $tag->name : Str::ucfirst($tag->name) }}
                                            </span>
                                        @endforeach
                                    </dd>
                                </div>
                            @endif
                        </div>

                        <!-- Product Attributes (Options) -->
                        @if(optional($product->productAttributes)->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($product->productAttributes as $attribute)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-1">{{ $attribute->name }}</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(optional($attribute->values)->isNotEmpty() ? $attribute->values : [] as $value)
                                                <button type="button" class="px-3 py-1 border rounded-md text-sm font-medium
                                                    {{ $loop->first ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                                                    {{ $value->value }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Product Variants -->
                        @if($product->type === 'variable' && $product->variants->isNotEmpty())
                            <div class="space-y-2">
                                <h3 class="text-sm font-medium text-gray-900">Variants</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Variant
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                SKU
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Price
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sale Price
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Stock
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($product->variants as $variant)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $variant->attributes_list }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $variant->sku ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ format_taka($variant->price) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $variant->sale_price ? format_taka($variant->sale_price) : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $variant->stock_quantity }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Add to Cart / Buy Now -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button type="button"
                                        class="quantity-btn px-3 py-2 text-gray-600 hover:text-gray-700 focus:outline-none"
                                        data-action="decrease">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" id="quantity" name="quantity" min="1" value="1"
                                       class="w-12 text-center border-0 focus:ring-0 focus:outline-none">
                                <button type="button"
                                        class="quantity-btn px-3 py-2 text-gray-600 hover:text-gray-700 focus:outline-none"
                                        data-action="increase">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>

                            <button type="button"
                                    class="flex-1 bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Add to Cart
                            </button>

                            <button type="button"
                                    class="flex-1 bg-green-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Buy Now
                            </button>
                        </div>

                        <!-- Wishlist & Compare -->
                        <div class="flex items-center space-x-4 pt-2">
                            <button type="button" class="text-gray-500 hover:text-red-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="sr-only">Add to wishlist</span>
                            </button>

                            <button type="button" class="text-gray-500 hover:text-blue-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="sr-only">Add to compare</span>
                            </button>

                            <button type="button" class="text-gray-500 hover:text-gray-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                                <span class="sr-only">Share</span>
                            </button>
                        </div>

                        <!-- Payment Options -->
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Payment & Security</h4>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-xs text-gray-500">SSL Secure</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-xs text-gray-500">Cash on Delivery</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-xs text-gray-500">Easy Returns</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px overflow-x-auto">
                        <button type="button" data-tab="description"
                                class="tab-button active whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                            Description
                        </button>
                        <button type="button" data-tab="specifications"
                                class="tab-button whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Specifications
                        </button>
                        <button type="button" data-tab="pricing-tiers"
                                class="tab-button whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Bulk Pricing
                        </button>
                        <button type="button" data-tab="faqs"
                                class="tab-button whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            FAQs
                        </button>
                        <button type="button" data-tab="reviews"
                                class="tab-button whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Reviews ({{ $product->reviews_count }})
                        </button>
                        <button type="button" data-tab="shipping"
                                class="tab-button whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Shipping & Returns
                        </button>
                        <button type="button" data-tab="seo"
                                class="tab-button whitespace-nowrap py-4 px-6 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            SEO Data
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Description Tab -->
                    <div id="description-tab" class="tab-content active">
                        <div class="prose max-w-none text-gray-700">
                            <h3>Product Description</h3>
                            {!! $product->description !!}
                        </div>
                        @if($product->short_description)
                            <div class="prose max-w-none text-gray-700 mt-6">
                                <h3>Short Description</h3>
                                {!! $product->short_description !!}
                            </div>
                        @endif
                    </div>

                    <!-- Specifications Tab -->
                    <div id="specifications-tab" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">General</h3>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">Brand</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->brand->name ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">Model</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->model ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">SKU</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->sku ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">Barcode</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->barcode ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">Dimensions & Weight</h3>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">Weight</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</dd>
                                    </div>
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">Length</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->length ? $product->length . ' cm' : 'N/A' }}</dd>
                                    </div>
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">Width</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->width ? $product->width . ' cm' : 'N/A' }}</dd>
                                    </div>
                                    <div class="border-b border-gray-100 pb-1">
                                        <dt class="text-xs text-gray-500">Height</dt>
                                        <dd class="text-sm text-gray-900">{{ $product->height ? $product->height . ' cm' : 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Product Attributes -->
                            @if ($product->productAttributes->isNotEmpty())
                                <div class="md:col-span-2">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Features</h3>
                                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                                        @foreach ($product->productAttributes as $attribute)
                                            <div class="border-b border-gray-100 pb-1">
                                                <dt class="text-xs text-gray-500">{{ $attribute->name }}</dt>
                                                <dd class="text-sm text-gray-900">
                                                    @if($attribute->values->isNotEmpty())
                                                        {{ $attribute->values->pluck('value')->implode(', ') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pricing Tiers Tab -->
                    <div id="pricing-tiers-tab" class="tab-content hidden">
                        @if($product->pricingTiers->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Unit Price
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            You Save
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($product->pricingTiers as $tier)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $tier->min_quantity }}+
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ format_taka($tier->price) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                                @if($product->price > $tier->price)
                                                    {{ format_taka($product->price - $tier->price) }}
                                                    ({{ round(($product->price - $tier->price) / $product->price * 100) }}
                                                    %)
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow p-6 text-center">
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No bulk pricing available</h3>
                                <p class="mt-1 text-sm text-gray-500">This product does not have tiered pricing.</p>
                            </div>
                        @endif
                    </div>

                    <!-- FAQs Tab -->
                    <div id="faqs-tab" class="tab-content hidden">
                        @if($product->faqs->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($product->faqs as $faq)
                                    <div class="bg-white rounded-lg shadow p-4">
                                        <h4 class="font-semibold text-gray-900">{{ $faq->question }}</h4>
                                        <p class="text-gray-700 mt-1">{{ $faq->answer }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow p-6 text-center">
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No FAQs found</h3>
                                <p class="mt-1 text-sm text-gray-500">No frequently asked questions have been added for
                                    this product.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Reviews Tab -->
                    <div id="reviews-tab" class="tab-content hidden">
                        @if($product->reviews_count > 0)
                            <div class="flex flex-col md:flex-row md:items-center mb-6">
                                <div class="flex items-center mb-4 md:mb-0 md:mr-8">
                                    <div class="text-center mr-6">
                                        <span
                                            class="text-4xl font-bold text-gray-900">{{ number_format($product->average_rating, 1) }}</span>
                                        <div class="flex justify-center mt-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg
                                                    class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500 mt-1 block">Based on {{ $product->reviews_count }} reviews</span>
                                    </div>

                                    <div class="flex-1">
                                        @for($i = 5; $i >= 1; $i--)
                                            <div class="flex items-center mb-1">
                                                <span
                                                    class="text-sm font-medium text-gray-900 mr-2">{{ $i }} star</span>
                                                <div class="w-32 bg-gray-200 rounded-full h-2.5 mr-2">
                                                    <div class="bg-yellow-400 h-2.5 rounded-full"
                                                         style="width: {{ ($product->reviews->where('rating', $i)->count() / $product->reviews_count) * 100 }}%"></div>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500">{{ $product->reviews->where('rating', $i)->count() }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <button type="button"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Write a Review
                                </button>
                            </div>

                            <div class="space-y-6">
                                @foreach ($product->reviews as $review)
                                    <div class="bg-white rounded-lg shadow p-4">
                                        <div class="flex items-center mb-2">
                                            <div class="flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg
                                                        class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span
                                                class="ml-2 text-sm font-medium text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</span>
                                            <span class="mx-1 text-gray-300">•</span>
                                            <span
                                                class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-1">{{ $review->title }}</h4>
                                        <p class="text-gray-700">{{ $review->comment ?? 'No comment provided.' }}</p>

                                        @if($review->images->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach($review->images as $image)
                                                    <img src="{{ $image->url }}" alt="Review image"
                                                         class="h-20 w-20 object-cover rounded-md cursor-pointer hover:opacity-75 transition-opacity">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Be the first to review this product.</p>
                                <div class="mt-6">
                                    <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Write a Review
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Shipping & Returns Tab -->
                    <div id="shipping-tab" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">Shipping Information</h3>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span class="ml-2 text-sm text-gray-700">Free shipping on orders over $50</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span
                                            class="ml-2 text-sm text-gray-700">Estimated delivery: 2-5 business days</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span
                                            class="ml-2 text-sm text-gray-700">Ships from: {{ $product->warehouse ? $product->warehouse->name : 'our warehouse' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">Return Policy</h3>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span class="ml-2 text-sm text-gray-700">30-day return policy</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span class="ml-2 text-sm text-gray-700">Free returns within 30 days</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span class="ml-2 text-sm text-gray-700">Money-back guarantee</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Tab -->
                    <div id="seo-tab" class="tab-content hidden">
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
                                            <img src="{{ asset($product->seo->og_image) }}" alt="OG Image"
                                                 class="w-32 h-auto rounded-md">
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
                                            <img src="{{ asset($product->seo->twitter_image) }}" alt="Twitter Image"
                                                 class="w-32 h-auto rounded-md">
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Schema Markup</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($product->seo && $product->seo->schema_markup)
                                            <div class="bg-gray-100 p-2 rounded overflow-x-auto">
                                                <pre
                                                    class="text-xs">{{ json_encode(json_decode($product->seo->schema_markup), JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Robots</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->seo->robots ?? 'index, follow' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if ($product->relatedProducts->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">You may also like</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($product->relatedProducts as $relatedProduct)
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200 group">
                                <a href="{{ route('products.show', $relatedProduct->id) }}" class="block">
                                    <div class="bg-gray-50 h-48 flex items-center justify-center p-4 relative">
                                        <img
                                            src="{{ asset($relatedProduct->thumbnail_url ?? 'images/default-product.jpg') }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                        @if($relatedProduct->discount)
                                            <span
                                                class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                -{{ $relatedProduct->discount }}%
                                            </span>
                                        @endif

                                        <!-- Quick View Button -->
                                        <button type="button"
                                                class="absolute bottom-2 left-1/2 transform -translate-x-1/2 bg-white rounded-md px-3 py-1 text-xs font-medium text-gray-900 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            Quick View
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $relatedProduct->name }}</h3>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ format_taka($relatedProduct->sale_price ?? $relatedProduct->price) }}
                                                </span>
                                                @if($relatedProduct->sale_price)
                                                    <span class="text-xs line-through text-gray-500 ml-1">
                                                        {{ format_taka($relatedProduct->price) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor"
                                                     viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 ml-1">{{ number_format($relatedProduct->average_rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Cross-Selling Products -->
            @if ($product->crossSellingProducts->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">You might also be interested in</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($product->crossSellingProducts as $crossSellingProduct)
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200 group">
                                <a href="{{ route('products.show', $crossSellingProduct->id) }}" class="block">
                                    <div class="bg-gray-50 h-48 flex items-center justify-center p-4 relative">
                                        <img
                                            src="{{ asset($crossSellingProduct->thumbnail_url ?? 'images/default-product.jpg') }}"
                                            alt="{{ $crossSellingProduct->name }}"
                                            class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                        @if($crossSellingProduct->discount)
                                            <span
                                                class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                -{{ $crossSellingProduct->discount }}%
                                            </span>
                                        @endif

                                        <!-- Quick View Button -->
                                        <button type="button"
                                                class="absolute bottom-2 left-1/2 transform -translate-x-1/2 bg-white rounded-md px-3 py-1 text-xs font-medium text-gray-900 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            Quick View
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $crossSellingProduct->name }}</h3>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ format_taka($crossSellingProduct->sale_price ?? $crossSellingProduct->price) }}
                                                </span>
                                                @if($crossSellingProduct->sale_price)
                                                    <span class="text-xs line-through text-gray-500 ml-1">
                                                        {{ format_taka($crossSellingProduct->price) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor"
                                                     viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 ml-1">{{ number_format($crossSellingProduct->average_rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            <!-- Recently Viewed Products -->
            @if($recentlyViewed->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Recently Viewed</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($recentlyViewed as $product)
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200 group">
                                <a href="{{ route('products.show', $product->id) }}" class="block">
                                    <div class="bg-gray-50 h-48 flex items-center justify-center p-4 relative">
                                        <img
                                            src="{{ asset($product->thumbnail_url ?? 'images/default-product.jpg') }}"
                                            alt="{{ $product->name }}"
                                            class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                        @if($product->discount)
                                            <span
                                                class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                -{{ $product->discount }}%
                                            </span>
                                        @endif

                                        <!-- Quick View Button -->
                                        <button type="button"
                                                class="absolute bottom-2 left-1/2 transform -translate-x-1/2 bg-white rounded-md px-3 py-1 text-xs font-medium text-gray-900 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            Quick View
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h3>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ format_taka($product->sale_price ?? $product->price) }}
                                                </span>
                                                @if($product->sale_price)
                                                    <span class="text-xs line-through text-gray-500 ml-1">
                                                        {{ format_taka($product->price) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor"
                                                     viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3 .921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784 .57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81 .588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                                </svg>
                                                <span
                                                    class="text-xs text-gray-500 ml-1">{{ number_format($product->average_rating, 1) }}</span>
                                            </div>
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

    <!-- Video Modal -->
    <div id="video-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe id="video-iframe" class="w-full h-96" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm close-video-modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete confirmation with SweetAlert2
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
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
                button.addEventListener('click', function () {
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
                thumbnails.forEach(thumb => {
                    thumb.parentElement.addEventListener('click', function () {
                        // Add fade effect
                        mainImage.style.opacity = '0';
                        setTimeout(() => {
                            mainImage.src = thumb.src;
                            mainImage.dataset.zoomImage = thumb.dataset.zoomImage;
                            mainImage.style.opacity = '1';


                        }, 300);

                        // Remove active class from all thumbnails
                        document.querySelectorAll('.product-thumbnail').forEach(t => t.classList.remove('ring-2', 'ring-blue-500'));
                        // Add active class to the clicked thumbnail
                        this.classList.add('ring-2', 'ring-blue-500');
                    });
                });
            }

            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');

                    // Update button states
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    button.classList.add('active', 'border-blue-500', 'text-blue-600');
                    button.classList.remove('border-transparent', 'text-gray-500');

                    // Update content visibility
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('active');
                    });
                    document.getElementById(`${tabId}-tab`).classList.remove('hidden');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });

            // Quantity buttons
            const quantityInput = document.getElementById('quantity');
            document.querySelectorAll('.quantity-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const action = this.dataset.action;
                    let quantity = parseInt(quantityInput.value);

                    if (action === 'increase') {
                        quantityInput.value = quantity + 1;
                    } else if (action === 'decrease' && quantity > 1) {
                        quantityInput.value = quantity - 1;
                    }
                });
            });

            // Video modal
            const videoModal = document.getElementById('video-modal');
            const videoIframe = document.getElementById('video-iframe');

            document.querySelectorAll('.video-modal-trigger').forEach(button => {
                button.addEventListener('click', function () {
                    const videoUrl = this.dataset.videoUrl;
                    const embedUrl = videoUrl.includes('youtube') ?
                        videoUrl.replace('watch?v=', 'embed/') :
                        videoUrl;

                    videoIframe.src = embedUrl;
                    videoModal.classList.remove('hidden');
                });
            });

            document.querySelector('.close-video-modal').addEventListener('click', function () {
                videoIframe.src = '';
                videoModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            videoModal.addEventListener('click', function (e) {
                if (e.target === videoModal) {
                    videoIframe.src = '';
                    videoModal.classList.add('hidden');
                }
            });


        });
    </script>
@endpush

@push('styles')
    <style>
        .prose {
            line-height: 1.6;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.375rem;
        }

        .prose ul, .prose ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .prose h1 {
            font-size: 2rem;
        }

        .prose h2 {
            font-size: 1.5rem;
        }

        .prose h3 {
            font-size: 1.25rem;
        }

        .prose table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .prose th, .prose td {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        .prose th {
            background-color: #f7fafc;
            font-weight: 600;
        }

        .prose blockquote {
            margin: 1rem 0;
            padding-left: 1rem;
            border-left: 4px solid #e2e8f0;
            color: #4a5568;
        }

        /* Zoom container styles */
        .zoom-container {
            overflow: hidden;
        }

        .zoom-container img {
            transition: transform .3s;
        }

        .zoom-container:hover img {
            transform: scale(1.05);
        }

        /* Tab content animation */
        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
@endpush


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete confirmation with SweetAlert2
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
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
                button.addEventListener('click', function () {
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
                thumbnails.forEach(thumb => {
                    thumb.parentElement.addEventListener('click', function () {
                        // Add fade effect
                        mainImage.style.opacity = '0';
                        setTimeout(() => {
                            mainImage.src = thumb.src;
                            mainImage.dataset.zoomImage = thumb.dataset.zoomImage;
                            mainImage.style.opacity = '1';


                        }, 300);

                        // Remove active class from all thumbnails
                        document.querySelectorAll('.product-thumbnail').forEach(t => t.classList.remove('ring-2', 'ring-blue-500'));
                        // Add active class to the clicked thumbnail
                        this.classList.add('ring-2', 'ring-blue-500');
                    });
                });
            }

            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');

                    // Update button states
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    button.classList.add('active', 'border-blue-500', 'text-blue-600');
                    button.classList.remove('border-transparent', 'text-gray-500');

                    // Update content visibility
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('active');
                    });
                    document.getElementById(`${tabId}-tab`).classList.remove('hidden');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });

            // Quantity buttons
            const quantityInput = document.getElementById('quantity');
            document.querySelectorAll('.quantity-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const action = this.dataset.action;
                    let quantity = parseInt(quantityInput.value);

                    if (action === 'increase') {
                        quantityInput.value = quantity + 1;
                    } else if (action === 'decrease' && quantity > 1) {
                        quantityInput.value = quantity - 1;
                    }
                });
            });

            // Video modal
            const videoModal = document.getElementById('video-modal');
            const videoIframe = document.getElementById('video-iframe');

            document.querySelectorAll('.video-modal-trigger').forEach(button => {
                button.addEventListener('click', function () {
                    const videoUrl = this.dataset.videoUrl;
                    const embedUrl = videoUrl.includes('youtube') ?
                        videoUrl.replace('watch?v=', 'embed/') :
                        videoUrl;

                    videoIframe.src = embedUrl;
                    videoModal.classList.remove('hidden');
                });
            });

            document.querySelector('.close-video-modal').addEventListener('click', function () {
                videoIframe.src = '';
                videoModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            videoModal.addEventListener('click', function (e) {
                if (e.target === videoModal) {
                    videoIframe.src = '';
                    videoModal.classList.add('hidden');
                }
            });


        });
    </script>
@endpush

@push('styles')
    <style>
        .prose {
            line-height: 1.6;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.375rem;
        }

        .prose ul, .prose ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .prose h1 {
            font-size: 2rem;
        }

        .prose h2 {
            font-size: 1.5rem;
        }

        .prose h3 {
            font-size: 1.25rem;
        }

        .prose table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .prose th, .prose td {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        .prose th {
            background-color: #f7fafc;
            font-weight: 600;
        }

        .prose blockquote {
            margin: 1rem 0;
            padding-left: 1rem;
            border-left: 4px solid #e2e8f0;
            color: #4a5568;
        }

        /* Zoom container styles */
        .zoom-container {
            overflow: hidden;
        }

        .zoom-container img {
            transition: transform .3s;
        }

        .zoom-container:hover img {
            transform: scale(1.05);
        }

        /* Tab content animation */
        .tab-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
@endpush
