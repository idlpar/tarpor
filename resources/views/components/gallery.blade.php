<style>
    /* Context Menu */
    .context-menu {
        position: fixed;
        z-index: 99999 !important;
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        padding: 0.5rem 0;
        opacity: 1 !important; /* Force visibility */
        display: block !important; /* Force display */
    }

    .context-menu-item {
        width: 100%;
        padding: 0.75rem 1rem;
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
    .context-menu-item[disabled] {
        opacity: 0.5;
        pointer-events: none;
    }

    .context-menu-item:not([disabled]):hover {
        background-color: #f1f5f9;
    }

    .context-menu-item:hover {
        background-color: #f8fafc;
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
        background: #FFFFFF;
        border: 2px solid #FFFFFF;
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #be123c;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .gallery-close:hover {
        background: #be123c;
        color: white;
        border-color: #be123c;
        transform: scale(1.05);
    }

    /* Trash view specific styles */
    /* Remove the empty trash button from the items grid */
    .trash-header {
        display: none; /* We'll now use the toolbar button */
    }

    /* Style the empty trash button in toolbar */
    #emptyTrashBtn {
        margin-left: 0.5rem;
        transition: all 0.2s;
    }

    #emptyTrashBtn:hover {
        transform: scale(1.05);
    }

    .trash-view .breadcrumb-container {
        justify-content: flex-end;
    }

    .empty-trash-btn {
        background-color: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .empty-trash-btn:hover {
        background-color: #dc2626;
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
        height: 40px;
        transition: all 0.2s;
        cursor: pointer;
        border: 1px solid transparent;
        overflow: hidden;
        text-overflow: ellipsis;
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
        align-items: center;
        padding: 0.75rem 1.25rem;
        background-color: white;
        border-bottom: 1px solid #e2e8f0;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    .breadcrumb-container.show
    {
        display: flex;
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
        cursor: pointer;
        height: 160px;
        display: flex;
        flex-direction: column;
    }

    /* For cut items */
    .gallery-item.cut {
        opacity: 0.6;
        position: relative;
    }

    .gallery-item.cut::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            45deg,
            rgba(0,0,0,0.1),
            rgba(0,0,0,0.1) 10px,
            transparent 10px,
            transparent 20px
        );
    }

    .gallery-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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

    .item-count-badge {
        position: absolute;
        top: 4px;
        left: 4px;
        font-size: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 2px 4px;
        border-radius: 4px;
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
        top: 4px;
        right: 4px;
        z-index: 10;
    }


    .item-date {
        position: absolute;
        bottom: 4px;
        left: 4px;
        font-size: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 2px 4px;
        border-radius: 4px;
    }

    .item-size {
        position: absolute;
        bottom: 4px;
        right: 4px;
        font-size: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 2px 4px;
        border-radius: 4px;
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
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .preview-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 4px;
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

    .action-button.secondary {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .action-button.secondary:hover {
        background-color: #cbd5e1;
    }

    /* Footer */
    .gallery-footer {
        height: 60px;
        padding: 1rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        background-color: white;
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
        z-index: 1000;
    }

    .loading-spinner {
        animation: spin 1s linear infinite;
        color: #4f46e5;
        font-size: 3rem;
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
                <button type="button" id="toggleTrashView" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded hover:bg-purple-700">
                    <i class="fas fa-trash-restore"></i>
                    <span id="trashButtonText">Trash</span>
                </button>
                <button type="button" id="emptyTrashBtn" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-700 rounded hover:bg-red-800 hidden">
                    <i class="fas fa-broom"></i>
                    <span>Empty Trash</span>
                </button>
                <button type="button" id="restoreImage" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700 hidden">
                    <i class="fas fa-trash-restore"></i>
                    <span>Restore</span>
                </button>
                <button type="button" id="setAsFeatured" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600 hidden">
                    <i class="fas fa-star"></i>
                    <span>Featured</span>
                </button>
            </div>

            <div class="relative w-full sm:w-auto sm:flex-1 max-w-sm">
                <input type="text" id="searchInput" class="search-container w-full px-4 py-2 pr-10 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search...">
                <i class="fas fa-search absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div id="breadcrumbContainer" class="breadcrumb-container show">
            <!-- Dynamic breadcrumbs will be inserted here -->
        </div>

        <!-- Main Content -->
        <div class="gallery-content relative">
            <!-- Items Grid -->
            <div class="items-grid" id="galleryImages">
                <!-- Dynamic content will be inserted here -->
            </div>

            <!-- Preview Panel -->
            <div class="preview-panel p-4 bg-white border-l border-gray-200 overflow-y-auto">
                <div class="preview-header text-xl font-semibold mb-4">Preview</div>
                <div class="preview-content" id="previewContent">
                    <div class="preview-empty flex flex-col items-center justify-center h-64 text-gray-400">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <p>No item selected</p>
                    </div>
                </div>

                <div id="previewDetails" class="preview-details hidden mt-6">
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
                    <!-- Details will be populated here -->
                </div>

                <div id="imageActions" class="action-buttons hidden mt-6">
                    <button class="action-button primary" id="insertSingleButton">
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
                    <!-- Action buttons -->
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
        // ======================
        // 1. CORE INITIALIZATION
        // ======================
        if (!document.getElementById('galleryModal')) {
            console.error('Gallery modal container not found');
            return;
        }

        const gallery = {
            // DOM Elements
            elements: {
                modal: document.getElementById('galleryModal'),
                closeBtn: document.getElementById('closeGalleryModal'),
                imagesContainer: document.getElementById('galleryImages'),
                breadcrumbs: document.getElementById('breadcrumbContainer'),
                imageActions: document.getElementById('imageActions'),
                deleteButton: document.getElementById('deleteSelected'),
                restoreButton: document.getElementById('restoreImage'),
                featuredButton: document.getElementById('setAsFeatured'),
                insertButton: document.getElementById('insertButton'),
                uploadButton: document.getElementById('uploadButton'),
                newFolderButton: document.getElementById('newFolder'),
                refreshButton: document.getElementById('refreshFolders'),
                trashButton: document.getElementById('toggleTrashView'),
                emptyTrashBtn: document.getElementById('emptyTrashBtn'),
                searchInput: document.getElementById('searchInput'),
                progressModal: document.getElementById('progressModal'),
                progressTitle: document.getElementById('progressTitle'),
                progressBar: document.getElementById('progressBar'),
                progressStatus: document.getElementById('progressStatus'),

                previewPanel: document.querySelector('.preview-panel'),
                previewContent: document.getElementById('previewContent'),
                previewImage: document.createElement('img'), // Will be added dynamically
                previewEmpty: document.querySelector('.preview-empty'),
                previewDetails: document.getElementById('previewDetails'),
                detailName: document.getElementById('detailName'),
                detailType: document.getElementById('detailType'),
                detailSize: document.getElementById('detailSize'),
                detailDimensions: document.getElementById('detailDimensions'),
                detailUploaded: document.getElementById('detailUploaded')

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
                // Verify all required elements exist
                for (const [key, element] of Object.entries(this.elements)) {
                    if (!element && key !== 'previewImage') { // previewImage is created dynamically
                        console.error(`Missing gallery element: ${key}`);
                        return false;
                    }
                }

                this.setupEventListeners();
                this.setupAccessibility();
                return true;
            },

            // ======================
            // 2. EVENT HANDLING
            // ======================
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

                this.elements.trashButton.addEventListener('click', (e) => {
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

                this.elements.featuredButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.setAsFeatured();
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

                // Context menu with proper event prevention
                this.elements.imagesContainer.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.handleContextMenu(e);
                });

                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.context-menu')) {
                        this.closeContextMenu();
                    }
                }, true); // Use capture phase

                document.addEventListener('keydown', (e) => {
                    this.handleKeyboardShortcuts(e);
                });

                // Breadcrumb navigation with event prevention
                this.elements.breadcrumbs.addEventListener('click', (e) => {
                    const breadcrumb = e.target.closest('.breadcrumb-button');
                    if (breadcrumb) {
                        e.preventDefault();
                        const path = breadcrumb.dataset.path;
                        this.navigateToPath(path);
                    }
                });

                // Item selection with event delegation
                this.elements.imagesContainer.addEventListener('click', (e) => {
                    const item = e.target.closest('.gallery-item');
                    if (item) {
                        e.preventDefault();
                        this.handleItemClick(item, e);
                    }

                    const checkbox = e.target.closest('.item-checkbox input');
                    if (checkbox) {
                        e.preventDefault();
                        e.stopPropagation();
                        const item = checkbox.closest('.gallery-item');
                        const id = item.dataset.id;
                        const type = item.dataset.type;
                        this.toggleItemSelection(id, type, item);
                    }
                });
            },

            setupAccessibility() {
                // Add ARIA attributes
                this.elements.modal.setAttribute('aria-modal', 'true');
                this.elements.modal.setAttribute('role', 'dialog');
                this.elements.modal.setAttribute('aria-labelledby', 'galleryTitle');

                // Set tabindex for better keyboard navigation
                this.elements.imagesContainer.setAttribute('tabindex', '0');

                // Add keyboard navigation for items
                this.elements.imagesContainer.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowRight' || e.key === 'ArrowLeft' ||
                        e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.handleKeyboardNavigation(e);
                    }
                });
            },

            // ======================
            // 3. NAVIGATION & DISPLAY
            // ======================
            navigateToPath(path) {
                this.state.currentPath = path;
                this.loadContents();
            },

            loadTrashContents(parentId = null) {
                this.showLoading();
                this.elements.breadcrumbs.classList.add('hidden');

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
                            this.state.currentTrashParent = data.parent_id;
                            this.renderContents(data.contents);
                            this.updateContextMenu(data.contextMenu);
                        } else {
                            throw new Error(data.message || 'Failed to load trash contents');
                        }
                    })
                    .catch(error => this.showError(error.message))
                    .finally(() => this.hideLoading());
            },


            loadContents() {
                this.showLoading();

                const url = new URL('{{ route("gallery.index") }}');
                url.searchParams.append('path', this.state.currentPath);

                // Add trash parameter if in trash view
                if (this.state.isTrashView) {
                    url.searchParams.append('trash', 'true');
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
                    this.elements.breadcrumbs.appendChild(button);
                });
            },

            renderContents(contents) {
                this.elements.imagesContainer.innerHTML = '';

                if (this.state.isTrashView) {
                    this.renderTrashView(contents);
                } else {
                    this.renderNormalView(contents);
                }

                this.updateSelectionDisplay();
            },

            renderTrashView(contents) {
                if ((!contents.folders || contents.folders.length === 0) &&
                    (!contents.files || contents.files.length === 0)) {
                    this.showEmptyState('Trash is empty', 'fa-trash-alt');
                    return;
                }

                // Add empty trash button at the top
                const emptyTrashBtn = document.createElement('button');
                emptyTrashBtn.className = 'empty-trash-btn';
                emptyTrashBtn.innerHTML = '<i class="fas fa-trash"></i> Empty Trash';
                emptyTrashBtn.addEventListener('click', () => this.emptyTrash());

                const header = document.createElement('div');
                header.className = 'trash-header';
                header.appendChild(emptyTrashBtn);
                this.elements.imagesContainer.appendChild(header);

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
                this.elements.imagesContainer.appendChild(emptyState);
            },

            createGoUpItem() {
                const goUpItem = document.createElement('div');
                goUpItem.className = 'gallery-item';
                goUpItem.dataset.id = 'go-up';
                goUpItem.dataset.type = 'folder';
                goUpItem.innerHTML = `
                <div class="item-thumbnail">
                    <div class="bg-teal-50">
                        <i class="fas fa-level-up-alt folder-icon"></i>
                    </div>
                    <div class="absolute bottom-1 left-1 text-xs text-gray-600 bg-white/70 backdrop-blur px-2 py-0.5 rounded">
                        Parent
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

                this.elements.imagesContainer.appendChild(goUpItem);
            },

            createFolderItem(folder, isTrash) {
                const folderItem = document.createElement('div');
                folderItem.className = `gallery-item ${this.isSelected(folder.id, 'folder') ? 'selected' : ''}`;
                folderItem.dataset.id = folder.id;
                folderItem.dataset.type = 'folder';
                folderItem.dataset.path = folder.path;
                if (folder.parent_id) folderItem.dataset.parent_id = folder.parent_id;

                folderItem.innerHTML = `
                <div class="item-thumbnail bg-teal-100">
                    <div class="h-full flex items-center justify-center">
                        <i class="fas fa-folder folder-icon text-4xl text-yellow-400"></i>
                    </div>
                    <div class="item-count-badge"> <span class="text-amber-500">${folder.folder_count}</span> | ${folder.file_count}</div>
                    <div class="item-checkbox">
                        <input type="checkbox" ${this.isSelected(folder.id, 'folder') ? 'checked' : ''}>
                    </div>
                    <div class="item-date">${folder.created_at}</div>

                </div>
                <div class="item-info p-2 truncate">
                    <div class="item-name text-sm truncate">${folder.name}</div>
                </div>
            `;

                if (isTrash) {
                    folderItem.classList.add('deleted-item');
                }

                folderItem.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.navigateToFolder(folderItem);
                });

                this.elements.imagesContainer.appendChild(folderItem);
            },

            navigateToParentFolder(parentId) {
                this.loadTrashContents(parentId);
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
                    ${file.is_featured ? '<i class="fas fa-star item-featured text-yellow-400"></i>' : ''}
                    <div class="item-checkbox">
                        <input type="checkbox" ${this.isSelected(file.id, 'file') ? 'checked' : ''}>
                    </div>
                    <div class="item-date">${file.created_at}</div>
                    <div class="item-size">${file.size}</div>
                </div>
                <div class="item-info p-2 truncate">
                    <div class="item-name text-sm truncate">${file.name}</div>
                </div>
            `;

                if (isTrash) {
                    fileItem.classList.add('deleted-item');
                }

                fileItem.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleItemSelection(file.id, 'file', fileItem);
                    this.updatePreview(file.id);
                });

                this.elements.imagesContainer.appendChild(fileItem);
            },

            updateContextMenu(menuOptions) {
                // Store the current context menu options
                this.state.contextMenuOptions = menuOptions;
            },

            // ======================
            // 4. CONTEXT MENU SYSTEM
            // ======================
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

                // Get contextually appropriate menu items
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
                    { type: 'separator', available: !isTrashView },
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
                    createFolder: () => this.createNewFolder(),
                    uploadFiles: () => this.showUploadDialog(),
                    cutItems: () => this.cutItems(),
                    copyItems: () => this.copyItems(),
                    pasteItems: () => this.pasteItems(),
                    renameItem: () => this.renameItem(),
                    deleteItems: () => this.deleteSelected(),
                    restoreItems: () => this.restoreSelected(),
                    emptyTrash: () => this.emptyTrash(),
                    selectAll: () => this.selectAllItems(),
                    showProperties: () => this.showProperties()
                };

                if (actions[action]) {
                    actions[action]();
                }
            },

            // ======================
            // 5. ITEM INTERACTIONS
            // ======================
            handleItemClick(item, event) {
                const id = item.dataset.id;
                const type = item.dataset.type;

                if (type === 'folder') {
                    if (this.state.isTrashView) {
                        this.toggleItemSelection(id, type, item);
                    } else {
                        this.navigateToFolder(item);
                    }
                } else {
                    this.toggleItemSelection(id, type, item);
                    this.updatePreview(id, type);
                }
            },

            navigateToFolder(folderElement) {
                if (this.state.isTrashView) {
                    // For trash view, we navigate by parent_id
                    const folderId = folderElement.dataset.id;
                    this.loadTrashContents(folderId);
                } else {
                    // Regular folder navigation
                    this.state.currentPath = folderElement.dataset.path;
                    this.state.currentFolderId = folderElement.dataset.id;
                    this.loadContents();
                }
            },

            toggleItemSelection(id, type, element) {
                const index = this.state.selectedItems.findIndex(item =>
                    item.id === id && item.type === type
                );

                if (index === -1) {
                    this.state.selectedItems.push({ id, type });
                    element.classList.add('selected');
                    element.querySelector('.item-checkbox input').checked = true;
                } else {
                    this.state.selectedItems.splice(index, 1);
                    element.classList.remove('selected');
                    element.querySelector('.item-checkbox input').checked = false;
                }

                this.updateSelectionDisplay();
            },

            isSelected(id, type) {
                return this.state.selectedItems.some(item =>
                    item.id === id && item.type === type
                );
            },

            clearSelections() {
                this.elements.imagesContainer.querySelectorAll('.gallery-item.selected').forEach(el => {
                    el.classList.remove('selected');
                    const checkbox = el.querySelector('.item-checkbox input');
                    if (checkbox) checkbox.checked = false;
                });
                this.state.selectedItems = [];
                this.updateSelectionDisplay();
            },

            updateSelectionDisplay() {
                const hasSelection = this.state.selectedItems.length > 0;
                const singleSelection = this.state.selectedItems.length === 1;
                const isTrashView = this.state.isTrashView;

                // Update action buttons visibility
                this.elements.deleteButton.classList.toggle('hidden', !hasSelection);
                this.elements.restoreButton.classList.toggle('hidden', !isTrashView || !hasSelection);
                this.elements.featuredButton.classList.toggle('hidden',
                    isTrashView || !hasSelection ||
                    this.state.selectedItems.some(item => item.type !== 'file')
                );

                // Update button text based on context
                if (hasSelection) {
                    this.elements.deleteButton.innerHTML = `
                    <i class="fas fa-trash mr-2"></i>
                    ${isTrashView ? 'Delete Permanently' : 'Move to Trash'}
                `;
                }

                // Update preview if single file is selected
                if (singleSelection && this.state.selectedItems[0].type === 'file') {
                    this.fetchFileDetails(this.state.selectedItems[0].id);
                } else if (!hasSelection) {
                    this.clearPreview();
                }
            },

            updatePreview(id, type) {
                this.clearPreview();
                this.showLoadingPreview();

                // Determine the correct endpoint based on type
                const endpoint = type === 'folder'
                    ? `{{ route("gallery.folder.show", '') }}/${id}`
                    : `{{ route("gallery.file.show", '') }}/${id}`;

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
                                this.showFilePreview(data.file ?? data.data); // fallback to `data.data` if needed
                            } else {
                                this.showFolderPreview(data.folder ?? data.data);
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

            showFolderPreview(folder) {
                if (!folder) {
                    this.clearPreview();
                    return;
                }

                // Clear previous content
                this.elements.previewContent.innerHTML = '';

                // Create preview container
                const previewContainer = document.createElement('div');
                previewContainer.className = 'preview-image-container flex items-center justify-center bg-gray-100 rounded-lg p-4';

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
                this.elements.imageActions.classList.remove('hidden');
                this.elements.previewEmpty.classList.add('hidden');
            },

            showLoadingPreview() {
                this.clearPreview();

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
                this.elements.previewContent.innerHTML = '';
                this.elements.previewEmpty.classList.remove('hidden');
                this.elements.previewDetails.classList.add('hidden');
                this.elements.imageActions.classList.add('hidden');

                // Clear details
                this.elements.detailName.textContent = '-';
                this.elements.detailType.textContent = '-';
                this.elements.detailSize.textContent = '-';
                this.elements.detailDimensions.textContent = '-';
                this.elements.detailUploaded.textContent = '-';
            },

            fetchFileDetails(fileId) {
                // Clear preview first to avoid showing stale data
                this.clearPreview();

                fetch(`{{ route("gallery.file.show", '') }}/${fileId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('File not found');
                        }
                        return response.json();
                    })
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

            showFilePreview(file) {
                if (!file) {
                    this.clearPreview();
                    return;
                }

                // Clear previous content
                this.elements.previewContent.innerHTML = '';

                // Create preview container
                const previewContainer = document.createElement('div');
                previewContainer.className = 'preview-image-container flex items-center justify-center bg-gray-100 rounded-lg p-4';

                if (file.mime_type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = file.url;
                    img.alt = file.name;
                    img.className = 'preview-image max-w-full max-h-64 object-contain';
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
                this.elements.imageActions.classList.remove('hidden');
                this.elements.previewEmpty.classList.add('hidden');
            },



            // ======================
            // 6. GALLERY OPERATIONS
            // ======================
            toggleTrashView() {
                this.state.isTrashView = !this.state.isTrashView;
                this.state.currentPath = '';
                this.state.selectedItems = [];

                // Show/hide breadcrumbs based on view
                if (this.state.isTrashView) {
                    this.elements.breadcrumbs.classList.remove('show');
                } else {
                    this.elements.breadcrumbs.classList.add('show');
                }

                // Update trash button text and show/hide empty trash button
                const trashButtonText = document.getElementById('trashButtonText');
                const emptyTrashBtn = document.getElementById('emptyTrashBtn');

                if (trashButtonText) {
                    trashButtonText.textContent = this.state.isTrashView ? 'Back to Gallery' : 'Trash';
                }

                if (emptyTrashBtn) {
                    emptyTrashBtn.classList.toggle('hidden', !this.state.isTrashView);
                }

                // Load the appropriate content
                this.refreshContents();
            },

            refreshContents() {
                if (this.state.isTrashView) {
                    this.loadTrashContents();
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
                        parent_id: this.state.currentFolderId ? parseInt(this.state.currentFolderId) : null
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

            deleteSelected() {
                if (this.state.selectedItems.length === 0) return;

                const permanent = this.state.isTrashView;
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
                                this.refreshContents(); // Refresh the current view
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
                                this.refreshContents(); // Refresh the current view
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

            setAsFeatured() {
                if (this.state.selectedItems.length !== 1 || this.state.selectedItems[0].type !== 'file') return;

                this.showProgress('Setting as Featured');
                this.updateProgress(30, 'Processing...');

                fetch(`{{ route("gallery.set-featured", '') }}/${this.state.selectedItems[0].id}`, {
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
                                this.loadContents();
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Failed to set featured');
                        }
                    })
                    .catch(error => {
                        this.hideProgress();
                        console.error('Featured error:', error);
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
                    fetch(`/gallery/file/${item.id}/for-insertion`, {
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
                            this.elements.searchInput.focus();
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
                                this.clearSelections(); // Clear any selections
                                this.loadTrashContents(); // Reload trash contents (which should now be empty)
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


            selectAllItems() {
                const items = this.elements.imagesContainer.querySelectorAll('.gallery-item');
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

            // ======================
            // 7. MODAL MANAGEMENT
            // ======================
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

            // ======================
            // 8. PROGRESS MANAGEMENT
            // ======================
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

            // ======================
            // 9. UTILITY FUNCTIONS
            // ======================
            // ======================
            showLoading() {
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className = 'loading-overlay';
                loadingOverlay.innerHTML = '<i class="fas fa-spinner loading-spinner"></i>';
                this.elements.imagesContainer.appendChild(loadingOverlay);
            },

            hideLoading() {
                const loadingOverlay = this.elements.imagesContainer.querySelector('.loading-overlay');
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

            handleKeyboardNavigation(e) {
                // Implement keyboard navigation for items
                const items = Array.from(this.elements.imagesContainer.querySelectorAll('.gallery-item'));
                const currentIndex = items.findIndex(item => item === document.activeElement);
                let newIndex = currentIndex;

                switch (e.key) {
                    case 'ArrowRight':
                        newIndex = (currentIndex + 1) % items.length;
                        break;
                    case 'ArrowLeft':
                        newIndex = (currentIndex - 1 + items.length) % items.length;
                        break;
                    case 'ArrowDown':
                        newIndex = Math.min(currentIndex + 5, items.length - 1);
                        break;
                    case 'ArrowUp':
                        newIndex = Math.max(currentIndex - 5, 0);
                        break;
                }

                if (newIndex !== -1) {
                    items[newIndex].focus();
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
