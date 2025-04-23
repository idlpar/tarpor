@extends('layouts.admin')

@section('title', 'Add Product | ' . strtoupper(config('app.name')))

@push('styles')
    <!-- Preload CKEditor styles -->
    <link rel="preload" href="{{ asset('ckeditor/content-styles.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('ckeditor/content-styles.css') }}"></noscript>

    <style>
        /* Hide body until loaded to prevent FOUC */
        body { visibility: hidden; }

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
        .hidden {
            display: none !important;
        }
    </style>
@endpush

@section('page-content')
    <!-- Loading spinner -->
    <div id="loading-spinner" class="text-4xl text-blue-500">Loading...</div>

    <!-- Breadcrumb Navigation -->
    @include('components.breadcrumbs', [
        'links' => [
            'Dashboard' => route('admin.dashboard'),
            'Products' => route('product.index'),
            'Create Product' => null
        ]
    ])

    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">
        <form action="#" method="POST" class="w-full flex flex-col lg:flex-row gap-6">
            @csrf
            <!-- Left Column -->
            <div class="w-full lg:w-9/12">
                <!-- Product Details -->
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">New Product</h2>

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block font-semibold text-gray-700 mb-2">Name *</label>
                        <input type="text" id="name" name="name" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Product Name">
                    </div>

                    <!-- Permalink -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-700 mb-2">Permalink *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 bg-teal-50 text-gray-500 select-none rounded-lg">{{ url('/product') . '/' }}</span>
                            <input type="text" name="slug" class="w-full pl-[230px] border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="your-slug">
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            Preview: <a href="#" class="text-blue-500 hover:underline" id="permalink-preview">{{ url('/product/your-slug') }}</a>
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-700 mb-2">Content</label>
                        <textarea id="content" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

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
                                <div class="relative w-24 h-24 rounded-lg overflow-hidden border border-gray-200 sortable-image">
                                    <img src="http://localhost:8000/storage/products/thumb/cumin-seed.jpg" alt="Cumin Seed" class="w-full h-full object-cover">
                                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-600 remove-image" data-index="0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div id="imageActionButtons" class="flex justify-start gap-4 mt-4 hidden">
                                <button type="button" id="addMoreImages" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 transition-all">Add Images</button>
                                <button type="button" id="resetImages" class="px-5 py-2.5 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition-all">Reset</button>
                            </div>
                        </div>
                        <input type="hidden" name="images" id="productImagesInput" value="">
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="bg-gray-300 p-6 rounded-lg shadow-lg mt-6">
                    <!-- Specification Tables -->
                    <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                        <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                            <label class="block font-semibold text-lg text-gray-700 mb-2">Specification Tables</label>
                            <select id="specificationDropdown" class="border text-sm p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">None</option>
                                <option value="general">General Specification</option>
                                <option value="technical">Technical Specification</option>
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
                    </div>

                    <!-- Overview -->
                    <x-form.card label="Overview" class="bg-transparent">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">SKU</label>
                                <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="SKU-CZA-PZ-997">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Price</label>
                                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tk. 0">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Price Sale</label>
                                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tk. 0">
                                <p class="text-sm text-gray-500 mt-2">Choose Discount Period</p>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Cost per Item</label>
                                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tk. 0">
                                <p class="text-sm text-gray-500 mt-2">Customers won't see this price.</p>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Stock Quantity</label>
                                <input type="number" name="stock_quantity" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter stock quantity" value="0" min="0">
                                <p class="text-sm text-gray-500 mt-2">Number of items available in stock.</p>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700 mb-2">Barcode (ISBN, UPC, GTIN, etc.)</label>
                                <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter barcode">
                                <p class="text-sm text-gray-500 mt-2">Must be unique for each product.</p>
                            </div>
                        </div>
                    </x-form.card>

                    <!-- Stock Status -->
                    <x-form.card label="Stock Status" class="bg-transparent">
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="stock_status" value="in_stock" class="mr-2">
                                <span class="text-gray-700">In Stock</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="stock_status" value="out_of_stock" class="mr-2">
                                <span class="text-gray-700">Out of Stock</span>
                            </label>
                        </div>
                    </x-form.card>

                    <!-- Shipping -->
                    <x-form.card label="Shipping" class="bg-transparent">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Weight (g)</label>
                                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Length (cm)</label>
                                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Width (cm)</label>
                                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                            </div>
                            <div>
                                <label class="block font-semibold text-sm text-gray-700 mb-2">Height (cm)</label>
                                <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                            </div>
                        </div>
                    </x-form.card>

                    <!-- Attributes -->
                    <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                        <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                            <label class="block text-xl font-bold text-gray-800">Attributes</label>
                            <button class="px-6 py-2 rounded-lg text-white text-sm font-semibold bg-blue-500 hover:bg-blue-600 transition-all">Add Attribute</button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Adding new attributes helps the product to have many options, such as size or color.</p>
                    </div>

                    <!-- Product Options -->
                    <x-form.card label="Product Options" class="bg-transparent">
                        <select class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Select Global Option</option>
                        </select>
                    </x-form.card>

                    <!-- Related Products -->
                    <x-form.card label="Related Products" class="bg-transparent">
                        <div class="relative">
                            <div class="relative">
                                <input type="text" id="related-products-search" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search products by name or SKU" autocomplete="off">
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
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <input type="hidden" name="related_products" id="related-products-input" value="{{ old('related_products', '[]') }}">
                        </div>
                    </x-form.card>

                    <!-- Cross-Selling Products -->
                    <x-form.card label="Cross-Selling Products" class="bg-transparent">
                        <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search products">
                    </x-form.card>

                    <!-- Product FAQs -->
                    <x-form.card label="Product FAQs" class="bg-transparent">
                        <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search or select from existing FAQs">
                    </x-form.card>

                    <!-- Search Engine Optimize -->
                    <div class="bg-white p-6 mb-6 shadow-lg rounded-lg">
                        <div class="flex justify-between items-center border-b border-gray-200 mb-4">
                            <label class="block text-xl font-bold text-gray-800">Search Engine Optimize</label>
                            <button class="px-6 py-2 rounded-lg text-white text-sm font-semibold bg-blue-500 hover:bg-blue-600 transition-all">Edit SEO Meta</button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Setup meta title & description to make your site easy to discover on search engines such as Google.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="w-full lg:w-3/12">
                <!-- Publish Card -->
                <div class="mb-6 bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Publish</h3>
                    <div class="pt-4 border-t border-gray-200 flex gap-4">
                        <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover-effect">Save</button>
                        <button class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover-effect">Save & Exit</button>
                    </div>
                </div>

                <!-- Status Card -->
                <x-form.card label="Status" required="true">
                    <select name="status" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </x-form.card>

                <!-- Store Card -->
                <x-form.card label="Store">
                    <select class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Select a store...</option>
                    </select>
                </x-form.card>

                <!-- Is Featured? Card -->
                <x-form.card label="Is Featured?">
                    <label class="relative inline-flex items-center cursor-pointer ml-2">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer" id="featuredToggle">
                        <div class="w-11 h-6 bg-gray-100 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </x-form.card>

                <!-- Categories Card -->
                <x-form.card label="Categories">
                    <div class="relative mb-3">
                        <input type="text" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" placeholder="Search..." id="category-search">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                <path d="M21 21l-6 -6"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="max-h-96 overflow-auto">
                        <ul id="category-tree" class="mt-2 space-y-1">
                            @include('partials.category-checkboxes', ['categories' => $categories])
                        </ul>
                    </div>
                </x-form.card>

                <!-- Brand Card -->
                <x-form.card label="Brand">
                    <div class="relative">
                        <input type="text" id="brand-search" class="w-full border border-gray-300 p-2.5 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Search brands...">
                        <div id="brand-dropdown" class="absolute z-20 bg-white mt-1 w-full border border-gray-200 rounded-md shadow-md hidden max-h-64 overflow-y-auto">
                            <ul id="brand-list" class="divide-y divide-gray-100 text-sm text-gray-700">
                                @foreach($brands as $brand)
                                    <li class="px-3 py-2 hover:bg-blue-50 cursor-pointer transition-all" data-value="{{ $brand->id }}">{{ $brand->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" name="brand_id" id="selected-brand-id">
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
                    <input type="hidden" name="featured_image" id="featuredImageInput" value="">
                </x-form.card>

                <x-gallery />

                <!-- Product Collections Card -->
                @php
                    $collections = old('product_collections', []);
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
                </x-form.card>

                <!-- Labels Card -->
                @php
                    $labels = old('labels', []);
                @endphp
                <x-form.card label="Labels">
                    <div class="flex flex-col space-y-3 bg-gray-50 rounded-lg">
                        @foreach (['hot' => ['🔥', 'red'], 'new' => ['🆕', 'green'], 'sale' => ['💸', 'blue']] as $value => [$emoji, $color])
                            <label x-data="{ checked: {{ in_array($value, $labels ?? []) ? 'true' : 'false' }} }" class="flex items-center space-x-3" :class="{ 'text-{{ $color }}-600': checked }">
                                <input type="checkbox" name="labels[]" value="{{ $value }}" x-model="checked" class="w-5 h-5 border-gray-300 rounded focus:ring-{{ $color }}-500">
                                <span class="font-medium">{{ ucfirst($value) }} {{ $emoji }}</span>
                            </label>
                        @endforeach
                    </div>
                </x-form.card>

                <!-- Minimum Order Quantity Card -->
                <x-form.card label="Minimum Order Quantity" required="true">
                    <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                    <p class="text-sm text-gray-500 mt-2">Minimum quantity to place an order, if the value is 0, there is no limit.</p>
                </x-form.card>

                <!-- Maximum Order Quantity Card -->
                <x-form.card label="Maximum Order Quantity" required="true">
                    <input type="number" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0">
                    <p class="text-sm text-gray-500 mt-2">Maximum quantity to place an order, if the value is 0, there is no limit.</p>
                </x-form.card>

                <!-- Tags Card -->
                <x-form.card label="Tags">
                    <div class="relative" id="tags-container">
                        <div class="flex flex-wrap gap-2 mb-2 items-center border border-gray-300 rounded-lg p-2 min-h-12" id="tags-input-wrapper">
                            <input type="text" id="tag-input" class="flex-grow outline-none min-w-[100px]" placeholder="Add tags..." autocomplete="off">
                        </div>
                        <div id="tag-suggestions" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto"></div>
                        <input type="hidden" name="tags" id="tags-hidden-input">
                    </div>
                </x-form.card>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- CKEditor Script -->
    <script src="{{ asset('ckeditor/ckeditor.js') }}" defer></script>

    <!-- Image Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const galleryManager = {
                productImagesInput: document.getElementById('productImagesInput'),
                selectedImagesPreview: document.getElementById('selectedImagesPreview'),
                defaultUploadContent: document.getElementById('defaultUploadContent'),
                imageActionButtons: document.getElementById('imageActionButtons'),
                featuredImageInput: document.getElementById('featuredImageInput'),
                featuredImageThumbnail: document.getElementById('featuredImageThumbnail'),
                featuredImagePreview: document.getElementById('featuredImagePreview'),
                featuredImageContainer: document.getElementById('featuredImageContainer'),

                addProductImage(file) {
                    if (!file?.id) {
                        console.warn('Invalid file:', file);
                        return;
                    }
                    const currentImages = JSON.parse(this.productImagesInput.value || '[]');
                    if (currentImages.includes(file.id)) return;

                    currentImages.push(file.id);
                    this.productImagesInput.value = JSON.stringify(currentImages);

                    const imageWrapper = document.createElement('div');
                    imageWrapper.className = 'relative w-24 h-24 rounded-lg overflow-hidden border-2 border-gray-300 hover:shadow-lg hover:scale-105 transition-all duration-200';
                    imageWrapper.innerHTML = `
                        <img src="${file.thumb_url || file.url}" alt="${file.name}" class="w-full h-full object-cover">
                        <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-600 remove-image">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    `;
                    this.selectedImagesPreview.appendChild(imageWrapper);
                    this.updateProductImageVisibility(currentImages.length);

                    imageWrapper.querySelector('.remove-image').addEventListener('click', (e) => {
                        e.stopPropagation();
                        imageWrapper.remove();
                        const updatedImages = currentImages.filter(id => id !== file.id);
                        this.productImagesInput.value = JSON.stringify(updatedImages);
                        this.updateProductImageVisibility(updatedImages.length);
                    });
                },

                setFeaturedImage(file) {
                    if (!file?.id) {
                        console.warn('Invalid file:', file);
                        return;
                    }
                    this.featuredImageThumbnail.src = file.thumb_url || file.url;
                    this.featuredImageThumbnail.alt = file.name;
                    this.featuredImagePreview.classList.remove('hidden');
                    this.featuredImageContainer.classList.add('hidden');
                    this.featuredImageInput.value = file.id;
                },

                updateProductImageVisibility(imageCount) {
                    this.selectedImagesPreview.classList.toggle('hidden', imageCount === 0);
                    this.defaultUploadContent.classList.add('hidden', imageCount !== 0);
                    this.imageActionButtons.classList.toggle('hidden', imageCount === 0);
                },

                resetProductImages() {
                    this.selectedImagesPreview.innerHTML = '';
                    this.productImagesInput.value = '[]';
                    this.updateProductImageVisibility(0);
                },

                init() {
                    const uploadArea = document.querySelector('.clickable-upload-area');
                    uploadArea?.removeEventListener('click', this.handleProductUpload);
                    uploadArea?.addEventListener('click', this.handleProductUpload.bind(this));

                    const addMoreImages = document.getElementById('addMoreImages');
                    addMoreImages?.removeEventListener('click', this.handleProductUpload);
                    addMoreImages?.addEventListener('click', this.handleProductUpload.bind(this));

                    const resetImages = document.getElementById('resetImages');
                    resetImages?.addEventListener('click', () => this.resetProductImages());

                    const featuredContainer = document.getElementById('featuredImageContainer');
                    featuredContainer?.removeEventListener('click', this.handleFeaturedUpload);
                    featuredContainer?.addEventListener('click', this.handleFeaturedUpload.bind(this));

                    const removeFeatured = document.getElementById('removeFeaturedImage');
                    removeFeatured?.addEventListener('click', () => {
                        this.featuredImageInput.value = '';
                        this.featuredImagePreview.classList.add('hidden');
                        this.featuredImageContainer.classList.remove('hidden');
                    });
                },

                handleProductUpload() {
                    window.openGalleryModal('', (files) => {
                        if (!files) return;
                        const fileArray = Array.isArray(files) ? files : [files];
                        fileArray.forEach(file => this.addProductImage(file));
                    }, { mode: 'multiple', accept: 'image/*' });
                },

                handleFeaturedUpload() {
                    window.openGalleryModal('', (file) => {
                        if (file) this.setFeaturedImage(file);
                    }, { mode: 'single', accept: 'image/*' });
                }
            };

            galleryManager.init();
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

    <!-- Page Load and CKEditor Initialization -->
    <script>
        document.getElementById('loading-spinner').style.display = 'block';
        window.addEventListener('load', () => {
            document.getElementById('loading-spinner').style.display = 'none';
            document.body.style.visibility = 'visible';

            setTimeout(() => {
                ['description', 'content'].forEach(id => {
                    ClassicEditor.create(document.querySelector(`#${id}`), {
                        toolbar: {
                            items: [
                                'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                                'blockQuote', 'imageUpload', 'insertTable', 'mediaEmbed', '|',
                                'undo', 'redo', 'code', 'codeBlock', 'strikethrough', 'underline', '|',
                                'alignment', 'fontSize', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                'horizontalLine', 'indent', 'outdent', 'removeFormat', '|',
                                'selectAll', 'findAndReplace', 'sourceEditing', 'fullscreen'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        image: {
                            toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side', 'linkImage']
                        },
                        language: 'en',
                        table: {
                            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'insertTable', 'tableProperties', 'tableCellProperties']
                        }
                    }).then(editor => {
                        editor.ui.view.editable.element.style.minHeight = '300px';
                    }).catch(error => console.error(error));
                });
            }, 100);
        });
    </script>

    <!-- Slug Generation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const slugInput = document.querySelector('input[name="slug"]');
            const permalinkPreview = document.getElementById('permalink-preview');
            const baseUrl = "{{ url('/product') }}/";
            function debounce(func, timeout = 500) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), timeout);
                };
            }
            function createSlug(text) {
                return text.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
            }
            function updatePreview(slug) {
                slug = slug || 'your-slug';
                permalinkPreview.textContent = baseUrl + slug;
                permalinkPreview.href = baseUrl + slug;
            }
            const checkSlug = debounce(async (slug) => {
                if (!slug || slug === 'your-slug') return;
                try {
                    const response = await fetch(`/product/slug/check?slug=${encodeURIComponent(slug)}`);
                    const data = await response.json();
                    updatePreview(data.suggested);
                    if (data.exists && slugInput.value === slug) {
                        slugInput.value = data.suggested;
                    }
                } catch (error) {
                    console.error('Error checking slug:', error);
                }
            });
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
            const debouncedFilter = ((func, timeout = 300) => {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), timeout);
                };
            })(filterCategories, 300);
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
                try {
                    if (skuInput) skuInput.value = 'Generating SKU...';
                    const response = await fetch('/generate-sku', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ category_ids: selectedCategories })
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
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            `;
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
            const searchProducts = ((func, wait) => {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            })(async (term) => {
                if (searchController) searchController.abort();
                searchController = new AbortController();
                if (!term || term.length < 2) {
                    resultsContainer.classList.add('hidden');
                    return;
                }
                loadingIndicator.classList.remove('hidden');
                resultsContainer.classList.add('hidden');
                try {
                    const response = await fetch(`/product/search?q=${encodeURIComponent(term)}`, {
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
                                <div class="text-sm text-gray-700 font-semibold">$${product.price}</div>
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
            async function loadSmartSuggestions() {
                const selectedCategories = Array.from(document.querySelectorAll('input[name="categories[]"]:checked')).map(el => el.value);
                const selectedTags = Array.from(document.querySelectorAll('input[name="tags[]"]:checked')).map(el => el.value);
                loadingIndicator.classList.remove('hidden');
                resultsContainer.innerHTML = '<div class="p-3 text-gray-500">Loading suggestions...</div>';
                resultsContainer.classList.remove('hidden');
                try {
                    const params = new URLSearchParams();
                    selectedCategories.forEach(id => params.append('category_ids[]', id));
                    selectedTags.forEach(id => params.append('tag_ids[]', id));
                    const response = await fetch(`/product/suggestions?${params.toString()}`);
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
                                    <div class="text-sm text-gray-700 font-semibold">$${suggestion.price}</div>
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

    <!-- Tags -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tagsContainer = document.getElementById('tags-container');
            const tagsInputWrapper = document.getElementById('tags-input-wrapper');
            const tagInput = document.getElementById('tag-input');
            const tagSuggestions = document.getElementById('tag-suggestions');
            const tagsHiddenInput = document.getElementById('tags-hidden-input');
            let tags = [];
            let suggestions = [];
            let hoveredIndex = -1;
            let lastSearch = '';
            if (tagsHiddenInput.value) {
                try {
                    tags = JSON.parse(tagsHiddenInput.value).map(tag => ({ name: tag }));
                    renderTags();
                } catch (e) {
                    console.error('Error parsing tags:', e);
                }
            }
            tagInput.addEventListener('input', handleInput);
            tagInput.addEventListener('keydown', handleKeyDown);
            tagInput.addEventListener('blur', handleBlur);
            function renderTags() {
                const existingTags = tagsInputWrapper.querySelectorAll('.tag-pill');
                existingTags.forEach(tag => tag.remove());
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
                    button.addEventListener('click', () => {
                        const index = parseInt(button.getAttribute('data-index'));
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
            async function handleInput() {
                const query = tagInput.value.trim();
                if (query === '' || query.length < 2 || query === lastSearch) {
                    hideSuggestions();
                    return;
                }
                lastSearch = query;
                try {
                    const response = await fetch(`/tag/suggest?query=${encodeURIComponent(query)}`);
                    suggestions = await response.json();
                    showSuggestions();
                } catch (error) {
                    console.error('Error fetching tag suggestions:', error);
                    suggestions = [];
                    hideSuggestions();
                }
            }
            function handleKeyDown(e) {
                switch (e.key) {
                    case ' ':
                        if (tagInput.value.trim() !== '') {
                            addTag(tagInput.value.trim());
                            e.preventDefault();
                        }
                        break;
                    case 'Tab':
                    case 'Enter':
                        if (suggestions.length > 0 && hoveredIndex >= 0) {
                            selectSuggestion(suggestions[hoveredIndex]);
                            e.preventDefault();
                        } else if (tagInput.value.trim() !== '') {
                            addTag(tagInput.value.trim());
                            e.preventDefault();
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
                }
            }
            function handleBlur() {
                setTimeout(() => {
                    if (!tagsContainer.contains(document.activeElement)) hideSuggestions();
                }, 200);
            }
            function addTag(tagName) {
                if (tagName === '') return;
                const capitalizedTagName = tagName.charAt(0).toUpperCase() + tagName.slice(1).toLowerCase();
                const normalizedTagName = capitalizedTagName.toLowerCase();
                if (!tags.some(tag => tag.name.toLowerCase() === normalizedTagName)) {
                    tags.push({ name: capitalizedTagName });
                    renderTags();
                }
                tagInput.value = '';
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
                    suggestionElement.addEventListener('click', () => selectSuggestion(suggestion));
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
                if (!tags.some(tag => tag.name.toLowerCase() === suggestion.name.toLowerCase())) {
                    tags.push({ name: suggestion.name });
                    renderTags();
                }
                tagInput.value = '';
                hideSuggestions();
                tagInput.focus();
            }
            const form = tagsContainer.closest('form');
            if (form) {
                form.addEventListener('submit', async () => {
                    const tagNames = tags.map(tag => tag.name);
                    try {
                        const response = await fetch('/tag/store-multiple', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ tags: tagNames })
                        });
                        if (!response.ok) console.error('Failed to store some tags');
                    } catch (error) {
                        console.error('Error storing tags:', error);
                    }
                });
            }
        });
    </script>
@endpush
