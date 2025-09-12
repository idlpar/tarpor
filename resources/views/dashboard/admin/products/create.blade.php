@extends('layouts.admin')

@section('title', 'Add Product')

@push('styles')
    <style>
        /* Hide body until loaded to prevent FOUC */
        /*body { visibility: hidden; }*/

        /* Loading spinner */
        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Hover effects for buttons */
        .hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* CKEditor height */
        .ck-editor__editable {
            min-height: 300px !important;
        }

        /* Gradient background for form */
        .gradient-bg {
            background: linear-gradient(135deg, #f9fafb, #e5e7eb);
        }

        /* Highlight for search */
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }

        /* Related Products Styles */
        #selected-related-products {
            background: linear-gradient(180deg, #f9fafb 0%, #ffffff 100%);
            border: 1px solid #d1d5db;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        #selected-related-products .selected-product-item {
            transition: all 0.2s ease;
            border-radius: 0.375rem;
            background: #f1f5f9;
        }

        #selected-related-products .selected-product-item:hover {
            background: #e5e7eb;
        }

        .remove-related-product {
            transition: transform 0.2s ease;
        }

        .remove-related-product:hover {
            transform: scale(1.2);
        }

        #related-products-results {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-top: 0;
            border-radius: 0 0 0.375rem 0.375rem;
        }

        .product-result {
            transition: background-color 0.2s ease;
        }

        /* Hidden class for visibility toggling */
        .d-none {
            display: none !important;
        }
    </style>
@endpush

