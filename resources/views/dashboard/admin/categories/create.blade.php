@extends('layouts.admin')

@section('title', 'Create Category')

@section('admin_content')
    <div class="container mx-auto">
        @include('components.breadcrumbs', [
            'links' => [
                'Categories' => route('categories.index'),
                'Create New Category' => null
            ]
        ])
        <div class="max-w-full mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-text-dark">Create New Category</h1>
                    <p class="mt-2 text-sm text-text-light">Add a new product category to your store</p>
                </div>
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-input-border text-sm font-medium rounded-full shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
            </div>

            <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-input-border">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="p-8 space-y-8">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-text-dark mb-2">Category Name <span class="text-error">*</span></label>
                            <div class="mt-1 relative rounded-lg shadow-sm">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="block w-full px-4 py-2.5 border border-input-border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm placeholder-text-light transition-all duration-200 bg-input-bg text-text-dark"
                                       placeholder="e.g. Electronics">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('name')
                            <p class="mt-2 text-sm text-error font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug Field -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-text-dark mb-2">Slug <span class="text-error">*</span></label>
                            <div class="mt-1 relative rounded-lg shadow-sm">
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                       class="block w-full px-4 py-2.5 border border-input-border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm placeholder-text-light transition-all duration-200 bg-input-bg text-text-dark"
                                       placeholder="e.g. electronics">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <span id="slug-status" class="text-text-light sm:text-sm">
                                        <svg id="slug-loading" class="animate-spin h-5 w-5 text-text-light hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm font-medium" id="slug-help">
                                <span class="text-text-light">The slug is auto-generated but can be customized</span>
                            </p>
                            @error('slug')
                            <p class="mt-2 text-sm text-error font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Category Field -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-text-dark mb-2">Parent Category</label>
                            <div class="mt-1 relative">
                                <select name="parent_id" id="parent_id"
                                        class="block w-full pl-4 pr-10 py-2.5 text-base border border-input-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm bg-input-bg shadow-sm transition-all duration-200 text-text-dark">
                                    <option value="" class="text-text-light">-- No Parent --</option>
                                    @foreach($categoriesTree as $category)
                                        @include('dashboard.admin.categories.partials.category-option', [
                                            'category' => $category,
                                            'depth' => 0,
                                            'oldValue' => old('parent_id')
                                        ])
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('parent_id')
                            <p class="mt-2 text-sm text-error font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-text-dark mb-2">Status <span class="text-error">*</span></label>
                            <div class="mt-1 relative">
                                <select name="status" id="status" required
                                        class="block w-full pl-4 pr-10 py-2.5 text-base border border-input-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm bg-input-bg shadow-sm transition-all duration-200 text-text-dark">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }} class="text-success">Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }} class="text-accent">Inactive</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('status')
                            <p class="mt-2 text-sm text-error font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-bg-light border-t border-input-border flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:scale-105">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const slugHelp = document.getElementById('slug-help');
            const slugLoading = document.getElementById('slug-loading');
            let manualSlugChange = false;

            // Auto-generate slug from name
            nameInput.addEventListener('input', function() {
                if (!manualSlugChange) {
                    slugInput.value = slugify(nameInput.value);
                    checkSlugAvailability(slugInput.value);
                }
            });

            // Allow manual slug override
            slugInput.addEventListener('change', function() {
                manualSlugChange = true;
            });

            slugInput.addEventListener('input', function() {
                this.value = slugify(this.value);
                checkSlugAvailability(this.value);
            });

            function slugify(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '');            // Trim - from end of text
            }

            function checkSlugAvailability(slug) {
                if (!slug) {
                    slugHelp.innerHTML = '<span class="text-indigo-500">The slug is auto-generated but can be customized</span>';
                    return;
                }

                slugLoading.classList.remove('hidden');

                fetch(`{{ route('api.category.slug.check') }}?slug=${slug}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            slugHelp.innerHTML = `<span class="text-amber-600 font-semibold">Slug exists!</span> <span class="text-indigo-600">Suggested: <span class="font-mono underline">${data.suggested}</span></span>`;
                        } else {
                            slugHelp.innerHTML = '<span class="text-green-600 font-semibold">✓ Slug available</span>';
                        }
                    })
                    .catch(error => {
                        slugHelp.innerHTML = '<span class="text-pink-600 font-semibold">Error checking slug</span>';
                    })
                    .finally(() => {
                        slugLoading.classList.add('hidden');
                    });
            }
        });
    </script>
@endpush
