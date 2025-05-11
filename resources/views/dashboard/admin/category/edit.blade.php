{{--@extends('layouts.admin')--}}

{{--@section('page-content')--}}
{{--    <div class="container mx-auto p-4">--}}
{{--        <!-- Breadcrumb Navigation -->--}}
{{--        <x-breadcrumbs--}}
{{--            title="Edit Category"--}}
{{--            :links="[--}}
{{--                'Dashboard' => route('admin.dashboard'),--}}
{{--                'Categories' => route('category.index'),--}}
{{--                'Edit' => null,--}}
{{--            ]"--}}
{{--        />--}}

{{--        <!-- Form Container -->--}}
{{--        <x-form-container title="Edit Category" maxWidth="xl">--}}
{{--            <form action="{{ route('category.update', $category) }}" method="POST">--}}
{{--                @csrf--}}
{{--                @method('PUT') <!-- Use PUT method for updates -->--}}

{{--                <!-- Category Name -->--}}
{{--                <x-form.input--}}
{{--                    name="name"--}}
{{--                    id="name"--}}
{{--                    label="Category Name"--}}
{{--                    type="text"--}}
{{--                    required--}}
{{--                    value="{{ old('name', $category->name) }}"--}}
{{--                    placeholder="Enter category name"--}}
{{--                />--}}

{{--                <!-- Slug -->--}}
{{--                <x-form.input--}}
{{--                    name="slug"--}}
{{--                    id="slug"--}}
{{--                    label="Slug"--}}
{{--                    type="text"--}}
{{--                    required--}}
{{--                    value="{{ old('slug', $category->slug) }}"--}}
{{--                    placeholder="Enter slug (auto-generated if empty)"--}}
{{--                />--}}

{{--                <!-- Parent Category -->--}}
{{--                <x-form.select--}}
{{--                    name="parent_id"--}}
{{--                    label="Parent Category"--}}
{{--                    :options="$parentCategories->pluck('name', 'id')->prepend('None', '')"--}}
{{--                    selected="{{ old('parent_id', $category->parent_id) }}"--}}
{{--                />--}}

{{--                <!-- Status -->--}}
{{--                <x-form.select--}}
{{--                    name="status"--}}
{{--                    label="Status"--}}
{{--                    :options="['active' => 'Active', 'inactive' => 'Inactive']"--}}
{{--                    required--}}
{{--                    selected="{{ old('status', $category->status) }}"--}}
{{--                />--}}

{{--                <!-- Submit Button -->--}}
{{--                <div class="flex justify-end mt-6">--}}
{{--                    <x-submit-button--}}
{{--                        type="submit"--}}
{{--                        color="blue"--}}
{{--                        size="base"--}}
{{--                    >--}}
{{--                        Update--}}
{{--                    </x-submit-button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </x-form-container>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@push('scripts')--}}
{{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            // Function to convert name to slug--}}
{{--            function convertToSlug(name) {--}}
{{--                return name.toLowerCase()--}}
{{--                    .replace(/[^a-z0-9\s-]/g, '')  // Remove non-alphanumeric characters--}}
{{--                    .trim()--}}
{{--                    .replace(/\s+/g, '-')          // Replace spaces with hyphens--}}
{{--                    .replace(/-+/g, '-');          // Remove multiple hyphens--}}
{{--            }--}}

{{--            // When the name field changes--}}
{{--            $('#name').on('input', function() {--}}
{{--                var name = $(this).val();--}}
{{--                var slug = convertToSlug(name);--}}
{{--                $('#slug').val(slug);--}}

{{--                // Check if the slug exists via the API (excluding the current category's slug)--}}
{{--                checkSlugAvailability(slug, '{{ $category->slug }}');--}}
{{--            });--}}

{{--            // When the slug field changes manually--}}
{{--            $('#slug').on('input', function() {--}}
{{--                var slug = $(this).val();--}}
{{--                checkSlugAvailability(slug, '{{ $category->slug }}');--}}
{{--            });--}}

{{--            // Function to check if slug exists using AJAX--}}
{{--            function checkSlugAvailability(slug, currentSlug = null) {--}}
{{--                $.ajax({--}}
{{--                    url: '{{ route('api.category.slug.check') }}',--}}
{{--                    method: 'GET',--}}
{{--                    data: {--}}
{{--                        slug: slug,--}}
{{--                        current_slug: currentSlug // Pass the current slug to exclude it from the check--}}
{{--                    },--}}
{{--                    success: function(response) {--}}
{{--                        if (response.exists) {--}}
{{--                            $('#slug').val(response.suggested); // Show suggested slug--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function() {--}}
{{--                        console.log('Error checking slug');--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
