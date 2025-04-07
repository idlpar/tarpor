@push('styles')
    <style>
        /* Modern Gallery Styles */
        .gallery-container {
            display: grid;
            grid-template-rows: auto auto auto 1fr auto;
            height: 80vh;
            max-height: 800px;
            background-color: #f8fafc;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Header Section */
        .gallery-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gallery-title {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .gallery-close {
            background: rgba(190, 18, 60, 0.1); /* Subtle rose tone */
            border: 2px solid rgba(190, 18, 60, 0.2); /* Matching soft border */
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #be123c; /* Tailwind's rose-600 */
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .gallery-close:hover {
            background: #be123c; /* Solid rose on hover */
            color: white;
            border-color: #be123c;
            transform: scale(1.05);
        }
        .item-thumbnail {
            position: relative;
        }




        /* Toolbar Section */
        .gallery-toolbar {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 1.25rem;
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .toolbar-group {
            display: flex;
            gap: 0.5rem;
        }

        .toolbar-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .toolbar-button.primary {
            background-color: #4f46e5;
            color: white;
        }

        .toolbar-button.primary:hover {
            background-color: #4338ca;
        }

        .toolbar-button.secondary {
            background-color: white;
            border-color: #e2e8f0;
            color: #4f46e5;
        }

        .toolbar-button.secondary:hover {
            background-color: #f8fafc;
        }

        .toolbar-button.danger {
            background-color: #ef4444;
            color: white;
        }

        .toolbar-button.danger:hover {
            background-color: #dc2626;
        }

        .search-container {
            position: relative;
            width: 250px;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 2rem 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #a5b4fc;
            box-shadow: 0 0 0 3px rgba(199, 210, 254, 0.5);
        }

        .search-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* Breadcrumb Navigation */
        .breadcrumb-container {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .breadcrumb-button {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .breadcrumb-button:hover {
            background-color: #f1f5f9;
        }

        .breadcrumb-separator {
            margin: 0 0.5rem;
            color: #cbd5e1;
        }

        /* Main Content Area */
        .gallery-content {
            display: grid;
            grid-template-columns: 75% 25%;
            height: 100%;
            overflow: hidden;
        }

        /* Items Grid */
        .items-grid {
            padding: 1rem;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            align-content: start;
        }

        .empty-state {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: #64748b;
        }

        /* Gallery Item */
        .gallery-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background-color: white;
        }

        .gallery-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .gallery-item.selected {
            border: 2px solid #4f46e5;
        }

        .item-thumbnail {
            width: 100%;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
            position: relative;
        }

        .item-thumbnail img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .folder-icon {
            font-size: 2.5rem;
            color: #94a3b8;
        }

        .item-checkbox {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            z-index: 10;
        }

        .item-featured {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            color: #f59e0b;
            z-index: 10;
        }

        .item-info {
            padding: 0.25rem 0.50rem;
        }

        .item-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-meta {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.25rem;
            display: flex;
            justify-content: space-between;
        }

        /* Preview Panel */
        .preview-panel {
            border-left: 1px solid #e2e8f0;
            padding: 1.5rem;
            overflow-y: auto;
            background-color: white;
        }

        .preview-header {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #1e293b;
        }

        .preview-content {
            margin-bottom: 1.5rem;
        }

        .preview-image {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .preview-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #94a3b8;
        }

        .preview-details {
            margin-top: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-label {
            font-weight: 500;
            color: #64748b;
        }

        .detail-value {
            color: #1e293b;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .action-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-button.primary {
            background-color: #4f46e5;
            color: white;
        }

        .action-button.primary:hover {
            background-color: #4338ca;
        }

        .action-button.danger {
            background-color: #ef4444;
            color: white;
        }

        .action-button.danger:hover {
            background-color: #dc2626;
        }

        .action-button.secondary {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        .action-button.secondary:hover {
            background-color: #cbd5e1;
        }

        /* Footer */
        .gallery-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            background-color: white;
        }

        /* Context Menu */
        .context-menu {
            position: absolute;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
            min-width: 200px;
            overflow: hidden;
        }

        .context-menu-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .context-menu-item:hover {
            background-color: #f8fafc;
        }

        .context-menu-item.danger {
            color: #ef4444;
        }

        .context-menu-separator {
            height: 1px;
            background-color: #e2e8f0;
            margin: 0.25rem 0;
        }

        /* Loading State */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 40;
        }

        .loading-spinner {
            animation: spin 1s linear infinite;
            color: #4f46e5;
            font-size: 2rem;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Progress Modal Styles */
        .progress-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .progress-modal.show {
            display: flex; /* Only show when 'show' class is added */
        }

        .progress-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
        }

        .progress-header {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .progress-bar-container {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: #4f46e5;
            width: 0%;
            transition: width 0.3s ease;
        }

        .progress-status {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .gallery-content {
                grid-template-columns: 65% 35%;
            }
        }

        @media (max-width: 768px) {
            .gallery-content {
                grid-template-columns: 100%;
            }

            .preview-panel {
                display: none;
            }
        }
    </style>
@endpush

<!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 overflow-y-auto transition-opacity duration-300 flex justify-center items-center p-4">
    <div class="gallery-container w-full max-w-6xl">
        <!-- Header -->
        <div class="gallery-header">
            <div class="gallery-title">
                <i class="fas fa-images"></i>
                <span>Media Gallery</span>
            </div>
            <button type="button" id="closeGalleryModal" class="gallery-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-wrap items-center justify-between gap-4 px-4 py-2 bg-white shadow rounded-lg">
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" id="refreshFolders" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-500 rounded hover:bg-green-700">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh</span>
                </button>
                <button type="button" id="uploadButton" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                    <i class="fas fa-upload"></i>
                    <span>Upload</span>
                </button>
                <button type="button" id="newFolder" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-teal-500 rounded hover:bg-teal-700">
                    <i class="fas fa-folder-plus"></i>
                    <span>New Folder</span>
                </button>
                <button type="button" id="deleteSelected" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                    <i class="fas fa-trash"></i>
                    <span>Delete</span>
                </button>
                <button type="button" id="trashFolder" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-300 rounded hover:bg-red-900">
                    <i class="fas fa-trash-restore"></i>
                    <span>Trash</span>
                </button>
            </div>

            <div class="relative w-full sm:w-auto sm:flex-1 max-w-sm">
                <input type="text" id="searchInput" class="w-full px-4 py-2 pr-10 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search...">
                <i class="fas fa-search absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>


        <!-- Breadcrumbs -->
        <div id="breadcrumbContainer" class="breadcrumb-container">
            <!-- Dynamic breadcrumbs will be inserted here -->
        </div>

        <!-- Main Content -->
        <div class="gallery-content">
            <!-- Items Grid -->
            <div class="items-grid" id="galleryImages">
                <!-- Dynamic content will be inserted here -->
            </div>

            <!-- Preview Panel -->
            <div class="preview-panel">
                <div class="preview-header">Preview</div>
                <div class="preview-content">
                    <div id="imagePreview">
                        <div class="preview-empty">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p>No item selected</p>
                        </div>
                        <img id="previewImage" class="preview-image hidden" src="" alt="Preview">
                    </div>

                    <div id="previewDetails" class="preview-details hidden">
                        <div class="detail-row">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value" id="detailName">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Type:</span>
                            <span class="detail-value" id="detailType">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Size:</span>
                            <span class="detail-value" id="detailSize">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Dimensions:</span>
                            <span class="detail-value" id="detailDimensions">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Uploaded:</span>
                            <span class="detail-value" id="detailUploaded">-</span>
                        </div>
                    </div>
                </div>

                <div id="imageActions" class="action-buttons hidden">
                    <button type="button" id="deleteImage" class="action-button danger">
                        <i class="fas fa-trash mr-2"></i>
                        Delete
                    </button>
                    <button type="button" id="restoreImage" class="action-button secondary">
                        <i class="fas fa-undo mr-2"></i>
                        Restore
                    </button>
                    <button type="button" id="setAsFeatured" class="action-button primary">
                        <i class="fas fa-star mr-2"></i>
                        Set as Featured
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="gallery-footer">
            <button type="button" id="insertButton" class="toolbar-button primary">
                <i class="fas fa-check mr-2"></i>
                Insert Selected
            </button>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div id="progressModal" class="progress-modal show">
    <div class="progress-content">
        <div class="progress-header" id="progressTitle">Processing Files</div>
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        <div class="progress-status" id="progressStatus">0% Complete</div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Explicitly hide progress modal on load
            document.getElementById('progressModal').classList.remove('show');

            // DOM Elements
            const galleryModal = document.getElementById('galleryModal');
            const closeGalleryBtn  = document.getElementById('closeGalleryModal');
            const galleryImages = document.getElementById('galleryImages');
            const breadcrumbContainer = document.getElementById('breadcrumbContainer');
            const previewImage = document.getElementById('previewImage');
            const previewEmpty = document.querySelector('.preview-empty');
            const previewDetails = document.getElementById('previewDetails');
            const imageActions = document.getElementById('imageActions');
            const insertButton = document.getElementById('insertButton');
            const uploadButton = document.getElementById('uploadButton');
            const newFolderButton = document.getElementById('newFolder');
            const deleteImageButton = document.getElementById('deleteImage');
            const restoreImageButton = document.getElementById('restoreImage');
            const featuredButton = document.getElementById('setAsFeatured');
            const refreshButton = document.getElementById('refreshFolders');
            const trashButton = document.getElementById('trashFolder');
            const searchInput = document.getElementById('searchInput');
            const searchIcon = document.querySelector('.search-icon');

            // Progress modal elements
            const progressModal = document.getElementById('progressModal');
            const progressTitle = document.getElementById('progressTitle');
            const progressBar = document.getElementById('progressBar');
            const progressStatus = document.getElementById('progressStatus');

            // State variables
            let currentPath = '';
            let selectedItems = [];
            let isTrashView = false;
            let clipboard = null;
            let galleryCallback = null;

            // Initialize the gallery
            function initGallery() {
                setupEventListeners();
            }

            // Setup event listeners
            function setupEventListeners() {
                // Modal controls
                closeGalleryBtn.addEventListener('click', closeGalleryModal);

                // Navigation
                refreshButton.addEventListener('click', loadGalleryContents);
                trashButton.addEventListener('click', toggleTrashView);

                // File operations
                uploadButton.addEventListener('click', showUploadDialog);
                newFolderButton.addEventListener('click', createNewFolder);

                // Item actions
                deleteImageButton.addEventListener('click', deleteSelectedItems);
                restoreImageButton.addEventListener('click', restoreSelectedItems);
                featuredButton.addEventListener('click', setAsFeatured);
                insertButton.addEventListener('click', insertSelectedItem);

                // Search
                searchIcon.addEventListener('click', performSearch);
                searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') performSearch();
                });

                // Context menu
                document.addEventListener('click', closeContextMenu);
                galleryImages.addEventListener('contextmenu', (e) => {
                    if (e.target.closest('.gallery-item')) {
                        e.preventDefault();
                        showContextMenu(e);
                    }
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', handleKeyboardShortcuts);
            }

            // Show progress modal
            function showProgress(title) {
                console.trace("showProgress called with title:", title);
                progressTitle.textContent = title;
                progressBar.style.width = '0%';
                progressStatus.textContent = '0% Complete';
                progressModal.classList.add('show');
            }

            // Update progress
            function updateProgress(percent, status) {
                progressBar.style.width = `${percent}%`;
                progressStatus.textContent = status || `${percent}% Complete`;
            }

            // Hide progress modal
            function hideProgress() {
                progressModal.classList.remove('show');
            }

            // Open gallery modal
            window.openGalleryModal = function(targetPath = '', callback = null) {
                galleryModal.classList.remove('hidden');
                currentPath = targetPath;
                isTrashView = false;
                selectedItems = [];
                galleryCallback = callback;
                loadGalleryContents();
            };

            // Close gallery modal
            function closeGalleryModal() {
                galleryModal.classList.add('hidden');
                galleryCallback = null;
            }

            // Load gallery contents
            function loadGalleryContents() {
                showLoading();

                const url = new URL('{{ route("gallery.index") }}');
                url.searchParams.append('path', currentPath);
                if (isTrashView) url.searchParams.append('trash', 'true');

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderBreadcrumbs(data.breadcrumbs);
                            renderGalleryContents(data.contents);
                        } else {
                            throw new Error(data.message || 'Failed to load contents');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError(error.message);
                    })
                    .finally(hideLoading);
            }

            // Render breadcrumbs
            function renderBreadcrumbs(breadcrumbs) {
                breadcrumbContainer.innerHTML = '';

                breadcrumbs.forEach((crumb, index) => {
                    if (index > 0) {
                        const separator = document.createElement('span');
                        separator.className = 'breadcrumb-separator';
                        separator.innerHTML = '<i class="fas fa-chevron-right"></i>';
                        breadcrumbContainer.appendChild(separator);
                    }

                    const button = document.createElement('button');
                    button.className = 'breadcrumb-button';
                    button.innerHTML = `
                        ${crumb.icon ? `<i class="fas fa-${crumb.icon}"></i>` : ''}
                        <span>${crumb.name}</span>
                    `;
                    button.addEventListener('click', () => {
                        event.preventDefault();
                        currentPath = crumb.path;
                        loadGalleryContents();
                    });

                    breadcrumbContainer.appendChild(button);
                });
            }

            // Render gallery contents
            function renderGalleryContents(contents) {
                galleryImages.innerHTML = '';

                // Empty state
                if ((!contents.folders || contents.folders.length === 0) &&
                    (!contents.files || contents.files.length === 0)) {
                    const emptyState = document.createElement('div');
                    emptyState.className = 'empty-state';
                    emptyState.innerHTML = `
                        <i class="fas fa-folder-open text-5xl mb-4"></i>
                        <p>${isTrashView ? 'Trash is empty' : 'This folder is empty'}</p>
                    `;
                    galleryImages.appendChild(emptyState);
                    return;
                }

                // Add "Go Up" button if not in root
                if (!isTrashView && currentPath) {
                    const goUpItem = createGalleryItem({
                        id: 'go-up',
                        name: 'Go Up',
                        type: 'folder',
                        icon: 'level-up-alt',
                        isSpecial: true
                    });

                    goUpItem.addEventListener('click', () => {
                        const parts = currentPath.split('/');
                        parts.pop();
                        currentPath = parts.join('/');
                        loadGalleryContents();
                    });

                    galleryImages.appendChild(goUpItem);
                }

                // Add folders
                if (contents.folders && contents.folders.length > 0) {
                    contents.folders.forEach(folder => {
                        const folderItem = createGalleryItem({
                            id: folder.id,
                            name: folder.name,
                            type: 'folder',
                            icon: 'folder',
                            itemCount: folder.item_count,
                            createdAt: folder.created_at,
                            isSelected: selectedItems.some(item => item.id === folder.id && item.type === 'folder')
                        });

                        folderItem.addEventListener('click', (e) => {
                            if (e.target.closest('.item-checkbox')) return;

                            if (isTrashView) {
                                toggleItemSelection(folder.id, 'folder', folderItem);
                            } else {
                                currentPath = folder.path;
                                loadGalleryContents();
                            }
                        });

                        galleryImages.appendChild(folderItem);
                    });
                }

                // Add files
                if (contents.files && contents.files.length > 0) {
                    contents.files.forEach(file => {
                        const fileItem = createGalleryItem({
                            id: file.id,
                            name: file.name,
                            type: 'file',
                            icon: getFileIcon(file.mime_type),
                            thumbnail: file.thumb_url || file.url,
                            size: file.size,
                            createdAt: file.created_at,
                            isFeatured: file.is_featured,
                            isSelected: selectedItems.some(item => item.id === file.id && item.type === 'file')
                        });

                        fileItem.addEventListener('click', (e) => {
                            if (e.target.closest('.item-checkbox')) return;

                            toggleItemSelection(file.id, 'file', fileItem);
                            updatePreview(file);
                        });

                        galleryImages.appendChild(fileItem);
                    });
                }

                updateSelectionDisplay();
            }

            // Create a gallery item element
            function createGalleryItem(options) {
                const item = document.createElement('div');
                item.className = `gallery-item ${options.isSelected ? 'selected' : ''}`;
                item.dataset.id = options.id;
                item.dataset.type = options.type;

                let thumbnailContent = '';
                if (options.thumbnail && options.type === 'file') {
                    thumbnailContent = `<img src="${options.thumbnail}" alt="${options.name}">`;
                } else {
                    const icon = options.icon || 'file';
                    thumbnailContent = `
                        <div class="${options.isSpecial ? 'bg-teal-50' : 'bg-gray-100'}">
                            <i class="fas fa-${icon} folder-icon"></i>
                        </div>
                    `;
                }

                item.innerHTML = `
                    <div class="item-thumbnail relative">
                        ${thumbnailContent}

                        ${options.type === 'folder' && options.itemCount !== undefined ? `
                            <div class="absolute top-1 left-1 text-xs px-2 py-0.5 bg-blue-600 text-white rounded">
                                ${options.itemCount} items
                            </div>` : ''}


                        ${options.isFeatured ? '<i class="fas fa-star item-featured"></i>' : ''}

                        <div class="absolute bottom-1 left-1 text-xs text-gray-600 bg-white/70 backdrop-blur px-2 py-0.5 rounded">
                            ${formatDate(options.createdAt)}
                        </div>

                        ${options.type === 'file' ? `
                            <div class="absolute bottom-1 right-1 text-xs text-gray-600 bg-white/70 backdrop-blur px-2 py-0.5 rounded">
                                ${formatFileSize(options.size)}
                            </div>` : ''}

                        <div class="item-checkbox absolute top-1 right-1">
                            <input type="checkbox" ${options.isSelected ? 'checked' : ''}>
                        </div>
                    </div>

                    <div class="item-info">
                        <div class="item-name">${options.name}</div>
                        <div class="item-meta">
                            <span></span>
                        </div>
                    </div>
                `;



                // Add checkbox event listener
                const checkbox = item.querySelector('.item-checkbox input');
                checkbox.addEventListener('change', (e) => {
                    e.stopPropagation();
                    toggleItemSelection(options.id, options.type, item);
                });

                return item;
            }



            // Toggle item selection
            function toggleItemSelection(id, type, element) {
                const index = selectedItems.findIndex(item => item.id === id && item.type === type);

                if (index === -1) {
                    selectedItems.push({ id, type });
                    element.classList.add('selected');
                    element.querySelector('.item-checkbox input').checked = true;
                } else {
                    selectedItems.splice(index, 1);
                    element.classList.remove('selected');
                    element.querySelector('.item-checkbox input').checked = false;
                }

                updateSelectionDisplay();
            }

            // Update UI based on current selection
            function updateSelectionDisplay() {
                const hasSelection = selectedItems.length > 0;
                const singleSelection = selectedItems.length === 1;

                // Update action buttons
                deleteImageButton.classList.toggle('hidden', !hasSelection);
                restoreImageButton.classList.toggle('hidden', !isTrashView || !hasSelection);
                featuredButton.classList.toggle('hidden', isTrashView || !hasSelection ||
                    selectedItems.some(item => item.type !== 'file'));

                // Update preview
                if (singleSelection && selectedItems[0].type === 'file') {
                    fetchFileDetails(selectedItems[0].id);
                } else if (!hasSelection) {
                    clearPreview();
                }
            }

            // Update preview panel
            function updatePreview(file) {
                if (!file) {
                    clearPreview();
                    return;
                }

                previewImage.src = file.url;
                previewImage.alt = file.name;
                previewImage.classList.remove('hidden');
                previewEmpty.classList.add('hidden');
                previewDetails.classList.remove('hidden');
                imageActions.classList.remove('hidden');

                // Update details
                document.getElementById('detailName').textContent = file.name;
                document.getElementById('detailType').textContent = file.mime_type;
                document.getElementById('detailSize').textContent = formatFileSize(file.size);
                document.getElementById('detailDimensions').textContent = file.dimensions ?
                    `${file.dimensions.width} × ${file.dimensions.height}` : 'N/A';
                document.getElementById('detailUploaded').textContent = formatDate(file.created_at);
            }

            // Clear preview panel
            function clearPreview() {
                previewImage.classList.add('hidden');
                previewEmpty.classList.remove('hidden');
                previewDetails.classList.add('hidden');
                imageActions.classList.add('hidden');
            }

            // Fetch file details for preview
            function fetchFileDetails(fileId) {
                fetch(`{{ route("gallery.show", '') }}/${fileId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updatePreview(data.file);
                        } else {
                            throw new Error(data.message || 'Failed to load file details');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError(error.message);
                    });
            }

            // Toggle trash view
            function toggleTrashView() {
                isTrashView = !isTrashView;
                currentPath = '';
                selectedItems = [];
                loadGalleryContents();
            }

            // Show upload dialog
            function showUploadDialog() {
                const input = document.createElement('input');
                input.type = 'file';
                input.multiple = true;
                input.accept = 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx';

                input.onchange = (e) => {
                    const files = Array.from(e.target.files);
                    if (files.length === 0) return;

                    uploadFiles(files);
                };

                input.click();
            }

            // Upload files
            function uploadFiles(files) {
                showProgress('Uploading Files');

                const formData = new FormData();
                files.forEach(file => {
                    formData.append('files[]', file);
                });

                if (currentPath) {
                    formData.append('path', currentPath);
                }

                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        updateProgress(percent, `Uploading ${percent}%`);
                    }
                });

                xhr.addEventListener('load', () => {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            updateProgress(100, 'Processing files...');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess('Files uploaded successfully');
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Upload failed');
                        }
                    } catch (error) {
                        hideProgress();
                        showError(error.message);
                    }
                });

                xhr.addEventListener('error', () => {
                    hideProgress();
                    showError('Upload failed');
                });

                xhr.open('POST', '{{ route("gallery.upload") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send(formData);
            }

            // Create new folder
            function createNewFolder() {
                const folderName = prompt('Enter folder name:');
                if (!folderName) return;

                showProgress('Creating Folder');
                updateProgress(30, 'Creating folder...');

                fetch('{{ route("gallery.folder.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: folderName,
                        parent: currentPath
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateProgress(100, 'Folder created');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess('Folder created successfully');
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Folder creation failed');
                        }
                    })
                    .catch(error => {
                        hideProgress();
                        console.error('Folder creation error:', error);
                        showError(error.message);
                    });
            }

            // Delete selected items
            function deleteSelectedItems() {
                if (selectedItems.length === 0) return;

                const permanent = isTrashView;
                const message = permanent
                    ? `Are you sure you want to permanently delete ${selectedItems.length} item(s)? This cannot be undone.`
                    : `Move ${selectedItems.length} item(s) to trash?`;

                if (!confirm(message)) return;

                showProgress(permanent ? 'Deleting Items' : 'Moving to Trash');
                updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.batch.destroy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: selectedItems,
                        permanent: permanent
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateProgress(100, 'Completed');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess(data.message);
                                clearPreview();
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        hideProgress();
                        console.error('Delete error:', error);
                        showError(error.message);
                    });
            }

            // Restore selected items
            function restoreSelectedItems() {
                if (selectedItems.length === 0) return;

                if (!confirm(`Restore ${selectedItems.length} item(s) from trash?`)) return;

                showProgress('Restoring Items');
                updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.batch.restore") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: selectedItems
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateProgress(100, 'Completed');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess(data.message);
                                clearPreview();
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Restore failed');
                        }
                    })
                    .catch(error => {
                        hideProgress();
                        console.error('Restore error:', error);
                        showError(error.message);
                    });
            }

            // Set as featured
            function setAsFeatured() {
                if (selectedItems.length !== 1 || selectedItems[0].type !== 'file') return;

                showProgress('Setting as Featured');
                updateProgress(30, 'Processing...');

                fetch(`{{ route("gallery.set-featured", '') }}/${selectedItems[0].id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateProgress(100, 'Completed');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess(data.message);
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Failed to set featured');
                        }
                    })
                    .catch(error => {
                        hideProgress();
                        console.error('Featured error:', error);
                        showError(error.message);
                    });
            }

            // Insert selected item
            function insertSelectedItem() {
                if (selectedItems.length !== 1) {
                    alert('Please select exactly one item');
                    return;
                }

                const item = selectedItems[0];

                if (galleryCallback) {
                    fetch(`/gallery/${item.type}/${item.id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                galleryCallback(data.item);
                                closeGalleryModal();
                            } else {
                                throw new Error(data.message || 'Failed to get item details');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError(error.message);
                        });
                } else {
                    fetch(`{{ route("gallery.generate-url", '') }}/${item.id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
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
                            showError(error.message);
                        });
                }
            }

            // Perform search
            function performSearch() {
                const query = searchInput.value.trim();
                if (!query) {
                    loadGalleryContents();
                    return;
                }

                showLoading();

                const url = new URL('{{ route("gallery.index") }}');
                url.searchParams.append('search', query);
                if (currentPath) url.searchParams.append('path', currentPath);

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderGalleryContents(data.contents);
                            searchInput.focus();
                        } else {
                            throw new Error(data.message || 'Search failed');
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        showError(error.message);
                    })
                    .finally(hideLoading);
            }

            // Show context menu
            function showContextMenu(e) {
                e.preventDefault();
                closeContextMenu();

                const menu = document.createElement('div');
                menu.className = 'context-menu';
                menu.style.left = `${e.pageX}px`;
                menu.style.top = `${e.pageY}px`;

                const itemElement = e.target.closest('.gallery-item');
                let item = null;

                if (itemElement) {
                    item = {
                        id: itemElement.dataset.id,
                        type: itemElement.dataset.type
                    };

                    if (!selectedItems.some(selected => selected.id === item.id && selected.type === item.type)) {
                        selectedItems = [item];
                        updateSelectionDisplay();
                    }
                }

                const menuItems = getContextMenuItems(item);
                menuItems.forEach(menuItem => {
                    if (menuItem.type === 'separator') {
                        const separator = document.createElement('div');
                        separator.className = 'context-menu-separator';
                        menu.appendChild(separator);
                    } else {
                        const button = document.createElement('div');
                        button.className = `context-menu-item ${menuItem.danger ? 'danger' : ''}`;
                        button.innerHTML = `
                            <i class="fas fa-${menuItem.icon}"></i>
                            <span>${menuItem.label}</span>
                            ${menuItem.shortcut ? `<span class="ml-auto">${menuItem.shortcut}</span>` : ''}
                        `;
                        button.addEventListener('click', () => {
                            executeContextMenuAction(menuItem.action);
                            closeContextMenu();
                        });
                        menu.appendChild(button);
                    }
                });

                document.body.appendChild(menu);

                const closeOnClick = (e) => {
                    if (!e.target.closest('.context-menu')) {
                        closeContextMenu();
                        document.removeEventListener('click', closeOnClick);
                    }
                };

                setTimeout(() => {
                    document.addEventListener('click', closeOnClick);
                }, 100);
            }

            // Close context menu
            function closeContextMenu() {
                const menu = document.querySelector('.context-menu');
                if (menu) menu.remove();
            }

            // Get context menu items based on current state
            function getContextMenuItems(item) {
                const items = [];

                if (isTrashView) {
                    items.push(
                        { label: 'Restore', icon: 'trash-restore', action: 'restoreItems', available: selectedItems.length > 0 },
                        { label: 'Delete Permanently', icon: 'trash-alt', action: 'deleteItems', available: selectedItems.length > 0, danger: true },
                        { type: 'separator' },
                        { label: 'Empty Trash', icon: 'broom', action: 'emptyTrash', available: true },
                        { type: 'separator' },
                        { label: 'Refresh', icon: 'sync', action: 'refresh', available: true }
                    );
                } else {
                    items.push(
                        { label: 'New Folder', icon: 'folder-plus', action: 'createFolder', available: true },
                        { label: 'Upload Files', icon: 'upload', action: 'uploadFiles', available: true },
                        { type: 'separator' },
                        { label: 'Cut', icon: 'cut', action: 'cutItems', available: selectedItems.length > 0, shortcut: 'Ctrl+X' },
                        { label: 'Copy', icon: 'copy', action: 'copyItems', available: selectedItems.length > 0, shortcut: 'Ctrl+C' },
                        { label: 'Paste', icon: 'paste', action: 'pasteItems', available: clipboard !== null, shortcut: 'Ctrl+V' },
                        { type: 'separator' },
                        { label: 'Rename', icon: 'i-cursor', action: 'renameItem', available: selectedItems.length === 1, shortcut: 'F2' },
                        { label: 'Delete', icon: 'trash', action: 'deleteItems', available: selectedItems.length > 0, shortcut: 'Del', danger: true },
                        { type: 'separator' },
                        { label: 'Select All', icon: 'check-square', action: 'selectAll', available: true, shortcut: 'Ctrl+A' },
                        { label: 'Properties', icon: 'info-circle', action: 'showProperties', available: selectedItems.length === 1 }
                    );
                }

                return items.filter(item => item.available !== false);
            }

            // Execute context menu action
            function executeContextMenuAction(action) {
                switch (action) {
                    case 'createFolder': createNewFolder(); break;
                    case 'uploadFiles': showUploadDialog(); break;
                    case 'cutItems': cutItems(); break;
                    case 'copyItems': copyItems(); break;
                    case 'pasteItems': pasteItems(); break;
                    case 'renameItem': renameItem(); break;
                    case 'deleteItems': deleteSelectedItems(); break;
                    case 'restoreItems': restoreSelectedItems(); break;
                    case 'emptyTrash': emptyTrash(); break;
                    case 'selectAll': selectAllItems(); break;
                    case 'showProperties': showProperties(); break;
                    case 'refresh': loadGalleryContents(); break;
                    default: console.warn('Unknown action:', action);
                }
            }

            // Cut items to clipboard
            function cutItems() {
                if (selectedItems.length === 0) return;

                clipboard = {
                    action: 'cut',
                    items: selectedItems
                };

                fetch('{{ route("gallery.cut") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: selectedItems
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess(data.message);
                        } else {
                            throw new Error(data.message || 'Failed to cut items');
                        }
                    })
                    .catch(error => {
                        console.error('Cut error:', error);
                        showError(error.message);
                    });
            }

            // Copy items to clipboard
            function copyItems() {
                if (selectedItems.length === 0) return;

                clipboard = {
                    action: 'copy',
                    items: selectedItems
                };

                fetch('{{ route("gallery.copy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: selectedItems
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess(data.message);
                        } else {
                            throw new Error(data.message || 'Failed to copy items');
                        }
                    })
                    .catch(error => {
                        console.error('Copy error:', error);
                        showError(error.message);
                    });
            }

            // Paste items from clipboard
            function pasteItems() {
                if (!clipboard || clipboard.items.length === 0) return;

                showProgress('Pasting Items');
                updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.paste") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: clipboard.items,
                        target_path: currentPath
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateProgress(100, 'Completed');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess(data.message);
                                if (clipboard.action === 'cut') {
                                    clipboard = null;
                                }
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Paste failed');
                        }
                    })
                    .catch(error => {
                        hideProgress();
                        console.error('Paste error:', error);
                        showError(error.message);
                    });
            }

            // Rename selected item
            function renameItem() {
                if (selectedItems.length !== 1) return;

                const item = selectedItems[0];
                const currentName = prompt('Enter new name:');
                if (!currentName) return;

                showProgress('Renaming Item');
                updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.update", "") }}/' + item.id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        type: item.type,
                        new_name: currentName
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateProgress(100, 'Completed');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess(data.message);
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Rename failed');
                        }
                    })
                    .catch(error => {
                        hideProgress();
                        console.error('Rename error:', error);
                        showError(error.message);
                    });
            }

            // Empty trash
            function emptyTrash() {
                if (!confirm('Are you sure you want to permanently delete all items in the trash? This cannot be undone.')) {
                    return;
                }

                showProgress('Emptying Trash');
                updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.empty-trash") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateProgress(100, 'Completed');
                            setTimeout(() => {
                                hideProgress();
                                showSuccess(data.message);
                                loadGalleryContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Failed to empty trash');
                        }
                    })
                    .catch(error => {
                        hideProgress();
                        console.error('Empty trash error:', error);
                        showError(error.message);
                    });
            }

            // Select all items
            function selectAllItems() {
                const items = document.querySelectorAll('.gallery-item');
                selectedItems = [];

                items.forEach(item => {
                    const id = item.dataset.id;
                    const type = item.dataset.type;

                    if (type === 'go-up') return;

                    selectedItems.push({ id, type });
                    item.classList.add('selected');
                    item.querySelector('.item-checkbox input').checked = true;
                });

                updateSelectionDisplay();
            }

            // Show properties for selected item
            function showProperties() {
                if (selectedItems.length !== 1) return;

                const item = selectedItems[0];

                fetch(`/gallery/properties/${item.type}/${item.id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showPropertiesModal(data.properties, item.type);
                        } else {
                            throw new Error(data.message || 'Failed to get properties');
                        }
                    })
                    .catch(error => {
                        console.error('Properties error:', error);
                        showError(error.message);
                    });
            }

            // Show properties modal
            function showPropertiesModal(properties, type) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';

                const content = document.createElement('div');
                content.className = 'bg-white rounded-lg shadow-xl w-full max-w-md';

                const header = document.createElement('div');
                header.className = 'px-6 py-4 border-b border-gray-200 flex justify-between items-center';
                header.innerHTML = `
                    <h3 class="text-lg font-medium text-gray-900">
                        ${type === 'file' ? 'File' : 'Folder'} Properties
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                const body = document.createElement('div');
                body.className = 'px-6 py-4';

                let propertiesHTML = '';
                for (const [key, value] of Object.entries(properties)) {
                    propertiesHTML += `
                        <div class="mb-3">
                            <div class="text-sm font-medium text-gray-500 capitalize">${key.replace('_', ' ')}</div>
                            <div class="mt-1 text-sm text-gray-900">${value || 'N/A'}</div>
                        </div>
                    `;
                }

                body.innerHTML = propertiesHTML;

                const footer = document.createElement('div');
                footer.className = 'px-6 py-3 border-t border-gray-200 flex justify-end';
                footer.innerHTML = `
                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        OK
                    </button>
                `;

                content.appendChild(header);
                content.appendChild(body);
                content.appendChild(footer);
                modal.appendChild(content);
                document.body.appendChild(modal);

                const closeButton = header.querySelector('button');
                const okButton = footer.querySelector('button');

                const closeModal = () => {
                    modal.remove();
                };

                closeButton.addEventListener('click', closeModal);
                okButton.addEventListener('click', closeModal);
            }

            // Handle keyboard shortcuts
            function handleKeyboardShortcuts(e) {
                if (!galleryModal.classList.contains('hidden')) {
                    if (e.ctrlKey) {
                        switch (e.key.toLowerCase()) {
                            case 'a': e.preventDefault(); selectAllItems(); break;
                            case 'c': e.preventDefault(); copyItems(); break;
                            case 'x': e.preventDefault(); cutItems(); break;
                            case 'v': e.preventDefault(); pasteItems(); break;
                        }
                    }

                    switch (e.key) {
                        case 'F2': e.preventDefault(); renameItem(); break;
                        case 'Delete': e.preventDefault(); deleteSelectedItems(); break;
                        case 'Escape': e.preventDefault(); closeContextMenu(); break;
                    }
                }
            }

            // Helper functions
            function getFileIcon(mimeType) {
                if (mimeType.startsWith('image/')) return 'file-image';
                if (mimeType.startsWith('video/')) return 'file-video';
                if (mimeType.startsWith('audio/')) return 'file-audio';
                if (mimeType.includes('pdf')) return 'file-pdf';
                if (mimeType.includes('zip') || mimeType.includes('compressed')) return 'file-archive';
                if (mimeType.includes('word')) return 'file-word';
                if (mimeType.includes('excel')) return 'file-excel';
                if (mimeType.includes('powerpoint')) return 'file-powerpoint';
                return 'file';
            }

            function formatFileSize(bytes) {
                if (!bytes) return '0 B';

                const units = ['B', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return `${(bytes / Math.pow(1024, i)).toFixed(1)} ${units[i]}`;
            }

            function formatDate(dateString) {
                if (!dateString) return '';

                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = String(date.getFullYear()).slice(-2); // Get last 2 digits

                return `${day}-${month}-${year}`;
            }


            function copyToClipboard(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }

            function showLoading() {
                const loading = document.createElement('div');
                loading.className = 'loading-overlay';
                loading.innerHTML = '<i class="fas fa-spinner loading-spinner"></i>';
                galleryModal.querySelector('.gallery-container').appendChild(loading);
            }

            function hideLoading() {
                const loading = galleryModal.querySelector('.loading-overlay');
                if (loading) loading.remove();
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

            // Initialize the gallery
            initGallery();


            // Debugging: Make sure the open function is properly exposed
            console.log('Gallery initialized. Use window.openGalleryModal() to open the gallery');
        });
    </script>
@endpush