@section('admin_content')
    <div class="min-h-screen bg-gray-100 px-4 md:px-6">
        <!-- Display Success/Error Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        <!-- Spinner for loading data -->
        <div id="loading-spinner" class="text-center py-8">
            <img src="{{ asset('images/spinner.gif') }}" alt="Loading..." class="h-24 w-24 mx-auto">
            <p class="mt-2 text-gray-600">Loading product form...</p>
        </div>

        <!-- Breadcrumb Navigation -->
        @include('components.breadcrumbs', [
            'links' => [
                'Products' => route('products.index'),
                'Create Product' => null
            ]
        ])

        <x-ui.page-header title="Create New Product" description="Add a new product to your catalog.">
            <a href="{{ route('products.index') }}" class="ml-4 flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View All Products
            </a>
        </x-ui.page-header>

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">
            <div id="product-form-container" style="display: none;">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="w-full flex flex-col lg:flex-row gap-6" id="productForm">
                @csrf
                <!-- Left Column -->
                <div class="w-full lg:w-9/12">
                    <!-- Product Details -->
                    <div class="bg-white p-8 rounded-lg shadow-lg">

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block font-semibold text-gray-700 mb-2">Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="Product Name">
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permalink -->
                        <div class="mb-6">
                            <label class="block font-semibold text-gray-700 mb-2">Permalink *</label>
                            <div class="flex rounded-lg shadow-sm border border-gray-300 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 @error('slug') border-red-500 @enderror">
                                <span class="inline-flex items-center px-3 rounded-l-lg border-r border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    {{ url('/product') }}/
                                </span>
                                <input type="text" name="slug" value="{{ old('slug') }}" class="flex-1 block w-full border-0 p-2.5 focus:ring-0 focus:outline-none rounded-r-lg" placeholder="your-slug">
                            </div>
                            @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-2">
                                Preview: <a href="#" class="text-blue-500 hover:underline" id="permalink-preview"></a>
                            </p>
                        </div>

                        <!-- Description -->
                        <x-forms.tinymce id="description" name="description" value="{{ old('description') }}">Description</x-forms.tinymce>

                        <!-- Content (short_description) -->
                        <x-forms.tinymce id="short_description" name="short_description" value="{{ old('short_description') }}">Content</x-forms.tinymce>

                        <!-- Images -->
                        <div class="mb-6 border border-dashed border-gray-400 p-6 rounded-lg">
                            <label class="block font-semibold text-left text-gray-700 mb-4">Images</label>
                            <div class="clickable-upload-area border-dashed border-2 border-gray-300 p-6 rounded-lg text-center cursor-pointer hover:bg-gray-50 transition-all">
                                <div id="defaultUploadContent" class="flex flex-col items-center justify-center gap-3 min-h-[120px]">
                                    <svg class="w-16 h-16 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M15 8h.01"></path>
                                        <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"></path>
                                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4"></path>
                                        <path d="M14 14l1 -1c.67 -.644 1.45 -.824 2.182 -.54"></path>
                                        <path d="M16 19h6"></path>
                                        <path d="M19 16v6"></path>
                                    </svg>
                                    <span class="text-gray-500 text-lg">Click here to add images.</span>
                                </div>
                                <div id="selectedImagesPreview" class="flex flex-wrap gap-4 mb-4 hidden">
                                    <!-- Dynamic images will be populated here -->
                                </div>
                                <div id="imageActionButtons" class="flex justify-start gap-4 mt-4 hidden">
                                    <button type="button" id="addMoreImages" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-all">Add Images</button>
                                    <button type="button" id="resetImages" class="px-5 py-2.5 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition-all">Reset</button>
                                </div>
                            </div>
                            <input type="hidden" name="images_existing" id="productImagesInput" value="{{ Js::from(old('images_existing', [])) }}">
                            {{--                            <input type="hidden" name="images" id="productImagesInput" value="{{ Js::from(old('images_existing', [])) }}">--}}
                            @error('images')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="bg-gray-300 p-6 rounded-lg shadow-lg mt-6">
                        <!-- Specification Tables -->
                        <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                            <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                                <label class="block font-semibold text-lg text-gray-700 mb-2">Specification Tables</label>
                                <select id="specificationDropdown" name="attributes" class="border text-sm p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">None</option>
                                    <option value="general" {{ old('attributes') == 'general' ? 'selected' : '' }}>General Specification</option>
                                    <option value="technical" {{ old('attributes') == 'technical' ? 'selected' : '' }}>Technical Specification</option>
                                </select>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Setup meta title & description to make your site easy to discover on search engines such as Google.</p>
                            <div id="specificationFields" class="bg-white p-4 rounded-lg shadow-md hidden mt-4">
                                <table class="w-full border-collapse border border-gray-300">
                                    <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border p-3">Group</th>
                                        <th class="border p-3">Attribute</th>
                                        <th class="border p-3">Attribute Value</th>
                                    </tr>
                                    </thead>
                                    <tbody id="specTableBody"></tbody>
                                </table>
                            </div>
                            @error('attributes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <input type="hidden" name="specifications" id="specifications-hidden-input" value="{{ old('specifications', '[]') }}">
                        </div>

                        <!-- Overview -->
                        <x-form.card label="Overview" class="bg-transparent">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">SKU</label>
                                    <input type="text" name="sku" value="{{ old('sku') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sku') border-red-500 @enderror" placeholder="SKU-CZA-PZ-997">
                                    @error('sku')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Regular Price</label>
                                    <input type="number" name="price" value="{{ old('price') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror" placeholder="Tk. 0" step="0.01">
                                    @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Sale Price</label>
                                    <input type="number" name="sale_price" value="{{ old('sale_price') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sale_price') border-red-500 @enderror" placeholder="Tk. 0" step="0.01">
                                    <p class="text-sm text-gray-500 mt-2">Choose Discount Period</p>
                                    @error('sale_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Cost per Item</label>
                                    <input type="number" name="cost_price" value="{{ old('cost_price') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost_price') border-red-500 @enderror" placeholder="Tk. 0" step="0.01">
                                    <p class="text-sm text-gray-500 mt-2">Customers won't see this price.</p>
                                    @error('cost_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock_quantity') border-red-500 @enderror" placeholder="Enter stock quantity" min="0">
                                    <p class="text-sm text-gray-500 mt-2">Number of items available in stock.</p>
                                    @error('stock_quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Low Stock Threshold</label>
                                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 0) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('low_stock_threshold') border-red-500 @enderror" placeholder="0" min="0">
                                    <p class="text-sm text-gray-500 mt-2">Alert when stock falls below this quantity.</p>
                                    @error('low_stock_threshold')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Barcode (ISBN, UPC, GTIN, etc.)</label>
                                    <input type="text" name="barcode" value="{{ old('barcode') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('barcode') border-red-500 @enderror" placeholder="Enter barcode">
                                    @error('barcode')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </x-form.card>

                        <!-- Stock Status -->
                        <x-form.card label="Stock Status" class="bg-transparent">
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" name="stock_status" value="in_stock" {{ old('stock_status', 'in_stock') == 'in_stock' ? 'checked' : '' }} class="mr-2 @error('stock_status') border-red-500 @enderror">
                                    <span class="text-gray-700">In Stock</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="stock_status" value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'checked' : '' }} class="mr-2 @error('stock_status') border-red-500 @enderror">
                                    <span class="text-gray-700">Out of Stock</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="stock_status" value="backorder" {{ old('stock_status') == 'backorder' ? 'checked' : '' }} class="mr-2 @error('stock_status') border-red-500 @enderror">
                                    <span class="text-gray-700">Backorder</span>
                                </label>
                            </div>
                            @error('stock_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </x-form.card>

                        <!-- Shipping -->
                        <x-form.card label="Shipping" class="bg-transparent">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-semibold text-sm text-gray-700 mb-2">Weight (g)</label>
                                    <input type="number" name="weight" value="{{ old('weight') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('weight')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-sm text-gray-700 mb-2">Length (cm)</label>
                                    <input type="number" name="length" value="{{ old('length') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('length') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('length')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-sm text-gray-700 mb-2">Width (cm)</label>
                                    <input type="number" name="width" value="{{ old('width') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('width') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('width')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-sm text-gray-700 mb-2">Height (cm)</label>
                                    <input type="number" name="height" value="{{ old('height') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('height') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('height')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </x-form.card>

                        <!-- Product Options -->
                        <x-form.card label="Product Options" class="bg-transparent">
                            <select name="product_options" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('product_options') border-red-500 @enderror">
                                <option value="">Select Global Option</option>
                                <!-- Add options dynamically if needed -->
                            </select>
                            @error('product_options')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </x-form.card>

                        <!-- Related Products -->
                        <x-form.card label="Related Products" class="bg-transparent">
                            <div class="relative">
                                <div class="relative">
                                    <input type="text" id="related-products-search" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('related_products') border-red-500 @enderror" placeholder="Search products by name or SKU" autocomplete="off">
                                    <div id="related-products-loading" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div id="related-products-results" class="absolute z-10 w-full bg-white shadow-lg border border-gray-300 max-h-64 overflow-y-auto hidden"></div>
                                <div id="selected-related-products" class="w-full bg-white border border-gray-300 border-t-0 rounded-b-lg space-y-2 p-2">
                                    @if(old('related_products'))
                                        @foreach(json_decode(old('related_products')) as $relatedId)
                                            @php $related = App\Models\Product::find($relatedId); @endphp
                                            @if($related)
                                                <div class="flex items-center justify-between bg-gray-50 p-2 rounded selected-product-item" data-id="{{ $related->id }}">
                                                    <span>{{ $related->name }} (SKU: {{ $related->sku }})</span>
                                                    <button type="button" class="text-red-500 remove-related-product">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10L4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <input type="hidden" name="related_products" id="related-products-input" value="{{ old('related_products', '[]') }}">
                                @error('related_products')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </x-form.card>

                        <!-- Cross-Selling Products -->
                        <x-form.card label="Cross-Selling Products" class="bg-transparent">
                            <div class="relative">
                                <div class="relative">
                                    <input type="text" id="cross-selling-products-search" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cross_selling_products') border-red-500 @enderror" placeholder="Search products by name or SKU" autocomplete="off">
                                    <div id="cross-selling-products-loading" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div id="cross-selling-products-results" class="absolute z-10 w-full bg-white shadow-lg border border-gray-300 max-h-64 overflow-y-auto hidden"></div>
                                <div id="selected-cross-selling-products" class="w-full bg-white border border-gray-300 border-t-0 rounded-b-lg space-y-2 p-2">
                                    @if(old('cross_selling_products'))
                                        @foreach(json_decode(old('cross_selling_products')) as $crossSellingId)
                                            @php $crossSelling = App\Models\Product::find($crossSellingId); @endphp
                                            @if($crossSelling)
                                                <div class="flex items-center justify-between bg-gray-50 p-2 rounded selected-product-item" data-id="{{ $crossSelling->id }}">
                                                    <img src="{{ $crossSelling->thumbnail_media ? $crossSelling->thumbnail_media->thumb_url : '/placeholder-product.jpg' }}" class="w-10 h-10 object-cover rounded-md">
                                                    <div class="flex-1 ml-2">
                                                        <div class="font-medium text-gray-800">{{ $crossSelling->name }}</div>
                                                        <div class="text-sm text-gray-500">SKU: {{ $crossSelling->sku }}</div>
                                                        <div class="text-xs text-gray-400">Category: {{ $crossSelling->categories->isNotEmpty() ? $crossSelling->categories->first()->name : 'N/A' }}</div>
                                                    </div>
                                                    <div class="text-sm text-gray-700 font-semibold">{{ $crossSelling->price }}</div>
                                                    <button type="button" class="text-red-500 remove-cross-selling-product">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10L4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <input type="hidden" name="cross_selling_products" id="cross-selling-products-input" value="{{ old('cross_selling_products', '[]') }}">
                                @error('cross_selling_products')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </x-form.card>

                        <!-- Product FAQs -->
                        <x-form.card label="Product FAQs" class="bg-transparent">
                            <input type="text" name="product_faqs" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('product_faqs') border-red-500 @enderror" placeholder="Search or select from existing FAQs">
                            @error('product_faqs')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </x-form.card>

                        <!-- Search Engine Optimize -->
                        <div x-data="{
                            open: false,
                            metaTitle: '',
                            metaDescription: '',
                            ogTitle: '',
                            ogDescription: '',
                            twitterTitle: '',
                            twitterDescription: '',
                            userHasEdited: {
                                metaTitle: false,
                                metaDescription: false,
                                ogTitle: false,
                                ogDescription: false,
                                twitterTitle: false,
                                twitterDescription: false
                            },
                            init() {
                                const nameInput = document.getElementById('name');
                                const descriptionEditor = tinymce.get('description');

                                const updateDefaults = () => {
                                    const productName = nameInput.value;
                                    const productDescription = descriptionEditor ? descriptionEditor.getContent({ format: 'text' }).substring(0, 160) : '';

                                    if (!this.userHasEdited.metaTitle) this.metaTitle = productName;
                                    if (!this.userHasEdited.metaDescription) this.metaDescription = productDescription;
                                    if (!this.userHasEdited.ogTitle) this.ogTitle = productName;
                                    if (!this.userHasEdited.ogDescription) this.ogDescription = productDescription;
                                    if (!this.userHasEdited.twitterTitle) this.twitterTitle = productName;
                                    if (!this.userHasEdited.twitterDescription) this.twitterDescription = productDescription;
                                };

                                nameInput.addEventListener('input', debounce(updateDefaults, 300));
                                if(descriptionEditor) {
                                    descriptionEditor.on('change', debounce(updateDefaults, 300));
                                }
                            }
                        }" class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                            <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                                <label class="block text-xl font-bold text-gray-800">Search Engine Optimize</label>
                                <button @click="open = !open" type="button" class="px-6 py-2 rounded-lg text-white text-sm font-semibold bg-blue-500 hover:bg-blue-600 transition-all">
                                    <span x-show="!open">Edit SEO Meta</span>
                                    <span x-show="open">Close SEO Meta</span>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Setup meta title & description to make your site easy to discover on search engines such as Google.</p>
                            <div x-show="open" x-collapse class="mt-4 space-y-4">
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Meta Title</label>
                                    <input type="text" name="seo[meta_title]" :value="metaTitle" @input="userHasEdited.metaTitle = true" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Meta Title">
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Meta Description</label>
                                    <textarea name="seo[meta_description]" :value="metaDescription" @input="userHasEdited.metaDescription = true" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Open Graph Title</label>
                                    <input type="text" name="seo[og_title]" :value="ogTitle" @input="userHasEdited.ogTitle = true" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Open Graph Title">
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Open Graph Description</label>
                                    <textarea name="seo[og_description]" :value="ogDescription" @input="userHasEdited.ogDescription = true" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Twitter Title</label>
                                    <input type="text" name="seo[twitter_title]" :value="twitterTitle" @input="userHasEdited.twitterTitle = true" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Twitter Title">
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Twitter Description</label>
                                    <textarea name="seo[twitter_description]" :value="twitterDescription" @input="userHasEdited.twitterDescription = true" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Open Graph Image</label>
                                    <input type="file" name="seo[og_image]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Twitter Image</label>
                                    <input type="file" name="seo[twitter_image]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-full lg:w-3/12 sticky top-6">
                    <!-- Publish Card -->
                    <div class="mb-6 bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Publish</h3>
                        <div class="pt-4 border-t text-sm border-gray-200 flex gap-4">
                            <button type="submit" id="saveButton" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover-effect">Save</button>
                            <button type="submit" id="saveExitButton" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover-effect">Save & Exit</button>
                        </div>
                    </div>



                    <!-- Product Type -->
                    <x-form.card label="Product Type" required="true">
                        <select name="type" id="product_type" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                            <option value="simple" {{ old('type') == 'simple' ? 'selected' : '' }}>Simple Product</option>
                            <option value="variable" {{ old('type') == 'variable' ? 'selected' : '' }}>Variable Product</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Product Attributes Selection -->
                    <div id="product_attributes_wrapper" class="d-none">
                        <x-form.card label="Product Attributes" id="product_attributes_card">
                            <div class="flex flex-col space-y-2">
                                @foreach($attributes as $attribute)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="product_attribute_ids[]" value="{{ $attribute->id }}"
                                               class="form-checkbox h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700">{{ $attribute->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('product_attribute_ids')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </x-form.card>
                    </div>

                    <!-- Status Card -->
                    <x-form.card label="Status" required="true">
                        <select name="status" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>



                    <!-- Is Featured? Card -->
                    <x-form.card label="Is Featured?">
                        <label class="relative inline-flex items-center cursor-pointer ml-2">
                            <input type="checkbox" name="is_featured" value="1" class="sr-only peer" id="featuredToggle" {{ old('is_featured') ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-100 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                        @error('is_featured')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Categories Card -->
                    <x-form.card label="Categories">
                        <div class="relative mb-3">
                            <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10 @error('category_ids') border-red-500 @enderror" placeholder="Search..." id="category-search">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                    <path d="M21 21l-6 -6"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="max-h-48 overflow-y-auto">
                            <ul id="category-tree" class="mt-2 space-y-1">
                                @include('partials.category-checkboxes', ['categories' => $categories, 'selected' => []])
                            </ul>
                        </div>
                        @error('category_ids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('category_ids.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Brand Card -->
                    <x-form.card label="Brand">
                        <div class="relative">
                            <input type="text" id="brand-search" value="{{ old('brand_id') ? App\Models\Brand::find(old('brand_id'))->name : '' }}" class="w-full border border-gray-300 p-2.5 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('brand_id') border-red-500 @enderror" placeholder="Search brands...">
                            <div id="brand-dropdown" class="absolute z-20 bg-white mt-1 w-full border border-gray-200 rounded-md shadow-md hidden max-h-64 overflow-y-auto">
                                <ul id="brand-list" class="divide-y divide-gray-100 text-sm text-gray-700">
                                    @foreach($brands as $brand)
                                        <li class="px-3 py-2 hover:bg-blue-50 cursor-pointer transition-all" data-value="{{ $brand->id }}">{{ $brand->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" name="brand_id" id="selected-brand-id" value="{{ old('brand_id') }}">
                            @error('brand_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-form.card>

                    <!-- Featured Image Card -->
                    <x-form.card label="Featured Image (Optional)">
                        <div id="featuredImageContainer" class="border-dashed border-2 border-gray-300 p-10 rounded-lg text-center cursor-pointer hover:bg-gray-50 transition-all flex flex-col items-center justify-center gap-3 h-40">
                            <svg class="w-16 h-16 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M15 8h.01"></path>
                                <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"></path>
                                <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4"></path>
                                <path d="M14 14l1 -1c.67 -.644 1.45 -.824 2.182 -.54"></path>
                                <path d="M16 19h6"></path>
                                <path d="M19 16v6"></path>
                            </svg>
                            <span class="text-gray-500 text-md">Choose Image</span>
                        </div>
                        <div id="featuredImagePreview" class="mt-4 hidden">
                            <div class="relative w-full max-w-xs mx-auto">
                                <img id="featuredImageThumbnail" src="" alt="Featured Image" class="w-full h-auto rounded-lg border border-gray-200">
                                <button type="button" id="removeFeaturedImage" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="thumbnail_existing" id="featuredImageInput" value="{{ old('thumbnail_existing') }}">
                        @error('thumbnail_existing')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <x-gallery />

                    <x-form.card label="Product Collections">
                        <div class="flex flex-col space-y-3 bg-gray-50 rounded-lg p-4">
                            @foreach ($collections as $collection)
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="product_collections[]" value="{{ $collection->id }}"
                                           {{ in_array($collection->id, old('product_collections', [])) ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-gray-700 font-medium">{{ $collection->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('product_collections')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('product_collections.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Labels Card -->
                    <x-form.card label="Labels">
                        <div class="flex flex-col space-y-3 bg-gray-50 rounded-lg p-4">
                            @foreach ($labels as $label)
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="labels[]" value="{{ $label->id }}"
                                           {{ in_array($label->id, old('labels', [])) ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-gray-700 font-medium">{{ $label->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('labels')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('labels.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Minimum Order Quantity Card -->
                    <x-form.card label="Minimum Order Quantity" required="true">
                        <input type="number" name="min_order_quantity" value="{{ old('min_order_quantity', 0) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('min_order_quantity') border-red-500 @enderror" placeholder="0">
                        <p class="text-sm text-gray-500 mt-2">Minimum quantity to place an order, if the value is 0, there is no limit.</p>
                        @error('min_order_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Maximum Order Quantity Card -->
                    <x-form.card label="Maximum Order Quantity" required="true">
                        <input type="number" name="max_order_quantity" value="{{ old('max_order_quantity', 0) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_order_quantity') border-red-500 @enderror" placeholder="0">
                        <p class="text-sm text-gray-500 mt-2">Maximum quantity to place an order, if the value is 0, there is no limit.</p>
                        @error('max_order_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Tags Card -->
                    <x-form.card label="Tags">
                        <div class="relative" id="tags-container">
                            <div class="flex flex-wrap gap-2 mb-2 items-center border border-gray-300 rounded-lg p-2 min-h-12" id="tags-input-wrapper">
                                <input type="text" id="tag-input" class="flex-grow outline-none min-w-[100px]" placeholder="Add tags..." autocomplete="off">
                            </div>
                            <div id="tag-suggestions" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto"></div>
                            <input type="hidden" name="tags" id="tags-hidden-input" value="{{ old('tags', '[]') }}">
                            @error('tags')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-form.card>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Utility Functions -->
    <script>
        // Reusable debounce function
        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), timeout);
            };
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productTypeSelect = document.getElementById('product_type');

            function toggleInventoryFields() {
                const isSimpleProduct = productTypeSelect.value === 'simple';

                // Select specific inventory-related input fields
                const stockQuantityInput = document.querySelector('input[name="stock_quantity"]');
                const lowStockThresholdInput = document.querySelector('input[name="low_stock_threshold"]');
                const barcodeInput = document.querySelector('input[name="barcode"]');
                const stockStatusRadios = document.querySelectorAll('input[name="stock_status"]');

                // Enable/disable stock quantity, low stock threshold, and barcode
                if (stockQuantityInput) {
                    stockQuantityInput.disabled = !isSimpleProduct;
                    stockQuantityInput.closest('div').style.opacity = isSimpleProduct ? '1' : '0.5';
                    stockQuantityInput.closest('div').style.pointerEvents = isSimpleProduct ? 'auto' : 'none';
                }
                if (lowStockThresholdInput) {
                    lowStockThresholdInput.disabled = !isSimpleProduct;
                    lowStockThresholdInput.closest('div').style.opacity = isSimpleProduct ? '1' : '0.5';
                    lowStockThresholdInput.closest('div').style.pointerEvents = isSimpleProduct ? 'auto' : 'none';
                }
                if (barcodeInput) {
                    barcodeInput.disabled = !isSimpleProduct;
                    barcodeInput.closest('div').style.opacity = isSimpleProduct ? '1' : '0.5';
                    barcodeInput.closest('div').style.pointerEvents = isSimpleProduct ? 'auto' : 'none';
                }

                // Enable/disable stock status radio buttons
                stockStatusRadios.forEach(radio => {
                    radio.disabled = !isSimpleProduct;
                    // Visually dim the parent label or container for radio buttons
                    if (radio.closest('label')) {
                        radio.closest('label').style.opacity = isSimpleProduct ? '1' : '0.5';
                        radio.closest('label').style.pointerEvents = isSimpleProduct ? 'auto' : 'none';
                    }
                });

                // Ensure price fields are always enabled
                const priceFields = [
                    document.querySelector('input[name="price"]'),
                    document.querySelector('input[name="sale_price"]'),
                    document.querySelector('input[name="cost_price"]')
                ];
                priceFields.forEach(field => {
                    if (field) {
                        field.disabled = false;
                        field.closest('div').style.opacity = '1';
                        field.closest('div').style.pointerEvents = 'auto';
                    }
                });
            }

            productTypeSelect.addEventListener('change', toggleInventoryFields);
            toggleInventoryFields(); // Initial call on page load

            const productAttributesWrapper = document.getElementById('product_attributes_wrapper');

            function toggleProductAttributes() {
                if (productTypeSelect.value === 'variable') {
                    productAttributesWrapper.classList.remove('d-none');
                } else {
                    productAttributesWrapper.classList.add('d-none');
                }
            }

            productTypeSelect.addEventListener('change', toggleProductAttributes);
            toggleProductAttributes(); // Initial call on page load
        });
    </script>







    <!-- Image Handling, and Form Submission -->
    <script>
        // Sortable is globally available via app.js
        // import Sortable from 'sortablejs'; // No longer needed here

        document.addEventListener('DOMContentLoaded', () => {
            let galleryManager = {
                productImagesInput: document.getElementById('productImagesInput'),
                selectedImagesPreview: document.getElementById('selectedImagesPreview'),
                defaultUploadContent: document.getElementById('defaultUploadContent'),
                imageActionButtons: document.getElementById('imageActionButtons'),
                featuredImageInput: document.getElementById('featuredImageInput'),
                featuredImageThumbnail: document.getElementById('featuredImageThumbnail'),
                featuredImagePreview: document.getElementById('featuredImagePreview'),
                featuredImageContainer: document.getElementById('featuredImageContainer'),
                productImages: [], // Local state for images, storing {id, url, file} objects
                featuredImageFile: null, // Stores the actual File object for the featured image

                addImage(file) {
                    console.log('addImage called with:', file);
                    // Ensure we store the actual File object if available, or fetch it later if only ID is known
                    if (!file) return;
                    if (this.productImages.some(img => img.id === file.id)) {
                        console.log('Duplicate image detected, skipping:', file.id);
                        return; // Prevent duplicates
                    }

                    this.productImages.push(file);
                    console.log('Current productImages array:', this.productImages);
                    this.renderImages();
                    this.updateVisibility();
                },

                removeImage(id) {
                    this.productImages = this.productImages.filter(img => img.id !== id);
                    this.renderImages();
                    this.updateVisibility();
                },

                setFeaturedImage(file) {
                    if (!file) return;
                    this.featuredImageThumbnail.src = file.thumb_url || file.url;
                    this.featuredImageThumbnail.alt = file.name;
                    this.featuredImagePreview.classList.remove('hidden');
                    this.featuredImageContainer.classList.add('hidden');
                    this.featuredImageInput.value = file.id; // Store ID for existing media
                    this.featuredImageFile = file.file; // Store the actual File object for new uploads
                },

                renderImages() {
                    this.selectedImagesPreview.innerHTML = '';
                    if (this.productImages.length === 0) {
                        this.selectedImagesPreview.classList.add('hidden');
                        // this.productImagesInput.value = '[]';
                        this.productImagesInput.value = JSON.stringify(this.productImages.map(img => img.id || null));
                        this.updateVisibility();
                        return;
                    }

                    this.productImages = this.productImages.filter(img => img && (img.id || img.file)); // Filter invalid images
                    console.log('renderImages: Filtered productImages before rendering:', this.productImages); // ADDED LOG
                    this.productImages.forEach((image) => {
                        const imageWrapper = document.createElement('div');
                        imageWrapper.className = 'relative w-24 h-24 rounded-lg overflow-hidden border border-gray-200 sortable-image';
                        imageWrapper.innerHTML = `
                            <img src="${image.thumb_url || image.url || URL.createObjectURL(image.file)}" alt="${image.name}" class="w-full h-full object-cover">
                            <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-600 remove-image" data-id="${image.id || ''}" data-file-name="${image.file ? image.file.name : ''}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        `;
                        this.selectedImagesPreview.appendChild(imageWrapper);

                        imageWrapper.querySelector('.remove-image').addEventListener('click', (e) => {
                            e.stopPropagation();
                            const idToRemove = e.currentTarget.dataset.id;
                            const fileNameToRemove = e.currentTarget.dataset.fileName;

                            if (idToRemove) {
                                this.removeImage(parseInt(idToRemove));
                            } else if (fileNameToRemove) {
                                this.productImages = this.productImages.filter(img => img.file?.name !== fileNameToRemove);
                                this.renderImages();
                                this.updateVisibility();
                            }
                        });
                    });

                    // Update hidden input with IDs of existing media, or mark as new for files

                    this.updateVisibility();
                },

                updateVisibility() {
                    if (this.productImages.length > 0) {
                        this.defaultUploadContent.classList.add('hidden');
                        this.selectedImagesPreview.classList.remove('hidden');
                        this.imageActionButtons.classList.remove('hidden');
                    } else {
                        this.defaultUploadContent.classList.remove('hidden');
                        this.selectedImagesPreview.classList.add('hidden');
                        this.imageActionButtons.classList.add('hidden');
                    }
                },

                resetImages() {
                    this.productImages = [];
                    this.selectedImagesPreview.innerHTML = '';
                    this.productImagesInput.value = '[]';
                    this.updateVisibility();
                },

                initPreloadedImages() {
                    // For a new product, there are no preloaded images.
                    // Just ensure visibility is correctly set based on initial state (empty).
                    this.updateVisibility();
                },

                init() {
                    this.initPreloadedImages();
                    const uploadArea = document.querySelector('.clickable-upload-area');
                    uploadArea?.addEventListener('click', this.handleProductUpload.bind(this));
                    const addMoreImages = document.getElementById('addMoreImages');
                    addMoreImages?.addEventListener('click', this.handleProductUpload.bind(this));
                    const resetImages = document.getElementById('resetImages');
                    resetImages?.addEventListener('click', () => this.resetImages());
                    const featuredContainer = document.getElementById('featuredImageContainer');
                    featuredContainer?.addEventListener('click', this.handleFeaturedUpload.bind(this));
                    const removeFeatured = document.getElementById('removeFeaturedImage');
                    removeFeatured?.addEventListener('click', () => {
                        this.featuredImageInput.value = '';
                        this.featuredImageFile = null; // Clear the File object
                        this.featuredImagePreview.classList.add('hidden');
                        this.featuredImageContainer.classList.remove('hidden');
                    });
                    if (this.selectedImagesPreview) {
                        new window.Sortable(this.selectedImagesPreview, {
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            dragClass: 'sortable-image',
                            onEnd: () => {
                                const sortedImages = Array.from(this.selectedImagesPreview.children).map(wrapper => {
                                    const id = parseInt(wrapper.querySelector('.remove-image').dataset.id);
                                    const fileName = wrapper.querySelector('.remove-image').dataset.fileName;
                                    return this.productImages.find(img => (img.id === id) || (img.file?.name === fileName));
                                });
                                this.productImages = sortedImages.filter(img => img);
                                this.productImagesInput.value = JSON.stringify(this.productImages.map(img => img.id || null));
                            }
                        });
                    }
                },

                handleProductUpload() {
                    window.openGalleryModal('', (files) => {
                        if (!files) return;
                        const fileArray = Array.isArray(files) ? files : [files];
                        fileArray.forEach(file => {
                            // If it's a new file from the gallery, it will have a 'file' property
                            // If it's an existing media item, it will have an 'id' and 'url'
                            this.addImage(file);
                        });
                    }, { mode: 'multiple', accept: 'image/*' });
                },

                handleFeaturedUpload() {
                    window.openGalleryModal('', (file) => {
                        if (file) {
                            this.setFeaturedImage(file);
                        }
                    }, { mode: 'single', accept: 'image/*' });
                }
            };

            galleryManager.init();

            const form = document.getElementById('productForm');
            const saveButton = document.getElementById('saveButton');
            const saveExitButton = document.getElementById('saveExitButton');
            const spinner = document.getElementById('loading-spinner');

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

            async function handleSubmit(event) {
                event.preventDefault();
                const isSaveExit = event.submitter === saveExitButton;

                // Replace the CKEditor sync code with:
                if (window.tinymce && Array.isArray(window.tinymce.editors)) {
                    window.tinymce.editors.forEach(editor => {
                        if (editor.initialized) {
                            editor.save();
                        }
                    });
                }

                // Collect specifications data
                const specifications = [];
                const specTableRows = document.querySelectorAll('#specTableBody tr');
                specTableRows.forEach(row => {
                    const group = row.children[0].textContent.trim();
                    const attribute = row.children[1].textContent.trim();
                    const value = row.children[2].querySelector('input').value.trim();
                    specifications.push({
                        group_name: group,
                        attribute_name: attribute,
                        attribute_value: value
                    });
                });
                document.getElementById('specifications-hidden-input').value = JSON.stringify(specifications);

                // Clear previous errors
                document.querySelectorAll('.text-red-500.text-sm.mt-1').forEach(el => el.remove());
                document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                const formData = new FormData(form);

                // Append product images (new files and existing IDs)
                galleryManager.productImages.forEach((image) => {
                    if (image.file) {
                        formData.append('images_new[]', image.file); // New file upload
                        console.log('Appending new image file:', image.file.name);
                    } else if (image.id) {
                        formData.append('images_existing[]', image.id); // Existing media ID
                        console.log('Appending existing image ID:', image.id);
                    }
                });

                // Append featured image (new file or existing ID)
                // The thumbnail_existing input is already handled by the form, no need to delete and re-append
                if (galleryManager.featuredImageFile) {
                    formData.append('thumbnail_new', galleryManager.featuredImageFile); // New file upload
                    console.log('Appending new thumbnail file:', galleryManager.featuredImageFile.name);
                }

                // Log all formData entries for debugging
                console.log('--- FormData Contents (before send) ---');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                console.log('Product Collections in FormData:', formData.getAll('product_collections[]'));
                console.log('Labels in FormData:', formData.getAll('labels[]'));
                console.log('-------------------------');



                saveButton.disabled = true;
                saveExitButton.disabled = true;
                spinner.style.display = 'block';

                try {
                    if (isSaveExit) {
                        formData.append('save_exit', '1');
                    }

                    // Log form data for debugging
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    const result = await response.json();

                    if (response.ok) {
                        showToast('Product saved successfully!');
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        } else if (isSaveExit) {
                            window.location.href = '{{ route("products.index") }}';
                        } else {
                            form.reset();
                        }
                    } else {
                        if (result.errors) {
                            for (const [field, messages] of Object.entries(result.errors)) {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    const errorDiv = document.createElement('p');
                                    errorDiv.className = 'text-red-500 text-sm mt-1';
                                    errorDiv.textContent = messages[0];
                                    input.parentNode.appendChild(errorDiv);
                                    input.classList.add('border-red-500');
                                } else {
                                    // Fallback for fields without a direct input (e.g., arrays like product_collections)
                                    const errorContainer = form.querySelector(`[name="${field}[]"]`)?.closest('.x-form-card') ||
                                        form.querySelector(`[name="${field}"]`)?.closest('.x-form-card');
                                    if (errorContainer) {
                                        const errorDiv = document.createElement('p');
                                        errorDiv.className = 'text-red-500 text-sm mt-1';
                                        errorDiv.textContent = messages[0];
                                        errorContainer.appendChild(errorDiv);
                                    }
                                }
                            }
                        }
                        showToast(result.message || 'Failed to save product.', 'error');
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    showToast('An error occurred while saving the product.', 'error');
                } finally {
                    saveButton.disabled = false;
                    saveExitButton.disabled = false;
                    spinner.style.display = 'none';
                }
            }

            form.addEventListener('submit', handleSubmit);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productTypeSelect = document.getElementById('product_type');
            const simpleProductFields = document.querySelectorAll('.x-form-card[label="Overview"], .x-form-card[label="Stock Status"], .x-form-card[label="Shipping"]');

            function toggleSimpleProductFields() {
                if (productTypeSelect.value === 'simple') {
                    simpleProductFields.forEach(card => card.style.display = 'block');
                } else {
                    simpleProductFields.forEach(card => card.style.display = 'none');
                }
            }

            productTypeSelect.addEventListener('change', toggleSimpleProductFields);
            toggleSimpleProductFields(); // Initial call on page load
        });
    </script>

    <!-- Dynamic Specification Table -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdown = document.getElementById('specificationDropdown');
            const specFields = document.getElementById('specificationFields');
            const specTableBody = document.getElementById('specTableBody');

            dropdown.addEventListener('change', () => {
                specTableBody.innerHTML = '';
                if (dropdown.value === '') {
                    specFields.classList.add('hidden');
                    return;
                }

                specFields.classList.remove('hidden');
                const specifications = {
                    general: [
                        { group: 'General', attribute: 'Brand' },
                        { group: 'General', attribute: 'Model' }
                    ],
                    technical: [
                        { group: 'Battery', attribute: 'Battery Life' },
                        { group: 'Display', attribute: 'Screen Size' },
                        { group: 'Display', attribute: 'Resolution', value: '1920×1080' }
                    ]
                };

                specifications[dropdown.value].forEach(spec => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border p-3">${spec.group}</td>
                        <td class="border p-3">${spec.attribute}</td>
                        <td class="border p-3">
                            <input type="text" class="w-full border rounded p-2" value="${spec.value || ''}">
                        </td>
                    `;
                    specTableBody.appendChild(row);
                });
            });
        });
    </script>

    <!-- Page Load -->
    <script>
        window.addEventListener('load', () => {
            document.getElementById('loading-spinner').style.display = 'none';
            document.getElementById('product-form-container').style.display = 'block';
        });
    </script>

    <!-- Slug Generation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const slugInput = document.querySelector('input[name="slug"]');
            const permalinkPreview = document.getElementById('permalink-preview');
            const baseUrl = "{{ url('/product') }}";
            let manualSlugEdit = false; // Flag to track manual edits

            function createSlug(text) {
                return text.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
            }

            function updatePreview(slug) {
                slug = slug || 'your-slug';
                permalinkPreview.textContent = baseUrl + '/' + slug;
                permalinkPreview.href = baseUrl + '/' + slug;
            }

            const checkSlug = debounce(async (slugToCheck, isManual = false) => {
                if (!slugToCheck || slugToCheck === 'your-slug') {
                    updatePreview(''); // Clear preview if slug is empty
                    return;
                }

                try {
                    const response = await fetch(`/api/product/slug/check?slug=${encodeURIComponent(slugToCheck)}`);
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Error checking slug:', response.status, errorText);
                        return;
                    }
                    const data = await response.json();
                    if (!isManual) {
                        slugInput.value = data.suggested;
                    } else if (slugToCheck !== data.suggested) {
                        // If manually entered slug is not unique, update with suggested unique slug
                        slugInput.value = data.suggested;
                    }
                    updatePreview(data.suggested);
                } catch (error) {
                    console.error('Error checking slug:', error);
                }
            }, 2000); // Debounce for 2000ms (2 seconds)

            nameInput.addEventListener('input', () => {
                if (!manualSlugEdit) {
                    const generatedSlug = createSlug(nameInput.value);
                    slugInput.value = generatedSlug; // Immediately update slug input
                    checkSlug(generatedSlug);
                }
            });

            slugInput.addEventListener('input', () => {
                manualSlugEdit = true; // User is manually editing
                const currentSlug = slugInput.value.trim();
                if (currentSlug) {
                    checkSlug(currentSlug, true); // Check uniqueness for manual slug
                } else {
                    // If manual slug is cleared, revert to auto-generating from name
                    manualSlugEdit = false;
                    const generatedSlug = createSlug(nameInput.value);
                    slugInput.value = generatedSlug;
                    checkSlug(generatedSlug);
                }
            });

            // Reset manualSlugEdit flag if name input is focused after a manual edit
            nameInput.addEventListener('focus', () => {
                if (manualSlugEdit && slugInput.value.trim() === createSlug(nameInput.value)) {
                    manualSlugEdit = false;
                }
            });

            // Initial preview update on page load
            updatePreview(slugInput.value || 'your-slug');
        });
    </script>

    <!-- Category Search -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('#category-search');

            function filterCategories() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                const allLis = document.querySelectorAll('#category-tree li');

                // Reset all highlights
                allLis.forEach(li => {
                    const label = li.querySelector('.category-label');
                    if (label) label.innerHTML = label.textContent;
                });

                function checkMatch(li) {
                    const label = li.querySelector('.category-label');
                    const children = li.querySelectorAll(':scope > ul > li');
                    let isMatch = false;

                    if (label) {
                        const text = label.textContent.trim().toLowerCase();
                        isMatch = text.includes(searchTerm);
                        if (isMatch && searchTerm) {
                            const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                            label.innerHTML = label.textContent.replace(regex, '<span class="highlight">$1</span>');
                        }
                    }

                    let childHasMatch = false;
                    children.forEach(childLi => {
                        if (checkMatch(childLi)) childHasMatch = true;
                    });

                    li.style.display = (searchTerm === '' || isMatch || childHasMatch) ? 'block' : 'none';
                    return isMatch || childHasMatch;
                }

                document.querySelectorAll('#category-tree > li').forEach(topLi => checkMatch(topLi));
            }

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            }

            const debouncedFilter = debounce(filterCategories, 300);
            searchInput.addEventListener('input', debouncedFilter);
        });
    </script>

    <!-- Brand Selection -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const brandSearch = document.getElementById('brand-search');
            const brandList = document.getElementById('brand-list');
            const brandDropdown = document.getElementById('brand-dropdown');
            const selectedBrandInput = document.getElementById('selected-brand-id');
            const allItems = Array.from(brandList.querySelectorAll('li'));

            brandSearch.addEventListener('focus', () => {
                allItems.forEach(item => item.style.display = 'block');
                brandDropdown.classList.remove('hidden');
            });

            brandSearch.addEventListener('input', () => {
                const term = brandSearch.value.trim().toLowerCase();
                let hasMatch = false;
                allItems.forEach(item => {
                    const match = item.textContent.toLowerCase().includes(term);
                    item.style.display = match ? 'block' : 'none';
                    if (match) hasMatch = true;
                });
                brandDropdown.classList.toggle('hidden', !hasMatch && term === '');
            });

            brandList.addEventListener('click', (e) => {
                const item = e.target.closest('li');
                if (!item) return;
                brandSearch.value = item.textContent.trim();
                selectedBrandInput.value = item.dataset.value;
                brandDropdown.classList.add('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!brandDropdown.contains(e.target) && !brandSearch.contains(e.target)) {
                    brandDropdown.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- SKU Generation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
            const skuInput = document.querySelector('input[placeholder="SKU-CZA-PZ-997"]');

            async function fetchGeneratedSku() {
                const selectedCategories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(checkbox => parseInt(checkbox.value));
                const productName = document.getElementById('name').value;
                const brandId = document.getElementById('selected-brand-id').value;

                if (!selectedCategories.length && !productName && !brandId) {
                    if (skuInput) skuInput.value = '';
                    return;
                }
                console.log('Sending data for SKU generation:', { selectedCategories, productName, brandId });
                try {
                    if (skuInput) skuInput.value = 'Generating SKU...';
                    const response = await fetch('/api/generate-sku', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ category_ids: selectedCategories, product_name: productName, brand_id: brandId })
                    });
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    const data = await response.json();
                    if (skuInput && data.sku) skuInput.value = data.sku;
                } catch (error) {
                    console.error('Error:', error);
                    if (skuInput) {
                        skuInput.value = '';
                        alert('SKU generation failed. Please check console for details.');
                    }
                }
            }

            categoryCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', fetchGeneratedSku);
            });

            if (document.querySelector('input[name="categories[]"]:checked')) {
                fetchGeneratedSku();
            }
        });
    </script>

    <script>
        function formatTaka(amount, symbol = '৳', includeDecimal = false) {
            const formatter = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'BDT',
                minimumFractionDigits: includeDecimal ? 2 : 0,
                maximumFractionDigits: includeDecimal ? 2 : 0,
            });
            return formatter.format(amount).replace('BDT', symbol).trim();
        }

        function setupProductSelector(options) {
            const {
                searchInputId,
                resultsContainerId,
                selectedContainerId,
                hiddenInputId,
                loadingIndicatorId,
                removeButtonClass,
                fetchBriefUrl,
                fetchSearchUrl,
                fetchSuggestionsUrl,
                consoleErrorPrefix
            } = options;

            const searchInput = document.getElementById(searchInputId);
            const resultsContainer = document.getElementById(resultsContainerId);
            const selectedContainer = document.getElementById(selectedContainerId);
            const hiddenInput = document.getElementById(hiddenInputId);
            const loadingIndicator = document.getElementById(loadingIndicatorId);
            const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
            const tagInputs = document.querySelectorAll('input[name="tags[]"]'); // Assuming tags are handled by a separate script and their values are in a hidden input

            let selectedProducts = JSON.parse(hiddenInput.value || '[]');
            let searchController = null;

            async function fetchProductBrief(productId) {
                try {
                    const response = await fetch(`${fetchBriefUrl.replace('{productId}', productId)}`);
                    if (!response.ok) {
                        console.error(`Failed to fetch product ${productId}: ${response.status}`);
                        return null; // or return a default product object
                    }
                    return await response.json();
                } catch (error) {
                    console.error(`Error fetching product ${productId}:`, error);
                    return null;
                }
            }

            async function updateSelectedProductsDisplay() {
                selectedProducts = selectedProducts.filter(id => id && !isNaN(id));
                hiddenInput.value = JSON.stringify(selectedProducts);
                selectedContainer.innerHTML = '';
                if (selectedProducts.length === 0) {
                    selectedContainer.classList.add('hidden');
                } else {
                    selectedContainer.classList.remove('hidden');
                    for (const productId of selectedProducts) {
                        const product = await fetchProductBrief(productId);
                        if (product) {
                            const productEl = document.createElement('div');
                            productEl.className = 'flex items-center justify-between bg-gray-50 p-2 rounded selected-product-item';
                            productEl.dataset.id = product.id;
                            productEl.innerHTML = `
                                <img src="${product.thumbnail || '/placeholder-product.jpg'}" class="w-10 h-10 object-cover rounded-md">
                                <div class="flex-1 ml-2">
                                    <div class="font-medium text-gray-800">${product.name}</div>
                                    <div class="text-sm text-gray-500">SKU: ${product.sku}</div>
                                    <div class="text-xs text-gray-400">Category: ${product.category_name || 'N/A'}</div>
                                </div>
                                <div class="text-sm font-semibold">
                                    ${product.sale_price && product.sale_price < product.price
                                        ? `<span class="text-gemini-pink">${formatTaka(product.sale_price)}</span>`
                                        : `<span class="text-gemini-pink">${formatTaka(product.price)}</span>`
                                    }
                                </div>
                                <button type="button" class="text-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 rounded-full p-1 transition-all duration-200 ${removeButtonClass}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            `;
                            selectedContainer.appendChild(productEl);
                        }
                    }
                }
            }

            updateSelectedProductsDisplay();

            searchInput.addEventListener('focus', () => {
                searchInput.value = '';
                loadSmartSuggestions();
                resultsContainer.classList.remove('hidden');
            });

            const searchProducts = debounce(async (term) => {
                if (searchController) searchController.abort();
                searchController = new AbortController();
                if (!term || term.length < 2) {
                    resultsContainer.classList.add('hidden');
                    return;
                }
                loadingIndicator.classList.remove('hidden');
                resultsContainer.classList.add('hidden');
                try {
                    const params = new URLSearchParams();
                    selectedProducts.forEach(id => params.append('exclude_ids[]', id));
                    const response = await fetch(`${fetchSearchUrl}?q=${encodeURIComponent(term)}&${params.toString()}`, {
                        signal: searchController.signal
                    });
                    if (!response.ok) throw new Error('Network response was not ok');
                    const products = await response.json();
                    resultsContainer.innerHTML = products.length > 0
                        ? products.map(product => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer flex items-center gap-4 border-b border-gray-200 product-result transition-colors duration-150" data-id="${product.id}" data-name="${product.name}" data-sku="${product.sku}">
                                <img src="${product.thumbnail || '/placeholder-product.jpg'}" class="w-12 h-12 object-cover rounded-md shadow-sm">
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                                    <div class="font-medium text-gray-800 text-base truncate">${product.name}</div>
                                    <div class="text-sm text-gray-500">SKU: ${product.sku}</div>
                                    <div class="text-xs text-gray-400 sm:col-span-2">Category: ${product.category_name || 'N/A'}</div>
                                </div>
                                <div class="text-sm font-semibold text-right">
                                    ${product.sale_price && product.sale_price < product.price
                                        ? `<span class="text-gemini-pink">${formatTaka(product.sale_price)}</span>`
                                        : `<span class="text-gemini-pink">${formatTaka(product.price)}</span>`
                                    }
                                </div>
                            </div>
                        `).join('')
                        : '<div class="p-3 text-gray-500">No products found</div>';
                    resultsContainer.classList.remove('hidden');
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error(`${consoleErrorPrefix} Search failed:`, error);
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Error loading results</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } finally {
                    loadingIndicator.classList.add('hidden');
                    searchController = null;
                }
            }, 300);

            searchInput.addEventListener('input', () => {
                const term = searchInput.value.trim();
                if (term.length > 1) searchProducts(term);
                else resultsContainer.classList.add('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.classList.add('hidden');
                }
            });

            async function loadSmartSuggestions() {
                const selectedCategories = Array.from(categoryCheckboxes).filter(el => el.checked).map(el => el.value);
                const tagsHiddenInput = document.getElementById('tags-hidden-input');
                const selectedTags = tagsHiddenInput && tagsHiddenInput.value ? JSON.parse(tagsHiddenInput.value) : [];
                const currentSelectedProductIds = selectedProducts; // Use the selectedProducts array from the closure

                loadingIndicator.classList.remove('hidden');
                resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Loading suggestions...</div>';
                resultsContainer.classList.remove('hidden');
                try {
                    const params = new URLSearchParams();
                    selectedCategories.forEach(id => params.append('category_ids[]', id));
                    selectedTags.forEach(tag => params.append('tag_names[]', tag));
                    currentSelectedProductIds.forEach(id => params.append('exclude_ids[]', id)); // Pass selected IDs to exclude

                    const response = await fetch(`${fetchSuggestionsUrl}?${params.toString()}`);
                    if (!response.ok) throw new Error('Network response was not ok');
                    const suggestions = await response.json();
                    resultsContainer.innerHTML = suggestions.length > 0
                        ? `
                            <div class="p-2 text-xs font-semibold text-gray-500 border-b">
                                ${selectedCategories.length || selectedTags.length ? 'RELEVANT PRODUCTS' : 'POPULAR PRODUCTS'}
                            </div>
                            ${suggestions.map(suggestion => `
                                <div class="p-3 hover:bg-gray-100 cursor-pointer flex items-center gap-4 border-b border-gray-200 product-result" data-id="${suggestion.id}" data-name="${suggestion.name}" data-sku="${suggestion.sku}">
                                    <img src="${suggestion.thumbnail || '/placeholder-product.jpg'}" class="w-10 h-10 object-cover rounded-md">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">${suggestion.name}</div>
                                        <div class="text-sm text-gray-500">SKU: ${suggestion.sku}</div>
                                        <div class="text-xs text-gray-400 mt-1">${suggestion.reason}</div>
                                    </div>
                                    <div class="text-sm font-semibold">
                                        ${suggestion.sale_price && suggestion.sale_price < suggestion.price
                                            ? `<span class="text-gemini-pink">${formatTaka(suggestion.sale_price)}</span>`
                                            : `<span class="text-gemini-pink">${formatTaka(suggestion.price)}</span>`
                                        }
                                    </div>
                                </div>
                            `).join('')}
                        `
                        : '<div class="p-3 text-gray-500">No suggestions available</div>';
                } catch (error) {
                    console.error(`${consoleErrorPrefix} Failed to load suggestions:`, error);
                    resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Error loading suggestions</div>';
                } finally {
                    loadingIndicator.classList.add('hidden');
                }
            }

            categoryCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    if (searchInput === document.activeElement) loadSmartSuggestions();
                });
            });

            // Assuming tags are managed by a separate script and update a hidden input
            // You might need to listen to changes on the hidden tags input if it's not directly a checkbox/select
            const tagsHiddenInput = document.getElementById('tags-hidden-input');
            if (tagsHiddenInput) {
                // A simple way to detect changes if the input value is updated programmatically
                // This might require a custom event or MutationObserver for more robust detection
                tagsHiddenInput.addEventListener('change', () => {
                    if (searchInput === document.activeElement) loadSmartSuggestions();
                });
            }


            resultsContainer.addEventListener('click', (e) => {
                const resultItem = e.target.closest('.product-result');
                if (!resultItem) return;
                const productId = parseInt(resultItem.dataset.id);
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId);
                    updateSelectedProductsDisplay();
                }
                searchInput.value = '';
                resultsContainer.classList.add('hidden');
            });

            selectedContainer.addEventListener('click', (e) => {
                if (e.target.closest(`.${removeButtonClass}`)) {
                    const item = e.target.closest('[data-id]');
                    const productId = parseInt(item.dataset.id);
                    selectedProducts = selectedProducts.filter(id => id !== productId);
                    updateSelectedProductsDisplay();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Setup for Related Products
            setupProductSelector({
                searchInputId: 'related-products-search',
                resultsContainerId: 'related-products-results',
                selectedContainerId: 'selected-related-products',
                hiddenInputId: 'related-products-input',
                loadingIndicatorId: 'related-products-loading',
                removeButtonClass: 'remove-related-product',
                fetchBriefUrl: '/api/product/{productId}/brief',
                fetchSearchUrl: '/api/product/search',
                fetchSuggestionsUrl: '/api/product/suggestions',
                consoleErrorPrefix: 'Related Products:'
            });

            // Setup for Cross-Selling Products
            setupProductSelector({
                searchInputId: 'cross-selling-products-search',
                resultsContainerId: 'cross-selling-products-results',
                selectedContainerId: 'selected-cross-selling-products',
                hiddenInputId: 'cross-selling-products-input',
                loadingIndicatorId: 'cross-selling-products-loading',
                removeButtonClass: 'remove-cross-selling-product',
                fetchBriefUrl: '/api/product/{productId}/brief',
                fetchSearchUrl: '/api/product/search',
                fetchSuggestionsUrl: '/api/product/suggestions',
                consoleErrorPrefix: 'Cross-Selling Products:'
            });
        });
    </script>

    <!-- Tags -->




    <!-- Tags -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tagsContainer = document.getElementById('tags-container');
            const tagsInputWrapper = document.getElementById('tags-input-wrapper');
            const tagInput = document.getElementById('tag-input');
            const tagSuggestions = document.getElementById('tag-suggestions');
            const tagsHiddenInput = document.getElementById('tags-hidden-input');
            const tagSuggestRoute = "{{ route('tag.suggest') }}";

            let tags = [];
            let suggestions = [];
            let hoveredIndex = -1;
            let lastSearch = '';
            let tagDebounceTimer;
            let spacePressTimer;

            // Initial loading of tags (for edit page or old input)
            if (tagsHiddenInput.value) {
                try {
                    const initialTags = JSON.parse(tagsHiddenInput.value);
                    // Assuming initialTags are just names, we need to convert them to objects
                    // For a create page, this will likely be empty unless old() input is present
                    tags = initialTags.map(name => ({ name: name, normalized: name.toLowerCase() }));
                    renderTags();
                } catch (e) {
                    console.error('Error parsing initial tags:', e);
                }
            }

            tagInput.addEventListener('input', handleTagInput);
            tagInput.addEventListener('keydown', handleTagKeyDown);
            tagInput.addEventListener('blur', handleTagBlur);

            function renderTags() {
                const existingTags = tagsInputWrapper.querySelectorAll('.tag-pill');
                existingTags.forEach(tag => tag.remove());

                tags = tags.filter(tag => tag && tag.name);
                tags.forEach((tag, index) => {
                    const tagElement = document.createElement('div');
                    tagElement.className = 'flex items-center gap-1 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm tag-pill transition duration-200 hover:bg-blue-200';
                    tagElement.innerHTML = `
                        <span class="capitalize">${tag.name}</span>
                        <button type="button" class="text-blue-500 hover:text-red-600 remove-tag" data-index="${index}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    `;
                    tagsInputWrapper.insertBefore(tagElement, tagInput);
                });

                document.querySelectorAll('.remove-tag').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const index = parseInt(e.currentTarget.getAttribute('data-index'));
                        tags.splice(index, 1);
                        renderTags();
                        updateHiddenInput();
                    });
                });

                updateHiddenInput();
            }

            function updateHiddenInput() {
                tagsHiddenInput.value = JSON.stringify(tags.map(tag => tag.name));
            }

            function handleTagInput() {
                const query = tagInput.value.trim();
                clearTimeout(tagDebounceTimer);

                if (query === '') {
                    hideSuggestions();
                    return;
                }

                lastSearch = query;
                tagDebounceTimer = setTimeout(async () => {
                    suggestions = await fetchTagSuggestions(query);
                    showSuggestions();
                }, 300); // Debounce for fetching suggestions
                clearTimeout(spacePressTimer);
            }

            function handleTagKeyDown(e) {
                switch (e.key) {
                    case ' ':
                        clearTimeout(tagDebounceTimer);
                        clearTimeout(spacePressTimer);
                        spacePressTimer = setTimeout(() => {
                            const currentTag = tagInput.value.trim();
                            if (currentTag !== '') {
                                addTag(currentTag);
                            }
                        }, 4000); // 4-second debounce for adding tag on space
                        break;

                    case 'Tab':
                    case 'Enter':
                        if (suggestions.length > 0 && hoveredIndex >= 0) {
                            selectSuggestion(suggestions[hoveredIndex]);
                            e.preventDefault();
                        } else {
                            const currentTag = tagInput.value.trim();
                            if (currentTag !== '') {
                                addTag(currentTag);
                                e.preventDefault();
                            }
                        }
                        break;

                    case 'Backspace':
                        if (tagInput.value === '' && tags.length > 0) {
                            tags.pop();
                            renderTags();
                        }
                        break;

                    case 'ArrowUp':
                        if (suggestions.length > 0) {
                            hoveredIndex = Math.max(hoveredIndex - 1, 0);
                            highlightSuggestion();
                            e.preventDefault();
                        }
                        break;

                    case 'ArrowDown':
                        if (suggestions.length > 0) {
                            hoveredIndex = Math.min(hoveredIndex + 1, suggestions.length - 1);
                            highlightSuggestion();
                            e.preventDefault();
                        }
                        break;

                    case ',':
                        const tagName = tagInput.value.trim().replace(/,/g, '');
                        if (tagName !== '') {
                            addTag(tagName);
                            e.preventDefault();
                        }
                        break;
                }
            }

            function handleTagBlur() {
                clearTimeout(tagDebounceTimer);
                clearTimeout(spacePressTimer);

                if (lastSearch !== '') {
                    spacePressTimer = setTimeout(() => {
                        addTag(lastSearch);
                    }, 4000); // 4-second debounce for adding tag on blur
                } else {
                    hideSuggestions();
                }
            }

            async function fetchTagSuggestions(query) {
                try {
                    const response = await fetch(`${tagSuggestRoute}?query=${encodeURIComponent(query)}`);
                    if (!response.ok) throw new Error('Network response error');
                    return await response.json();
                } catch (error) {
                    console.error('Error fetching tag suggestions:', error);
                    return [];
                }
            }

            function addTag(tagName) {
                if (tagName === '') return;

                clearTimeout(tagDebounceTimer);
                clearTimeout(spacePressTimer);
                tagInput.value = '';
                lastSearch = '';

                const normalizedTagName = tagName.toLowerCase();

                // Check if tag already exists in the current list
                if (tags.some(tag => tag.normalized === normalizedTagName)) {
                    // If it exists, do not add again, but clear input
                    hideSuggestions();
                    return;
                }

                // Check if the tag exists in suggestions (meaning it's an existing tag in DB)
                const existingSuggestion = suggestions.find(s => s.name.toLowerCase() === normalizedTagName);

                if (existingSuggestion) {
                    tags.push({ name: existingSuggestion.name, normalized: existingSuggestion.name.toLowerCase() });
                } else {
                    // It's a new tag, check for similar names to suggest a unique one
                    let finalTagName = tagName;
                    let counter = 1;
                    while (tags.some(tag => tag.normalized === finalTagName.toLowerCase()) || suggestions.some(s => s.name.toLowerCase() === finalTagName.toLowerCase())) {
                        finalTagName = `${tagName}-${counter++}`;
                    }
                    tags.push({ name: finalTagName, normalized: finalTagName.toLowerCase() });
                }

                renderTags();
                hideSuggestions();
            }

            function showSuggestions() {
                if (suggestions.length === 0) {
                    hideSuggestions();
                    return;
                }

                tagSuggestions.innerHTML = '';
                suggestions.forEach((suggestion, index) => {
                    const suggestionElement = document.createElement('div');
                    suggestionElement.className = 'px-4 py-2 cursor-pointer hover:bg-blue-50 suggestion-item';
                    suggestionElement.textContent = suggestion.name;
                    suggestionElement.dataset.index = index;

                    suggestionElement.addEventListener('mouseenter', () => {
                        hoveredIndex = index;
                        highlightSuggestion();
                    });

                    suggestionElement.addEventListener('click', () => {
                        selectSuggestion(suggestion);
                    });

                    tagSuggestions.appendChild(suggestionElement);
                });

                tagSuggestions.classList.remove('hidden');
                hoveredIndex = 0;
                highlightSuggestion();
            }

            function hideSuggestions() {
                tagSuggestions.classList.add('hidden');
                hoveredIndex = -1;
            }

            function highlightSuggestion() {
                document.querySelectorAll('.suggestion-item').forEach((item, index) => {
                    item.classList.toggle('bg-blue-50', index === hoveredIndex);
                });
            }

            function selectSuggestion(suggestion) {
                addTag(suggestion.name);
            }
        });
    </script>
@endpush
