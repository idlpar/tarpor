@push('styles')
    <style>
        #galleryImages::-webkit-scrollbar {
            width: 8px;
        }

        #galleryImages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        #galleryImages::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        #galleryImages::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endpush

<!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 overflow-y-auto transition-opacity duration-300 flex justify-center items-center">
    <div class="relative w-full max-w-7xl bg-white rounded-lg shadow-lg flex flex-col">

        <!-- Header (Full Width) -->
        <div class="w-full flex justify-between items-center p-4 bg-gray-200 rounded-t-lg">
            <h3 class="text-2xl font-semibold text-gray-800">Manage Gallery</h3>
            <button id="closeGalleryModal" type="button" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Content Area: Left & Right Sections -->
        <div class="flex w-full p-4 gap-4">

            <!-- Left Side: Actions + Gallery (Fixed Height) -->
            <div class="w-3/4 flex flex-col">

                <!-- Action Buttons and Search Bar -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex gap-4">
                        <button id="refreshFolders" type="button" class="px-4 py-2 bg-blue-700 text-white rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-sync-alt mr-2"></i> Refresh
                        </button>
                        <button id="uploadButton" type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-500 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-upload mr-2"></i> Upload
                        </button>
                        <button id="newFolder" type="button" class="px-4 py-2 bg-emerald-600 text-white rounded-lg shadow-md hover:bg-emerald-500 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-folder-plus mr-2"></i> New Folder
                        </button>
                        <button id="deleteSelected" type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-500 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                        <button id="trashFolder" type="button" class="px-4 py-2 bg-gray-700 text-white rounded-lg shadow-md hover:bg-gray-600 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-trash-restore mr-2"></i> Trash
                        </button>
                    </div>

                    <div class="relative">
                        <input type="text" id="searchInput" class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search...">
                        <button id="searchButton" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Gallery (Fixed Height) -->
                <div class="grid grid-cols-[repeat(auto-fill,_minmax(100px,_1fr))] gap-4 h-96 overflow-y-auto border border-gray-300 p-2 rounded-lg" id="galleryImages">
                    <!-- Images and folders will be dynamically loaded here -->
                </div>

            </div>

            <!-- Right Side: Preview -->
            <div class="w-1/4 bg-gray-100 border-l border-gray-300 p-4 flex flex-col">
                <h4 class="text-xl font-semibold text-gray-800 mb-4">Preview</h4>
                <div id="imagePreview" class="mb-6">
                    <img id="previewImage" src="" alt="Preview Image" class="hidden max-h-[200px] object-contain rounded-lg shadow-md w-full">
                    <p id="noPreview" class="text-center text-gray-500">No image selected.</p>
                </div>

                <!-- Image Actions -->
                <div id="imageActions" class="hidden flex flex-col gap-2">
                    <button id="deleteImage" class="w-full px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 flex items-center">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                    <button id="restoreImage" class="w-full px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 flex items-center">
                        <i class="fas fa-undo mr-2"></i> Restore
                    </button>
                    <button id="setAsFeatured" class="w-full px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 flex items-center">
                        <i class="fas fa-star mr-2"></i> Set as Featured
                    </button>
                </div>
            </div>

        </div>

        <!-- Insert Button (Bottom of the Modal) -->
        <div class="w-full p-4 border-t border-gray-300 flex justify-end">
            <button id="insertButton" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center">
                <i class="fas fa-upload mr-2"></i> Insert
            </button>
        </div>

    </div>
