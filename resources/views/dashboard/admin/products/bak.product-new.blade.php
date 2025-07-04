@extends('layouts.admin')

@section('title', 'Add Product | ' . strtoupper(config('app.name')))


@section('page-content')
    <div class="w-full h-full bg-sky-100 p-4 md:p-8 transition-all duration-300">
        <!-- Breadcrumbs -->
        @include('components.breadcrumbs', [
            'links' => [
                'Dashboard' => role_route('dashboard'),
                'Products' => role_route('products.index'),
                'Create Product' => null
            ],
            'title' => 'Create Product'
        ])

        <!-- Form Container -->
        <div class="max-w-full mx-auto bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-span-1">
                        <!-- Hidden Input for Storing Tags -->
                        <input type="hidden" name="tags" id="tags" />

                        <!-- Label -->
                        <label class="block text-sm font-medium text-gray-700">
                            Tags <span class="text-red-500">*</span>
                        </label>

                        <!-- Tag Input Container -->
                        <div class="border border-gray-300 rounded-lg p-3 w-full max-w-full">
                            <!-- Tags Container -->
                            <div id="tags-container" class="flex flex-wrap gap-2 mb-2">
                                <!-- Tags will be dynamically added here -->
                            </div>

                            <!-- Input Field -->
                            <input
                                type="text"
                                id="tag-input"
                                class="w-full outline-none focus:ring-0 placeholder-gray-400"
                                placeholder="Add tags..."
                            />

                            <!-- Suggestions Container -->
                            <div id="suggestions-container" class="mt-2 space-y-1">
                                <!-- Suggestions will be dynamically added here -->
                            </div>
                        </div>
                    </div>
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
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const tagInput = document.getElementById('tag-input');
                    const tagsContainer = document.getElementById('tags-container');
                    const suggestionsContainer = document.getElementById('suggestions-container');
                    const hiddenTagsInput = document.getElementById('tags'); // Hidden input for form submission

                    // Timer for auto-wrapping non-existing tags
                    let autoWrapTimer;

                    // Debounce timer for suggestions
                    let debounceTimer;

                    // Add tag
                    function addTag(tagText) {
                        const formattedTag = capitalizeFirstLetter(tagText); // Capitalize first letter
                        // Create a new tag element
                        const tag = document.createElement('span');
                        tag.className = 'inline-flex items-center bg-blue-100 rounded-full px-3 py-1 text-sm';
                        tag.innerHTML = `
                ${formattedTag}
                <span class="ml-2 cursor-pointer" onclick="window.removeTag(this.parentElement)">×</span>
            `;
                        tagsContainer.appendChild(tag);

                        // Update the hidden input with selected tags
                        updateHiddenTags();
                    }

                    // Remove tag (attached to the window object for global access)
                    window.removeTag = function (tagElement) {
                        tagElement.remove(); // Remove the tag from the UI
                        updateHiddenTags(); // Update the hidden input
                    };

                    // Update the hidden input with selected tags
                    function updateHiddenTags() {
                        const tags = Array.from(tagsContainer.querySelectorAll('span')).map(tag =>
                            tag.textContent.replace('×', '').trim() // Extract tag text
                        );
                        hiddenTagsInput.value = tags.join(','); // Update the hidden input
                    }

                    // Fetch tag suggestions from the backend
                    async function fetchTagSuggestions(query) {
                        const response = await fetch(`{{ route('tag.suggest') }}?query=${query}`);
                        const data = await response.json();
                        return data;
                    }

                    // Show suggestions in the dropdown
                    async function showSuggestions(input) {
                        suggestionsContainer.innerHTML = ''; // Clear previous suggestions
                        if (input) {
                            const suggestions = await fetchTagSuggestions(input);

                            // Use a Set to ensure unique suggestions
                            const uniqueSuggestions = [...new Set(suggestions.map(suggestion => capitalizeFirstLetter(suggestion.name)))];

                            uniqueSuggestions.forEach(suggestion => {
                                const suggestionElement = document.createElement('div');
                                suggestionElement.className = 'px-3 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200';
                                suggestionElement.textContent = suggestion;
                                suggestionElement.addEventListener('click', function () {
                                    addTag(suggestion); // Add the selected tag
                                    tagInput.value = ''; // Clear the input field
                                    suggestionsContainer.innerHTML = ''; // Clear the dropdown
                                    clearTimeout(autoWrapTimer); // Clear the auto-wrap timer
                                });
                                suggestionsContainer.appendChild(suggestionElement);
                            });
                        }
                    }

                    // Debounce function to reduce API calls
                    function debounce(func, delay) {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(func, delay);
                    }

                    // Handle input events with debouncing
                    tagInput.addEventListener('input', function () {
                        const inputValue = tagInput.value.trim();

                        // Clear the auto-wrap timer
                        clearTimeout(autoWrapTimer);

                        // Debounce the showSuggestions function
                        debounce(() => {
                            if (inputValue) {
                                showSuggestions(inputValue); // Show suggestions
                            } else {
                                suggestionsContainer.innerHTML = ''; // Clear the dropdown if input is empty
                            }
                        }, 300); // 300ms delay

                        // Auto-wrap non-existing tags after 3 seconds
                        if (inputValue) {
                            autoWrapTimer = setTimeout(async () => {
                                const suggestions = await fetchTagSuggestions(inputValue);
                                if (!suggestions.some(suggestion => suggestion.name === inputValue)) {
                                    addTag(inputValue); // Add the input value as a new tag
                                    tagInput.value = ''; // Clear the input field
                                    suggestionsContainer.innerHTML = ''; // Clear the dropdown
                                }
                            }, 60000); // 60-second delay
                        }
                    });

                    // Handle keydown events
                    tagInput.addEventListener('keydown', async function (e) {
                        const inputValue = tagInput.value.trim();

                        // Auto-complete on Tab
                        if (e.key === 'Tab' && inputValue) {
                            e.preventDefault();
                            const suggestions = await fetchTagSuggestions(inputValue);
                            if (suggestions.length > 0) {
                                const matchingTag = suggestions[0].name; // Use the first suggestion
                                addTag(matchingTag); // Add the matching tag
                                tagInput.value = ''; // Clear the input field
                                suggestionsContainer.innerHTML = ''; // Clear the dropdown
                                clearTimeout(autoWrapTimer); // Clear the auto-wrap timer
                            }
                        }

                        // Wrap tag on comma or space
                        if ((e.key === ',' || e.key === ' ') && inputValue) {
                            e.preventDefault();
                            addTag(inputValue); // Add the input value as a tag
                            tagInput.value = ''; // Clear the input field
                            suggestionsContainer.innerHTML = ''; // Clear the dropdown
                            clearTimeout(autoWrapTimer); // Clear the auto-wrap timer
                        }
                    });

                    // Helper function to capitalize the first letter of a string
                    function capitalizeFirstLetter(string) {
                        return string.charAt(0).toUpperCase() + string.slice(1);
                    }
                });
            </script>
{{--            <script>--}}
{{--                document.addEventListener('DOMContentLoaded', function () {--}}
{{--                    const tagInput = document.getElementById('tag-input');--}}
{{--                    const tagsContainer = document.getElementById('tags-container');--}}
{{--                    const suggestionsContainer = document.getElementById('suggestions-container');--}}

{{--                    // Timer for auto-wrapping non-existing tags--}}
{{--                    let autoWrapTimer;--}}

{{--                    // Debounce timer for suggestions--}}
{{--                    let debounceTimer;--}}

{{--                    // Add tag--}}
{{--                    function addTag(tagText) {--}}
{{--                        const tag = document.createElement('span');--}}
{{--                        tag.className = 'inline-flex items-center bg-blue-100 rounded-full px-3 py-1 text-sm';--}}
{{--                        tag.innerHTML = `--}}
{{--                ${tagText}--}}
{{--                <span class="ml-2 cursor-pointer" onclick="this.parentElement.remove()">×</span>--}}
{{--            `;--}}
{{--                        tagsContainer.appendChild(tag);--}}
{{--                    }--}}

{{--                    // Fetch tag suggestions from the backend--}}
{{--                    async function fetchTagSuggestions(query) {--}}
{{--                        const response = await fetch(`{{ route('tag.suggest') }}?query=${query}`);--}}
{{--                        const data = await response.json();--}}
{{--                        return data;--}}
{{--                    }--}}

{{--                    // Update the showSuggestions function--}}
{{--                    async function showSuggestions(input) {--}}
{{--                        suggestionsContainer.innerHTML = '';--}}
{{--                        if (input) {--}}
{{--                            const suggestions = await fetchTagSuggestions(input);--}}

{{--                            // Use a Set to ensure unique suggestions--}}
{{--                            const uniqueSuggestions = [...new Set(suggestions.map(suggestion => suggestion.name))];--}}

{{--                            uniqueSuggestions.forEach(suggestion => {--}}
{{--                                const suggestionElement = document.createElement('div');--}}
{{--                                suggestionElement.className = 'px-3 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200';--}}
{{--                                suggestionElement.textContent = suggestion;--}}
{{--                                suggestionElement.addEventListener('click', function () {--}}
{{--                                    addTag(suggestion);--}}
{{--                                    tagInput.value = '';--}}
{{--                                    suggestionsContainer.innerHTML = '';--}}
{{--                                    clearTimeout(autoWrapTimer); // Clear the timer when a suggestion is clicked--}}
{{--                                });--}}
{{--                                suggestionsContainer.appendChild(suggestionElement);--}}
{{--                            });--}}
{{--                        }--}}
{{--                    }--}}

{{--                    // Debounce function--}}
{{--                    function debounce(func, delay) {--}}
{{--                        clearTimeout(debounceTimer);--}}
{{--                        debounceTimer = setTimeout(func, delay);--}}
{{--                    }--}}

{{--                    // Handle input events with debouncing--}}
{{--                    tagInput.addEventListener('input', function () {--}}
{{--                        const inputValue = tagInput.value.trim();--}}

{{--                        // Clear the auto-wrap timer--}}
{{--                        clearTimeout(autoWrapTimer);--}}

{{--                        // Debounce the showSuggestions function--}}
{{--                        debounce(() => {--}}
{{--                            if (inputValue) {--}}
{{--                                showSuggestions(inputValue);--}}
{{--                            } else {--}}
{{--                                suggestionsContainer.innerHTML = '';--}}
{{--                            }--}}
{{--                        }, 300); // 300ms delay--}}

{{--                        // Auto-wrap non-existing tags after 3 seconds--}}
{{--                        if (inputValue) {--}}
{{--                            autoWrapTimer = setTimeout(async () => {--}}
{{--                                const suggestions = await fetchTagSuggestions(inputValue);--}}
{{--                                if (!suggestions.some(suggestion => suggestion.name === inputValue)) {--}}
{{--                                    addTag(inputValue);--}}
{{--                                    tagInput.value = '';--}}
{{--                                    suggestionsContainer.innerHTML = '';--}}
{{--                                }--}}
{{--                            }, 30000);--}}
{{--                        }--}}
{{--                    });--}}

{{--                    // Handle keydown events--}}
{{--                    tagInput.addEventListener('keydown', async function (e) {--}}
{{--                        const inputValue = tagInput.value.trim();--}}

{{--                        // Auto-complete on Tab--}}
{{--                        if (e.key === 'Tab' && inputValue) {--}}
{{--                            e.preventDefault();--}}
{{--                            const suggestions = await fetchTagSuggestions(inputValue);--}}
{{--                            if (suggestions.length > 0) {--}}
{{--                                const matchingTag = suggestions[0].name; // Use the first suggestion--}}
{{--                                addTag(matchingTag);--}}
{{--                                tagInput.value = '';--}}
{{--                                suggestionsContainer.innerHTML = '';--}}
{{--                                clearTimeout(autoWrapTimer); // Clear the timer when Tab is pressed--}}
{{--                            }--}}
{{--                        }--}}

{{--                        // Wrap tag on comma or space--}}
{{--                        if ((e.key === ',' || e.key === ' ') && inputValue) {--}}
{{--                            e.preventDefault();--}}
{{--                            addTag(inputValue);--}}
{{--                            tagInput.value = '';--}}
{{--                            suggestionsContainer.innerHTML = '';--}}
{{--                            clearTimeout(autoWrapTimer); // Clear the timer when a tag is added manually--}}
{{--                        }--}}
{{--                    });--}}
{{--                });--}}
{{--            </script>--}}
    @endpush

