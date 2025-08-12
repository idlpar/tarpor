@extends('layouts.admin')

@section('title', 'Create Collection')

@section('admin_content')
    <div class="container mx-auto px-4 py-4">
        @include('components.breadcrumbs', [
            'links' => [
                'Collections' => route('collections.index'),
                'Create' => null
            ]
        ])

        <x-ui.page-header title="Create New Collection" description="Organize your products into collections.">
            <a href="{{ route('collections.index') }}" class="ml-4 flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View All Collections
            </a>
        </x-ui.page-header>

        <x-ui.session-messages />

        <form action="{{ route('collections.store') }}" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Column -->
                <div class="w-full lg:w-9/12">
                    <x-ui.content-card class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Collection Name">
                                @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug *</label>
                                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="collection-slug">
                                @error('slug')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea id="description" name="description" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" placeholder="Collection Description">{{ old('description') }}</textarea>
                                @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <select id="status" name="status" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-ui.content-card>
                </div>

                <!-- Right Column -->
                <div class="w-full lg:w-3/12 sticky top-6">
                    <x-ui.content-card class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Publish</h3>
                        <div class="pt-4 border-t border-gray-200 flex gap-4">
                            <button type="submit" id="saveButton" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Save</button>
                            <button type="submit" name="save_exit" value="1" id="saveExitButton" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-sm">Save & Exit</button>
                        </div>
                    </x-ui.content-card>
                </div>
            </div>
        </form>
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
            let manualSlugEdit = false; // Flag to track manual edits

            const checkSlug = debounce(async (slugToCheck, isManual = false) => {
                if (!slugToCheck) {
                    return;
                }

                try {
                    const response = await fetch(`/api/collection/slug/check?name=${encodeURIComponent(slugToCheck)}`);
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

            // Initial check on page load if name is pre-filled (e.g., old input from validation error)
            if (nameInput.value) {
                checkSlug(createSlug(nameInput.value));
            }
        });
    </script>
@endpush