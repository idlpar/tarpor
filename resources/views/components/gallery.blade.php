<style>
    /* Gallery Container */
    .gallery-container {
        display: flex;
        flex-direction: column;
        height: 80vh;
        max-height: 800px;
        background-color: #f8fafc;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Header */
    .gallery-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .gallery-title {
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .gallery-close {
        background: #FFFFFF;
        border: 2px solid #FFFFFF;
        border-radius: 50%;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #be123c;
        cursor: pointer;
        transition: all 0.2s;
    }

    .gallery-close:hover {
        background: #be123c;
        color: white;
        border-color: #be123c;
        transform: scale(1.05);
    }

    /* Toolbar */
    .gallery-toolbar {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 1rem;
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
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s;
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
        border-radius: 0.375rem;
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

    /* Breadcrumbs */
    .breadcrumb-container {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: white;
        border-bottom: 1px solid #e2e8f0;
        overflow-x: auto;
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
    }

    .breadcrumb-button {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: none;
        border: none;
        cursor: pointer;
    }

    .breadcrumb-button:hover {
        background-color: #f1f5f9;
    }

    .breadcrumb-separator {
        margin: 0 0.5rem;
        color: #cbd5e1;
    }

    /* Main Content */
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
        border-radius: 0.5rem;
        overflow: hidden;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background-color: white;
        cursor: pointer;
        height: 160px;
        display: flex;
        flex-direction: column;
    }

    .gallery-item.selected {
        border: 2px solid #4f46e5;
    }

    .item-thumbnail {
        flex-grow: 1;
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
        top: 0.25rem;
        right: 0.25rem;
        z-index: 10;
    }

    .item-checkbox input {
        cursor: pointer;
    }

    .item-info {
        padding: 0.5rem;
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
    }

    /* Preview Panel */
    .preview-panel {
        position: relative;
        overflow-y: auto;
        padding: 1rem;
        background: white;
        border-left: 1px solid #e2e8f0;
    }

    .preview-image-container {
        max-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .preview-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 0.25rem;
    }

    .preview-header {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #1e293b;
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
        padding: 0.5rem 0;
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
        gap: 0.5rem;
        margin-top: 1.5rem;
    }

    .action-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
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

    /* Context Menu */
    .context-menu {
        position: fixed;
        z-index: 1000;
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        padding: 0.5rem 0;
    }

    .context-menu-item {
        width: 100%;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: left;
        background: none;
        border: none;
        font-size: 0.875rem;
    }

    .context-menu-item:hover {
        background-color: #f1f5f9;
    }

    .context-menu-item.danger {
        color: #ef4444;
    }

    .context-menu-item.danger:hover {
        background-color: #fee2e2;
    }

    .context-menu-separator {
        height: 1px;
        background-color: #e2e8f0;
        margin: 0.25rem 0;
    }

    .shortcut {
        margin-left: auto;
        color: #94a3b8;
        font-size: 0.75rem;
    }

    /* Loading State */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
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

    /* Progress Modal */
    .progress-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .progress-modal.show {
        display: flex;
    }

    .progress-content {
        background-color: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        width: 400px;
        max-width: 90%;
    }

    .progress-header {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .progress-bar-container {
        height: 0.5rem;
        background-color: #e2e8f0;
        border-radius: 0.25rem;
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

    /* Trash View */
    .trash-actions {
        display: none;
    }

    .trash-view .trash-actions {
        display: flex;
        gap: 0.5rem;
    }

    .trash-view .normal-actions {
        display: none;
    }

    .deleted-item {
        opacity: 0.8;
        position: relative;
    }

    .deleted-item::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            45deg,
            rgba(239, 68, 68, 0.1),
            rgba(239, 68, 68, 0.1) 10px,
            transparent 10px,
            transparent 20px
        );
        pointer-events: none;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .gallery-content {
            grid-template-columns: 100%;
        }

        .preview-panel {
            display: none;
        }
    }
</style>

<!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 overflow-y-auto transition-opacity duration-300 flex justify-center items-center p-4">
    <div class="gallery-container w-full max-w-6xl bg-white rounded-lg shadow-xl overflow-hidden">
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
        <div class="gallery-toolbar">
            <div class="toolbar-group normal-actions">
                <button type="button" id="refreshButton" class="toolbar-button secondary">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh</span>
                </button>
                <button type="button" id="newFolderButton" class="toolbar-button secondary">
                    <i class="fas fa-folder-plus"></i>
                    <span>New Folder</span>
                </button>
                <button type="button" id="uploadButton" class="toolbar-button secondary">
                    <i class="fas fa-upload"></i>
                    <span>Upload</span>
                </button>
                <button type="button" id="deleteButton" class="toolbar-button danger hidden">
                    <i class="fas fa-trash"></i>
                    <span>Delete</span>
                </button>
            </div>

            <div class="toolbar-group trash-actions">
                <button type="button" id="restoreButton" class="toolbar-button secondary">
                    <i class="fas fa-trash-restore"></i>
                    <span>Restore</span>
                </button>
                <button type="button" id="permanentDeleteButton" class="toolbar-button danger">
                    <i class="fas fa-trash"></i>
                    <span>Delete Permanently</span>
                </button>
                <button type="button" id="emptyTrashButton" class="toolbar-button danger">
                    <i class="fas fa-broom"></i>
                    <span>Empty Trash</span>
                </button>
            </div>

            <div class="toolbar-group">
                <button type="button" id="toggleTrashButton" class="toolbar-button secondary">
                    <i class="fas fa-trash"></i>
                    <span id="trashButtonText">Trash</span>
                </button>
                <div class="search-container">
                    <input type="text" id="searchInput" class="search-input" placeholder="Search...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div id="breadcrumbContainer" class="breadcrumb-container">
            <!-- Dynamic breadcrumbs will be inserted here -->
        </div>

        <!-- Main Content -->
        <div class="gallery-content relative">
            <!-- Items Grid -->
            <div class="items-grid" id="galleryItems">
                <!-- Dynamic content will be inserted here -->
            </div>

            <!-- Preview Panel -->
            <div class="preview-panel">
                <div class="preview-header">Preview</div>
                <div id="previewContent" class="preview-content">
                    <div class="preview-empty">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <p>No item selected</p>
                    </div>
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

                <div id="actionButtons" class="action-buttons hidden">
                    <button class="action-button primary" id="insertButton">
                        <i class="fas fa-check mr-2"></i>
                        Insert
                    </button>
                    <button class="action-button secondary" id="downloadButton">
                        <i class="fas fa-download mr-2"></i>
                        Download
                    </button>
                    <button class="action-button danger" id="deleteSingleButton">
                        <i class="fas fa-trash mr-2"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div id="progressModal" class="progress-modal">
    <div class="progress-content">
        <div class="progress-header" id="progressTitle">Processing Files</div>
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        <div class="progress-status" id="progressStatus">0% Complete</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gallery Controller
        const gallery = {
            // DOM Elements
            elements: {
                modal: document.getElementById('galleryModal'),
                closeBtn: document.getElementById('closeGalleryModal'),
                itemsContainer: document.getElementById('galleryItems'),
                breadcrumbs: document.getElementById('breadcrumbContainer'),
                previewContent: document.getElementById('previewContent'),
                previewDetails: document.getElementById('previewDetails'),
                detailName: document.getElementById('detailName'),
                detailType: document.getElementById('detailType'),
                detailSize: document.getElementById('detailSize'),
                detailDimensions: document.getElementById('detailDimensions'),
                detailUploaded: document.getElementById('detailUploaded'),
                actionButtons: document.getElementById('actionButtons'),
                insertButton: document.getElementById('insertButton'),
                downloadButton: document.getElementById('downloadButton'),
                deleteSingleButton: document.getElementById('deleteSingleButton'),
                refreshButton: document.getElementById('refreshButton'),
                newFolderButton: document.getElementById('newFolderButton'),
                uploadButton: document.getElementById('uploadButton'),
                deleteButton: document.getElementById('deleteButton'),
                restoreButton: document.getElementById('restoreButton'),
                permanentDeleteButton: document.getElementById('permanentDeleteButton'),
                emptyTrashButton: document.getElementById('emptyTrashButton'),
                toggleTrashButton: document.getElementById('toggleTrashButton'),
                trashButtonText: document.getElementById('trashButtonText'),
                searchInput: document.getElementById('searchInput'),
                progressModal: document.getElementById('progressModal'),
                progressTitle: document.getElementById('progressTitle'),
                progressBar: document.getElementById('progressBar'),
                progressStatus: document.getElementById('progressStatus')
            },

            // State
            state: {
                currentPath: '',
                currentFolderId: null,
                selectedItems: [],
                isTrashView: false,
                clipboard: null,
                callback: null
            },

            // Initialize the gallery
            init() {
                this.setupEventListeners();
                this.setupAccessibility();
            },

            // Event Listeners
            setupEventListeners() {
                // Modal controls
                this.elements.closeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.closeModal();
                });

                // Navigation
                this.elements.refreshButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.refreshContents();
                });

                this.elements.toggleTrashButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleTrashView();
                });

                // File operations
                this.elements.uploadButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showUploadDialog();
                });

                this.elements.newFolderButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.createNewFolder();
                });

                // Item actions
                this.elements.deleteButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.deleteSelected();
                });

                this.elements.restoreButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.restoreSelected();
                });

                this.elements.permanentDeleteButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.deleteSelected(true);
                });

                this.elements.emptyTrashButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.emptyTrash();
                });

                this.elements.insertButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.insertSelected();
                });

                // Search
                this.elements.searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.performSearch();
                    }
                });

                // Context menu
                this.elements.itemsContainer.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                    this.handleContextMenu(e);
                });

                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.context-menu')) {
                        this.closeContextMenu();
                    }
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    this.handleKeyboardShortcuts(e);
                });

                // Item selection
                this.elements.itemsContainer.addEventListener('click', (e) => {
                    const item = e.target.closest('.gallery-item');
                    if (item) {
                        e.preventDefault();
                        this.handleItemClick(item, e);
                    }

                    const checkbox = e.target.closest('.item-checkbox input');
                    if (checkbox) {
                        e.stopPropagation();
                        const item = checkbox.closest('.gallery-item');
                        const id = item.dataset.id;
                        const type = item.dataset.type;
                        this.toggleItemSelection(id, type, item);
                    }
                });

                // Double click for opening folders
                this.elements.itemsContainer.addEventListener('dblclick', (e) => {
                    const item = e.target.closest('.gallery-item');
                    if (item && item.dataset.type === 'folder') {
                        e.preventDefault();
                        this.navigateToFolder(item);
                    }
                });
            },

            setupAccessibility() {
                this.elements.modal.setAttribute('aria-modal', 'true');
                this.elements.modal.setAttribute('role', 'dialog');
                this.elements.modal.setAttribute('aria-labelledby', 'galleryTitle');
                this.elements.itemsContainer.setAttribute('tabindex', '0');
            },

            // Navigation
            navigateToPath(path) {
                this.state.currentPath = path;
                this.loadContents();
            },

            navigateToFolder(folderElement) {
                if (this.state.isTrashView) {
                    const folderId = folderElement.dataset.id;
                    this.loadTrashContents(folderId);
                } else {
                    this.state.currentPath = folderElement.dataset.path;
                    this.state.currentFolderId = folderElement.dataset.id;
                    this.loadContents();
                }
            },

            loadContents() {
                this.showLoading();

                const url = new URL('{{ route("gallery.index") }}');
                url.searchParams.append('path', this.state.currentPath);

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.state.currentPath = data.currentPath || '';
                            this.renderBreadcrumbs(data.breadcrumbs);
                            this.renderContents(data.contents);
                        } else {
                            throw new Error(data.message || 'Failed to load contents');
                        }
                    })
                    .catch(error => this.showError(error.message))
                    .finally(() => this.hideLoading());
            },

            loadTrashContents(parentId = null) {
                this.showLoading();

                const url = new URL('{{ route("gallery.trash") }}');
                if (parentId) {
                    url.searchParams.append('parent_id', parentId);
                }

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.state.currentTrashParent = parentId;
                            this.renderContents(data.contents);
                        } else {
                            throw new Error(data.message || 'Failed to load trash contents');
                        }
                    })
                    .catch(error => this.showError(error.message))
                    .finally(() => this.hideLoading());
            },

            renderBreadcrumbs(breadcrumbs) {
                this.elements.breadcrumbs.innerHTML = '';

                breadcrumbs.forEach((crumb, index) => {
                    if (index > 0) {
                        const separator = document.createElement('span');
                        separator.className = 'breadcrumb-separator';
                        separator.innerHTML = '<i class="fas fa-chevron-right"></i>';
                        this.elements.breadcrumbs.appendChild(separator);
                    }

                    const button = document.createElement('button');
                    button.className = 'breadcrumb-button';
                    button.dataset.path = crumb.path;
                    button.innerHTML = `
                        ${crumb.icon ? `<i class="fas fa-${crumb.icon}"></i>` : ''}
                        <span>${crumb.name}</span>
                    `;
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.navigateToPath(crumb.path);
                    });
                    this.elements.breadcrumbs.appendChild(button);
                });
            },

            renderContents(contents) {
                this.elements.itemsContainer.innerHTML = '';
                this.clearSelections();

                if (this.state.isTrashView) {
                    this.renderTrashView(contents);
                } else {
                    this.renderNormalView(contents);
                }
            },

            renderTrashView(contents) {
                if ((!contents.folders || contents.folders.length === 0) &&
                    (!contents.files || contents.files.length === 0)) {
                    this.showEmptyState('Trash is empty', 'fa-trash-alt');
                    return;
                }

                if (contents.folders && contents.folders.length > 0) {
                    contents.folders.forEach(folder => {
                        this.createFolderItem(folder, true);
                    });
                }

                if (contents.files && contents.files.length > 0) {
                    contents.files.forEach(file => {
                        this.createFileItem(file, true);
                    });
                }
            },

            renderNormalView(contents) {
                if ((!contents.folders || contents.folders.length === 0) &&
                    (!contents.files || contents.files.length === 0)) {
                    this.showEmptyState('This folder is empty', 'fa-folder-open');
                    return;
                }

                if (this.state.currentPath) {
                    this.createGoUpItem();
                }

                if (contents.folders && contents.folders.length > 0) {
                    contents.folders.forEach(folder => {
                        this.createFolderItem(folder, false);
                    });
                }

                if (contents.files && contents.files.length > 0) {
                    contents.files.forEach(file => {
                        this.createFileItem(file, false);
                    });
                }
            },

            showEmptyState(message, icon) {
                const emptyState = document.createElement('div');
                emptyState.className = 'empty-state';
                emptyState.innerHTML = `
                    <i class="fas ${icon} text-5xl mb-4"></i>
                    <p>${message}</p>
                `;
                this.elements.itemsContainer.appendChild(emptyState);
            },

            createGoUpItem() {
                const goUpItem = document.createElement('div');
                goUpItem.className = 'gallery-item';
                goUpItem.dataset.id = 'go-up';
                goUpItem.dataset.type = 'folder';
                goUpItem.innerHTML = `
                    <div class="item-thumbnail">
                        <div class="h-full flex items-center justify-center">
                            <i class="fas fa-level-up-alt folder-icon text-4xl"></i>
                        </div>
                    </div>
                    <div class="item-info">
                        <div class="item-name">Go Up</div>
                    </div>
                `;

                goUpItem.addEventListener('click', (e) => {
                    e.preventDefault();
                    const parts = this.state.currentPath.split('/');
                    parts.pop();
                    this.state.currentPath = parts.join('/');
                    this.state.currentFolderId = null;
                    this.loadContents();
                });

                this.elements.itemsContainer.appendChild(goUpItem);
            },

            createFolderItem(folder, isTrash) {
                const folderItem = document.createElement('div');
                folderItem.className = `gallery-item ${this.isSelected(folder.id, 'folder') ? 'selected' : ''}`;
                folderItem.dataset.id = folder.id;
                folderItem.dataset.type = 'folder';
                folderItem.dataset.path = folder.path;
                if (folder.parent_id) folderItem.dataset.parent_id = folder.parent_id;

                folderItem.innerHTML = `
                    <div class="item-thumbnail">
                        <div class="h-full flex items-center justify-center">
                            <i class="fas fa-folder folder-icon text-4xl text-yellow-400"></i>
                        </div>
                        <div class="item-checkbox">
                            <input type="checkbox" ${this.isSelected(folder.id, 'folder') ? 'checked' : ''}>
                        </div>
                        <div class="absolute bottom-1 left-1 text-xs text-gray-600 bg-white/70 px-1 rounded">
                            ${folder.item_count || 0} items
                        </div>
                    </div>
                    <div class="item-info">
                        <div class="item-name">${folder.name}</div>
                        <div class="item-meta">${this.formatDate(folder.created_at)}</div>
                    </div>
                `;

                if (isTrash) {
                    folderItem.classList.add('deleted-item');
                }

                folderItem.addEventListener('click', (e) => {
                    if (e.shiftKey) {
                        this.toggleItemSelection(folder.id, 'folder', folderItem);
                    } else {
                        // Single click just selects the item
                        if (!this.isSelected(folder.id, 'folder')) {
                            this.clearSelections();
                            this.toggleItemSelection(folder.id, 'folder', folderItem);
                        }
                    }
                });

                this.elements.itemsContainer.appendChild(folderItem);
            },

            createFileItem(file, isTrash) {
                const fileItem = document.createElement('div');
                fileItem.className = `gallery-item ${this.isSelected(file.id, 'file') ? 'selected' : ''}`;
                fileItem.dataset.id = file.id;
                fileItem.dataset.type = 'file';

                const thumbnail = file.thumb_url || file.url;
                const thumbnailContent = thumbnail ?
                    `<img src="${thumbnail}" alt="${file.name}" class="max-h-full max-w-full">` :
                    `<i class="fas fa-${this.getFileIcon(file.mime_type)} folder-icon text-4xl text-blue-400"></i>`;

                fileItem.innerHTML = `
                    <div class="item-thumbnail">
                        ${thumbnailContent}
                        ${file.is_featured ? '<i class="fas fa-star absolute top-1 left-1 text-yellow-400"></i>' : ''}
                        <div class="item-checkbox">
                            <input type="checkbox" ${this.isSelected(file.id, 'file') ? 'checked' : ''}>
                        </div>
                        <div class="absolute bottom-1 left-1 text-xs text-gray-600 bg-white/70 px-1 rounded">
                            ${this.formatFileSize(file.size)}
                        </div>
                    </div>
                    <div class="item-info">
                        <div class="item-name">${file.name}</div>
                        <div class="item-meta">${this.formatDate(file.created_at)}</div>
                    </div>
                `;

                if (isTrash) {
                    fileItem.classList.add('deleted-item');
                }

                fileItem.addEventListener('click', (e) => {
                    if (e.shiftKey) {
                        this.toggleItemSelection(file.id, 'file', fileItem);
                    } else {
                        // Single click just selects the item
                        if (!this.isSelected(file.id, 'file')) {
                            this.clearSelections();
                            this.toggleItemSelection(file.id, 'file', fileItem);
                        }
                    }
                    this.updatePreview(file.id, 'file');
                });

                this.elements.itemsContainer.appendChild(fileItem);
            },

            // Item Interactions
            handleItemClick(item, event) {
                const id = item.dataset.id;
                const type = item.dataset.type;

                if (event.shiftKey) {
                    // Handle shift+click for multi-selection
                    this.toggleItemSelection(id, type, item);
                } else {
                    // Regular click - just select the item
                    if (!this.isSelected(id, type)) {
                        this.clearSelections();
                        this.toggleItemSelection(id, type, item);
                    }
                }

                if (type === 'file') {
                    this.updatePreview(id, type);
                }
            },

            toggleItemSelection(id, type, element) {
                const index = this.state.selectedItems.findIndex(item =>
                    item.id === id && item.type === type
                );

                if (index === -1) {
                    this.state.selectedItems.push({ id, type });
                    element.classList.add('selected');
                    const checkbox = element.querySelector('.item-checkbox input');
                    if (checkbox) checkbox.checked = true;
                } else {
                    this.state.selectedItems.splice(index, 1);
                    element.classList.remove('selected');
                    const checkbox = element.querySelector('.item-checkbox input');
                    if (checkbox) checkbox.checked = false;
                }

                this.updateSelectionDisplay();
            },

            isSelected(id, type) {
                return this.state.selectedItems.some(item =>
                    item.id === id && item.type === type
                );
            },

            clearSelections() {
                this.elements.itemsContainer.querySelectorAll('.gallery-item.selected').forEach(el => {
                    el.classList.remove('selected');
                    const checkbox = el.querySelector('.item-checkbox input');
                    if (checkbox) checkbox.checked = false;
                });
                this.state.selectedItems = [];
                this.updateSelectionDisplay();
                this.clearPreview();
            },

            updateSelectionDisplay() {
                const hasSelection = this.state.selectedItems.length > 0;
                const singleSelection = this.state.selectedItems.length === 1;
                const isTrashView = this.state.isTrashView;

                // Update action buttons visibility
                this.elements.deleteButton.classList.toggle('hidden', !hasSelection || isTrashView);
                this.elements.restoreButton.classList.toggle('hidden', !isTrashView || !hasSelection);
                this.elements.permanentDeleteButton.classList.toggle('hidden', !isTrashView || !hasSelection);

                // Update preview if single file is selected
                if (singleSelection && this.state.selectedItems[0].type === 'file') {
                    this.fetchFileDetails(this.state.selectedItems[0].id);
                } else if (!hasSelection) {
                    this.clearPreview();
                }
            },

            // Preview Management
            updatePreview(id, type) {
                this.clearPreview();
                this.showLoadingPreview();

                const endpoint = type === 'folder' ?
                    `{{ route("gallery.folder.show", '') }}/${id}` :
                    `{{ route("gallery.file.show", '') }}/${id}`;

                fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (type === 'file') {
                                this.showFilePreview(data.file);
                            } else {
                                this.showFolderPreview(data.folder);
                            }
                        } else {
                            throw new Error(data.message || 'Failed to load preview');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showError('Could not load preview details');
                        this.clearPreview();
                    })
                    .finally(() => {
                        this.hideLoadingPreview();
                    });
            },

            showFilePreview(file) {
                if (!file) {
                    this.clearPreview();
                    return;
                }

                this.elements.previewContent.innerHTML = '';

                const previewContainer = document.createElement('div');
                previewContainer.className = 'preview-image-container';

                if (file.mime_type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = file.url;
                    img.alt = file.name;
                    img.className = 'preview-image';
                    previewContainer.appendChild(img);
                } else {
                    const icon = document.createElement('i');
                    icon.className = `fas fa-${this.getFileIcon(file.mime_type)} text-6xl text-gray-400`;
                    previewContainer.appendChild(icon);
                }

                this.elements.previewContent.appendChild(previewContainer);

                // Update details
                this.elements.detailName.textContent = file.name;
                this.elements.detailType.textContent = file.mime_type;
                this.elements.detailSize.textContent = this.formatFileSize(file.size);
                this.elements.detailDimensions.textContent = file.dimensions ?
                    `${file.dimensions.width} × ${file.dimensions.height}` : 'N/A';
                this.elements.detailUploaded.textContent = this.formatDate(file.created_at);

                // Show elements
                this.elements.previewDetails.classList.remove('hidden');
                this.elements.actionButtons.classList.remove('hidden');
                this.elements.previewContent.querySelector('.preview-empty')?.classList.add('hidden');
            },

            showFolderPreview(folder) {
                if (!folder) {
                    this.clearPreview();
                    return;
                }

                this.elements.previewContent.innerHTML = '';

                const previewContainer = document.createElement('div');
                previewContainer.className = 'preview-image-container flex items-center justify-center';

                const icon = document.createElement('i');
                icon.className = 'fas fa-folder text-6xl text-yellow-400';
                previewContainer.appendChild(icon);

                this.elements.previewContent.appendChild(previewContainer);

                // Update details
                this.elements.detailName.textContent = folder.name;
                this.elements.detailType.textContent = 'Folder';
                this.elements.detailSize.textContent = '-';
                this.elements.detailDimensions.textContent = '-';
                this.elements.detailUploaded.textContent = this.formatDate(folder.created_at);

                // Show elements
                this.elements.previewDetails.classList.remove('hidden');
                this.elements.actionButtons.classList.remove('hidden');
                this.elements.previewContent.querySelector('.preview-empty')?.classList.add('hidden');
            },

            showLoadingPreview() {
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay flex items-center justify-center';
                loadingOverlay.innerHTML = '<i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>';

                this.elements.previewContent.appendChild(loadingOverlay);
            },

            hideLoadingPreview() {
                const loadingOverlay = this.elements.previewContent.querySelector('.loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.remove();
                }
            },

            clearPreview() {
                this.elements.previewContent.innerHTML = `
                    <div class="preview-empty">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <p>No item selected</p>
                    </div>
                `;
                this.elements.previewDetails.classList.add('hidden');
                this.elements.actionButtons.classList.add('hidden');
            },

            fetchFileDetails(fileId) {
                fetch(`{{ route("gallery.file.show", '') }}/${fileId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.showFilePreview(data.file);
                        } else {
                            throw new Error(data.message || 'Failed to load file details');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showError('Could not load file details');
                        this.clearPreview();
                    });
            },

            // Gallery Operations
            toggleTrashView() {
                this.state.isTrashView = !this.state.isTrashView;
                this.state.currentPath = '';
                this.state.selectedItems = [];

                // Update UI
                document.querySelector('.gallery-container').classList.toggle('trash-view', this.state.isTrashView);
                this.elements.trashButtonText.textContent = this.state.isTrashView ? 'Back to Gallery' : 'Trash';

                // Load the appropriate content
                if (this.state.isTrashView) {
                    this.loadTrashContents();
                } else {
                    this.loadContents();
                }
            },

            refreshContents() {
                if (this.state.isTrashView) {
                    this.loadTrashContents(this.state.currentTrashParent);
                } else {
                    this.loadContents();
                }
            },

            showUploadDialog() {
                const input = document.createElement('input');
                input.type = 'file';
                input.multiple = true;
                input.accept = 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx';

                input.onchange = (e) => {
                    const files = Array.from(e.target.files);
                    if (files.length > 0) {
                        this.uploadFiles(files);
                    }
                };

                input.click();
            },

            uploadFiles(files) {
                this.showProgress('Uploading Files');

                const formData = new FormData();
                files.forEach(file => {
                    formData.append('files[]', file);
                });

                if (this.state.currentPath) {
                    formData.append('path', this.state.currentPath);
                }

                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        this.updateProgress(percent, `Uploading ${percent}%`);
                    }
                });

                xhr.addEventListener('load', () => {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            this.updateProgress(100, 'Processing files...');
                            setTimeout(() => {
                                this.hideProgress();
                                this.showSuccess('Files uploaded successfully');
                                this.loadContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Upload failed');
                        }
                    } catch (error) {
                        this.hideProgress();
                        this.showError(error.message);
                    }
                });

                xhr.addEventListener('error', () => {
                    this.hideProgress();
                    this.showError('Upload failed');
                });

                xhr.open('POST', '{{ route("gallery.upload") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send(formData);
            },

            createNewFolder() {
                const folderName = prompt('Enter folder name:');
                if (!folderName) return;

                if (!/^[a-zA-Z0-9\-_ ]+$/.test(folderName)) {
                    this.showError('Folder name can only contain letters, numbers, spaces, hyphens and underscores');
                    return;
                }

                this.showProgress('Creating Folder');

                fetch('{{ route("gallery.folder.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: folderName,
                        parent_id: this.state.currentFolderId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.showSuccess('Folder created successfully');
                            this.loadContents();
                        } else {
                            throw new Error(data.message || 'Folder creation failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showError(error.message);
                    })
                    .finally(() => this.hideProgress());
            },

            deleteSelected(permanent = false) {
                if (this.state.selectedItems.length === 0) return;

                const message = permanent
                    ? `Are you sure you want to permanently delete ${this.state.selectedItems.length} item(s)? This cannot be undone.`
                    : `Move ${this.state.selectedItems.length} item(s) to trash?`;

                if (!confirm(message)) return;

                this.showProgress(permanent ? 'Deleting Items' : 'Moving to Trash');
                this.updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.batch.delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: this.state.selectedItems,
                        permanent: permanent
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.updateProgress(100, 'Completed');
                            setTimeout(() => {
                                this.hideProgress();
                                this.showSuccess(data.message);
                                this.clearPreview();
                                this.refreshContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        this.hideProgress();
                        console.error('Delete error:', error);
                        this.showError(error.message);
                    });
            },

            restoreSelected() {
                if (this.state.selectedItems.length === 0) return;

                if (!confirm(`Restore ${this.state.selectedItems.length} item(s) from trash?`)) return;

                this.showProgress('Restoring Items');
                this.updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.batch.restore") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: this.state.selectedItems
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.updateProgress(100, 'Completed');
                            setTimeout(() => {
                                this.hideProgress();
                                this.showSuccess(data.message);
                                this.clearPreview();
                                this.refreshContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Restore failed');
                        }
                    })
                    .catch(error => {
                        this.hideProgress();
                        console.error('Restore error:', error);
                        this.showError(error.message);
                    });
            },

            emptyTrash() {
                if (!confirm('Are you sure you want to permanently delete all items in the trash? This cannot be undone.')) {
                    return;
                }

                this.showProgress('Emptying Trash');
                this.updateProgress(30, 'Processing...');

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
                            this.updateProgress(100, 'Completed');
                            setTimeout(() => {
                                this.hideProgress();
                                this.showSuccess(data.message);
                                this.clearPreview();
                                this.loadTrashContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Failed to empty trash');
                        }
                    })
                    .catch(error => {
                        this.hideProgress();
                        console.error('Empty trash error:', error);
                        this.showError(error.message);
                    });
            },

            insertSelected() {
                if (this.state.selectedItems.length !== 1) {
                    alert('Please select exactly one item');
                    return;
                }

                const item = this.state.selectedItems[0];

                if (item.type !== 'file') {
                    alert('Please select a file to insert');
                    return;
                }

                if (this.state.callback) {
                    fetch(`{{ route("gallery.file.for-insertion", '') }}/${item.id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.state.callback(data.file);
                                this.closeModal();
                            } else {
                                throw new Error(data.message || 'Failed to get file details');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.showError(error.message);
                        });
                } else {
                    // Default behavior if no callback provided
                    fetch(`{{ route("gallery.generate-url", '') }}/${item.id}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.copyToClipboard(data.url);
                                this.showSuccess('URL copied to clipboard');
                            } else {
                                throw new Error(data.message || 'URL generation failed');
                            }
                        })
                        .catch(error => {
                            console.error('URL generation error:', error);
                            this.showError(error.message);
                        });
                }
            },

            performSearch() {
                const query = this.elements.searchInput.value.trim();
                if (!query) {
                    this.loadContents();
                    return;
                }

                this.showLoading();

                const url = new URL('{{ route("gallery.index") }}');
                url.searchParams.append('search', query);
                if (this.state.currentPath) url.searchParams.append('path', this.state.currentPath);

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.renderContents(data.contents);
                        } else {
                            throw new Error(data.message || 'Search failed');
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        this.showError(error.message);
                    })
                    .finally(() => this.hideLoading());
            },

            // Context Menu
            handleContextMenu(e) {
                const item = e.target.closest('.gallery-item');
                if (!item) {
                    this.clearSelections();
                    return;
                }

                const id = item.dataset.id;
                const type = item.dataset.type;
                const isSelected = this.isSelected(id, type);

                if (!isSelected) {
                    this.clearSelections();
                    this.state.selectedItems = [{ id, type }];
                    item.classList.add('selected');
                    const checkbox = item.querySelector('.item-checkbox input');
                    if (checkbox) checkbox.checked = true;
                }

                this.updateSelectionDisplay();
                this.showContextMenu(e);
            },

            showContextMenu(e) {
                e.preventDefault();
                this.closeContextMenu();

                const menu = document.createElement('div');
                menu.className = 'context-menu';
                menu.style.left = `${Math.min(e.clientX, window.innerWidth - 200)}px`;
                menu.style.top = `${Math.min(e.clientY, window.innerHeight - 300)}px`;

                // Get context menu items
                const items = this.getContextMenuItems();

                items.forEach(item => {
                    if (item.type === 'separator') {
                        const sep = document.createElement('div');
                        sep.className = 'context-menu-separator';
                        menu.appendChild(sep);
                    } else {
                        const btn = document.createElement('button');
                        btn.className = `context-menu-item ${item.danger ? 'danger' : ''}`;
                        btn.innerHTML = `
                            <i class="fas fa-${item.icon}"></i>
                            <span>${item.label}</span>
                            ${item.shortcut ? `<span class="shortcut">${item.shortcut}</span>` : ''}
                        `;
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            this.executeMenuAction(item.action);
                            this.closeContextMenu();
                        });
                        menu.appendChild(btn);
                    }
                });

                document.body.appendChild(menu);
            },

            closeContextMenu() {
                const menu = document.querySelector('.context-menu');
                if (menu) menu.remove();
            },

            getContextMenuItems() {
                const { selectedItems, isTrashView, clipboard } = this.state;
                const hasSelection = selectedItems.length > 0;
                const singleSelection = selectedItems.length === 1;

                return [
                    {
                        label: 'Open',
                        icon: 'folder-open',
                        action: 'openItem',
                        available: singleSelection && selectedItems[0].type === 'folder'
                    },
                    { type: 'separator' },
                    {
                        label: 'New Folder',
                        icon: 'folder-plus',
                        action: 'createFolder',
                        available: !isTrashView
                    },
                    {
                        label: 'Upload Files',
                        icon: 'upload',
                        action: 'uploadFiles',
                        available: !isTrashView
                    },
                    { type: 'separator' },
                    {
                        label: 'Cut',
                        icon: 'cut',
                        action: 'cutItems',
                        available: !isTrashView && hasSelection,
                        shortcut: 'Ctrl+X'
                    },
                    {
                        label: 'Copy',
                        icon: 'copy',
                        action: 'copyItems',
                        available: !isTrashView && hasSelection,
                        shortcut: 'Ctrl+C'
                    },
                    {
                        label: 'Paste',
                        icon: 'paste',
                        action: 'pasteItems',
                        available: !isTrashView && clipboard !== null,
                        shortcut: 'Ctrl+V'
                    },
                    { type: 'separator' },
                    {
                        label: 'Rename',
                        icon: 'i-cursor',
                        action: 'renameItem',
                        available: singleSelection,
                        shortcut: 'F2'
                    },
                    {
                        label: isTrashView ? 'Delete Permanently' : 'Delete',
                        icon: 'trash',
                        action: 'deleteItems',
                        available: hasSelection,
                        danger: true,
                        shortcut: 'Del'
                    },
                    {
                        label: 'Restore',
                        icon: 'trash-restore',
                        action: 'restoreItems',
                        available: isTrashView && hasSelection
                    },
                    { type: 'separator' },
                    {
                        label: 'Select All',
                        icon: 'check-square',
                        action: 'selectAll',
                        available: true,
                        shortcut: 'Ctrl+A'
                    },
                    {
                        label: 'Properties',
                        icon: 'info-circle',
                        action: 'showProperties',
                        available: singleSelection
                    }
                ].filter(item => item.available !== false);
            },

            executeMenuAction(action) {
                const actions = {
                    openItem: () => {
                        if (this.state.selectedItems.length === 1 && this.state.selectedItems[0].type === 'folder') {
                            const item = document.querySelector(`.gallery-item[data-id="${this.state.selectedItems[0].id}"]`);
                            this.navigateToFolder(item);
                        }
                    },
                    createFolder: () => this.createNewFolder(),
                    uploadFiles: () => this.showUploadDialog(),
                    cutItems: () => this.cutItems(),
                    copyItems: () => this.copyItems(),
                    pasteItems: () => this.pasteItems(),
                    renameItem: () => this.renameItem(),
                    deleteItems: () => this.deleteSelected(),
                    restoreItems: () => this.restoreSelected(),
                    selectAll: () => this.selectAllItems(),
                    showProperties: () => this.showProperties()
                };

                if (actions[action]) {
                    actions[action]();
                }
            },

            cutItems() {
                if (this.state.selectedItems.length === 0) return;

                this.state.clipboard = {
                    action: 'cut',
                    items: this.state.selectedItems
                };

                fetch('{{ route("gallery.cut") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: this.state.selectedItems
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.showSuccess(data.message);
                        } else {
                            throw new Error(data.message || 'Failed to cut items');
                        }
                    })
                    .catch(error => {
                        console.error('Cut error:', error);
                        this.showError(error.message);
                    });
            },

            copyItems() {
                if (this.state.selectedItems.length === 0) return;

                this.state.clipboard = {
                    action: 'copy',
                    items: this.state.selectedItems
                };

                fetch('{{ route("gallery.copy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: this.state.selectedItems
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.showSuccess(data.message);
                        } else {
                            throw new Error(data.message || 'Failed to copy items');
                        }
                    })
                    .catch(error => {
                        console.error('Copy error:', error);
                        this.showError(error.message);
                    });
            },

            pasteItems() {
                if (!this.state.clipboard || this.state.clipboard.items.length === 0) return;

                this.showProgress('Pasting Items');
                this.updateProgress(30, 'Processing...');

                fetch('{{ route("gallery.paste") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        items: this.state.clipboard.items,
                        target_path: this.state.currentPath
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.updateProgress(100, 'Completed');
                            setTimeout(() => {
                                this.hideProgress();
                                this.showSuccess(data.message);
                                if (this.state.clipboard.action === 'cut') {
                                    this.state.clipboard = null;
                                }
                                this.loadContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Paste failed');
                        }
                    })
                    .catch(error => {
                        this.hideProgress();
                        console.error('Paste error:', error);
                        this.showError(error.message);
                    });
            },

            renameItem() {
                if (this.state.selectedItems.length !== 1) return;

                const item = this.state.selectedItems[0];
                const currentName = prompt('Enter new name:');
                if (!currentName) return;

                this.showProgress('Renaming Item');
                this.updateProgress(30, 'Processing...');

                fetch(`{{ route('gallery.file.rename', ['id' => '__ID__']) }}`.replace('__ID__', item.id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        type: item.type,
                        id: item.id,
                        new_name: currentName
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.updateProgress(100, 'Completed');
                            setTimeout(() => {
                                this.hideProgress();
                                this.showSuccess(data.message);
                                this.loadContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Rename failed');
                        }
                    })
                    .catch(error => {
                        this.hideProgress();
                        console.error('Rename error:', error);
                        this.showError(error.message);
                    });
            },

            selectAllItems() {
                const items = this.elements.itemsContainer.querySelectorAll('.gallery-item');
                this.state.selectedItems = [];

                items.forEach(item => {
                    const id = item.dataset.id;
                    const type = item.dataset.type;

                    if (type === 'go-up') return;

                    this.state.selectedItems.push({ id, type });
                    item.classList.add('selected');
                    const checkbox = item.querySelector('.item-checkbox input');
                    if (checkbox) checkbox.checked = true;
                });

                this.updateSelectionDisplay();
            },

            showProperties() {
                if (this.state.selectedItems.length !== 1) return;

                const item = this.state.selectedItems[0];

                fetch(`/gallery/properties/${item.type}/${item.id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.showPropertiesModal(data.properties, item.type);
                        } else {
                            throw new Error(data.message || 'Failed to get properties');
                        }
                    })
                    .catch(error => {
                        console.error('Properties error:', error);
                        this.showError(error.message);
                    });
            },

            showPropertiesModal(properties, type) {
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
                    <button type="button" class="text-gray-400 hover:text-gray-500" aria-label="Close">
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
            },

            // Modal Management
            openModal(path = '', callback = null) {
                this.state.currentPath = path;
                this.state.isTrashView = false;
                this.state.selectedItems = [];
                this.state.callback = callback;
                this.elements.modal.classList.remove('hidden');
                this.loadContents();
            },

            closeModal() {
                this.elements.modal.classList.add('hidden');
                this.state.callback = null;
            },

            // Progress Management
            showProgress(title) {
                this.elements.progressTitle.textContent = title;
                this.elements.progressBar.style.width = '0%';
                this.elements.progressStatus.textContent = '0% Complete';
                this.elements.progressModal.classList.add('show');
            },

            updateProgress(percent, status) {
                this.elements.progressBar.style.width = `${percent}%`;
                this.elements.progressStatus.textContent = status || `${percent}% Complete`;
            },

            hideProgress() {
                this.elements.progressModal.classList.remove('show');
            },

            // Utility Functions
            showLoading() {
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = '<i class="fas fa-spinner loading-spinner"></i>';
                this.elements.itemsContainer.appendChild(loadingOverlay);
            },

            hideLoading() {
                const loadingOverlay = this.elements.itemsContainer.querySelector('.loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.remove();
                }
            },

            getFileIcon(mimeType) {
                if (!mimeType) return 'file';

                if (mimeType.startsWith('image/')) return 'file-image';
                if (mimeType.startsWith('video/')) return 'file-video';
                if (mimeType.startsWith('audio/')) return 'file-audio';
                if (mimeType.includes('pdf')) return 'file-pdf';
                if (mimeType.includes('zip') || mimeType.includes('compressed')) return 'file-archive';
                if (mimeType.includes('word')) return 'file-word';
                if (mimeType.includes('excel')) return 'file-excel';
                if (mimeType.includes('powerpoint')) return 'file-powerpoint';
                return 'file';
            },

            formatFileSize(bytes) {
                if (!bytes) return '0 B';

                const units = ['B', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return `${(bytes / Math.pow(1024, i)).toFixed(1)} ${units[i]}`;
            },

            formatDate(dateString) {
                if (!dateString) return '';

                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = String(date.getFullYear()).slice(-2);

                return `${day}-${month}-${year}`;
            },

            copyToClipboard(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            },

            handleKeyboardShortcuts(e) {
                if (!this.elements.modal.classList.contains('hidden')) {
                    if (e.ctrlKey || e.metaKey) {
                        switch (e.key.toLowerCase()) {
                            case 'a':
                                e.preventDefault();
                                this.selectAllItems();
                                break;
                            case 'c':
                                e.preventDefault();
                                this.copyItems();
                                break;
                            case 'x':
                                e.preventDefault();
                                this.cutItems();
                                break;
                            case 'v':
                                e.preventDefault();
                                this.pasteItems();
                                break;
                        }
                    }

                    switch (e.key) {
                        case 'F2':
                            e.preventDefault();
                            this.renameItem();
                            break;
                        case 'Delete':
                            e.preventDefault();
                            this.deleteSelected();
                            break;
                        case 'Escape':
                            e.preventDefault();
                            this.closeContextMenu();
                            break;
                    }
                }
            },

            showSuccess(message) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
                toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${message}`;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            },

            showError(message) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
                toast.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> ${message}`;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }
        };

        // Initialize the gallery
        gallery.init();

        // Expose the open function to window
        window.openGalleryModal = function(path = '', callback = null) {
            gallery.openModal(path, callback);
        };
    });
</script>
