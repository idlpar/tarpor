@extends('layouts.admin')

@section('title', 'Edit Product | ' . strtoupper(config('app.name')))

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
            display: none;
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
        /*.hidden {
            display: none !important;
        }*/
    </style>
@endpush

@section('admin_content')
    <div class="min-h-screen bg-gray-100 p-6 md:p-8">
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
        <!-- Loading spinner -->
        <div id="loading-spinner" class="text-4xl text-blue-500">Loading...</div>

        <!-- Breadcrumb Navigation -->
        @include('components.breadcrumbs', [
            'links' => [
                'Products' => route('products.index'),
                'Edit Product' => null
            ]
        ])

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="w-full flex flex-col lg:flex-row gap-6" id="productForm">
                @method('PUT')
                @csrf
                <!-- Left Column -->
                <div class="w-full lg:w-9/12">
                    <!-- Product Details -->
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Product</h2>



                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block font-semibold text-gray-700 mb-2">Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="Product Name">
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permalink -->
                        <div class="mb-6">
                            <label for="slug" class="block font-semibold text-gray-700 mb-2">Permalink *</label>
                            <div class="relative">
                                <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror" placeholder="your-slug">
                            </div>
                            @error('slug')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-2">
                                Preview: <span class="text-gray-500">{{ url('/product') . '/' }}</span><a href="#" class="text-blue-500 hover:underline" id="permalink-preview">{{ old('slug', $product->slug) }}</a>
                            </p>
                        </div>

                        <!-- Description -->
                        <x-forms.ckeditor id="description" name="description" value="{{ old('description', $product->description) }}">Description</x-forms.ckeditor>

                        <!-- Content (short_description) -->
                        <x-forms.ckeditor id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}">Content</x-forms.ckeditor>

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
{{--                            <input type="hidden" name="images" id="productImagesInput" value="{{ Js::from(old('images', [])) }}">--}}
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
                            <input type="hidden" name="specifications" id="specifications-hidden-input" value="{{ old('specifications', $product->specifications->toJson()) }}">
                        </div>

                        <!-- Overview -->
                        <x-form.card label="Overview" class="bg-transparent">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sku') border-red-500 @enderror" placeholder="SKU-CZA-PZ-997">
                                    @error('sku')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Price</label>
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror" placeholder="Tk. 0" step="0.01">
                                    @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Price Sale</label>
                                    <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sale_price') border-red-500 @enderror" placeholder="Tk. 0" step="0.01">
                                    <p class="text-sm text-gray-500 mt-2">Choose Discount Period</p>
                                    @error('sale_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Cost per Item</label>
                                    <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cost_price') border-red-500 @enderror" placeholder="Tk. 0" step="0.01">
                                    <p class="text-sm text-gray-500 mt-2">Customers won't see this price.</p>
                                    @error('cost_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock_quantity') border-red-500 @enderror" placeholder="Enter stock quantity" min="0">
                                    <p class="text-sm text-gray-500 mt-2">Number of items available in stock.</p>
                                    @error('stock_quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Low Stock Threshold</label>
                                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('low_stock_threshold') border-red-500 @enderror" placeholder="0" min="0">
                                    <p class="text-sm text-gray-500 mt-2">Alert when stock falls below this quantity.</p>
                                    @error('low_stock_threshold')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 mb-2">Barcode (ISBN, UPC, GTIN, etc.)</label>
                                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('barcode') border-red-500 @enderror" placeholder="Enter barcode">
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
                                    <input type="radio" name="stock_status" value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'checked' : '' }} class="mr-2 @error('stock_status') border-red-500 @enderror">
                                    <span class="text-gray-700">In Stock</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="stock_status" value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'checked' : '' }} class="mr-2 @error('stock_status') border-red-500 @enderror">
                                    <span class="text-gray-700">Out of Stock</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="stock_status" value="backorder" {{ old('stock_status', $product->stock_status) == 'backorder' ? 'checked' : '' }} class="mr-2 @error('stock_status') border-red-500 @enderror">
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
                                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('weight')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-sm text-gray-700 mb-2">Length (cm)</label>
                                    <input type="number" name="length" value="{{ old('length', $product->length) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('length') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('length')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-sm text-gray-700 mb-2">Width (cm)</label>
                                    <input type="number" name="width" value="{{ old('width', $product->width) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('width') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('width')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-semibold text-sm text-gray-700 mb-2">Height (cm)</label>
                                    <input type="number" name="height" value="{{ old('height', $product->height) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('height') border-red-500 @enderror" placeholder="0" step="0.01">
                                    @error('height')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </x-form.card>

                        <!-- Attributes -->
                        <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                            <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                                <label class="block text-xl font-bold text-gray-800">Attributes</label>
                                <button type="button" class="px-6 py-2 rounded-lg text-white text-sm font-semibold bg-blue-500 hover:bg-blue-600 transition-all">Add Attribute</button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Adding new attributes helps the product to have many options, such as size or color.</p>
                        </div>

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
                                <input type="hidden" name="related_products" id="related-products-input" value="{{ old('related_products', json_encode($product->relatedProducts->pluck('id'))) }}">
                                @error('related_products')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </x-form.card>

                        <!-- Cross-Selling Products -->
                        <x-form.card label="Cross-Selling Products" class="bg-transparent">
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
                                @if(old('cross_selling_products', $product->crossSellingProducts->pluck('id')))
                                    @foreach(json_decode(old('cross_selling_products', $product->crossSellingProducts->pluck('id'))) as $crossSellingId)
                                        @php $crossSelling = App\Models\Product::find($crossSellingId); @endphp
                                        @if($crossSelling)
                                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded selected-product-item" data-id="{{ $crossSelling->id }}">
                                                <span>{{ $crossSelling->name }} (SKU: {{ $crossSelling->sku }})</span>
                                                <button type="button" class="text-red-500 remove-cross-selling-product">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <input type="hidden" name="cross_selling_products" id="cross-selling-products-input" value="{{ old('cross_selling_products', json_encode($product->crossSellingProducts->pluck('id'))) }}">
                            @error('cross_selling_products')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </x-form.card>

                        <!-- Product FAQs -->
                        <x-form.card label="Product FAQs" class="bg-transparent">
                            <div id="faqs-container">
                                @if(old('product_faqs'))
                                    @foreach(json_decode(old('product_faqs')) as $faq)
                                        <div class="faq-item mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                            <div class="mb-2">
                                                <label class="block font-semibold text-gray-700 mb-1">Question</label>
                                                <input type="text" name="product_faqs[][question]" value="{{ $faq->question ?? '' }}" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div class="mb-2">
                                                <label class="block font-semibold text-gray-700 mb-1">Answer</label>
                                                <textarea name="product_faqs[][answer]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $faq->answer ?? '' }}</textarea>
                                            </div>
                                            <button type="button" class="remove-faq-item text-red-500 hover:text-red-700 text-sm">Remove FAQ</button>
                                        </div>
                                    @endforeach
                                @elseif($product->faqs->isNotEmpty())
                                    @foreach($product->faqs as $faq)
                                        <div class="faq-item mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                            <div class="mb-2">
                                                <label class="block font-semibold text-gray-700 mb-1">Question</label>
                                                <input type="text" name="product_faqs[][question]" value="{{ $faq->question }}" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div class="mb-2">
                                                <label class="block font-semibold text-gray-700 mb-1">Answer</label>
                                                <textarea name="product_faqs[][answer]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $faq->answer }}</textarea>
                                            </div>
                                            <button type="button" class="remove-faq-item text-red-500 hover:text-red-700 text-sm">Remove FAQ</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-faq-item" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Add FAQ</button>
                            @error('product_faqs')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @error('product_faqs.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </x-form.card>

                        <!-- Search Engine Optimize -->
                        <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                            <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                                <label class="block text-xl font-bold text-gray-800">Search Engine Optimize</label>
                                <button type="button" class="px-6 py-2 rounded-lg text-white text-sm font-semibold bg-blue-500 hover:bg-blue-600 transition-all">Edit SEO Meta</button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Setup meta title & description to make your site easy to discover on search engines such as Google.</p>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Meta Title</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title', $product->seo->meta_title ?? '') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_title') border-red-500 @enderror" placeholder="Meta Title">
                                @error('meta_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Meta Description</label>
                                <textarea name="meta_description" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $product->seo->meta_description ?? '') }}</textarea>
                                @error('meta_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Meta Keywords</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->seo->meta_keywords ?? '') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('meta_keywords') border-red-500 @enderror" placeholder="Meta Keywords">
                                @error('meta_keywords')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Canonical URL</label>
                                <input type="url" name="canonical_url" value="{{ old('canonical_url', $product->seo->canonical_url ?? '') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('canonical_url') border-red-500 @enderror" placeholder="Canonical URL">
                                @error('canonical_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Open Graph Title</label>
                                <input type="text" name="og_title" value="{{ old('og_title', $product->seo->og_title ?? '') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('og_title') border-red-500 @enderror" placeholder="Open Graph Title">
                                @error('og_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Open Graph Description</label>
                                <textarea name="og_description" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('og_description') border-red-500 @enderror">{{ old('og_description', $product->seo->og_description ?? '') }}</textarea>
                                @error('og_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Open Graph Image</label>
                                <input type="file" name="og_image" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('og_image') border-red-500 @enderror">
                                @error('og_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Twitter Title</label>
                                <input type="text" name="twitter_title" value="{{ old('twitter_title', $product->seo->twitter_title ?? '') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('twitter_title') border-red-500 @enderror">
                                @error('twitter_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Twitter Description</label>
                                <textarea name="twitter_description" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('twitter_description') border-red-500 @enderror">{{ old('twitter_description', $product->seo->twitter_description ?? '') }}</textarea>
                                @error('twitter_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Twitter Image</label>
                                <input type="file" name="twitter_image" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('twitter_image') border-red-500 @enderror">
                                @error('twitter_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Schema Markup</label>
                                <textarea name="schema_markup" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('schema_markup') border-red-500 @enderror">{{ old('schema_markup', $product->seo->schema_markup ?? '') }}</textarea>
                                @error('schema_markup')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4">
                                <label class="block font-semibold text-gray-700 mb-2">Robots</label>
                                <input type="text" name="robots" value="{{ old('robots', $product->seo->robots ?? 'index, follow') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('robots') border-red-500 @enderror" placeholder="index, follow">
                                @error('robots')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-full lg:w-3/12 sticky top-6">
                    <!-- Publish Card -->
                    <div class="mb-6 bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Publish</h3>
                        <div class="pt-4 border-t border-gray-200 flex gap-4">
                            <button type="submit" id="saveButton" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover-effect">Save</button>
                            <button type="submit" id="saveExitButton" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover-effect">Save & Exit</button>
                        </div>
                    </div>



                    <!-- Product Type -->
                    <x-form.card label="Product Type" required="true">
                        <select name="type" id="product_type" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                            <option value="simple" {{ old('type', $product->type) == 'simple' ? 'selected' : '' }}>Simple Product</option>
                            <option value="variable" {{ old('type', $product->type) == 'variable' ? 'selected' : '' }}>Variable Product</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Status Card -->
                    <x-form.card label="Status" required="true">
                        <select name="status" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="published" {{ old('status', $product->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Store Card -->
                    <x-form.card label="Store">
                        <select name="store" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('store') border-red-500 @enderror">
                            <option value="">Select a store...</option>
                            <!-- Add store options dynamically if needed -->
                        </select>
                        @error('store')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Is Featured? Card -->
                    <x-form.card label="Is Featured?">
                        <label class="relative inline-flex items-center cursor-pointer ml-2">
                            <input type="checkbox" name="is_featured" value="1" class="sr-only peer" id="featuredToggle" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
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
                        <div class="max-h-[200px] overflow-auto">
                            <ul id="category-tree" class="mt-2 space-y-1">
                                @include('partials.category-checkboxes', ['categories' => $categories, 'selected' => old('category_ids', $product->categories->pluck('id')->toArray())])
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
                            <input type="text" id="brand-search" value="{{ old('brand_id', $product->brand_id) ? App\Models\Brand::find(old('brand_id', $product->brand_id))->name : '' }}" class="w-full border border-gray-300 p-2.5 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm @error('brand_id') border-red-500 @enderror" placeholder="Search brands...">
                            <div id="brand-dropdown" class="absolute z-20 bg-white mt-1 w-full border border-gray-200 rounded-md shadow-md hidden max-h-64 overflow-y-auto">
                                <ul id="brand-list" class="divide-y divide-gray-100 text-sm text-gray-700">
                                    @foreach($brands as $brand)
                                        <li class="px-3 py-2 hover:bg-blue-50 cursor-pointer transition-all" data-value="{{ $brand->id }}">{{ $brand->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" name="brand_id" id="selected-brand-id" value="{{ old('brand_id', $product->brand_id) }}">
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
                        <input type="hidden" name="thumbnail_existing" id="featuredImageInput" value="{{ old('thumbnail_existing', $product->thumbnail_media->id ?? '') }}">
                        @error('thumbnail_existing')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <x-gallery />

                    <!-- Product Collections Card -->
                    @php
                        $collections = old('product_collections', $product->collections->pluck('name')->toArray());
                    @endphp
                    <x-form.card label="Product Collections">
                        <div class="flex flex-col space-y-3 bg-gray-50 rounded-lg">
                            @foreach ([
                                'new_arrival' => ['New Arrival', 'text-indigo-600'],
                                'best_sellers' => ['Best Sellers', 'text-emerald-600'],
                                'special_offer' => ['Special Offer', 'text-amber-600'],
                            ] as $value => [$label, $activeClass])
                                <label class="flex items-center space-x-3" x-data="{ checked: {{ in_array($value, $collections) ? 'true' : 'false' }} }">
                                    <input type="checkbox" name="product_collections[]" value="{{ $value }}" @change="checked = $event.target.checked" {{ in_array($value, $collections) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span :class="checked ? '{{ $activeClass }} font-semibold' : 'text-gray-700 font-medium'">{{ $label }}</span>
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
                    @php
                        $labels = old('labels', collect($product->labels)->pluck('name')->toArray());
                    @endphp
                    <x-form.card label="Labels">
                        <div class="flex flex-col space-y-3 bg-gray-50 rounded-lg">
                            @foreach (['hot' => ['🔥', 'red'], 'new' => ['🆕', 'green'], 'sale' => ['💸', 'blue']] as $value => [$emoji, $color])
                                <label x-data="{ checked: {{ in_array($value, $labels) ? 'true' : 'false' }} }" class="flex items-center space-x-3" :class="{ 'text-{{ $color }}-600': checked }">
                                    <input type="checkbox" name="labels[]" value="{{ $value }}" x-model="checked" class="w-5 h-5 border-gray-300 rounded focus:ring-{{ $color }}-500">
                                    <span class="font-medium">{{ ucfirst($value) }} {{ $emoji }}</span>
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
                        <input type="number" name="min_order_quantity" value="{{ old('min_order_quantity', $product->min_order_quantity) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('min_order_quantity') border-red-500 @enderror" placeholder="0">
                        <p class="text-sm text-gray-500 mt-2">Minimum quantity to place an order, if the value is 0, there is no limit.</p>
                        @error('min_order_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </x-form.card>

                    <!-- Maximum Order Quantity Card -->
                    <x-form.card label="Maximum Order Quantity" required="true">
                        <input type="number" name="max_order_quantity" value="{{ old('max_order_quantity', $product->max_order_quantity) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_order_quantity') border-red-500 @enderror" placeholder="0">
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
                            <input type="hidden" name="tags" id="tags-hidden-input" value="{{ old('tags', optional($product->tags)->pluck('name')->toJson() ?? '[]') }}">
                            @error('tags')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </x-form.card>
                </div>
            </form>
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
        });
    </script>







    <!-- Image Handling, and Form Submission -->
    <script>
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
                    // Ensure we store the actual File object if available, or fetch it later if only ID is known
                    if (!file) return;
                    if (this.productImages.some(img => img.id === file.id)) return; // Prevent duplicates

                    this.productImages.push(file);
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
                        this.productImagesInput.value = '[]';
                        this.updateVisibility();
                        return;
                    }

                    this.productImages = this.productImages.filter(img => img && (img.id || img.file)); // Filter invalid images
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
                    const productMedia = @json($product->media);
                    if (productMedia && productMedia.length > 0) {
                        this.productImages = [];
                        let featuredImageFound = false;

                        productMedia.forEach(mediaItem => {
                            if (mediaItem.pivot.type === 'featured') {
                                this.setFeaturedImage(mediaItem);
                                featuredImageFound = true;
                            } else if (mediaItem.pivot.type === 'gallery') {
                                this.productImages.push(mediaItem);
                            }
                        });

                        if (!featuredImageFound && this.productImages.length > 0) {
                            this.setFeaturedImage(this.productImages[0]);
                            this.productImages.shift();
                        }

                        this.renderImages();
                        this.updateVisibility();
                    }
                },

                init() {
                    console.log('galleryManager.init() called');
                    this.initPreloadedImages();
                    const uploadArea = document.querySelector('.clickable-upload-area');
                    uploadArea?.addEventListener('click', () => {
                        console.log('Click event on uploadArea');
                        this.handleProductUpload();
                    });
                    const addMoreImages = document.getElementById('addMoreImages');
                    addMoreImages?.addEventListener('click', () => {
                        console.log('Click event on addMoreImages button');
                        this.handleProductUpload();
                    });
                    const resetImages = document.getElementById('resetImages');
                    resetImages?.addEventListener('click', () => this.resetImages());
                    const featuredContainer = document.getElementById('featuredImageContainer');
                    featuredContainer?.addEventListener('click', () => {
                        console.log('Click event on featuredImageContainer');
                        this.handleFeaturedUpload();
                    });
                    const removeFeatured = document.getElementById('removeFeaturedImage');
                    removeFeatured?.addEventListener('click', () => {
                        this.featuredImageInput.value = '';
                        this.featuredImageFile = null; // Clear the File object
                        this.featuredImagePreview.classList.add('hidden');
                        this.featuredImageContainer.classList.remove('hidden');
                    });
                    if (this.selectedImagesPreview) {
                        new Sortable(this.selectedImagesPreview, {
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
                    console.log('handleProductUpload called, dispatching open-gallery event');
                    const event = new CustomEvent('open-gallery', {
                        detail: {
                            callback: (files) => {
                                if (!files) return;
                                const fileArray = Array.isArray(files) ? files : [files];
                                fileArray.forEach(file => {
                                    this.addImage(file);
                                });
                            },
                            options: { mode: 'multiple', accept: 'image/*' }
                        }
                    });
                    window.dispatchEvent(event);
                },

                handleFeaturedUpload() {
                    console.log('handleFeaturedUpload called, dispatching open-gallery event');
                    const event = new CustomEvent('open-gallery', {
                        detail: {
                            callback: (file) => {
                                if (file) {
                                    this.setFeaturedImage(file);
                                }
                            },
                            options: { mode: 'single', accept: 'image/*' }
                        }
                    });
                    window.dispatchEvent(event);
                }
            };

            // Wait for the gallery to be ready before initializing the manager
            window.addEventListener('gallery-ready', () => {
                console.log('Product page: Gallery is ready, initializing galleryManager.');
                galleryManager.init();
            });

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

                // Sync CKEditor data
                ['description', 'short_description'].forEach(id => {
                    const textarea = document.querySelector(`#${id}`);
                    if (textarea && window.editors?.[id]) {
                        textarea.value = window.editors[id].getData();
                    }
                });

                // Clear previous errors
                document.querySelectorAll('.text-red-500.text-sm.mt-1').forEach(el => el.remove());
                document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                const formData = new FormData(form);

                // Append product images (new files and existing IDs)
                galleryManager.productImages.forEach((image) => {
                    if (image.file) {
                        formData.append('images_new[]', image.file); // New file upload
                    } else if (image.id) {
                        formData.append('images_existing[]', image.id); // Existing media ID
                    }
                });

                // Append featured image (new file or existing ID)
                if (galleryManager.featuredImageFile) {
                    formData.append('thumbnail_new', galleryManager.featuredImageFile); // New file upload
                }

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
                        }
                        // No form.reset() here for edit page, as we want to retain current state
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
            const specificationsHiddenInput = document.getElementById('specifications-hidden-input');

            function renderSpecifications(specs) {
                specTableBody.innerHTML = '';
                if (specs.length === 0) {
                    specFields.classList.add('hidden');
                    return;
                }
                specFields.classList.remove('hidden');
                specs.forEach(spec => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border p-3">${spec.group_name}</td>
                        <td class="border p-3">${spec.attribute_name}</td>
                        <td class="border p-3">
                            <input type="text" class="w-full border rounded p-2" value="${spec.attribute_value || ''}" data-group="${spec.group_name}" data-attribute="${spec.attribute_name}">
                        </td>
                    `;
                    specTableBody.appendChild(row);
                });
            }

            // Initial render based on preloaded data
            if (specificationsHiddenInput.value) {
                try {
                    const preloadedSpecs = JSON.parse(specificationsHiddenInput.value);
                    renderSpecifications(preloadedSpecs);
                } catch (e) {
                    console.error('Error parsing preloaded specifications:', e);
                }
            }

            dropdown.addEventListener('change', () => {
                const selectedType = dropdown.value;
                let specsToRender = [];

                if (selectedType === 'general') {
                    specsToRender = [
                        { group_name: 'General', attribute_name: 'Brand', attribute_value: '' },
                        { group_name: 'General', attribute_name: 'Model', attribute_value: '' }
                    ];
                } else if (selectedType === 'technical') {
                    specsToRender = [
                        { group_name: 'Battery', attribute_name: 'Battery Life', attribute_value: '' },
                        { group_name: 'Display', attribute_name: 'Screen Size', attribute_value: '' },
                        { group_name: 'Display', attribute_name: 'Resolution', attribute_value: '1920×1080' }
                    ];
                }
                renderSpecifications(specsToRender);
            });

            // Update hidden input on input change in the table
            specTableBody.addEventListener('input', (e) => {
                if (e.target.tagName === 'INPUT') {
                    const currentSpecs = [];
                    document.querySelectorAll('#specTableBody tr').forEach(row => {
                        const group = row.children[0].textContent.trim();
                        const attribute = row.children[1].textContent.trim();
                        const value = row.children[2].querySelector('input').value.trim();
                        currentSpecs.push({
                            group_name: group,
                            attribute_name: attribute,
                            attribute_value: value
                        });
                    });
                    specificationsHiddenInput.value = JSON.stringify(currentSpecs);
                }
            });
        });
    </script>

    <!-- Page Load -->
    <script>
        document.getElementById('loading-spinner').style.display = 'block';
        window.addEventListener('load', () => {
            document.getElementById('loading-spinner').style.display = 'none';
            document.body.style.visibility = 'visible';
        });
    </script>

    <!-- Slug Generation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const slugInput = document.querySelector('input[name="slug"]');
            const permalinkPreview = document.getElementById('permalink-preview');
            const baseUrl = "{{ url('/product') }}";

            function createSlug(text) {
                return text.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
            }

            function updatePreview(slug) {
                slug = slug || 'your-slug';
                permalinkPreview.textContent = slug;
                permalinkPreview.href = baseUrl + '/' + slug;
            }

            const checkSlug = debounce(async (slug) => {
                if (!slug || slug === 'your-slug') return;
                try {
                    const response = await fetch(`{{ route('api.slug.check') }}?slug=${encodeURIComponent(slug)}`);
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Error checking slug:', response.status, errorText);
                        // Optionally, display an error message to the user or revert to a default slug
                        return;
                    }
                    const data = await response.json();
                    updatePreview(data.suggested);
                    if (data.exists && slugInput.value === slug) {
                        slugInput.value = data.suggested;
                    }
                } catch (error) {
                    console.error('Error checking slug:', error);
                }
            }, 5000);

            nameInput.addEventListener('input', () => {
                const generatedSlug = createSlug(nameInput.value);
                slugInput.value = generatedSlug;
                updatePreview(generatedSlug);
                checkSlug(generatedSlug);
            });

            slugInput.addEventListener('input', () => {
                const manualSlug = slugInput.value.trim();
                if (manualSlug) {
                    updatePreview(manualSlug);
                    checkSlug(manualSlug);
                } else {
                    const generatedSlug = createSlug(nameInput.value);
                    slugInput.value = generatedSlug;
                    updatePreview(generatedSlug);
                }
            });

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
                if (!selectedCategories.length) {
                    if (skuInput) skuInput.value = '';
                    return;
                }
                console.log('Sending category IDs for SKU generation:', selectedCategories);
                try {
                    if (skuInput) skuInput.value = 'Generating SKU...';
                    const selectedBrandId = document.getElementById('selected-brand-id').value;
                    const productName = document.getElementById('name').value;

                    const response = await fetch(`/api/generate-sku`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            category_ids: selectedCategories,
                            brand_id: selectedBrandId,
                            product_name: productName
                        })
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

    <!-- Related Products -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('related-products-search');
            const resultsContainer = document.getElementById('related-products-results');
            const selectedContainer = document.getElementById('selected-related-products');
            const hiddenInput = document.getElementById('related-products-input');
            const loadingIndicator = document.getElementById('related-products-loading');
            const categorySelects = document.querySelectorAll('input[name="categories[]"]');
            const tagSelects = document.querySelectorAll('input[name="tags[]"]');
            let selectedProducts = JSON.parse(hiddenInput.value || '[]');
            let searchController = null;

            function updateSelectedProducts() {
                selectedProducts = selectedProducts.filter(id => id && !isNaN(id));
                hiddenInput.value = JSON.stringify(selectedProducts);
                selectedContainer.innerHTML = '';
                if (selectedProducts.length === 0) {
                    selectedContainer.classList.add('hidden');
                } else {
                    selectedContainer.classList.remove('hidden');
                    selectedProducts.forEach(async productId => {
                        try {
                            const response = await fetch(`/product/${productId}/brief`);
                            const product = await response.json();
                            const productEl = document.createElement('div');
                            productEl.className = 'flex items-center justify-between bg-gray-50 p-2 rounded selected-product-item';
                            productEl.dataset.id = product.id;
                            productEl.innerHTML = `
                                <span>${product.name} (SKU: ${product.sku})</span>
                                <button type="button" class="text-red-500 remove-related-product">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10L4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>`;
                            selectedContainer.appendChild(productEl);
                        } catch (error) {
                            console.error('Failed to fetch product:', error);
                        }
                    });
                }
            }

            updateSelectedProducts();

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
                    const response = await fetch(`/api/product/search?q=${encodeURIComponent(term)}`, {
                        signal: searchController.signal
                    });
                    if (!response.ok) throw new Error('Network response was not ok');
                    const products = await response.json();
                    resultsContainer.innerHTML = products.length > 0
                        ? products.map(product => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer flex items-center gap-4 border-b border-gray-200 product-result" data-id="${product.id}" data-name="${product.name}" data-sku="${product.sku}">
                                <img src="${product.thumbnail || '/placeholder-product.jpg'}" class="w-10 h-10 object-cover rounded-md">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">${product.name}</div>
                                    <div class="text-sm text-gray-500">SKU: ${product.sku}</div>
                                </div>
                                <div class="text-sm text-gray-700 font-semibold">${product.price}</div>
                            </div>
                        `).join('')
                        : '<div class="p-3 text-gray-500">No products found</div>';
                    resultsContainer.classList.remove('hidden');
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error('Search failed:', error);
                        resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Error loading results</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                } finally {
                    loadingIndicator.classList.add('hidden');
                }
            }

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
                const selectedCategories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(el => el.value);
                const selectedTags = Array.from(document.querySelectorAll('input[name="tags[]"]')).map(el => el.value);
                loadingIndicator.classList.remove('hidden');
                resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Loading suggestions...</div>';
                resultsContainer.classList.remove('hidden');
                try {
                    const params = new URLSearchParams();
                    selectedCategories.forEach(id => params.append('category_ids[]', id));
                    selectedTags.forEach(id => params.append('tag_ids[]', id));
                    const response = await fetch(`/api/product/suggestions?${params.toString()}`);
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
                                    <div class="text-sm text-gray-700 font-semibold">${suggestion.price}</div>
                                </div>
                            `).join('')}
                        `
                        : '<div class="p-3 text-gray-500">No suggestions available</div>';
                } catch (error) {
                    console.error('Failed to load suggestions:', error);
                    resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Error loading suggestions</div>';
                } finally {
                    loadingIndicator.classList.add('hidden');
                }
            }

            categorySelects.forEach(select => {
                select.addEventListener('change', () => {
                    if (searchInput === document.activeElement) loadSmartSuggestions();
                });
            });

            tagSelects.forEach(select => {
                select.addEventListener('change', () => {
                    if (searchInput === document.activeElement) loadSmartSuggestions();
                });
            });

            resultsContainer.addEventListener('click', (e) => {
                const resultItem = e.target.closest('.product-result');
                if (!resultItem) return;
                const productId = parseInt(resultItem.dataset.id);
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId);
                    updateSelectedProducts();
                }
                searchInput.value = '';
                resultsContainer.classList.add('hidden');
            });

            selectedContainer.addEventListener('click', (e) => {
                if (e.target.closest('.remove-related-product')) {
                    const item = e.target.closest('[data-id]');
                    const productId = parseInt(item.dataset.id);
                    selectedProducts = selectedProducts.filter(id => id !== productId);
                    updateSelectedProducts();
                }
            });
        });
    </script>

    <!-- Cross-Selling Products -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('cross-selling-products-search');
            const resultsContainer = document.getElementById('cross-selling-products-results');
            const selectedContainer = document.getElementById('selected-cross-selling-products');
            const hiddenInput = document.getElementById('cross-selling-products-input');
            const loadingIndicator = document.getElementById('cross-selling-products-loading');
            let selectedProducts = JSON.parse(hiddenInput.value || '[]');
            let searchController = null;

            function updateSelectedProducts() {
                selectedProducts = selectedProducts.filter(id => id && !isNaN(id));
                hiddenInput.value = JSON.stringify(selectedProducts);
                selectedContainer.innerHTML = '';
                if (selectedProducts.length === 0) {
                    selectedContainer.classList.add('hidden');
                } else {
                    selectedContainer.classList.remove('hidden');
                    selectedProducts.forEach(async productId => {
                        try {
                            const response = await fetch(`/product/${productId}/brief`);
                            const product = await response.json();
                            const productEl = document.createElement('div');
                            productEl.className = 'flex items-center justify-between bg-gray-50 p-2 rounded selected-product-item';
                            productEl.dataset.id = product.id;
                            productEl.innerHTML = `
                                <span>${product.name} (SKU: ${product.sku})</span>
                                <button type="button" class="text-red-500 remove-cross-selling-product">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10L4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>`;
                            selectedContainer.appendChild(productEl);
                        } catch (error) {
                            console.error('Failed to fetch product:', error);
                        }
                    });
                }
            }

            updateSelectedProducts();

            searchInput.addEventListener('focus', () => {
                searchInput.value = '';
                // No smart suggestions for cross-selling for now, just show empty results
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
                    const response = await fetch(`/api/product/search?q=${encodeURIComponent(term)}`, {
                        signal: searchController.signal
                    });
                    if (!response.ok) throw new Error('Network response was not ok');
                    const products = await response.json();
                    resultsContainer.innerHTML = products.length > 0
                        ? products.map(product => `
                            <div class="p-3 hover:bg-gray-100 cursor-pointer flex items-center gap-4 border-b border-gray-200 product-result" data-id="${product.id}" data-name="${product.name}" data-sku="${product.sku}">
                                <img src="${product.thumbnail || '/placeholder-product.jpg'}" class="w-10 h-10 object-cover rounded-md">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">${product.name}</div>
                                    <div class="text-sm text-gray-500">SKU: ${product.sku}</div>
                                </div>
                                <div class="text-sm text-gray-700 font-semibold">${product.price}</div>
                            </div>
                        `).join('')
                        : '<div class="p-3 text-gray-500">No products found</div>';
                    resultsContainer.classList.remove('hidden');
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error('Search failed:', error);
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

            resultsContainer.addEventListener('click', (e) => {
                const resultItem = e.target.closest('.product-result');
                if (!resultItem) return;
                const productId = parseInt(resultItem.dataset.id);
                if (!selectedProducts.includes(productId)) {
                    selectedProducts.push(productId);
                    updateSelectedProducts();
                }
                searchInput.value = '';
                resultsContainer.classList.add('hidden');
            });

            selectedContainer.addEventListener('click', (e) => {
                if (e.target.closest('.remove-cross-selling-product')) {
                    const item = e.target.closest('[data-id]');
                    const productId = parseInt(item.dataset.id);
                    selectedProducts = selectedProducts.filter(id => id !== productId);
                    updateSelectedProducts();
                }
            });
        });
    </script>

    <!-- Product FAQs -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqsContainer = document.getElementById('faqs-container');
            const addFaqButton = document.getElementById('add-faq-item');

            function renderFaqs(faqs) {
                faqsContainer.innerHTML = ''; // Clear existing FAQs
                faqs.forEach((faq, index) => {
                    const faqItem = document.createElement('div');
                    faqItem.className = 'faq-item mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50';
                    faqItem.innerHTML = `
                        <div class="mb-2">
                            <label class="block font-semibold text-gray-700 mb-1">Question</label>
                            <input type="text" name="product_faqs[${index}][question]" value="${faq.question || ''}" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-2">
                            <label class="block font-semibold text-gray-700 mb-1">Answer</label>
                            <textarea name="product_faqs[${index}][answer]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">${faq.answer || ''}</textarea>
                        </div>
                        <button type="button" class="remove-faq-item text-red-500 hover:text-red-700 text-sm">Remove FAQ</button>
                    `;
                    faqsContainer.appendChild(faqItem);
                });
                attachRemoveFaqListeners();
            }

            function addEmptyFaq() {
                const index = faqsContainer.children.length;
                const faqItem = document.createElement('div');
                faqItem.className = 'faq-item mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50';
                faqItem.innerHTML = `
                    <div class="mb-2">
                        <label class="block font-semibold text-gray-700 mb-1">Question</label>
                        <input type="text" name="product_faqs[${index}][question]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-2">
                        <label class="block font-semibold text-gray-700 mb-1">Answer</label>
                        <textarea name="product_faqs[${index}][answer]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="button" class="remove-faq-item text-red-500 hover:text-red-700 text-sm">Remove FAQ</button>
                `;
                faqsContainer.appendChild(faqItem);
                attachRemoveFaqListeners();
            }

            function attachRemoveFaqListeners() {
                document.querySelectorAll('.remove-faq-item').forEach(button => {
                    button.onclick = (e) => {
                        e.target.closest('.faq-item').remove();
                        updateFaqInputNames();
                    };
                });
            }

            function updateFaqInputNames() {
                document.querySelectorAll('.faq-item').forEach((item, index) => {
                    item.querySelector('input[name*="[question]"]').name = `product_faqs[${index}][question]`;
                    item.querySelector('textarea[name*="[answer]"]').name = `product_faqs[${index}][answer]`;
                });
            }

            // Load existing FAQs
            const preloadedFaqs = @json($product->faqs);
            if (preloadedFaqs && preloadedFaqs.length > 0) {
                renderFaqs(preloadedFaqs);
            }

            addFaqButton.addEventListener('click', addEmptyFaq);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productTypeSelect = document.getElementById('product_type');
            const simpleProductFields = document.querySelectorAll('.x-form-card[label="Overview"], .x-form-card[label="Stock Status"], .x-form-card[label="Shipping"]');
            const manageVariantsButton = document.querySelector('a[href*="products.variants.edit"]');

            function toggleProductFields() {
                const isSimpleProduct = productTypeSelect.value === 'simple';

                // Select specific inventory-related input fields
                const stockQuantityInput = document.querySelector('input[name="stock_quantity"]');
                const lowStockThresholdInput = document.querySelector('input[name="low_stock_threshold"]');
                const barcodeInput = document.querySelector('input[name="barcode"]');
                const stockStatusRadios = document.querySelectorAll('input[name="stock_status"]');

                // Enable/disable stock quantity and barcode
                if (stockQuantityInput) {
                    stockQuantityInput.disabled = !isSimpleProduct;
                    stockQuantityInput.closest('div').style.opacity = isSimpleProduct ? '1' : '0.5';
                    stockQuantityInput.closest('div').style.pointerEvents = isSimpleProduct ? 'auto' : 'none';
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

                // Toggle visibility of the "Manage Variants" button
                if (manageVariantsButton) {
                    manageVariantsButton.style.display = isSimpleProduct ? 'none' : 'block';
                }

                // Toggle visibility of simple product fields cards
                simpleProductFields.forEach(card => {
                    card.style.display = isSimpleProduct ? 'block' : 'none';
                });
            }

            productTypeSelect.addEventListener('change', toggleProductFields);
            toggleProductFields(); // Initial call on page load
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqsContainer = document.getElementById('faqs-container');
            const addFaqButton = document.getElementById('add-faq-item');

            function renderFaqs(faqs) {
                faqsContainer.innerHTML = ''; // Clear existing FAQs
                faqs.forEach((faq, index) => {
                    const faqItem = document.createElement('div');
                    faqItem.className = 'faq-item mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50';
                    faqItem.innerHTML = `
                        <div class="mb-2">
                            <label class="block font-semibold text-gray-700 mb-1">Question</label>
                            <input type="text" name="product_faqs[${index}][question]" value="${faq.question || ''}" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-2">
                            <label class="block font-semibold text-gray-700 mb-1">Answer</label>
                            <textarea name="product_faqs[${index}][answer]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">${faq.answer || ''}</textarea>
                        </div>
                        <button type="button" class="remove-faq-item text-red-500 hover:text-red-700 text-sm">Remove FAQ</button>
                    `;
                    faqsContainer.appendChild(faqItem);
                });
                attachRemoveFaqListeners();
            }

            function addEmptyFaq() {
                const index = faqsContainer.children.length;
                const faqItem = document.createElement('div');
                faqItem.className = 'faq-item mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50';
                faqItem.innerHTML = `
                    <div class="mb-2">
                        <label class="block font-semibold text-gray-700 mb-1">Question</label>
                        <input type="text" name="product_faqs[${index}][question]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-2">
                        <label class="block font-semibold text-gray-700 mb-1">Answer</label>
                        <textarea name="product_faqs[${index}][answer]" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="button" class="remove-faq-item text-red-500 hover:text-red-700 text-sm">Remove FAQ</button>
                `;
                faqsContainer.appendChild(faqItem);
                attachRemoveFaqListeners();
            }

            function attachRemoveFaqListeners() {
                document.querySelectorAll('.remove-faq-item').forEach(button => {
                    button.onclick = (e) => {
                        e.target.closest('.faq-item').remove();
                        updateFaqInputNames();
                    };
                });
            }

            function updateFaqInputNames() {
                document.querySelectorAll('.faq-item').forEach((item, index) => {
                    item.querySelector('input[name*="[question]"]').name = `product_faqs[${index}][question]`;
                    item.querySelector('textarea[name*="[answer]"]').name = `product_faqs[${index}][answer]`;
                });
            }

            // Load existing FAQs
            const preloadedFaqs = @json($product->faqs);
            if (preloadedFaqs && preloadedFaqs.length > 0) {
                renderFaqs(preloadedFaqs);
            }

            addFaqButton.addEventListener('click', addEmptyFaq);
        });
    </script>
    @endpush
