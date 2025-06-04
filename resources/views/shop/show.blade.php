@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 p-6 md:p-8">
        <!-- Breadcrumbs -->
        @include('components.breadcrumbs', [
            'links' => $breadcrumbs,
            'title' => 'Product Details'
        ])

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h1>
            <div class="flex space-x-3">
                @can('update', $product)
                    <a href="{{ route('products.edit', $product->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit Product
                    </a>
                @endcan
                @can('delete', $product)
                    @if (!$product->trashed())
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Product
                            </button>
                        </form>
                    @else
                        <form action="{{ route('products.restore', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-13.357-2M9 9H4.582A8.001 8.001 0 0120.581 11m-8.581 2H20"></path>
                                </svg>
                                Restore Product
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>

        <!-- Product Details -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Images -->
                <div>
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Images</h2>
                    <img
                        src="{{ asset($product->thumbnail ?? 'images/default-product.jpg') }}"
                        alt="{{ $product->name }}"
                        class="w-full h-auto rounded-lg object-contain max-h-96 mb-4"
                        loading="lazy"
                    >
                    @if ($product->images)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (json_decode($product->images, true) as $image)
                                <img
                                    src="{{ asset($image) }}"
                                    alt="{{ $product->name }} image"
                                    class="w-full h-auto rounded-md object-cover max-h-24"
                                    loading="lazy"
                                >
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Information -->
                <div>
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Basic Information</h2>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Name</dt>
                            <dd class="text-gray-900">{{ $product->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">SKU</dt>
                            <dd class="text-gray-900">{{ $product->sku ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Price</dt>
                            <dd class="text-gray-900">{{ format_taka($product->price ?? 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Sale Price</dt>
                            <dd class="text-gray-900">{{ format_taka($product->sale_price ?? 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Stock Quantity</dt>
                            <dd class="text-gray-900">{{ $product->stock_quantity }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Stock Status</dt>
                            <dd class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800' :
                                   ($product->stock_status === 'out_of_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Status</dt>
                            <dd class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                {{ $product->trashed() ? 'bg-red-100 text-red-800' :
                                   ($product->status === 'published' ? 'bg-green-100 text-green-800' :
                                   ($product->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $product->trashed() ? 'Trashed' : ucfirst($product->status) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Brand</dt>
                            <dd class="text-gray-900">{{ $product->brand->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Categories</dt>
                            <dd class="text-gray-900">
                                @if ($product->categories->isNotEmpty())
                                    {{ $product->categories->pluck('name')->implode(', ') }}
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-gray-600">Tags</dt>
                            <dd class="text-gray-900">
                                @if ($product->tags->isNotEmpty())
                                    {{ $product->tags->pluck('name')->implode(', ') }}
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Additional Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Description</h3>
                        <div class="prose text-gray-900">{!! $product->description !!}</div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Short Description</h3>
                        <p class="text-gray-900">{{ $product->short_description ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Attributes</h3>
                        <p class="text-gray-900">{{ $product->attributes ? json_encode($product->attributes) : 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Dimensions</h3>
                        <p class="text-gray-900">
                            Weight: {{ $product->weight ?? 'N/A' }}<br>
                            Length: {{ $product->length ?? 'N/A' }}<br>
                            Width: {{ $product->width ?? 'N/A' }}<br>
                            Height: {{ $product->height ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Collections</h3>
                        <p class="text-gray-900">{{ $product->product_collections ? implode(', ', $product->product_collections) : 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Labels</h3>
                        <p class="text-gray-900">{{ $product->labels ? implode(', ', $product->labels) : 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Barcode</h3>
                        <p class="text-gray-900">{{ $product->barcode ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Views</h3>
                        <p class="text-gray-900">{{ $product->views ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- SEO Metadata -->
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-700 mb-4">SEO Metadata</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Meta Title</dt>
                        <dd class="text-gray-900">{{ $product->seo->meta_title ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Meta Description</dt>
                        <dd class="text-gray-900">{{ $product->seo->meta_description ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Meta Keywords</dt>
                        <dd class="text-gray-900">{{ $product->seo->meta_keywords ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Canonical URL</dt>
                        <dd class="text-gray-900">{{ $product->seo->canonical_url ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">OG Title</dt>
                        <dd class="text-gray-900">{{ $product->seo->og_title ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">OG Description</dt>
                        <dd class="text-gray-900">{{ $product->seo->og_description ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">OG Image</dt>
                        <dd class="text-gray-900">
                            @if ($product->seo->og_image)
                                <img src="{{ asset($product->seo->og_image) }}" alt="OG Image" class="w-32 h-auto rounded-md">
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Twitter Title</dt>
                        <dd class="text-gray-900">{{ $product->seo->twitter_title ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Twitter Description</dt>
                        <dd class="text-gray-900">{{ $product->seo->twitter_description ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Twitter Image</dt>
                        <dd class="text-gray-900">
                            @if ($product->seo->twitter_image)
                                <img src="{{ asset($product->seo->twitter_image) }}" alt="Twitter Image" class="w-32 h-auto rounded-md">
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Schema Markup</dt>
                        <dd class="text-gray-900">{{ $product->seo->schema_markup ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-semibold text-gray-600">Robots</dt>
                        <dd class="text-gray-900">{{ $product->seo->robots ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Reviews -->
            <div class="mt-8">
                <h2 class="text-lg font-medium text-gray-700 mb-4">Customer Reviews ({{ $product->reviews_count }})</h2>
                @if ($product->reviews->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($product->reviews as $review)
                            <div class="border-b py-4">
                                <div class="flex items-center mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.375 2.45a1 1 0 00-.364 1.118l1.286 3.97c.3.921-.755 1.688-1.54 1.118l-3.375-2.45a1 1 0 00-1.175 0l-3.375 2.45c-.784.57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.735 8.397c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">{{ $review->user->name ?? 'Anonymous' }} - {{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-gray-600">{{ $review->comment ?? 'No comment provided.' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">No reviews yet.</p>
                @endif
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Related Products</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <a href="{{ route('products.show', $relatedProduct->id) }}">
                                    <img
                                        src="{{ asset($relatedProduct->thumbnail ?? 'images/default-product.jpg') }}"
                                        alt="{{ $relatedProduct->name }}"
                                        class="w-full h-48 object-cover"
                                        loading="lazy"
                                    >
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $relatedProduct->name }}</h3>
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

@push('head')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

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
        });
    </script>
@endpush

