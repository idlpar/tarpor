@extends('layouts.admin')

@section('title', 'Add Brand | ' . strtoupper(config('app.name')))

@section('admin_content')
    <div class="container mx-auto">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-700 p-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-700 p-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('components.breadcrumbs', [
            'links' => [
                'Brands' => route('brands.index'),
                'Add Brand' => null
            ]
        ])

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center">
                    <a href="{{ route('brands.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Add New Brand</h1>
                        <p class="mt-1 text-sm text-gray-600">Create a new brand for your products</p>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('brands.index') }}" class="inline-flex items-center px-4 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary)]">
                    View All Brands
                </a>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-8">

            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data" id="brandForm">
                @csrf
                <div class="mb-5">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Brand Name *</label>
                    <input type="text" name="name" id="name" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" value="{{ old('name') }}" required placeholder="Enter brand name">
                    @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                    <div class="flex rounded-md shadow-sm border border-gray-300 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition duration-200">
                        <span class="inline-flex items-center px-3 rounded-l-md border-r border-gray-300 bg-gray-200 text-gray-600 text-sm">
                            {{ url('/brand') }}/
                        </span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="flex-1 block w-full border-0 px-4 py-2 bg-gray-50 focus:ring-0 focus:outline-none rounded-r-md placeholder-gray-500" placeholder="your-slug">
                    </div>
                    @error('slug')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-2">
                        Preview: <a href="#" class="text-blue-600 hover:underline" id="permalink-preview"></a>
                    </p>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Brand Logo (Optional)</label>
                    <div id="brandLogoContainer" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-center cursor-pointer hover:bg-gray-100 flex flex-col items-center justify-center gap-3 h-32">
                        <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                    <div id="brandLogoPreview" class="mt-4 hidden">
                        <div class="relative w-full max-w-xs mx-auto">
                            <img id="brandLogoThumbnail" src="" alt="Brand Logo" class="w-full h-auto rounded-lg border border-gray-300">
                            <button type="button" id="removeBrandLogo" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="logo_existing" id="brandLogoInput" value="{{ old('logo_existing') }}">
                    @error('logo_existing')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                    <textarea id="description" name="description" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" rows="4" placeholder="Enter brand description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select id="status" name="status" class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Add Brand</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('components.gallery')

@push('scripts')
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
                    const response = await fetch(`/api/brand/slug/check?slug=${encodeURIComponent(slugToCheck)}`);
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

            // Handle form submission
            form.addEventListener('submit', (event) => {
                // Append the new brand logo file if it's a new upload
                if (brandLogoFile) {
                    event.preventDefault(); // Prevent default form submission as we're doing it manually
                    const formData = new FormData(form);
                    formData.append('logo_new', brandLogoFile);
                    // Manually submit the form with the new FormData
                    fetch(form.action, {
                        method: form.method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    }).then(response => {
                        console.log('Fetch Response Status:', response.status);
                        console.log('Fetch Response Redirected:', response.redirected);
                        console.log('Fetch Response URL:', response.url);

                        if (response.redirected) {
                            window.location.href = response.url;
                        } else if (!response.ok) {
                            // Attempt to read response body for more details on non-OK responses
                            response.text().then(text => {
                                console.error('Form submission failed:', response.status, text);
                            });
                        }
                        return response; // Pass response along for further chaining if needed
                    }).catch(error => {
                        console.error('Form submission error:', error);
                    });
                }
            });
        });
    </script>
@endpush
