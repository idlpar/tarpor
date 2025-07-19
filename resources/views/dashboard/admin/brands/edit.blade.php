@extends('layouts.admin')

@section('title', 'Edit Brand | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="min-h-screen bg-gray-100 p-6 md:p-8">
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

        @include('components.breadcrumbs', [
            'links' => [
                'Brands' => route('brands.index'),
                'Edit Brand' => null
            ]
        ])

        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Brand</h2>

            <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" id="brandForm">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="name" class="block font-semibold text-gray-700 mb-2">Brand Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="Enter brand name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="slug" class="block font-semibold text-gray-700 mb-2">Slug</label>
                    <div class="flex rounded-lg shadow-sm border border-gray-300 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 @error('slug') border-red-500 @enderror">
                        <span class="inline-flex items-center px-3 rounded-l-lg border-r border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            {{ url('/brand') }}/
                        </span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" class="flex-1 block w-full border-0 p-2.5 focus:ring-0 focus:outline-none rounded-r-lg" placeholder="your-slug">
                    </div>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-2">
                        Preview: <a href="#" class="text-blue-500 hover:underline" id="permalink-preview"></a>
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 mb-2">Brand Logo (Optional)</label>
                    <div id="brandLogoContainer" class="border-dashed border-2 border-gray-300 p-10 rounded-lg text-center cursor-pointer hover:bg-gray-50 transition-all flex flex-col items-center justify-center gap-3 h-40 {{ $brand->logo ? 'hidden' : '' }}">
                        <svg class="w-16 h-16 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M15 8h.01"></path>
                            <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"></path>
                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4"></path>
                            <path d="M14 14l1 -1c.67 -.644 1.45 -.824 2.182 -.54"></path>
                            <path d="M16 19h6"></path>
                            <path d="M19 16v6"></path>
                        </svg>
                        <span class="text-gray-500 text-md">Choose Logo</span>
                    </div>
                    <div id="brandLogoPreview" class="mt-4 {{ $brand->logo ? '' : 'hidden' }}">
                        <div class="relative w-full max-w-xs mx-auto">
                            <img id="brandLogoThumbnail" src="{{ $brand->logo ? $brand->logo->thumb_url : '' }}" alt="Brand Logo" class="w-full h-auto rounded-lg border border-gray-200">
                            <button type="button" id="removeBrandLogo" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="logo_existing" id="brandLogoInput" value="{{ old('logo_existing', $brand->logo ? $brand->logo->id : '') }}">
                    @error('logo_existing')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block font-semibold text-gray-700 mb-2">Description (Optional)</label>
                    <textarea id="description" name="description" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" rows="4" placeholder="Enter brand description">{{ old('description', $brand->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block font-semibold text-gray-700 mb-2">Status *</label>
                    <select id="status" name="status" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $brand->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('components.gallery')

    <script>
        // Reusable debounce function
        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), timeout);
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const permalinkPreview = document.getElementById('permalink-preview');
            const baseUrl = "{{ url('/brand') }}";
            let manualSlugEdit = false; // Flag to track manual edits

            function createSlug(text) {
                return text.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
            }

            function updatePreview(slug) {
                slug = slug || 'your-slug';
                permalinkPreview.textContent = baseUrl + '/' + slug;
                permalinkPreview.href = baseUrl + '/' + slug;
            }

            const checkSlug = debounce(async (slugToCheck, isManual = false) => {
                if (!slugToCheck || slugToCheck === 'your-slug') {
                    updatePreview(''); // Clear preview if slug is empty
                    return;
                }

                try {
                    const response = await fetch(`/api/brand/slug/check?slug=${encodeURIComponent(slugToCheck)}&ignore_id={{ $brand->id ?? '' }}`);
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Error checking slug:', response.status, errorText);
                        return;
                    }
                    const data = await response.json();
                    if (!isManual) {
                        slugInput.value = data.suggested;
                    } else if (slugToCheck !== data.suggested) {
                        // If manually entered slug is not unique, update with suggested unique slug
                        slugInput.value = data.suggested;
                    }
                    updatePreview(data.suggested);
                } catch (error) {
                    console.error('Error checking slug:', error);
                }
            }, 2000); // Debounce for 2000ms (2 seconds)

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

            // Initial preview update on page load
            updatePreview(slugInput.value || 'your-slug');
        });

        document.addEventListener('DOMContentLoaded', () => {
            const brandLogoInput = document.getElementById('brandLogoInput');
            const brandLogoThumbnail = document.getElementById('brandLogoThumbnail');
            const brandLogoPreview = document.getElementById('brandLogoPreview');
            const brandLogoContainer = document.getElementById('brandLogoContainer');
            const removeBrandLogoBtn = document.getElementById('removeBrandLogo');
            const form = document.getElementById('brandForm');

            let brandLogoFile = null; // Stores the actual File object for new uploads

            // Function to set the brand logo preview
            function setBrandLogo(file) {
                if (!file) return;

                brandLogoThumbnail.src = file.thumb_url || URL.createObjectURL(file.file);
                brandLogoThumbnail.alt = file.name;
                brandLogoPreview.classList.remove('hidden');
                brandLogoContainer.classList.add('hidden');

                if (file.id) {
                    brandLogoInput.value = file.id; // Store ID for existing media
                    brandLogoFile = null; // Clear new file if existing is selected
                } else if (file.file) {
                    brandLogoInput.value = ''; // Clear existing ID if new file is selected
                    brandLogoFile = file.file; // Store the actual File object for new uploads
                }
            }

            // Function to handle opening the gallery modal for brand logo
            function handleBrandLogoUpload() {
                window.openGalleryModal('', (file) => {
                    if (file) {
                        setBrandLogo(file);
                    }
                }, { mode: 'single', accept: 'image/*' });
            }

            // Event listener for clicking the upload area
            brandLogoContainer.addEventListener('click', handleBrandLogoUpload);

            // Event listener for removing the brand logo
            removeBrandLogoBtn.addEventListener('click', () => {
                brandLogoInput.value = ''; // Clear the hidden input
                brandLogoFile = null; // Clear the File object
                brandLogoPreview.classList.add('hidden');
                brandLogoContainer.classList.remove('hidden');
            });

            // Pre-populate if an existing logo is present
            @if($brand->logo)
                setBrandLogo({
                    id: {{ $brand->logo->id }},
                    url: '{{ $brand->logo->url }}',
                    thumb_url: '{{ $brand->logo->thumb_url }}',
                    name: '{{ $brand->logo->name }}'
                });
            @endif

            // Handle form submission
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);

                // Append the new brand logo file if it's a new upload
                if (brandLogoFile) {
                    formData.append('logo_new', brandLogoFile);
                }

                // Log all formData entries for debugging
                console.log('--- FormData Contents (before send) ---');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                console.log('---------------------------------------');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    const result = await response.json();

                    if (response.ok) {
                        showToast(result.message || 'Brand saved successfully!');
                        // Handle success (e.g., redirect, show message)
                        window.location.href = result.redirect || '{{ route("brands.index") }}';
                    } else {
                        // Handle validation errors
                        if (result.errors) {
                            // Clear previous errors
                            document.querySelectorAll('.text-red-500.text-sm.mt-1').forEach(el => el.remove());
                            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                            for (const [field, messages] of Object.entries(result.errors)) {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    const errorDiv = document.createElement('p');
                                    errorDiv.className = 'text-red-500 text-sm mt-1';
                                    errorDiv.textContent = messages[0];
                                    input.parentNode.appendChild(errorDiv);
                                    input.classList.add('border-red-500');
                                }
                            }
                        }
                        showToast(result.message || 'Failed to save brand.', 'error');
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    showToast('An error occurred while saving the brand.', 'error');
                }
            });
        });
    </script>
