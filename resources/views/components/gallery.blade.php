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
            <button id="closeGalleryModal" class="text-gray-500 hover:text-gray-700">
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
                        <button id="refreshFolders" class="px-4 py-2 bg-blue-700 text-white rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-sync-alt mr-2"></i> Refresh
                        </button>
                        <button  type="button" id="uploadButton" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-500 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-upload mr-2"></i> Upload
                        </button>
                        <button id="newFolder" class="px-4 py-2 bg-emerald-600 text-white rounded-lg shadow-md hover:bg-emerald-500 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-folder-plus mr-2"></i> New Folder
                        </button>
                        <button id="deleteSelected" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-md hover:bg-red-500 hover:shadow-lg flex items-center transition-all">
                            <i class="fas fa-trash mr-2"></i> Delete
                        </button>
                        <button id="trashFolder" class="px-4 py-2 bg-gray-700 text-white rounded-lg shadow-md hover:bg-gray-600 hover:shadow-lg flex items-center transition-all">
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
                <div class="grid grid-cols-[repeat(auto-fill,_minmax(150px,_1fr))] gap-4 h-48 overflow-y-auto border border-gray-300 p-2 rounded-lg" id="galleryImages">
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
        document.addEventListener('DOMContentLoaded', function() {
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
                galleryModal.classList.add('hidden');
                selectedMedia = null;
            });

            // Load gallery images
            function loadGalleryImages() {
                fetch("{{ route('gallery.index') }}")
                    .then(response => response.json())
                    .then(data => {
                        galleryImages.innerHTML = '';
                        data.forEach(media => {
                            const mediaElement = createMediaElement(media);
                            galleryImages.appendChild(mediaElement);
                        });
                    });
            }

            // Create media element
            function createMediaElement(media) {
                const div = document.createElement('div');
                div.className = 'relative cursor-pointer group';
                div.innerHTML = `
            <img src="${media.url}" alt="${media.name}" class="w-full h-32 object-cover rounded-lg">
            <div class="absolute inset-0 bg-black bg-opacity-50 hidden group-hover:flex items-center justify-center rounded-lg">
                <span class="text-white text-sm">${media.name}</span>
            </div>
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

                previewImage.src = media.url;
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

                    fetch("{{ route('gallery.upload') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => loadGalleryImages());
                };

                input.click();
            });

            // Delete handling
            document.getElementById('deleteImage').addEventListener('click', () => {
                if (!selectedMedia) return;

                fetch(`/gallery/delete/${selectedMedia.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(() => {
                        loadGalleryImages();
                        selectedMedia = null;
                        document.getElementById('previewImage').classList.add('hidden');
                        document.getElementById('noPreview').classList.remove('hidden');
                        document.getElementById('imageActions').classList.add('hidden');
                    });
            });

            // Refresh handling
            document.getElementById('refreshFolders').addEventListener('click', loadGalleryImages);

            // Initialize modal
            window.openGalleryModal();
        });
    </script>
@endpush
