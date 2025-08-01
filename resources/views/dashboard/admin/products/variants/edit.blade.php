@extends('layouts.admin')

@section('title', 'Manage Variants for ' . $product->name . ' | ' . strtoupper(config('app.name')))

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

        <!-- Breadcrumb Navigation -->
        @include('components.breadcrumbs', [
            'links' => [
                'Products' => route('products.index'),
                $product->name => route('products.edit', $product->id),
                'Manage Variants' => null
            ]
        ])

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('products.edit', $product->id) }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Manage Variants for {{ $product->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">Edit and manage product variants</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                    Back to Product
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-lg">

            <form action="{{ route('products.variants.sync', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div id="variants-container">
                    @forelse ($product->variants as $variant)
                        <div class="variant-row border border-gray-300 p-4 mb-4 rounded-lg bg-gray-50">
                            <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="col-span-full mb-2">
                                    <h3 class="font-semibold text-lg text-gray-700">Variant #{{ $loop->iteration }}</h3>
                                </div>

                                <div class="form-group">
                                    <label class="block font-semibold text-gray-700 mb-2">Attributes</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($attributes as $attribute)
                                            <div class="flex items-center space-x-2">
                                                <span class="font-medium text-gray-600">{{ $attribute->name }}:</span>
                                                <select name="variants[{{ $loop->parent->index }}][attribute_value_ids][]" class="border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    @foreach($attribute->values as $value)
                                                        <option value="{{ $value->id }}"
                                                            {{ in_array($value->id, $variant->attributeValues->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $value->value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][price]" class="block font-semibold text-gray-700 mb-2">Price *</label>
                                    <input type="number" name="variants[{{ $loop->index }}][price]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.price', $variant->price) }}" step="0.01" required>
                                    @error('variants.'.$loop->index.'.price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][sale_price]" class="block font-semibold text-gray-700 mb-2">Sale Price</label>
                                    <input type="number" name="variants[{{ $loop->index }}][sale_price]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.sale_price', $variant->sale_price) }}" step="0.01">
                                    @error('variants.'.$loop->index.'.sale_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][sku]" class="block font-semibold text-gray-700 mb-2">SKU</label>
                                    <input type="text" name="variants[{{ $loop->index }}][sku]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.sku', $variant->sku) }}">
                                    @error('variants.'.$loop->index.'.sku')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][stock_quantity]" class="block font-semibold text-gray-700 mb-2">Stock Quantity *</label>
                                    <input type="number" name="variants[{{ $loop->index }}][stock_quantity]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.stock_quantity', $variant->stock_quantity) }}" required>
                                    @error('variants.'.$loop->index.'.stock_quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][stock_status]" class="block font-semibold text-gray-700 mb-2">Stock Status *</label>
                                    <select name="variants[{{ $loop->index }}][stock_status]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="in_stock" {{ old('variants.'.$loop->index.'.stock_status', $variant->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                        <option value="out_of_stock" {{ old('variants.'.$loop->index.'.stock_status', $variant->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                        <option value="backorder" {{ old('variants.'.$loop->index.'.stock_status', $variant->stock_status) == 'backorder' ? 'selected' : '' }}>Backorder</option>
                                    </select>
                                    @error('variants.'.$loop->index.'.stock_status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][barcode]" class="block font-semibold text-gray-700 mb-2">Barcode</label>
                                    <input type="text" name="variants[{{ $loop->index }}][barcode]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.barcode', $variant->barcode) }}">
                                    @error('variants.'.$loop->index.'.barcode')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][weight]" class="block font-semibold text-gray-700 mb-2">Weight (g)</label>
                                    <input type="number" name="variants[{{ $loop->index }}][weight]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.weight', $variant->weight) }}" step="0.01">
                                    @error('variants.'.$loop->index.'.weight')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][length]" class="block font-semibold text-gray-700 mb-2">Length (cm)</label>
                                    <input type="number" name="variants[{{ $loop->index }}][length]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.length', $variant->length) }}" step="0.01">
                                    @error('variants.'.$loop->index.'.length')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][width]" class="block font-semibold text-gray-700 mb-2">Width (cm)</label>
                                    <input type="number" name="variants[{{ $loop->index }}][width]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.width', $variant->width) }}" step="0.01">
                                    @error('variants.'.$loop->index.'.width')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="variants[{{ $loop->index }}][height]" class="block font-semibold text-gray-700 mb-2">Height (cm)</label>
                                    <input type="number" name="variants[{{ $loop->index }}][height]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('variants.'.$loop->index.'.height', $variant->height) }}" step="0.01">
                                    @error('variants.'.$loop->index.'.height')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger remove-variant mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">Remove Variant</button>
                        </div>
                    @empty
                        <p class="text-gray-600">No variants added yet. Click "Add Variant" to start.</p>
                    @endforelse
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="button" id="add-variant" class="px-5 py-2.5 bg-blue-500 text-white rounded-lg font-semibold text-sm hover:bg-blue-600 transition-all">Add Variant</button>
                    <button type="submit" class="px-5 py-2.5 bg-green-500 text-white rounded-lg font-semibold text-sm hover:bg-green-600 transition-all">Save Variants</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const variantsContainer = document.getElementById('variants-container');
            const addVariantButton = document.getElementById('add-variant');
            let variantIndex = {{ $product->variants->count() }}; // Start index after existing variants

            addVariantButton.addEventListener('click', function () {
                const newVariantRow = document.createElement('div');
                newVariantRow.classList.add('variant-row', 'border', 'border-gray-300', 'p-4', 'mb-4', 'rounded-lg', 'bg-gray-50');
                newVariantRow.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="col-span-full mb-2">
                            <h3 class="font-semibold text-lg text-gray-700">Variant #${variantIndex + 1}</h3>
                        </div>
                        <div class="form-group">
                            <label class="block font-semibold text-gray-700 mb-2">Attributes</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($attributes as $attribute)
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-gray-600">{{ $attribute->name }}:</span>
                                        <select name="variants[${variantIndex}][attribute_value_ids][]" class="border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @foreach($attribute->values as $value)
                                                <option value="{{ $value->id }}">{{ $value->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][price]" class="block font-semibold text-gray-700 mb-2">Price *</label>
                            <input type="number" name="variants[${variantIndex}][price]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][sale_price]" class="block font-semibold text-gray-700 mb-2">Sale Price</label>
                            <input type="number" name="variants[${variantIndex}][sale_price]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][sku]" class="block font-semibold text-gray-700 mb-2">SKU</label>
                            <input type="text" name="variants[${variantIndex}][sku]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][stock_quantity]" class="block font-semibold text-gray-700 mb-2">Stock Quantity *</label>
                            <input type="number" name="variants[${variantIndex}][stock_quantity]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][stock_status]" class="block font-semibold text-gray-700 mb-2">Stock Status *</label>
                            <select name="variants[${variantIndex}][stock_status]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="backorder">Backorder</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][barcode]" class="block font-semibold text-gray-700 mb-2">Barcode</label>
                            <input type="text" name="variants[${variantIndex}][barcode]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][weight]" class="block font-semibold text-gray-700 mb-2">Weight (g)</label>
                            <input type="number" name="variants[${variantIndex}][weight]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][length]" class="block font-semibold text-gray-700 mb-2">Length (cm)</label>
                            <input type="number" name="variants[${variantIndex}][length]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][width]" class="block font-semibold text-gray-700 mb-2">Width (cm)</label>
                            <input type="number" name="variants[${variantIndex}][width]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="variants[${variantIndex}][height]" class="block font-semibold text-gray-700 mb-2">Height (cm)</label>
                            <input type="number" name="variants[${variantIndex}][height]" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01">
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger remove-variant mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all">Remove Variant</button>
                `;
                variantsContainer.appendChild(newVariantRow);
                variantIndex++;
            });

            variantsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-variant')) {
                    e.target.closest('.variant-row').remove();
                }
            });
        });
    </script>
@endsection
