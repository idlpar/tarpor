@extends('layouts.admin')

@section('title', 'Add Product | ' . strtoupper(config('app.name')))

@section('page-content')
    <div class="w-full h-full bg-sky-100 p-4 md:p-8 transition-all duration-300">
        <!-- Breadcrumbs -->
        @include('components.breadcrumbs', [
            'links' => [
                'Dashboard' => route('admin.dashboard'),
                'Products' => route('product.index'),
                'Create Product' => null
            ],
             'title' => "Create Product"
        ])

        <!-- Form Container -->
        <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Name & Slug -->
                    <x-form.input name="name" id="name" label="Product Name" value="{{ old('name') }}" required />
                    <x-form.input
                        name="slug"
                        id="slug"
                        label="Slug"
                        value="{{ old('slug') }}"
                        urlPrefix="{{ config('app.url') . '/product/' }}"
                    />
                    <!-- Tag Input -->
{{--                    <x-form.input name="tags" id="tags" label="Tags" value="{{ old('tag') }}" required hasDropdown />--}}
{{--                    <input type="hidden" name="tag_ids" id="tag_ids" />--}}

                    <!-- Tag Input Field -->
                    <div class="form-group mb-4">
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <div class="mt-1 relative">
                            <!-- Tag Container -->
                            <div id="tag-container" class="flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg">
                                <!-- Tags will be dynamically inserted here -->
                            </div>
                            <!-- Input Field -->
                            <input
                                type="text"
                                id="tags-input"
                                class="mt-1 block w-full border-none focus:ring-0 focus:outline-none"
                                placeholder="Type tags and press space or comma"
                            >
                            <!-- Suggestions Dropdown -->
                            <div id="tag-suggestions" class="hidden absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto z-50">
                                <!-- Suggestions will be dynamically inserted here -->
                            </div>
                        </div>
                        <!-- Hidden Input for Tags -->
                        <input type="hidden" name="tags" id="tags-hidden">
                    </div>
                    <!-- Container for suggestions -->

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const tagContainer = document.getElementById('tag-container');
                            const tagsInput = document.getElementById('tags-input');
                            const tagSuggestions = document.getElementById('tag-suggestions');
                            const tagsHidden = document.getElementById('tags-hidden');

                            let tags = []; // Array to store tags

                            // Function to create a tag span
                            function createTagSpan(tagName) {
                                const span = document.createElement('span');
                                span.className = 'inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
                                span.textContent = tagName;

                                // Add a remove button
                                const removeButton = document.createElement('button');
                                removeButton.className = 'ml-2 text-blue-800 hover:text-blue-600 focus:outline-none';
                                removeButton.innerHTML = '&times;';
                                removeButton.addEventListener('click', function () {
                                    // Remove the tag from the array
                                    tags = tags.filter(tag => tag !== tagName);
                                    // Update the hidden input
                                    tagsHidden.value = JSON.stringify(tags);
                                    // Remove the span from the DOM
                                    span.remove();
                                });

                                span.appendChild(removeButton);
                                return span;
                            }

                            // Function to update the hidden input with tags
                            function updateHiddenInput() {
                                tagsHidden.value = JSON.stringify(tags);
                            }

                            // Handle input events
                            tagsInput.addEventListener('input', function (e) {
                                const input = e.target.value.trim();

                                if (input.endsWith(' ') || input.endsWith(',')) {
                                    const tagName = input.slice(0, -1).trim(); // Remove the last space or comma
                                    if (tagName && !tags.includes(tagName)) {
                                        tags.push(tagName);
                                        const tagSpan = createTagSpan(tagName);
                                        tagContainer.insertBefore(tagSpan, tagsInput);
                                        updateHiddenInput();
                                    }
                                    e.target.value = ''; // Clear the input
                                }

                                // Fetch suggestions
                                if (input.length > 0) {
                                    fetch(`/tag/suggestions?query=${input}`)
                                        .then(response => response.json())
                                        .then(suggestions => {
                                            if (suggestions.length > 0) {
                                                tagSuggestions.innerHTML = suggestions.map(tag => `
                            <div class="tag-suggestion px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                ${tag.name}
                            </div>
                        `).join('');
                                                tagSuggestions.classList.remove('hidden');
                                            } else {
                                                tagSuggestions.classList.add('hidden');
                                            }
                                        });
                                } else {
                                    tagSuggestions.classList.add('hidden');
                                }
                            });

                            // Handle suggestion selection
                            tagSuggestions.addEventListener('click', function (e) {
                                if (e.target.classList.contains('tag-suggestion')) {
                                    const tagName = e.target.textContent.trim();
                                    if (!tags.includes(tagName)) {
                                        tags.push(tagName);
                                        const tagSpan = createTagSpan(tagName);
                                        tagContainer.insertBefore(tagSpan, tagsInput);
                                        updateHiddenInput();
                                    }
                                    tagsInput.value = ''; // Clear the input
                                    tagSuggestions.classList.add('hidden');
                                }
                            });

                            // Handle Tab key for auto-completion
                            tagsInput.addEventListener('keydown', function (e) {
                                if (e.key === 'Tab') {
                                    e.preventDefault();
                                    const firstSuggestion = tagSuggestions.querySelector('.tag-suggestion');
                                    if (firstSuggestion) {
                                        const tagName = firstSuggestion.textContent.trim();
                                        if (!tags.includes(tagName)) {
                                            tags.push(tagName);
                                            const tagSpan = createTagSpan(tagName);
                                            tagContainer.insertBefore(tagSpan, tagsInput);
                                            updateHiddenInput();
                                        }
                                        tagsInput.value = ''; // Clear the input
                                        tagSuggestions.classList.add('hidden');
                                    }
                                }
                            });
                        });
                    </script>

                    <!-- Price, Sale Price, Cost Price & SKU -->
                    <x-form.input name="price" label="Regular Price" type="number" step="1.00" value="{{ old('price') }}" required />
                    <x-form.input name="sale_price" label="Sale Price" type="number" step="1.00" value="{{ old('sale_price') }}" />
                    <x-form.input name="cost_price" label="Cost Price" type="number" step="1.00" value="{{ old('cost_price') }}" />
                    <x-form.input name="sku" label="SKU" value="{{ old('sku') }}" />

                    <!-- Stock & Stock Status -->
                    <x-form.input name="stock_quantity" label="Stock Quantity" type="number" step="1.00" value="{{ old('stock_quantity') }}" required />
                    <x-form.select
                        name="stock_status"
                        label="Inventory Status"
                        :options="[
                            'in_stock' => 'Available Now',
                            'out_of_stock' => 'Sold Out',
                            'backorder' => 'Pre-Order'
                        ]"
                        selected="{{ old('stock_status') }}"
                        required
                    />

                    <!-- Short Description -->
                    <x-form.textarea name="short_description" label="Short Description">{{ old('short_description') }}</x-form.textarea>
                    <x-form.textarea name="description" label="Description" required rows="6">{{ old('description') }}</x-form.textarea>

                    <!-- Brand & Category -->
                    <x-form.select
                        name="brand_id"
                        label="Brand"
                        :options="$brands->pluck('name', 'id')"
                        selected="{{ old('brand_id') }}"
                    />

                    <x-form.category-tree
                        name="category_ids"
                        label="Category"
                        :categories="$categories"
                        :selected="old('category_ids', [])"
                        required
                    />

                    <!-- Images & Thumbnail -->
                    <x-form.file-upload
                        name="images[]"
                        label="Product Images"
                        uploadLabel="Upload Product Images"
                        ManyImagesInput="true"
                        multiple
                        description="(Upload multiple images)"
                    />
                    <x-form.file-upload name="thumbnail" label="Thumbnail Image" uploadLabel="Upload Thumbnail Image" isSingleImage="true" />

                    <!-- SEO Section -->
                    <x-form.seo :seo="null" />

                    <!-- Status -->
                    <x-form.select
                        name="status"
                        label="Listing Status"
                        :options="[
                            'draft' => 'Unpublished',
                            'published' => 'Live',
                            'archived' => 'Retired'
                        ]"
                        selected="{{ old('status', 'draft') }}"
                        class="mb-4"
                    />

                    <!-- Submit Button -->
                    <div class="col-span-1 lg:col-span-2 text-right">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Create Product
                        </button>
                    </div>
                </div>
            </form>
        </div>
@endsection
@push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                function convertToSlug(name) {
                    return name.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                }

                $('#name').on('input', function() {
                    var name = $(this).val();
                    var slug = convertToSlug(name);
                    $('#slug').val(slug);
                    checkSlugAvailability(slug);
                });

                $('#slug').on('input', function() {
                    checkSlugAvailability($(this).val());
                });

                function checkSlugAvailability(slug) {
                    $.ajax({
                        url: '{{ route('api.slug.check') }}',
                        method: 'GET',
                        data: { slug: slug },
                        success: function(response) {
                            if (response.exists) {
                                $('#slug').val(response.suggested);
                            }
                        },
                        error: function() {
                            console.log('Error checking slug');
                        }
                    });
                }
            });
        </script>
@endpush

