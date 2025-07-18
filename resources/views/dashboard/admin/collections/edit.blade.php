@extends('layouts.admin')

@section('title', 'Edit Collection')

@section('admin_content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Collection</h1>
                @include('components.breadcrumbs', [
                    'links' => [
                        'Collections' => route('collections.index'),
                        'Edit' => route('collections.edit', $collection->id),
                    ]
                ])
            </div>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg">
            <form action="{{ route('collections.update', $collection->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="name" class="block font-semibold text-gray-700 mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $collection->name) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="Collection Name">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="slug" class="block font-semibold text-gray-700 mb-2">Slug *</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $collection->slug) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror" placeholder="collection-slug">
                    @error('slug')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block font-semibold text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" placeholder="Collection Description">{{ old('description', $collection->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block font-semibold text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $collection->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $collection->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Update Collection</button>
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

        // Client-side slugify function (from product create page)
        function createSlug(text) {
            return text.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const collectionId = '{{ $collection->id }}';
            let manualSlugEdit = false; // Flag to track manual edits

            const checkSlug = debounce(async (slugToCheck, isManual = false) => {
                if (!slugToCheck) {
                    return;
                }

                try {
                    const response = await fetch(`/api/collection/slug/check?name=${encodeURIComponent(slugToCheck)}&id=${collectionId}`);
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Error checking slug:', response.status, errorText);
                        return;
                    }
                    const data = await response.json();
                    if (!isManual) {
                        slugInput.value = data.slug;
                    } else if (slugToCheck !== data.slug) {
                        // If manually entered slug is not unique, update with suggested unique slug
                        slugInput.value = data.slug;
                    }
                } catch (error) {
                    console.error('Error checking slug:', error);
                }
            }, 500); // Debounce for 500ms

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

            // Initial check on page load
            if (nameInput.value) {
                checkSlug(createSlug(nameInput.value), true); // Pass true for isManual to ensure it checks against existing slug
            }
        });
    </script>
@endpush