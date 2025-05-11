{{--@extends('layouts.admin')--}}

{{--@section('title', 'Edit Product | ' . strtoupper(config('app.name')))--}}

{{--@section('page-content')--}}
{{--    <div class="w-full h-full bg-sky-100 p-4 md:p-8 transition-all duration-300">--}}
{{--        <!-- Breadcrumbs -->--}}
{{--        @include('components.breadcrumbs', [--}}
{{--            'links' => [--}}
{{--                'Dashboard' => route('admin.dashboard'),--}}
{{--                'Products' => route('product.index'),--}}
{{--                'Edit Product' => null--}}
{{--            ],--}}
{{--             'title' => "Edit Product"--}}
{{--        ])--}}

{{--        <!-- Form Container -->--}}
{{--        <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-8">--}}
{{--            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">--}}
{{--                @csrf--}}
{{--                @method('PUT')--}}
{{--                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">--}}
{{--                    <!-- Name & Slug -->--}}
{{--                    <x-form.input name="name" id="name" label="Product Name" value="{{ old('name', $product->name) }}" required />--}}
{{--                    <x-form.input--}}
{{--                        name="slug"--}}
{{--                        id="slug"--}}
{{--                        label="Slug"--}}
{{--                        value="{{ old('slug', $product->slug) }}"--}}
{{--                        urlPrefix="{{ config('app.url') . '/product/' }}"--}}
{{--                    />--}}

{{--                    <!-- Price, Sale Price, Cost Price & SKU -->--}}
{{--                    <x-form.input name="price" label="Regular Price" type="number" step="1.00" value="{{ old('price', $product->price) }}" required />--}}
{{--                    <x-form.input name="sale_price" label="Sale Price" type="number" step="1.00" value="{{ old('sale_price', $product->sale_price) }}" />--}}
{{--                    <x-form.input name="cost_price" label="Cost Price" type="number" step="1.00" value="{{ old('cost_price', $product->cost_price) }}" />--}}
{{--                    <x-form.input name="sku" label="SKU" value="{{ old('sku', $product->sku) }}" />--}}

{{--                    <!-- Stock & Stock Status -->--}}
{{--                    <x-form.input name="stock_quantity" label="Stock Quantity" type="number" step="1.00" value="{{ old('stock_quantity', $product->stock_quantity) }}" required />--}}
{{--                    <x-form.select--}}
{{--                        name="stock_status"--}}
{{--                        label="Inventory Status"--}}
{{--                        :options="[--}}
{{--                            'in_stock' => 'Available Now',--}}
{{--                            'out_of_stock' => 'Sold Out',--}}
{{--                            'backorder' => 'Pre-Order'--}}
{{--                        ]"--}}
{{--                        selected="{{ old('stock_status', $product->stock_status) }}"--}}
{{--                        required--}}
{{--                    />--}}

{{--                    <!-- Short Description -->--}}
{{--                    <x-form.textarea name="short_description" label="Short Description">{{ old('short_description', $product->short_description) }}</x-form.textarea>--}}
{{--                    <x-form.textarea name="description" label="Description" required rows="6">{{ old('description', $product->description) }}</x-form.textarea>--}}

{{--                    <!-- Brand & Category -->--}}
{{--                    <x-form.select--}}
{{--                        name="brand_id"--}}
{{--                        label="Brand"--}}
{{--                        :options="$brands->pluck('name', 'id')"--}}
{{--                        selected="{{ old('brand_id', $product->brand_id) }}"--}}
{{--                    />--}}

{{--                    <x-form.category-tree--}}
{{--                        name="category_ids"--}}
{{--                        label="Category"--}}
{{--                        :categories="$categories"--}}
{{--                        :selected="old('category_ids', $product->categories->pluck('id')->toArray())"--}}
{{--                        required--}}
{{--                    />--}}

{{--                    <!-- Images & Thumbnail -->--}}
{{--                    <x-form.file-upload--}}
{{--                        name="images[]"--}}
{{--                        label="Product Images"--}}
{{--                        uploadLabel="Upload Product Images"--}}
{{--                        ManyImagesInput="true"--}}
{{--                        multiple--}}
{{--                        :existingImages="$existingImages"--}}
{{--                        description="(Please remove your previous product image before uploading new image or images)"--}}
{{--                    />--}}
{{--                    <x-form.file-upload name="thumbnail" label="Thumbnail Image" uploadLabel="Upload Thumbnail Images" isSingleImage="true" :existingImages="$thumbnail" />--}}

{{--                    <!-- SEO Section -->--}}
{{--                    <x-form.seo :seo="$product->seo" />--}}


{{--                    <!-- Status -->--}}
{{--                    <x-form.select--}}
{{--                        name="status"--}}
{{--                        label="Listing Status"--}}
{{--                        :options="[--}}
{{--                            'draft' => 'Unpublished',--}}
{{--                            'published' => 'Live',--}}
{{--                            'archived' => 'Retired'--}}
{{--                        ]"--}}
{{--                        selected="{{ old('status', $product->status) }}"--}}
{{--                        class="mb-4"--}}
{{--                    />--}}

{{--                    <!-- Submit Button -->--}}
{{--                    <div class="col-span-1 lg:col-span-2 text-right">--}}
{{--                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">--}}
{{--                            Update Product--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
