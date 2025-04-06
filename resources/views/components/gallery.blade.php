@push('styles')
    <style>
        /* Gallery styles */
        #galleryImages {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            padding: 0.5rem;
            overflow-y: auto;
            max-height: 60vh;
        }

        #galleryImages .ring-2 {
            box-shadow: 0 0 0 2px #3b82f6;
        }

        /* Breadcrumb styles */
        .breadcrumb-container {
            display: flex;
            align-items: center;
            overflow-x: auto;
            white-space: nowrap;
            padding: 0.5rem 0;
            margin-bottom: 0.5em;
            scrollbar-width: thin;
        }

        .breadcrumb-container::-webkit-scrollbar {
            height: 4px;
        }

        .breadcrumb-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .breadcrumb-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }

        .breadcrumb-item {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

        .breadcrumb-separator {
            margin: 0 0.5rem;
            color: #6b7280;
        }

        /* Loading spinner */
        .loading-spinner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 50;
        }

        .loading-spinner i {
            font-size: 3rem;
            color: #3b82f6;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Scrollbar styles */
        #galleryImages::-webkit-scrollbar {
            width: 8px;
            height: 8px;
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

        /* Preview styles */
        #imagePreview {
            min-height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        #previewImage {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
        }

        /* Action buttons */
        #imageActions button {
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        #imageActions button:hover {
            transform: translateY(-1px);
        }

        #imageActions button i {
            margin-right: 0.5rem;
        }
        .fa-level-up-alt {
            transform: rotate(90deg);
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

                <!-- Breadcrumb Navigation (separate row) -->
                <div id="breadcrumbContainer" class="breadcrumb-container">
                    <!-- Breadcrumbs will be dynamically inserted here -->
                </div>

                <!-- Gallery (separate row below breadcrumb) -->
                <div class="grid grid-cols-[repeat(auto-fill,_minmax(100px,_1fr))] gap-4 max-h-[60vh] overflow-y-auto border border-gray-300 p-2 rounded-lg" id="galleryImages">
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
            const breadcrumbContainer = document.getElementById('breadcrumbContainer');
            const previewImage = document.getElementById('previewImage');
            const noPreview = document.getElementById('noPreview');
            const imageActions = document.getElementById('imageActions');
            const insertButton = document.getElementById('insertButton');
            const uploadButton = document.getElementById('uploadButton');
            const newFolderButton = document.getElementById('newFolder');
            const deleteButton = document.getElementById('deleteImage');
            const restoreButton = document.getElementById('restoreImage');
            const featuredButton = document.getElementById('setAsFeatured');
            const refreshButton = document.getElementById('refreshFolders');
            const trashButton = document.getElementById('trashFolder');
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            let currentFolder = '';
            let selectedMedia = null;
            let isTrashView = false;

            // Add event listener to the upload area
            document.querySelector('.clickable-upload-area').addEventListener('click', () => {
                window.openGalleryModal(); // Open the gallery modal
            });

            // Open modal and load images
            window.openGalleryModal = (targetFolder = '', callback = null) => {
                galleryModal.classList.remove('hidden');
                currentFolder = targetFolder;
                isTrashView = false;
                loadGalleryContents();

                if (callback && typeof callback === 'function') {
                    window.galleryCallback = callback;
                }
            };

            // Close modal
            document.getElementById('closeGalleryModal').addEventListener('click', () => {
                galleryModal.classList.add('hidden');
                selectedMedia = null;
                window.galleryCallback = null;
            });

            // Load gallery contents (folders and files)
            function loadGalleryContents() {
                showLoadingSpinner();

                const url = '/gallery' + (currentFolder ? `?folder=${encodeURIComponent(currentFolder)}` : '');

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to fetch contents');
                        return response.json();
                    })
                    .then(data => {
                        renderBreadcrumbs(data.breadcrumbs || []);
                        renderGalleryContents(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('Failed to load contents. Please try again.');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            }

            // Render breadcrumbs
            function renderBreadcrumbs(breadcrumbs) {
                breadcrumbContainer.innerHTML = '';

                // Root link
                const rootLink = document.createElement('button');
                rootLink.type = 'button';
                rootLink.className = 'breadcrumb-item text-blue-600 hover:text-blue-800';
                rootLink.innerHTML = '<i class="fas fa-home mr-1"></i> Root';
                rootLink.addEventListener('click', () => {
                    currentFolder = '';
                    loadGalleryContents();
                });
                breadcrumbContainer.appendChild(rootLink);

                // Show ellipsis if more than 5 breadcrumbs
                if (breadcrumbs.length > 5) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'breadcrumb-item text-gray-500 mx-1';
                    ellipsis.textContent = '...';
                    breadcrumbContainer.appendChild(ellipsis);

                    // Only show last 4 breadcrumbs
                    breadcrumbs = breadcrumbs.slice(-4);
                }

                // Other breadcrumbs
                breadcrumbs.forEach(crumb => {
                    const separator = document.createElement('span');
                    separator.className = 'breadcrumb-separator';
                    separator.innerHTML = '<i class="fas fa-chevron-right"></i>';
                    breadcrumbContainer.appendChild(separator);

                    const crumbLink = document.createElement('button');
                    crumbLink.type = 'button';
                    crumbLink.className = 'breadcrumb-item text-blue-600 hover:text-blue-800';
                    crumbLink.textContent = crumb.name;
                    crumbLink.addEventListener('click', () => {
                        currentFolder = crumb.path;
                        loadGalleryContents();
                    });
                    breadcrumbContainer.appendChild(crumbLink);
                });
            }

            // Render gallery contents
            function renderGalleryContents(data) {
                galleryImages.innerHTML = '';

                // Set fixed grid layout with no overflow
                galleryImages.style.overflow = 'visible';
                galleryImages.style.gridTemplateColumns = 'repeat(auto-fill, minmax(80px, 1fr))';
                galleryImages.style.autoRows = 'minmax(80px, auto)'; // Minimum row height

                // Render "Go Up" folder if not in the root directory
                if (data.current_folder) {
                    const goUpElement = document.createElement('div');
                    goUpElement.className = 'cursor-pointer group gallery-item';
                    goUpElement.dataset.type = 'folder';
                    goUpElement.dataset.id = 'go-up';

                    goUpElement.innerHTML = `
            <div class="w-full h-20 flex items-center justify-center bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl border border-teal-200 hover:border-teal-300 transition group-hover:scale-105">
                <i class="fas fa-arrow-up text-blue-500 text-2xl group-hover:text-blue-700"></i>
            </div>
            <p class="mt-1 text-xs text-center text-gray-600 group-hover:text-gray-800 truncate px-1">
                Go Up
            </p>
        `;

                    goUpElement.addEventListener('click', () => {
                        const parts = data.current_folder.split('/');
                        parts.pop();
                        currentFolder = parts.join('/');
                        loadGalleryContents();
                    });

                    galleryImages.appendChild(goUpElement);
                }

                // Show empty state if no content
                if (data.folders.length === 0 && data.files.length === 0 && !data.current_folder) {
                    const emptyState = document.createElement('div');
                    emptyState.className = 'col-span-full flex flex-col items-center justify-center py-8';
                    emptyState.innerHTML = `
            <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-500">This folder is empty</p>
        `;
                    galleryImages.appendChild(emptyState);
                    return;
                }

                // Add folders
                data.folders.forEach(folder => {
                    const folderElement = document.createElement('div');
                    folderElement.className = 'cursor-pointer group gallery-item';
                    folderElement.dataset.type = 'folder';
                    folderElement.dataset.id = folder.id;

                    folderElement.innerHTML = `
            <div class="w-full h-20 flex items-center justify-center bg-blue-50 rounded-lg border-2 border-blue-200 group-hover:border-blue-300 transition group-hover:scale-105 relative">
                <i class="fas fa-folder text-blue-400 text-2xl"></i>
                <div class="absolute inset-0 bg-blue-100 opacity-0 group-hover:opacity-20 transition-opacity rounded-lg"></div>
            </div>
            <p class="mt-1 text-xs text-center text-gray-700 group-hover:text-blue-600 truncate px-1">${folder.name}</p>
            <div class="absolute top-1 right-1 hidden group-hover:block">
                <input type="checkbox" class="item-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            </div>
        `;

                    folderElement.addEventListener('click', (e) => {
                        // Don't navigate if clicking on checkbox
                        if (e.target.classList.contains('item-checkbox')) {
                            e.stopPropagation();
                            return;
                        }
                        currentFolder = folder.path;
                        loadGalleryContents();
                    });

                    galleryImages.appendChild(folderElement);
                });

                // Add files
                data.files.forEach(file => {
                    const fileElement = document.createElement('div');
                    fileElement.className = 'cursor-pointer group gallery-item relative';
                    fileElement.dataset.type = 'file';
                    fileElement.dataset.id = file.id;

                    let thumbContent;
                    if (file.mime_type.startsWith('image/')) {
                        thumbContent = `<img src="${file.url}" alt="${file.name}" class="w-full h-full object-cover rounded">`;
                    } else {
                        let iconClass = 'fa-file';
                        if (file.mime_type.startsWith('video/')) iconClass = 'fa-film';
                        if (file.mime_type.startsWith('audio/')) iconClass = 'fa-music';
                        if (file.mime_type.includes('pdf')) iconClass = 'fa-file-pdf';
                        if (file.mime_type.includes('zip')) iconClass = 'fa-file-archive';

                        thumbContent = `
                <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded">
                    <i class="fas ${iconClass} text-gray-400 text-2xl"></i>
                </div>
            `;
                    }

                    fileElement.innerHTML = `
            <div class="w-full h-20 rounded border border-gray-200 overflow-hidden relative group-hover:shadow-md transition group-hover:scale-105">
                ${thumbContent}
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity"></div>
            </div>
            <p class="mt-1 text-xs text-center text-gray-700 group-hover:text-blue-600 truncate px-1">${file.name}</p>
            <div class="absolute top-1 right-1 hidden group-hover:block">
                <input type="checkbox" class="item-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            </div>
        `;

                    fileElement.addEventListener('click', (e) => {
                        // Don't select if clicking on checkbox
                        if (e.target.classList.contains('item-checkbox')) {
                            e.stopPropagation();
                            return;
                        }

                        // Set selected file for preview
                        selectedMedia = file;
                        previewImage.src = file.url;
                        previewImage.alt = file.name;
                        previewImage.classList.remove('hidden');
                        noPreview.classList.add('hidden');
                        imageActions.classList.remove('hidden');

                        // Highlight selected item
                        document.querySelectorAll('.gallery-item').forEach(item => {
                            item.classList.remove('ring-2', 'ring-blue-500');
                        });
                        fileElement.classList.add('ring-2', 'ring-blue-500');
                    });

                    galleryImages.appendChild(fileElement);
                });

                // Add event listeners for checkboxes
                document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const item = this.closest('.gallery-item');
                        if (this.checked) {
                            item.classList.add('ring-2', 'ring-blue-500');
                        } else {
                            item.classList.remove('ring-2', 'ring-blue-500');
                        }
                    });
                });
            }

            // Create folder element
            function createFolderElement(folder) {
                const div = document.createElement('div');
                div.className = 'relative cursor-pointer group flex flex-col items-center';
                div.dataset.type = 'folder';
                div.dataset.id = folder.id;

                div.innerHTML = `
            <div class="w-24 h-24 flex items-center justify-center bg-blue-50 rounded-lg transition-all duration-300 group-hover:bg-blue-100 border-2 border-blue-200 group-hover:border-blue-300">
                <i class="fas fa-folder text-blue-400 text-4xl"></i>
            </div>
            <p class="mt-2 text-xs md:text-sm text-center text-gray-700 truncate w-full">${folder.name}</p>
        `;

                div.addEventListener('click', () => {
                    if (isTrashView) {
                        selectTrashedItem(folder);
                    } else {
                        currentFolder = folder.path;
                        loadGalleryContents();
                    }
                });

                return div;
            }

            // Create file element
            function createFileElement(file) {
                const div = document.createElement('div');
                div.className = 'relative cursor-pointer group flex flex-col items-center';
                div.dataset.type = 'file';
                div.dataset.id = file.id;

                // Determine icon or thumbnail based on file type
                let thumbnail;
                if (file.mime_type.startsWith('image/')) {
                    const thumbUrl = file.generated_conversions?.thumb
                        ? `${file.disk === 'public' ? '/storage' : ''}/${file.directory ? file.directory + '/' : ''}thumb/${file.file_name}`
                        : file.url;

                    thumbnail = `<img src="${thumbUrl}" alt="${file.name}" class="w-full h-full object-cover rounded-lg">`;
                } else if (file.mime_type.startsWith('video/')) {
                    thumbnail = `
                <div class="relative w-full h-full">
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-lg">
                        <i class="fas fa-film text-gray-500 text-3xl"></i>
                    </div>
                    <div class="absolute bottom-1 right-1 bg-black bg-opacity-70 text-white text-xs px-1 rounded">
                        ${formatDuration(file.duration)}
                    </div>
                </div>
            `;
                } else {
                    thumbnail = `
                <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-lg">
                    <i class="fas fa-file text-gray-500 text-3xl"></i>
                </div>
            `;
                }

                div.innerHTML = `
            <div class="w-24 h-24 md:w-28 md:h-28 lg:w-32 lg:h-32 overflow-hidden rounded-lg transition-all duration-300 group-hover:scale-105 border border-gray-300 shadow-lg hover:shadow-xl relative">
                ${thumbnail}
                ${file.is_featured ? '<div class="absolute top-1 left-1 text-yellow-400"><i class="fas fa-star"></i></div>' : ''}
            </div>
            <p class="mt-2 text-xs md:text-sm text-center text-gray-700 truncate w-full">${file.name}</p>
        `;

                div.addEventListener('click', () => {
                    if (isTrashView) {
                        selectTrashedItem(file);
                    } else {
                        selectMedia(file);
                    }
                });

                return div;
            }

            // Select media for preview
            function selectMedia(media) {
                selectedMedia = media;

                // Update UI
                const allMediaElements = document.querySelectorAll('[data-type="file"], [data-type="folder"]');
                allMediaElements.forEach(el => el.classList.remove('ring-2', 'ring-blue-500'));

                const selectedElement = document.querySelector(`[data-id="${media.id}"]`);
                if (selectedElement) {
                    selectedElement.classList.add('ring-2', 'ring-blue-500');
                }

                // Show preview
                previewImage.src = media.url + '?t=' + new Date().getTime();
                previewImage.alt = media.name;
                previewImage.classList.remove('hidden');
                noPreview.classList.add('hidden');

                // Show appropriate actions
                imageActions.classList.remove('hidden');
                deleteButton.classList.toggle('hidden', isTrashView);
                restoreButton.classList.toggle('hidden', !isTrashView);
                featuredButton.classList.toggle('hidden', isTrashView || !media.mime_type.startsWith('image/'));
            }

            // Select trashed item
            function selectTrashedItem(item) {
                selectedMedia = item;

                // Update UI
                const allItems = document.querySelectorAll('[data-type="file"], [data-type="folder"]');
                allItems.forEach(el => el.classList.remove('ring-2', 'ring-blue-500'));

                const selectedElement = document.querySelector(`[data-id="${item.id}"]`);
                if (selectedElement) {
                    selectedElement.classList.add('ring-2', 'ring-blue-500');
                }

                // Show preview if it's a file
                if (item.mime_type) {
                    previewImage.src = item.url + '?t=' + new Date().getTime();
                    previewImage.alt = item.name;
                    previewImage.classList.remove('hidden');
                    noPreview.classList.add('hidden');
                } else {
                    previewImage.classList.add('hidden');
                    noPreview.classList.remove('hidden');
                }

                // Show appropriate actions
                imageActions.classList.remove('hidden');
                deleteButton.classList.add('hidden');
                restoreButton.classList.remove('hidden');
                featuredButton.classList.add('hidden');
            }

            // Clear preview
            function clearPreview() {
                selectedMedia = null;
                previewImage.classList.add('hidden');
                noPreview.classList.remove('hidden');
                imageActions.classList.add('hidden');

                const allMediaElements = document.querySelectorAll('[data-type="file"], [data-type="folder"]');
                allMediaElements.forEach(el => el.classList.remove('ring-2', 'ring-blue-500'));
            }

            // Upload handling
            uploadButton.addEventListener('click', () => {
                const input = document.createElement('input');
                input.type = 'file';
                input.multiple = true;
                input.accept = 'image/*,video/*';

                input.onchange = e => {
                    const files = Array.from(e.target.files);
                    if (files.length === 0) return;

                    const formData = new FormData();
                    files.forEach(file => {
                        formData.append('files[]', file);
                    });

                    if (currentFolder) {
                        formData.append('folder', currentFolder);
                    }

                    showLoadingSpinner();

                    fetch('/gallery/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Upload failed');
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showSuccess('Files uploaded successfully');
                                loadGalleryContents();
                            } else {
                                throw new Error(data.message || 'Upload failed');
                            }
                        })
                        .catch(error => {
                            console.error('Upload error:', error);
                            showError(error.message || 'Failed to upload files');
                        })
                        .finally(() => {
                            hideLoadingSpinner();
                        });
                };

                input.click();
            });

            // Create new folder
            newFolderButton.addEventListener('click', () => {
                const folderName = prompt('Enter folder name:');
                if (!folderName) return;

                showLoadingSpinner();

                fetch('/gallery/folder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: folderName,
                        parent: currentFolder // Send current folder as parent
                    })
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Folder creation failed');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showSuccess('Folder created successfully');
                            loadGalleryContents();
                        } else {
                            throw new Error(data.message || 'Folder creation failed');
                        }
                    })
                    .catch(error => {
                        console.error('Folder creation error:', error);
                        showError(error.message || 'Failed to create folder');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            });

            // Delete handling
            deleteButton.addEventListener('click', () => {
                if (!selectedMedia) return;

                if (!confirm(`Are you sure you want to move "${selectedMedia.name}" to trash?`)) {
                    return;
                }

                showLoadingSpinner();

                fetch(`/gallery/${selectedMedia.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Delete request failed');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showSuccess('Item moved to trash');
                            clearPreview();
                            loadGalleryContents();
                        } else {
                            throw new Error(data.message || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        showError(error.message || 'Failed to delete item');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            });

            // Restore handling
            restoreButton.addEventListener('click', () => {
                if (!selectedMedia) return;

                showLoadingSpinner();

                fetch(`/gallery/restore/${selectedMedia.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Restore request failed');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showSuccess('Item restored successfully');
                            clearPreview();
                            loadGalleryContents();
                        } else {
                            throw new Error(data.message || 'Restore failed');
                        }
                    })
                    .catch(error => {
                        console.error('Restore error:', error);
                        showError(error.message || 'Failed to restore item');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            });

            // Set as featured
            featuredButton.addEventListener('click', () => {
                if (!selectedMedia) return;

                showLoadingSpinner();

                fetch(`/gallery/set-featured/${selectedMedia.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Featured request failed');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showSuccess('Item set as featured');
                            loadGalleryContents();
                            selectMedia(selectedMedia); // Refresh selection
                        } else {
                            throw new Error(data.message || 'Featured update failed');
                        }
                    })
                    .catch(error => {
                        console.error('Featured error:', error);
                        showError(error.message || 'Failed to set as featured');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            });

            // View trash
            trashButton.addEventListener('click', () => {
                isTrashView = true;
                loadGalleryContents();
            });

            // Refresh handling
            refreshButton.addEventListener('click', () => {
                loadGalleryContents();
            });

            // Search handling
            searchButton.addEventListener('click', () => {
                performSearch();
            });

            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            function performSearch() {
                const query = searchInput.value.trim();
                if (!query) {
                    loadGalleryContents();
                    return;
                }

                showLoadingSpinner();

                fetch(`/gallery?search=${encodeURIComponent(query)}&folder=${encodeURIComponent(currentFolder)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Search failed');
                        return response.json();
                    })
                    .then(data => {
                        renderGalleryContents(data);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        showError('Failed to perform search');
                    })
                    .finally(() => {
                        hideLoadingSpinner();
                    });
            }

            // Insert selected media
            insertButton.addEventListener('click', () => {
                if (!selectedMedia) {
                    alert('Please select an item first');
                    return;
                }

                if (window.galleryCallback) {
                    window.galleryCallback(selectedMedia);
                    galleryModal.classList.add('hidden');
                } else {
                    // Default behavior - generate URL
                    fetch(`/gallery/generate-url/${selectedMedia.id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('URL generation failed');
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                copyToClipboard(data.url);
                                showSuccess('URL copied to clipboard');
                            } else {
                                throw new Error(data.message || 'URL generation failed');
                            }
                        })
                        .catch(error => {
                            console.error('URL generation error:', error);
                            showError(error.message || 'Failed to generate URL');
                        });
                }
            });

            // Helper functions
            function formatDuration(seconds) {
                if (!seconds) return '00:00';
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            function copyToClipboard(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }

            function showLoadingSpinner() {
                const spinner = document.createElement('div');
                spinner.className = 'absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10';
                spinner.innerHTML = '<i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i>';
                spinner.id = 'loadingSpinner';
                galleryImages.appendChild(spinner);
            }

            function hideLoadingSpinner() {
                const spinner = document.getElementById('loadingSpinner');
                if (spinner) spinner.remove();
            }

            function showSuccess(message) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
                toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${message}`;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }

            function showError(message) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
                toast.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> ${message}`;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }
        });
    </script>
@endpush