</div>



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const galleryModal = document.getElementById('galleryModal');
            const galleryImages = document.getElementById('galleryImages');
            let selectedMedia = null;

            // Open modal and load images
            window.openGalleryModal = () => {
                galleryModal.classList.remove('hidden');
                loadGalleryImages();
            };

            // Close modal
            document.getElementById('closeGalleryModal').addEventListener('click', () => {
                const galleryModal = document.getElementById('galleryModal');
                galleryModal.classList.add('hidden'); // Hide the modal
            });

            // Load gallery images
            function loadGalleryImages() {
                showLoadingSpinner();

                fetch("{{ route('gallery.index') }}", {
                    method: 'GET',
                    headers: {
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to fetch gallery images');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Gallery Data:', data); // Debugging
                        galleryImages.innerHTML = '';
                        data.forEach(media => {
                            const mediaElement = createMediaElement(media);
                            galleryImages.appendChild(mediaElement);
                        });
                        lazyLoadImages(); // Reinitialize lazy loading
                    })
                    .catch(error => {
                        console.error('Error loading gallery images:', error);
                        alert('Failed to load gallery images. Please try again.');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            }

            // Create media element
            function createMediaElement(media) {
                const div = document.createElement('div');
                div.className = 'relative cursor-pointer group flex flex-col items-center';

                div.innerHTML = `
                <div class="w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 overflow-hidden rounded-lg transition-transform duration-300 transform group-hover:scale-105 border border-gray-300 shadow-lg hover:shadow-xl">
                <img src="${media.url}"
                     data-src="${media.medium}?t=${new Date().getTime()}"
                     data-full="${media.url}?t=${new Date().getTime()}"
                     alt="${media.name}"
                     class="w-full h-full object-cover rounded-lg lazy-load"
                     loading="lazy"
                     srcset="${media.thumb} 150w, ${media.medium} 300w, ${media.url} 1024w"
                     sizes="(max-width: 600px) 150px, (max-width: 1024px) 300px, 1024px">
            </div>
                    <p class="mt-2 text-xs md:text-sm text-center text-gray-700 truncate w-full">${media.name}</p>
                `;

                div.addEventListener('click', () => selectMedia(media));
                return div;
            }

            // Select media for preview
            function selectMedia(media) {
                selectedMedia = media;
                const previewImage = document.getElementById('previewImage');
                const noPreview = document.getElementById('noPreview');
                const imageActions = document.getElementById('imageActions');

                previewImage.src = media.url + '?t=' + new Date().getTime();
                previewImage.classList.remove('hidden');
                noPreview.classList.add('hidden');
                imageActions.classList.remove('hidden');
            }

            // Upload handling
            document.getElementById('uploadButton').addEventListener('click', () => {
                const input = document.createElement('input');
                input.type = 'file';
                input.multiple = true;
                input.accept = 'image/*';

                input.onchange = e => {
                    const formData = new FormData();
                    Array.from(e.target.files).forEach(file => {
                        formData.append('files[]', file);
                    });

                    showLoadingSpinner();

                    fetch("{{ route('gallery.upload') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Upload failed');
                            return response.json();
                        })
                        .then(data => {
                            console.log('Uploaded Images:', data);
                            alert('Images uploaded successfully!');
                            setTimeout(() => {
                                loadGalleryImages(); // Reload after 1 second
                            }, 1000);
                        })
                        .catch(error => {
                            console.error('Error uploading images:', error);
                            alert('Failed to upload images. Please try again.');
                        })
                        .finally(() => {
                            hideLoadingSpinner();
                        });
                };

                input.click();
            });

            // Delete handling
            document.getElementById('deleteImage').addEventListener('click', () => {
                if (!selectedMedia) return;

                showLoadingSpinner();

                fetch(`/gallery/delete/${selectedMedia.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Delete request failed');
                        return response.json();
                    })
                    .then(() => {
                        loadGalleryImages();
                        selectedMedia = null;
                        document.getElementById('previewImage').classList.add('hidden');
                        document.getElementById('noPreview').classList.remove('hidden');
                        document.getElementById('imageActions').classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error deleting image:', error);
                        alert('Failed to delete image. Please try again.');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            });

            // Refresh handling
            document.getElementById('refreshFolders').addEventListener('click', () => {
                loadGalleryImages();
            });

            // Lazy load images
            function lazyLoadImages() {
                const lazyImages = document.querySelectorAll('.lazy-load');

                const lazyLoad = (target) => {
                    const io = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.src = img.dataset.src;
                                img.classList.remove('lazy-load');
                                observer.unobserve(img);
                            }
                        });
                    });

                    lazyImages.forEach(img => lazyLoad(img));
                }
            }

            // Loading spinner functions
            function showLoadingSpinner() {
                const spinner = document.createElement('div');
                spinner.className = 'loading-spinner';
                galleryImages.appendChild(spinner);
            }

            function hideLoadingSpinner() {
                const spinner = document.querySelector('.loading-spinner');
                if (spinner) spinner.remove();
            }

            // Add event listener to the upload area
            document.querySelector('.clickable-upload-area').addEventListener('click', () => {
                window.openGalleryModal(); // Open the gallery modal
            });
        });
    </script>
@endpush
