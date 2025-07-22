@extends('layouts.admin')

@section('title', 'Edit Collection')

@section('admin_content')
    <div class="container mx-auto">
        @include('components.breadcrumbs', [
            'links' => [
                'Collections' => route('collections.index'),
                'Edit' => null
            ]
        ])


        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('collections.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Collection</h1>
                        <p class="mt-1 text-sm text-gray-600">Modify the details of an existing collection</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('collections.index') }}" class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                    View All Collections
                </a>
            </div>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg">
            <form action="{{ route('collections.update', $collection->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $collection->name) }}" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Collection Name">
                    @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug *</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $collection->slug) }}" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="collection-slug">
                    @error('slug')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Collection Description">{{ old('description', $collection->description) }}</textarea>
                    @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <option value="active" {{ old('status', $collection->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $collection->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Update Collection</button>
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
