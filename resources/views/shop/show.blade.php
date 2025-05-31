@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap -mx-4">
            <!-- Product Images -->
            <div class="w-full lg:w-1/2 px-4 mb-8 lg:mb-0">
                <div class="sticky top-4">
                    <!-- Main Image -->
                    <div class="mb-4 bg-white rounded-lg overflow-hidden shadow">
                        <img id="mainProductImage" src="{{ $product->thumbnail ?? asset('images/placeholder-product.png') }}"
                             alt="{{ $product->name }}"
                             class="w-full h-96 object-contain">
                    </div>

                    <!-- Thumbnail Gallery -->
                    @if($product->images && count(json_decode($product->images)) > 0)
                        <div class="flex flex-wrap gap-2">
                            <div class="w-20 h-20 border-2 border-primary-500 rounded overflow-hidden">
                                <img src="{{ $product->thumbnail }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover cursor-pointer"
                                     onclick="changeMainImage('{{ $product->thumbnail }}')">
                            </div>
                            @foreach(json_decode($product->images) as $image)
                                <div class="w-20 h-20 border rounded overflow-hidden">
                                    <img src="{{ $image }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover cursor-pointer"
                                         onclick="changeMainImage('{{ $image }}')">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="w-full lg:w-1/2 px-4">
                <div class="bg-white p-6 rounded-lg shadow">
                    <!-- Breadcrumbs -->
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-500">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    Home
                                </a>
                            </li>
                            @if($product->categories->count() > 0)
                                <li>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <a href="{{ route('categories.show', $product->categories->first()->slug) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-500 md:ml-2">
                                            {{ $product->categories->first()->name }}
                                        </a>
                                    </div>
                                </li>
                            @endif
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <!-- Product Title -->
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>

                    <!-- Brand -->
                    @if($product->brand)
                        <div class="mb-3">
                            <span class="text-sm text-gray-600">Brand: </span>
                            <a href="{{ route('shop.index', ['brand' => $product->brand->slug]) }}" class="text-primary-500 hover:underline">
                                {{ $product->brand->name }}
                            </a>
                        </div>
                    @endif

                    <!-- Rating -->
                    <div class="flex items-center mb-4">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500 ml-2">({{ $product->reviews_count }} reviews)</span>
                        <a href="#reviews" class="text-sm text-primary-500 hover:underline ml-4">Write a review</a>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        @if($product->sale_price)
                            <span class="text-3xl font-bold text-gray-800">BDT {{ number_format($product->sale_price, 2) }}</span>
                            <span class="text-xl text-gray-500 line-through ml-2">BDT {{ number_format($product->price, 2) }}</span>
                            @if($product->discount)
                                <span class="ml-2 bg-red-100 text-red-800 text-sm font-semibold px-2 py-1 rounded">
                                {{ $product->discount }}% OFF
                            </span>
                            @endif
                        @else
                            <span class="text-3xl font-bold text-gray-800">BDT {{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <!-- Availability -->
                    <div class="mb-6">
                        @if($product->stock_status == 'in_stock')
                            <span class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            In Stock ({{ $product->stock_quantity }} available)
                        </span>
                        @elseif($product->stock_status == 'out_of_stock')
                            <span class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Out of Stock
                        </span>
                        @else
                            <span class="flex items-center text-blue-600">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Available for backorder
                        </span>
                        @endif
                    </div>

                    <!-- Short Description -->
                    @if($product->short_description)
                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Highlights</h3>
                            <div class="prose prose-sm max-w-none">
                                {!! $product->short_description !!}
                            </div>
                        </div>
                    @endif

                    <!-- Product Actions -->
                    <div class="mb-8">
                        <div class="flex flex-wrap items-center gap-4">
                            <!-- Quantity -->
                            <div class="flex items-center border rounded">
                                <button class="quantity-minus px-3 py-2 text-gray-600 hover:text-gray-700 focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                       class="w-16 text-center border-0 focus:ring-0">
                                <button class="quantity-plus px-3 py-2 text-gray-600 hover:text-gray-700 focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Add to Cart -->
                            <button id="addToCart" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded font-medium">
                                Add to Cart
                            </button>

                            <!-- Wishlist -->
                            <button class="p-3 text-gray-400 hover:text-red-500 border rounded">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Buy Now -->
                        <button class="w-full mt-4 bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded font-medium">
                            Buy Now
                        </button>
                    </div>

                    <!-- Product Meta -->
                    <div class="border-t pt-4">
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            @if($product->sku)
                                <div>
                                    <span class="font-medium">SKU:</span> {{ $product->sku }}
                                </div>
                            @endif
                            @if($product->barcode)
                                <div>
                                    <span class="font-medium">Barcode:</span> {{ $product->barcode }}
                                </div>
                            @endif
                            @if($product->categories->count() > 0)
                                <div>
                                    <span class="font-medium">Category:</span>
                                    @foreach($product->categories as $category)
                                        <a href="{{ route('categories.show', $category->slug) }}" class="text-primary-500 hover:underline">
                                            {{ $category->name }}@if(!$loop->last), @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            @if($product->tags)
                                <div>
                                    <span class="font-medium">Tags:</span>
                                    @foreach(json_decode($product->tags) as $tag)
                                        <a href="{{ route('shop.index', ['tag' => $tag]) }}" class="text-primary-500 hover:underline">
                                            {{ $tag }}@if(!$loop->last), @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="mt-12">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button id="description-tab" class="tab-button py-4 px-6 border-b-2 font-medium text-sm border-primary-500 text-primary-600">
                        Description
                    </button>
                    <button id="specifications-tab" class="tab-button py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Specifications
                    </button>
                    <button id="reviews-tab" class="tab-button py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Reviews ({{ $product->reviews_count }})
                    </button>
                    <button id="shipping-tab" class="tab-button py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Shipping & Returns
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="py-6">
                <!-- Description -->
                <div id="description-content" class="tab-content">
                    <div class="prose max-w-none">
                        {!! $product->description !!}
                    </div>
                </div>

                <!-- Specifications -->
                <div id="specifications-content" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($product->weight || $product->length || $product->width || $product->height)
                            <div>
                                <h3 class="font-semibold text-lg mb-3">Dimensions</h3>
                                <table class="w-full">
                                    <tbody>
                                    @if($product->weight)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">Weight</td>
                                            <td class="py-2 text-right">{{ $product->weight }} kg</td>
                                        </tr>
                                    @endif
                                    @if($product->length && $product->width && $product->height)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">Dimensions</td>
                                            <td class="py-2 text-right">{{ $product->length }} × {{ $product->width }} × {{ $product->height }} cm</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($product->attributes)
                            <div>
                                <h3 class="font-semibold text-lg mb-3">Attributes</h3>
                                <table class="w-full">
                                    <tbody>
                                    @foreach(json_decode($product->attributes, true) as $key => $value)
                                        <tr class="border-b">
                                            <td class="py-2 font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                            <td class="py-2 text-right">{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reviews -->
                <div id="reviews-content" class="tab-content hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Review Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>

                                <div class="flex items-center mb-4">
                                    <div class="mr-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-6 h-6 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-gray-800 font-medium">{{ number_format($product->average_rating, 1) }} out of 5</span>
                                </div>

                                <div class="text-sm text-gray-600 mb-6">
                                    {{ $product->reviews_count }} global ratings
                                </div>

                                <!-- Rating Breakdown -->
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="flex items-center mb-2">
                                        <span class="text-sm font-medium w-8">{{ $i }} star</span>
                                        <div class="flex-1 mx-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-yellow-400" style="width: {{ ($product->rating_counts[$i] ?? 0) / max($product->reviews_count, 1) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-8 text-right">{{ $product->rating_counts[$i] ?? 0 }}</span>
                                    </div>
                                @endfor

                                <button id="writeReviewBtn" class="w-full mt-6 bg-primary-500 hover:bg-primary-600 text-white py-2 rounded">
                                    Write a review
                                </button>
                            </div>
                        </div>

                        <!-- Review List -->
                        <div class="lg:col-span-2">
                            <!-- Review Form (Hidden by default) -->
                            <div id="reviewForm" class="bg-white p-6 rounded-lg shadow mb-6 hidden">
                                <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                                <form id="submitReviewForm">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 mb-2">Rating</label>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" class="star-rating" data-rating="{{ $i }}">
                                                    <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" id="ratingValue" value="0">
                                    </div>

                                    <div class="mb-4">
                                        <label for="reviewTitle" class="block text-gray-700 mb-2">Title</label>
                                        <input type="text" id="reviewTitle" name="title" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-1 focus:ring-primary-500">
                                    </div>

                                    <div class="mb-4">
                                        <label for="reviewComment" class="block text-gray-700 mb-2">Review</label>
                                        <textarea id="reviewComment" name="comment" rows="4" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-1 focus:ring-primary-500"></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" id="cancelReview" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600">
                                            Submit Review
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Reviews List -->
                            <div id="reviewsList">
                                <h3 class="text-lg font-semibold mb-4">Top Reviews</h3>

                                @if($product->reviews_count > 0)
                                    @foreach($product->reviews()->latest()->take(5)->get() as $review)
                                        <div class="border-b pb-4 mb-4">
                                            <div class="flex items-center mb-2">
                                                <div class="flex mr-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="font-medium">{{ $review->title }}</span>
                                            </div>
                                            <div class="text-sm text-gray-600 mb-2">
                                                By {{ $review->user->name }} on {{ $review->created_at->format('M d, Y') }}
                                            </div>
                                            <p class="text-gray-700">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach

                                    @if($product->reviews_count > 5)
                                        <a href="#" class="text-primary-500 hover:underline">See all {{ $product->reviews_count }} reviews</a>
                                    @endif
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-lg font-medium text-gray-900">No reviews yet</h3>
                                        <p class="mt-1 text-gray-500">Be the first to review this product</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping & Returns -->
                <div id="shipping-content" class="tab-content hidden">
                    <div class="prose max-w-none">
                        <h3>Shipping Information</h3>
                        <p>We offer shipping to all regions of Bangladesh. Shipping fees and delivery times vary depending on your location and the shipping method selected at checkout.</p>

                        <h3 class="mt-6">Delivery Options</h3>
                        <ul>
                            <li><strong>Standard Shipping:</strong> 3-5 business days</li>
                            <li><strong>Express Shipping:</strong> 1-2 business days</li>
                            <li><strong>Cash on Delivery:</strong> Available for most locations</li>
                        </ul>

                        <h3 class="mt-6">Returns Policy</h3>
                        <p>We accept returns within 7 days of delivery for most items. To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.</p>

                        <h3 class="mt-6">Refunds</h3>
                        <p>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0 || $similarProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">You may also like</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts->merge($similarProducts)->take(4) as $product)
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function changeMainImage(src) {
                document.getElementById('mainProductImage').src = src;
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Tab switching
                const tabs = {
                    'description-tab': 'description-content',
                    'specifications-tab': 'specifications-content',
                    'reviews-tab': 'reviews-content',
                    'shipping-tab': 'shipping-content'
                };

                Object.entries(tabs).forEach(([tabId, contentId]) => {
                    document.getElementById(tabId).addEventListener('click', function() {
                        // Hide all tab contents
                        document.querySelectorAll('.tab-content').forEach(content => {
                            content.classList.add('hidden');
                        });

                        // Remove active class from all tabs
                        document.querySelectorAll('.tab-button').forEach(tab => {
                            tab.classList.remove('border-primary-500', 'text-primary-600');
                            tab.classList.add('border-transparent', 'text-gray-500');
                        });

                        // Show selected tab content
                        document.getElementById(contentId).classList.remove('hidden');

                        // Add active class to selected tab
                        this.classList.remove('border-transparent', 'text-gray-500');
                        this.classList.add('border-primary-500', 'text-primary-600');
                    });
                });

                // Quantity controls
                const quantityInput = document.getElementById('quantity');
                document.querySelector('.quantity-minus').addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    if (value > 1) {
                        quantityInput.value = value - 1;
                    }
                });

                document.querySelector('.quantity-plus').addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    quantityInput.value = value + 1;
                });

                // Review form toggle
                const writeReviewBtn = document.getElementById('writeReviewBtn');
                const reviewForm = document.getElementById('reviewForm');
                const cancelReview = document.getElementById('cancelReview');

                if (writeReviewBtn && reviewForm) {
                    writeReviewBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        reviewForm.classList.remove('hidden');
                        document.getElementById('reviews-tab').click();
                        window.scrollTo({
                            top: reviewForm.offsetTop - 20,
                            behavior: 'smooth'
                        });
                    });

                    cancelReview.addEventListener('click', function() {
                        reviewForm.classList.add('hidden');
                    });
                }

                // Star rating selection
                document.querySelectorAll('.star-rating').forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        document.getElementById('ratingValue').value = rating;

                        // Update star display
                        document.querySelectorAll('.star-rating svg').forEach((svg, index) => {
                            if (index < rating) {
                                svg.classList.remove('text-gray-300');
                                svg.classList.add('text-yellow-400');
                            } else {
                                svg.classList.remove('text-yellow-400');
                                svg.classList.add('text-gray-300');
                            }
                        });
                    });
                });

                // Add to cart
                document.getElementById('addToCart').addEventListener('click', function() {
                    const productId = {{ $product->id }};
                    const quantity = document.getElementById('quantity').value;

                    // Implement your cart functionality here
                    console.log('Add to cart:', productId, quantity);
                });
            });
        </script>
    @endpush
@endsection
