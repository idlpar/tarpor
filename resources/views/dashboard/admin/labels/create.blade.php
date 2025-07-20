@extends('layouts.admin')

@section('title', 'Create Label')

@section('admin_content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-text-dark">Create Label</h1>
                @include('components.breadcrumbs', [
                    'links' => [
                        'Labels' => route('labels.index'),
                        'Create' => route('labels.create'),
                    ]
                ])
            </div>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg">
            <form action="{{ route('labels.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="name" class="block font-semibold text-text-dark mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full border border-input-border bg-input-bg text-text-dark p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('name') border-error @enderror" placeholder="Label Name">
                    @error('name')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="slug" class="block font-semibold text-text-dark mb-2">Slug *</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="w-full border border-input-border bg-input-bg text-text-dark p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('slug') border-error @enderror" placeholder="label-slug">
                    @error('slug')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block font-semibold text-text-dark mb-2">Description</label>
                    <textarea id="description" name="description" class="w-full border border-input-border bg-input-bg text-text-dark p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-error @enderror" placeholder="Label Description">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block font-semibold text-text-dark mb-2">Status</label>
                    <select id="status" name="status" class="w-full border border-input-border bg-input-bg text-text-dark p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('status') border-error @enderror">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition-colors">Create Label</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
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
                    const response = await fetch(`/api/label/slug/check?name=${encodeURIComponent(slugToCheck)}`);
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