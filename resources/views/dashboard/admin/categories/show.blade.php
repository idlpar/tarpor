@extends('layouts.admin')

@section('title', $category->name )

@section('admin_content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="container mx-auto">
            @php
                $currentPageTitle = '';
                $formattedLinks = $links; // Start with the full links array from the controller

                if (is_array($formattedLinks) && !empty($formattedLinks)) {
                    // Get the last key (label) and its value (url)
                    end($formattedLinks); // Move internal pointer to the last element
                    $lastLabel = key($formattedLinks); // Get the key (label) of the last element
                    array_pop($formattedLinks); // Remove the last element (its value is not needed here)

                    $currentPageTitle = $lastLabel; // The last label is the current page title

                    // $formattedLinks now contains all links *except* the current page, in the correct format.
                }
            @endphp
{{--            @include('components.admin.breadcrumb', [--}}
{{--                'links' => $formattedLinks,--}}
{{--                'title' => $currentPageTitle,--}}
{{--                'showHome' => true--}}
{{--            ])--}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-text-dark">Category: <span class="text-text-light">{{ $category->name }}</span></h1>
                    <p class="mt-2 text-sm text-text-light">View details for this product category.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('categories.edit', $category->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-input-border text-sm font-medium rounded-full shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Category Details Card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Category Information</h2>
                        </div>
                        <div class="px-6 py-5">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $category->slug }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $category->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $category->updated_at->format('M j, Y \a\t g:i A') }}</dd>
                                </div>
                                @if($category->description)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $category->description }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Products Section (if applicable) -->
                    @if(isset($products) && $products->count() > 0)
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-gray-900">Products ({{ $products->total() }})</h2>
                                    <!-- Sorting Dropdown -->
                                    <div class="relative">
                                        <select id="sort" name="sort" onchange="window.location.href='?sort='+this.value" class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-md leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
                                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                                            <option value="bestselling" {{ request('sort') === 'bestselling' ? 'selected' : '' }}>Bestselling</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($products as $product)
                                        <!-- Product Card -->
                                        <div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                            <div class="aspect-w-1 aspect-h-1 bg-gray-200 overflow-hidden">
                                                @if($product->media->first())
                                                    <img src="{{ $product->media->first()->getUrl() }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
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
                                                    <a href="{{ route('products.show', $product->id) }}">
                                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                                        {{ $product->name }}
                                                    </a>
                                                </h3>
                                                <div class="mt-2 flex justify-between items-center">
                                                    <p class="text-sm font-semibold text-gray-900">{{ format_currency($product->price) }}</p>
                                                    @if($product->is_featured)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Pagination -->
                                @if($products->hasPages())
                                    <div class="mt-6">
                                        {{ $products->appends(request()->query())->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Category Image Card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Category Image</h2>
                        </div>
                        <div class="p-6">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-auto rounded-md shadow-sm">
                            @else
                                <div class="bg-gray-100 rounded-md h-64 flex items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Subcategories Card -->
                    @if($category->children->isNotEmpty())
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Subcategories ({{ $category->children->count() }})</h2>
                            </div>
                            <div class="p-6">
                                <ul class="space-y-3">
                                    @foreach($category->children as $child)
                                        <li>
                                            <a href="{{ route('categories.show', $child->id) }}" class="group flex items-center justify-between p-3 rounded-md hover:bg-gray-50 transition">
                                                <div class="flex items-center space-x-3">
                                                    @if($child->image)
                                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ $child->image }}" alt="{{ $child->name }}">
                                                    @else
                                                        <span class="h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center">
                                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                            </svg>
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600">{{ $child->name }}</h3>
                                                        <p class="text-xs text-gray-500">{{ $child->products_count ?? 0 }} products</p>
                                                    </div>
                                                </div>
                                                <svg class="h-5 w-5 text-gray-400 group-hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Related Categories (for frontend) -->
                    @if(isset($relatedCategories) && $relatedCategories->isNotEmpty())
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Related Categories</h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach($relatedCategories as $related)
                                        <a href="{{ route('categories.show', $related->slug) }}" class="group flex items-center justify-between p-2 rounded-md hover:bg-gray-50 transition">
                                            <span class="text-sm font-medium text-gray-900 group-hover:text-blue-600">{{ $related->name }}</span>
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $related->products_count }} products</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
