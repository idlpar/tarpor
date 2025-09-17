{{--@extends('layouts.admin')--}}

{{--@section('title', 'Media Gallery')--}}

{{--@section('admin_content')--}}
{{--<div class="container mx-auto px-4 py-8">--}}
{{--    <h1 class="text-3xl font-bold text-gray-900 mb-6">Media Gallery</h1>--}}

{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}

{{--    <style>--}}
{{--        /* Gallery Container */--}}
{{--        .gallery-container {--}}
{{--            display: flex;--}}
{{--            flex-direction: column;--}}
{{--            height: 80vh;--}}
{{--            max-height: 800px;--}}
{{--            background-color: #f8fafc;--}}
{{--            border-radius: 0.5rem;--}}
{{--            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);--}}
{{--            overflow: hidden;--}}
{{--        }--}}

{{--        /* Header */--}}
{{--        .gallery-header {--}}
{{--            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);--}}
{{--            color: white;--}}
{{--            padding: 0.75rem 1rem;--}}
{{--            display: flex;--}}
{{--            justify-content: space-between;--}}
{{--            align-items: center;--}}
{{--            border-bottom: 1px solid rgba(255, 255, 255, 0.1);--}}
{{--        }--}}

{{--        .gallery-title {--}}
{{--            font-size: 1.25rem;--}}
{{--            font-weight: 600;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 0.75rem;--}}
{{--        }--}}

{{--        /* Toolbar */--}}
{{--        .gallery-toolbar {--}}
{{--            display: flex;--}}
{{--            justify-content: space-between;--}}
{{--            padding: 0.5rem 1rem;--}}
{{--            background-color: white;--}}
{{--            border-bottom: 1px solid #e2e8f0;--}}
{{--        }--}}

{{--        .lazy-load-container {--}}
{{--            position: relative;--}}
{{--            width: 100%;--}}
{{--            height: 100%;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            background-color: #f8fafc;--}}
{{--        }--}}

{{--        .lazy-load-container img {--}}
{{--            transition: opacity 0.3s ease;--}}
{{--            opacity: 0;--}}
{{--        }--}}

{{--        .lazy-load-container img.loaded {--}}
{{--            opacity: 1;--}}
{{--        }--}}

{{--        .toolbar-group {--}}
{{--            display: flex;--}}
{{--            gap: 0.5rem;--}}
{{--        }--}}

{{--        .toolbar-button {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 0.5rem;--}}
{{--            padding: 0.5rem 1rem;--}}
{{--            border-radius: 0.375rem;--}}
{{--            font-size: 0.875rem;--}}
{{--            font-weight: 500;--}}
{{--            cursor: pointer;--}}
{{--            border: 1px solid transparent;--}}
{{--            transition: all 0.2s;--}}
{{--        }--}}

{{--        .toolbar-button:hover {--}}
{{--            background-color: #f1f5f9;--}}
{{--            transform: translateY(-2px);--}}
{{--            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);--}}
{{--        }--}}

{{--        .toolbar-button.primary {--}}
{{--            background-color: #4f46e5;--}}
{{--            color: white;--}}
{{--        }--}}

{{--        .toolbar-button.primary:hover {--}}
{{--            background-color: #4338ca;--}}
{{--        }--}}

{{--        .toolbar-button.secondary {--}}
{{--            background-color: white;--}}
{{--            border-color: #e2e8f0;--}}
{{--            color: #4f46e5;--}}
{{--        }--}}

{{--        .toolbar-button.secondary:hover {--}}
{{--            background-color: #f8fafc;--}}
{{--        }--}}

{{--        .toolbar-button.danger {--}}
{{--            background-color: #ef4444;--}}
{{--            color: white;--}}
{{--        }--}}

{{--        .toolbar-button.danger:hover {--}}
{{--            background-color: #dc2626;--}}
{{--        }--}}

{{--        .search-container {--}}
{{--            position: relative;--}}
{{--            width: 250px;--}}
{{--        }--}}

{{--        .search-input {--}}
{{--            width: 100%;--}}
{{--            padding: 0.5rem 2rem 0.5rem 1rem;--}}
{{--            border-radius: 0.375rem;--}}
{{--            border: 1px solid #e2e8f0;--}}
{{--            font-size: 0.875rem;--}}
{{--            transition: all 0.2s;--}}
{{--        }--}}

{{--        .search-input:focus {--}}
{{--            outline: none;--}}
{{--            border-color: #a5b4fc;--}}
{{--            box-shadow: 0 0 0 3px rgba(199, 210, 254, 0.5);--}}
{{--        }--}}

{{--        .search-icon {--}}
{{--            position: absolute;--}}
{{--            right: 0.75rem;--}}
{{--            top: 50%;--}}
{{--            transform: translateY(-50%);--}}
{{--            color: #94a3b8;--}}
{{--        }--}}

{{--        /* Breadcrumbs */--}}
{{--        .breadcrumb-and-pagination {--}}
{{--            display: flex;--}}
{{--            justify-content: space-between;--}}
{{--            align-items: center;--}}
{{--            padding: 0.5rem 1rem;--}}
{{--            background-color: white;--}}
{{--            border-bottom: 1px solid #e2e8f0;--}}
{{--        }--}}

{{--        .breadcrumb-container {--}}
{{--            flex: 1;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            overflow-x: auto;--}}
{{--            padding: 0;--}}
{{--        }--}}

{{--        .gallery-notification {--}}
{{--            padding: 0.75rem 1rem;--}}
{{--            margin: 0 1rem;--}}
{{--            border-radius: 0.375rem;--}}
{{--            font-size: 0.875rem;--}}
{{--            font-weight: 500;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            animation: slideIn 0.3s ease-out;--}}
{{--            position: relative;--}}
{{--            z-index: 10;--}}
{{--        }--}}

{{--        .gallery-notification.success {--}}
{{--            background-color: #ecfdf5;--}}
{{--            color: #059669;--}}
{{--            border: 1px solid #a7f3d0;--}}
{{--        }--}}

{{--        .gallery-notification.error {--}}
{{--            background-color: #fee2e2;--}}
{{--            color: #dc2626;--}}
{{--            border: 1px solid #f87171;--}}
{{--        }--}}

{{--        .gallery-notification.fade-out {--}}
{{--            animation: fadeOut 0.3s ease-in;--}}
{{--        }--}}

{{--        @keyframes slideIn {--}}
{{--            from { transform: translateY(-20px); opacity: 0; }--}}
{{--            to { transform: translateY(0); opacity: 1; }--}}
{{--        }--}}

{{--        @keyframes fadeOut {--}}
{{--            from { opacity: 1; }--}}
{{--            to { opacity: 0; }--}}
{{--        }--}}

{{--        .breadcrumb-item {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--        }--}}

{{--        .breadcrumb-button {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 0.25rem;--}}
{{--            padding: 0.25rem 0.5rem;--}}
{{--            border-radius: 0.25rem;--}}
{{--            font-size: 0.875rem;--}}
{{--            transition: all 0.2s;--}}
{{--            background: none;--}}
{{--            border: none;--}}
{{--            cursor: pointer;--}}
{{--        }--}}

{{--        .breadcrumb-button:hover {--}}
{{--            background-color: #f1f5f9;--}}
{{--        }--}}

{{--        .breadcrumb-separator {--}}
{{--            margin: 0 0.5rem;--}}
{{--            color: #cbd5e1;--}}
{{--        }--}}

{{--        /* Main Content */--}}
{{--        .gallery-content {--}}
{{--            display: grid;--}}
{{--            grid-template-columns: 75% 25%;--}}
{{--            height: 100%;--}}
{{--            overflow: hidden;--}}
{{--        }--}}

{{--        /* Items Grid */--}}
{{--        .items-grid {--}}
{{--            padding: 1rem;--}}
{{--            overflow-y: auto;--}}
{{--            display: grid;--}}
{{--            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));--}}
{{--            gap: 1rem;--}}
{{--            align-content: start;--}}
{{--        }--}}

{{--        .items-grid.drop-active {--}}
{{--            border: 2px dashed #4f46e5;--}}
{{--            background-color: rgba(79, 70, 229, 0.1);--}}
{{--        }--}}

{{--        .empty-state {--}}
{{--            grid-column: 1 / -1;--}}
{{--            display: flex;--}}
{{--            flex-direction: column;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            padding: 2rem;--}}
{{--            color: #64748b;--}}
{{--        }--}}

{{--        /* Gallery Item */--}}
{{--        .gallery-item {--}}
{{--            position: relative;--}}
{{--            border-radius: 0.5rem;--}}
{{--            overflow: hidden;--}}
{{--            transition: all 0.2s;--}}
{{--            border: 1px solid #e2e8f0;--}}
{{--            background-color: white;--}}
{{--            cursor: pointer;--}}
{{--            height: 160px;--}}
{{--            display: flex;--}}
{{--            flex-direction: column;--}}
{{--        }--}}

{{--        .gallery-item.selected {--}}
{{--            border: 2px solid #4f46e5;--}}
{{--        }--}}

{{--        .item-thumbnail {--}}
{{--            flex-grow: 1;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            background-color: #f8fafc;--}}
{{--            position: relative;--}}
{{--        }--}}

{{--        .item-thumbnail img {--}}
{{--            max-width: 100%;--}}
{{--            max-height: 100%;--}}
{{--            object-fit: contain;--}}
{{--        }--}}

{{--        .folder-icon {--}}
{{--            font-size: 2.5rem;--}}
{{--            color: #94a3b8;--}}
{{--        }--}}

{{--        .item-checkbox {--}}
{{--            position: absolute;--}}
{{--            top: 0.25rem;--}}
{{--            right: 0.25rem;--}}
{{--            z-index: 10;--}}
{{--        }--}}

{{--        .item-checkbox input {--}}
{{--            cursor: pointer;--}}
{{--        }--}}

{{--        .item-info {--}}
{{--            padding: 0.5rem;--}}
{{--        }--}}

{{--        .item-name {--}}
{{--            font-size: 0.875rem;--}}
{{--            font-weight: 500;--}}
{{--            color: #1e293b;--}}
{{--            white-space: nowrap;--}}
{{--            overflow: hidden;--}}
{{--            text-overflow: ellipsis;--}}
{{--        }--}}

{{--        .item-meta {--}}
{{--            font-size: 0.75rem;--}}
{{--            color: #64748b;--}}
{{--            margin-top: 0.25rem;--}}
{{--        }--}}

{{--        /* Preview Panel */--}}
{{--        .preview-panel {--}}
{{--            position: relative;--}}
{{--            overflow-y: auto;--}}
{{--            padding: 1rem;--}}
{{--            background: white;--}}
{{--            border-left: 1px solid #e2e8f0;--}}
{{--        }--}}

{{--        .preview-image-container {--}}
{{--            max-height: 300px;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            background: #f8fafc;--}}
{{--            border-radius: 0.5rem;--}}
{{--            padding: 1rem;--}}
{{--            margin-bottom: 1rem;--}}
{{--            overflow: hidden;--}}
{{--        }--}}

{{--        .preview-image {--}}
{{--            max-width: 100%;--}}
{{--            max-height: 100%;--}}
{{--            object-fit: contain;--}}
{{--            border-radius: 0.25rem;--}}
{{--        }--}}

{{--        .preview-header {--}}
{{--            font-size: 1.125rem;--}}
{{--            font-weight: 600;--}}
{{--            margin-bottom: 1rem;--}}
{{--            color: #1e293b;--}}
{{--        }--}}

{{--        .preview-empty {--}}
{{--            display: flex;--}}
{{--            flex-direction: column;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            height: 200px;--}}
{{--            color: #94a3b8;--}}
{{--        }--}}

{{--        .preview-details {--}}
{{--            margin-top: 1.5rem;--}}
{{--        }--}}

{{--        .detail-row {--}}
{{--            display: flex;--}}
{{--            justify-content: space-between;--}}
{{--            padding: 0.5rem 0;--}}
{{--            border-bottom: 1px solid #f1f5f9;--}}
{{--        }--}}

{{--        .detail-label {--}}
{{--            font-weight: 500;--}}
{{--            color: #64748b;--}}
{{--        }--}}

{{--        .detail-value {--}}
{{--            color: #1e293b;--}}
{{--        }--}}

{{--        /* Action Buttons */--}}
{{--        .action-buttons {--}}
{{--            display: flex;--}}
{{--            flex-direction: column;--}}
{{--            gap: 0.5rem;--}}
{{--            margin-top: 1.5rem;--}}
{{--        }--}}

{{--        .action-button {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            gap: 0.5rem;--}}
{{--            padding: 0.5rem;--}}
{{--            border-radius: 0.375rem;--}}
{{--            font-weight: 500;--}}
{{--            cursor: pointer;--}}
{{--            transition: all 0.2s;--}}
{{--            border: none;--}}
{{--        }--}}

{{--        .action-button.primary {--}}
{{--            background-color: #4f46e5;--}}
{{--            color: white;--}}
{{--        }--}}

{{--        .action-button.primary:hover {--}}
{{--            background-color: #4338ca;--}}
{{--        }--}}

{{--        .action-button.danger {--}}
{{--            background-color: #ef4444;--}}
{{--            color: white;--}}
{{--        }--}}

{{--        .action-button.danger:hover {--}}
{{--            background-color: #dc2626;--}}
{{--        }--}}

{{--        /* Context Menu */--}}
{{--        .context-menu {--}}
{{--            position: fixed;--}}
{{--            z-index: 1000;--}}
{{--            background: white;--}}
{{--            border: 1px solid #e2e8f0;--}}
{{--            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);--}}
{{--            min-width: 200px;--}}
{{--            padding: 0.5rem 0;--}}
{{--        }--}}

{{--        .context-menu-item {--}}
{{--            width: 100%;--}}
{{--            padding: 0.5rem 1rem;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 0.75rem;--}}
{{--            cursor: pointer;--}}
{{--            transition: all 0.2s;--}}
{{--            text-align: left;--}}
{{--            background: none;--}}
{{--            border: none;--}}
{{--            font-size: 0.875rem;--}}
{{--        }--}}

{{--        .context-menu-item:hover {--}}
{{--            background-color: #f1f5f9;--}}
{{--        }--}}

{{--        .context-menu-item.danger {--}}
{{--            color: #ef4444;--}}
{{--        }--}}

{{--        .context-menu-item.danger:hover {--}}
{{--            background-color: #fee2e2;--}}
{{--        }--}}

{{--        .context-menu-separator {--}}
{{--            height: 1px;--}}
{{--            background-color: #e2e8f0;--}}
{{--            margin: 0.25rem 0;--}}
{{--        }--}}

{{--        .pagination-controls {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 0.5rem;--}}
{{--            padding: 0;--}}
{{--            margin-left: 1rem;--}}
{{--            background-color: transparent;--}}
{{--            border-top: none;--}}
{{--        }--}}

{{--        .pagination-button {--}}
{{--            padding: 0.5rem 1rem;--}}
{{--            border-radius: 0.375rem;--}}
{{--            background-color: #f8fafc;--}}
{{--            border: 1px solid #e2e8f0;--}}
{{--            cursor: pointer;--}}
{{--            transition: all 0.2s;--}}
{{--        }--}}

{{--        .pagination-button:hover:not(:disabled) {--}}
{{--            background-color: #e2e8f0;--}}
{{--        }--}}

{{--        .pagination-button:disabled {--}}
{{--            opacity: 0.5;--}}
{{--            cursor: not-allowed;--}}
{{--        }--}}

{{--        #pageInfo {--}}
{{--            font-size: 0.875rem;--}}
{{--            color: #64748b;--}}
{{--        }--}}

{{--        .shortcut {--}}
{{--            margin-left: auto;--}}
{{--            color: #94a3b8;--}}
{{--            font-size: 0.75rem;--}}
{{--        }--}}

{{--        /* Loading State */--}}
{{--        .loading-overlay {--}}
{{--            position: absolute;--}}
{{--            top: 0;--}}
{{--            left: 0;--}}
{{--            right: 0;--}}
{{--            bottom: 0;--}}
{{--            background-color: rgba(255, 255, 255, 0.8);--}}
{{--            backdrop-filter: blur(2px);--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            z-index: 100;--}}
{{--        }--}}

{{--        .loading-spinner {--}}
{{--            animation: spin 1s linear infinite;--}}
{{--            color: #4f46e5;--}}
{{--            font-size: 2rem;--}}
{{--        }--}}

{{--        @keyframes spin {--}}
{{--            from { transform: rotate(0deg); }--}}
{{--            to { transform: rotate(360deg); }--}}
{{--        }--}}

{{--        /* Progress Modal */--}}
{{--        .progress-modal {--}}
{{--            position: fixed;--}}
{{--            top: 0;--}}
{{--            left: 0;--}}
{{--            right: 0;--}}
{{--            bottom: 0;--}}
{{--            background-color: rgba(0, 0, 0, 0.5);--}}
{{--            display: none;--}}
{{--            justify-content: center;--}}
{{--            align-items: center;--}}
{{--            z-index: 1000;--}}
{{--        }--}}

{{--        .progress-modal.show {--}}
{{--            display: flex;--}}
{{--        }--}}

{{--        .progress-content {--}}
{{--            background-color: white;--}}
{{--            padding: 1.5rem;--}}
{{--            border-radius: 0.5rem;--}}
{{--            width: 400px;--}}
{{--            max-width: 90%;--}}
{{--        }--}}

{{--        .progress-header {--}}
{{--            font-size: 1.25rem;--}}
{{--            font-weight: 600;--}}
{{--            margin-bottom: 1rem;--}}
{{--        }--}}

{{--        .progress-bar-container {--}}
{{--            height: 0.5rem;--}}
{{--            background-color: #e2e8f0;--}}
{{--            border-radius: 0.25rem;--}}
{{--            margin-bottom: 1rem;--}}
{{--            overflow: hidden;--}}
{{--        }--}}

{{--        .progress-bar {--}}
{{--            height: 100%;--}}
{{--            background-color: #4f46e5;--}}
{{--            width: 0%;--}}
{{--            transition: width 0.3s ease;--}}
{{--        }--}}

{{--        .progress-status {--}}
{{--            font-size: 0.875rem;--}}
{{--            color: #64748b;--}}
{{--        }--}}

{{--        /* Trash View */--}}
{{--        .trash-actions {--}}
{{--            display: none;--}}
{{--        }--}}

{{--        .trash-view .trash-actions {--}}
{{--            display: flex;--}}
{{--            gap: 0.5rem;--}}
{{--        }--}}

{{--        .trash-view .normal-actions {--}}
{{--            display: none;--}}
{{--        }--}}

{{--        .trash-view .breadcrumb-and-pagination {--}}
{{--            display: none;--}}
{{--        }--}}

{{--        .deleted-item {--}}
{{--            opacity: 0.8;--}}
{{--            position: relative;--}}
{{--        }--}}

{{--        .deleted-item::after {--}}
{{--            content: "";--}}
{{--            position: absolute;--}}
{{--            top: 0;--}}
{{--            left: 0;--}}
{{--            right: 0;--}}
{{--            bottom: 0;--}}
{{--            background: repeating-linear-gradient(--}}
{{--                45deg,--}}
{{--                rgba(239, 68, 68, 0.1),--}}
{{--                rgba(239, 68, 68, 0.1) 10px,--}}
{{--                transparent 10px,--}}
{{--                transparent 20px--}}
{{--            );--}}
{{--            pointer-events: none;--}}
{{--        }--}}

{{--        /* Sortable Images */--}}
{{--        .sortable-image {--}}
{{--            cursor: move;--}}
{{--            transition: transform 0.2s;--}}
{{--        }--}}

{{--        .sortable-image.dragging {--}}
{{--            opacity: 0.5;--}}
{{--            transform: scale(0.95);--}}
{{--        }--}}

{{--        /* Responsive Adjustments */--}}
{{--        @media (max-width: 768px) {--}}
{{--            .gallery-content {--}}
{{--                grid-template-columns: 100%;--}}
{{--            }--}}

{{--            .preview-panel {--}}
{{--                display: none;--}}
{{--            }--}}
{{--        }--}}
{{--    </style>--}}

{{--    <div class="gallery-container w-full max-w-6xl bg-white rounded-lg shadow-xl overflow-hidden">--}}
{{--        <!-- Header -->--}}
{{--        <div class="gallery-header">--}}
{{--            <div class="gallery-title" id="galleryTitle">--}}
{{--                <i class="fas fa-images"></i>--}}
{{--                <span>Media Gallery</span>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Toolbar -->--}}
{{--        <div class="gallery-toolbar">--}}
{{--            <div class="toolbar-group normal-actions">--}}
{{--                <button type="button" id="refreshButton" class="toolbar-button secondary" aria-label="Refresh Gallery">--}}
{{--                    <i class="fas fa-sync-alt"></i>--}}
{{--                    <span>Refresh</span>--}}
{{--                </button>--}}
{{--                <button type="button" id="newFolderButton" class="toolbar-button secondary" aria-label="Create New Folder">--}}
{{--                    <i class="fas fa-folder-plus"></i>--}}
{{--                    <span>New Folder</span>--}}
{{--                </button>--}}
{{--                <button type="button" id="uploadButton" class="toolbar-button secondary" aria-label="Upload Files">--}}
{{--                    <i class="fas fa-upload"></i>--}}
{{--                    <span>Upload</span>--}}
{{--                </button>--}}
{{--                <button type="button" id="deleteButton" class="toolbar-button danger hidden" aria-label="Delete Selected Items">--}}
{{--                    <i class="fas fa-trash"></i>--}}
{{--                    <span>Delete</span>--}}
{{--                </button>--}}
{{--            </div>--}}

{{--            <div class="toolbar-group trash-actions">--}}
{{--                <button type="button" id="restoreButton" class="toolbar-button secondary" aria-label="Restore Selected Items">--}}
{{--                    <i class="fas fa-trash-restore"></i>--}}
{{--                    <span>Restore</span>--}}
{{--                </button>--}}
{{--                <button type="button" id="permanentDeleteButton" class="toolbar-button danger" aria-label="Delete Permanently">--}}
{{--                    <i class="fas fa-trash"></i>--}}
{{--                    <span>Delete Permanently</span>--}}
{{--                </button>--}}
{{--                <button type="button" id="emptyTrashButton" class="toolbar-button danger" aria-label="Empty Trash">--}}
{{--                    <i class="fas fa-broom"></i>--}}
{{--                    <span>Empty Trash</span>--}}
{{--                </button>--}}
{{--            </div>--}}

{{--            <div class="toolbar-group">--}}
{{--                <button type="button" id="toggleTrashButton" class="toolbar-button secondary" aria-label="Toggle Trash View">--}}
{{--                    <i class="fas fa-trash"></i>--}}
{{--                    <span id="trashButtonText">Trash</span>--}}
{{--                </button>--}}
{{--                <div class="search-container">--}}
{{--                    <input type="text" id="searchInput" class="search-input" placeholder="Search..." aria-label="Search Media">--}}
{{--                    <i class="fas fa-search search-icon"></i>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Breadcrumbs and Pagination -->--}}
{{--        <div class="breadcrumb-and-pagination" :class="{'hidden-in-trash': gallery.state.isTrashView}">--}}
{{--            <div id="breadcrumbContainer" class="breadcrumb-container">--}}
{{--                <!-- Dynamic breadcrumbs -->--}}
{{--            </div>--}}

{{--            <div class="pagination-controls">--}}
{{--                <button id="prevPage" class="pagination-button" disabled aria-label="Previous Page">--}}
{{--                    <i class="fas fa-chevron-left"></i>--}}
{{--                </button>--}}
{{--                <span id="pageInfo">Page 1 of 1</span>--}}
{{--                <button id="nextPage" class="pagination-button" disabled aria-label="Next Page">--}}
{{--                    <i class="fas fa-chevron-right"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Notifications -->--}}
{{--        <div id="notificationArea"></div>--}}

{{--        <!-- Main Content -->--}}
{{--        <div class="gallery-content relative">--}}
{{--            <!-- Items Grid -->--}}
{{--            <div class="items-grid" id="galleryItems" tabindex="0" aria-label="Media Items">--}}
{{--                <!-- Dynamic content -->--}}
{{--            </div>--}}

{{--            <!-- Preview Panel -->--}}
{{--            <div class="preview-panel">--}}
{{--                <div class="preview-header">Preview</div>--}}
{{--                <div id="previewContent" class="preview-content">--}}
{{--                    <div class="preview-empty">--}}
{{--                        <i class="fas fa-image text-4xl mb-2"></i>--}}
{{--                        <p>No item selected</p>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div id="previewDetails" class="preview-details hidden">--}}
{{--                    <div class="detail-row">--}}
{{--                        <span class="detail-label">Name:</span>--}}
{{--                        <span class="detail-value" id="detailName">-</span>--}}
{{--                    </div>--}}
{{--                    <div class="detail-row">--}}
{{--                        <span class="detail-label">Type:</span>--}}
{{--                        <span class="detail-value" id="detailType">-</span>--}}
{{--                    </div>--}}
{{--                    <div class="detail-row">--}}
{{--                        <span class="detail-label">Size:</span>--}}
{{--                        <span class="detail-value" id="detailSize">-</span>--}}
{{--                    </div>--}}
{{--                    <div class="detail-row">--}}
{{--                        <span class="detail-label">Dimensions:</span>--}}
{{--                        <span class="detail-value" id="detailDimensions">-</span>--}}
{{--                    </div>--}}
{{--                    <div class="detail-row">--}}
{{--                        <span class="detail-label">Uploaded:</span>--}}
{{--                        <span class="detail-value" id="detailUploaded">-</span>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div id="actionButtons" class="action-buttons hidden">--}}
{{--                    <button class="action-button secondary" id="downloadButton" aria-label="Download Media">--}}
{{--                        <i class="fas fa-download mr-2"></i>--}}
{{--                        Download--}}
{{--                    </button>--}}
{{--                    <button class="action-button danger" id="deleteSingleButton" aria-label="Delete Media">--}}
{{--                        <i class="fas fa-trash mr-2"></i>--}}
{{--                        Delete--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<!-- Progress Modal -->--}}
{{--<div id="progressModal" class="progress-modal">--}}
{{--    <div class="progress-content">--}}
{{--        <div class="progress-header" id="progressTitle">Processing Files</div>--}}
{{--        <div class="progress-bar-container">--}}
{{--            <div class="progress-bar" id="progressBar"></div>--}}
{{--        </div>--}}
{{--        <div class="progress-status" id="progressStatus">0% Complete</div>--}}
{{--    </div>--}}
{{--</div>--}}



{{--<script>--}}
{{--    // Sortable is globally available via app.js--}}
{{--    // import Sortable from 'sortablejs'; // No longer needed here--}}

{{--    document.addEventListener('DOMContentLoaded', function () {--}}
{{--        // Utility Functions--}}
{{--        const debounce = (func, wait) => {--}}
{{--            let timeout;--}}
{{--            return (...args) => {--}}
{{--                clearTimeout(timeout);--}}
{{--                timeout = setTimeout(() => func(...args), wait);--}}
{{--            };--}}
{{--        };--}}

{{--        const gallery = {--}}
{{--            // Cached DOM Elements--}}
{{--            elements: {--}}
{{--                itemsContainer: document.getElementById('galleryItems'),--}}
{{--                breadcrumbs: document.getElementById('breadcrumbContainer'),--}}
{{--                previewContent: document.getElementById('previewContent'),--}}
{{--                previewDetails: document.getElementById('previewDetails'),--}}
{{--                detailName: document.getElementById('detailName'),--}}
{{--                detailType: document.getElementById('detailType'),--}}
{{--                detailSize: document.getElementById('detailSize'),--}}
{{--                detailDimensions: document.getElementById('detailDimensions'),--}}
{{--                detailUploaded: document.getElementById('detailUploaded'),--}}
{{--                actionButtons: document.getElementById('actionButtons'),--}}
{{--                downloadButton: document.getElementById('downloadButton'),--}}
{{--                deleteSingleButton: document.getElementById('deleteSingleButton'),--}}
{{--                refreshButton: document.getElementById('refreshButton'),--}}
{{--                newFolderButton: document.getElementById('newFolderButton'),--}}
{{--                uploadButton: document.getElementById('uploadButton'),--}}
{{--                deleteButton: document.getElementById('deleteButton'),--}}
{{--                restoreButton: document.getElementById('restoreButton'),--}}
{{--                permanentDeleteButton: document.getElementById('permanentDeleteButton'),--}}
{{--                emptyTrashButton: document.getElementById('emptyTrashButton'),--}}
{{--                toggleTrashButton: document.getElementById('toggleTrashButton'),--}}
{{--                trashButtonText: document.getElementById('trashButtonText'),--}}
{{--                searchInput: document.getElementById('searchInput'),--}}
{{--                progressModal: document.getElementById('progressModal'),--}}
{{--                progressTitle: document.getElementById('progressTitle'),--}}
{{--                progressBar: document.getElementById('progressBar'),--}}
{{--                progressStatus: document.getElementById('progressStatus'),--}}
{{--                notificationArea: document.getElementById('notificationArea'),--}}
{{--                prevPage: document.getElementById('prevPage'),--}}
{{--                nextPage: document.getElementById('nextPage'),--}}
{{--                pageInfo: document.getElementById('pageInfo')--}}
{{--            },--}}

{{--            // State--}}
{{--            state: {--}}
{{--                currentPath: '',--}}
{{--                currentFolderId: null,--}}
{{--                selectedItems: new Set(), // Use Set to prevent duplicates--}}
{{--                isTrashView: false,--}}
{{--                clipboard: null,--}}
{{--                pagination: {--}}
{{--                    currentPage: 1,--}}
{{--                    perPage: 12,--}}
{{--                    totalItems: 0,--}}
{{--                    totalPages: 1,--}}
{{--                    hasPrevious: false,--}}
{{--                    hasNext: false--}}
{{--                },--}}
{{--            },--}}

{{--            // Initialize--}}
{{--            init() {--}}
{{--                this.setupEventListeners();--}}
{{--                this.setupAccessibility();--}}
{{--                this.setupLazyLoading();--}}
{{--                this.setupDragAndDrop();--}}
{{--            },--}}

{{--            showLoading() {--}}
{{--                const loadingOverlay = document.createElement('div');--}}
{{--                loadingOverlay.className = 'loading-overlay';--}}
{{--                loadingOverlay.innerHTML = '<i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>';--}}
{{--                this.elements.itemsContainer.appendChild(loadingOverlay);--}}
{{--            },--}}

{{--            hideLoading() {--}}
{{--                const loadingOverlay = this.elements.itemsContainer.querySelector('.loading-overlay');--}}
{{--                if (loadingOverlay) loadingOverlay.remove();--}}
{{--            },--}}

{{--            showProgress(title) {--}}
{{--                this.elements.progressTitle.textContent = title;--}}
{{--                this.elements.progressBar.style.width = '0%';--}}
{{--                this.elements.progressStatus.textContent = '0% Complete';--}}
{{--                this.elements.progressModal.classList.add('show');--}}
{{--            },--}}

{{--            updateProgress(percent, statusText) {--}}
{{--                this.elements.progressBar.style.width = `${percent}%`;--}}
{{--                this.elements.progressStatus.textContent = statusText;--}}
{{--            },--}}

{{--            hideProgress() {--}}
{{--                this.elements.progressModal.classList.remove('show');--}}
{{--            },--}}

{{--            // Event Listeners--}}
{{--            setupEventListeners() {--}}
{{--                // Navigation--}}
{{--                this.elements.refreshButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.refreshContents();--}}
{{--                });--}}

{{--                this.elements.toggleTrashButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.toggleTrashView();--}}
{{--                });--}}

{{--                // File operations--}}
{{--                this.elements.uploadButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.showUploadDialog();--}}
{{--                });--}}

{{--                this.elements.newFolderButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.createNewFolder();--}}
{{--                });--}}

{{--                // Item actions--}}
{{--                this.elements.deleteButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.deleteSelected();--}}
{{--                });--}}

{{--                this.elements.restoreButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.restoreSelected();--}}
{{--                });--}}

{{--                this.elements.permanentDeleteButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.deleteSelected(true);--}}
{{--                });--}}

{{--                this.elements.emptyTrashButton.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.emptyTrash();--}}
{{--                });--}}

{{--                // Search with debounce--}}
{{--                this.elements.searchInput.addEventListener('input', debounce(() => {--}}
{{--                    this.performSearch();--}}
{{--                }, 300));--}}

{{--                // Context menu--}}
{{--                this.elements.itemsContainer.addEventListener('contextmenu', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    this.handleContextMenu(e);--}}
{{--                });--}}

{{--                document.addEventListener('click', (e) => {--}}
{{--                    if (!e.target.closest('.context-menu')) {--}}
{{--                        this.closeContextMenu();--}}
{{--                    }--}}
{{--                });--}}

{{--                // Keyboard shortcuts--}}
{{--                document.addEventListener('keydown', (e) => {--}}
{{--                    this.handleKeyboardShortcuts(e);--}}
{{--                });--}}

{{--                // Pagination--}}
{{--                this.elements.prevPage.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    if (this.state.pagination.hasPrevious) {--}}
{{--                        this.state.pagination.currentPage--;--}}
{{--                        this.loadContents();--}}
{{--                    }--}}
{{--                });--}}

{{--                this.elements.nextPage.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    if (this.state.pagination.hasNext) {--}}
{{--                        this.state.pagination.currentPage++;--}}
{{--                        this.loadContents();--}}
{{--                    }--}}
{{--                });--}}

{{--                // Item selection--}}
{{--                this.elements.itemsContainer.addEventListener('click', (e) => {--}}
{{--                    const item = e.target.closest('.gallery-item');--}}
{{--                    if (item) {--}}
{{--                        e.preventDefault();--}}
{{--                        e.stopPropagation();--}}
{{--                        this.handleItemClick(item, e);--}}
{{--                    }--}}

{{--                    const checkbox = e.target.closest('.item-checkbox input');--}}
{{--                    if (checkbox) {--}}
{{--                        e.stopPropagation();--}}
{{--                        const item = checkbox.closest('.gallery-item');--}}
{{--                        const id = item.dataset.id;--}}
{{--                        const type = item.dataset.type;--}}
{{--                        this.toggleItemSelection(id, type, item);--}}
{{--                    }--}}
{{--                });--}}

{{--                this.elements.itemsContainer.addEventListener('dblclick', (e) => {--}}
{{--                    const item = e.target.closest('.gallery-item');--}}
{{--                    if (item && item.dataset.type === 'folder') {--}}
{{--                        e.preventDefault();--}}
{{--                        this.navigateToFolder(item);--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            // Drag-and-Drop Upload--}}
{{--            setupDragAndDrop() {--}}
{{--                const dropZone = this.elements.itemsContainer;--}}
{{--                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {--}}
{{--                    dropZone.addEventListener(eventName, (e) => {--}}
{{--                        e.preventDefault();--}}
{{--                        e.stopPropagation();--}}
{{--                    });--}}
{{--                });--}}

{{--                dropZone.addEventListener('dragenter', () => {--}}
{{--                    dropZone.classList.add('drop-active');--}}
{{--                });--}}

{{--                dropZone.addEventListener('dragleave', () => {--}}
{{--                    dropZone.classList.remove('drop-active');--}}
{{--                });--}}

{{--                dropZone.addEventListener('drop', (e) => {--}}
{{--                    dropZone.classList.remove('drop-active');--}}
{{--                    const files = Array.from(e.dataTransfer.files);--}}
{{--                    if (files.length > 0) {--}}
{{--                        this.uploadFiles(files);--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            // Accessibility--}}
{{--            setupAccessibility() {--}}
{{--                // No modal, so no aria-modal or role dialog--}}
{{--                this.elements.itemsContainer.setAttribute('tabindex', '0');--}}
{{--            },--}}

{{--            // Lazy Loading--}}
{{--            setupLazyLoading() {--}}
{{--                if ('IntersectionObserver' in window) {--}}
{{--                    this.lazyImageObserver = new IntersectionObserver((entries) => {--}}
{{--                        entries.forEach(entry => {--}}
{{--                            if (entry.isIntersecting) {--}}
{{--                                const container = entry.target;--}}
{{--                                this.loadLazyImage(container);--}}
{{--                                this.lazyImageObserver.unobserve(container);--}}
{{--                            }--}}
{{--                        });--}}
{{--                    }, {--}}
{{--                        rootMargin: '100px',--}}
{{--                        threshold: 0.01--}}
{{--                    });--}}
{{--                    this.observeLazyContainers();--}}
{{--                } else {--}}
{{--                    this.loadAllImages();--}}
{{--                }--}}
{{--            },--}}

{{--            loadLazyImage(container) {--}}
{{--                const imgSrc = container.dataset.src;--}}
{{--                const imgAlt = container.dataset.alt;--}}

{{--                if (imgSrc) {--}}
{{--                    container.innerHTML = '<i class="fas fa-spinner loading-spinner"></i>';--}}
{{--                    const img = new Image();--}}
{{--                    img.src = imgSrc;--}}
{{--                    img.alt = imgAlt;--}}
{{--                    img.className = 'max-h-full max-w-full';--}}
{{--                    img.onload = () => {--}}
{{--                        container.innerHTML = '';--}}
{{--                        img.classList.add('loaded');--}}
{{--                        container.appendChild(img);--}}
{{--                    };--}}
{{--                    img.onerror = () => {--}}
{{--                        container.innerHTML = `<i class="fas fa-${this.getFileIcon('')} folder-icon text-4xl text-blue-400"></i>`;--}}
{{--                    };--}}
{{--                }--}}
{{--            },--}}

{{--            observeLazyContainers() {--}}
{{--                document.querySelectorAll('.lazy-load-container').forEach(container => {--}}
{{--                    if (!container.querySelector('img.loaded')) {--}}
{{--                        this.lazyImageObserver.observe(container);--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            loadAllImages() {--}}
{{--                document.querySelectorAll('.lazy-load-container[data-src]').forEach(container => {--}}
{{--                    this.loadLazyImage(container);--}}
{{--                });--}}
{{--            },--}}

{{--            // Navigation--}}
{{--            navigateToPath(path) {--}}
{{--                this.state.currentPath = path;--}}
{{--                this.state.pagination.currentPage = 1;--}}
{{--                this.loadContents();--}}
{{--            },--}}

{{--            navigateToFolder(folderElement) {--}}
{{--                if (this.state.isTrashView) {--}}
{{--                    const folderId = folderElement.dataset.id;--}}
{{--                    this.loadTrashContents(folderId);--}}
{{--                } else {--}}
{{--                    this.state.currentPath = folderElement.dataset.path;--}}
{{--                    this.state.currentFolderId = folderElement.dataset.id;--}}
{{--                    this.state.pagination.currentPage = 1;--}}
{{--                    this.loadContents();--}}
{{--                }--}}
{{--            },--}}

{{--            loadContents() {--}}
{{--                this.showLoading();--}}
{{--                const url = new URL('{{ route("gallery.getContents") }}');--}}
{{--                url.searchParams.append('path', this.state.currentPath);--}}
{{--                url.searchParams.append('page', this.state.pagination.currentPage);--}}
{{--                url.searchParams.append('per_page', this.state.pagination.perPage);--}}

{{--                fetch(url, {--}}
{{--                    headers: {--}}
{{--                        'Accept': 'application/json',--}}
{{--                        'X-Requested-With': 'XMLHttpRequest',--}}
{{--                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content--}}
{{--                    }--}}
{{--                })--}}
{{--                    .then(response => response.json())--}}
{{--                    .then(data => {--}}
{{--                        if (data.success) {--}}
{{--                            this.state.currentPath = data.currentPath || '';--}}
{{--                            this.state.pagination = {--}}
{{--                                currentPage: data.pagination.current_page,--}}
{{--                                perPage: data.pagination.per_page,--}}
{{--                                totalItems: data.pagination.total_items,--}}
{{--                                totalPages: data.pagination.total_pages,--}}
{{--                                hasPrevious: data.pagination.has_previous,--}}
{{--                                hasNext: data.pagination.has_next--}}
{{--                            };--}}
{{--                            this.renderBreadcrumbs(data.breadcrumbs);--}}
{{--                            this.renderContents(data.contents);--}}
{{--                            this.updatePaginationControls();--}}
{{--                            this.observeLazyContainers();--}}
{{--                        } else {--}}
{{--                            throw new Error(data.message || 'Failed to load contents');--}}
{{--                        }--}}
{{--                    })--}}
{{--                    .catch(error => this.showError(error.message))--}}
{{--                    .finally(() => this.hideLoading());--}}
{{--            },--}}

{{--            loadTrashContents(parentId = null) {--}}
{{--                this.showLoading();--}}
{{--                const url = new URL('{{ route("gallery.trash") }}');--}}
{{--                if (parentId) url.searchParams.append('parent_id', parentId);--}}
{{--                url.searchParams.append('page', this.state.pagination.currentPage);--}}
{{--                url.searchParams.append('per_page', this.state.pagination.perPage);--}}

{{--                fetch(url, {--}}
{{--                    headers: {--}}
{{--                        'Accept': 'application/json',--}}
{{--                        'X-Requested-With': 'XMLHttpRequest'--}}
{{--                    }--}}
{{--                })--}}
{{--                    .then(response => response.json())--}}
{{--                    .then(data => {--}}
{{--                        if (data.success) {--}}
{{--                            this.state.currentTrashParent = parentId;--}}
{{--                            this.state.pagination = {--}}
{{--                                currentPage: data.pagination.current_page || 1,--}}
{{--                                perPage: data.pagination.per_page || 12,--}}
{{--                                totalItems: data.pagination.total_items || 0,--}}
{{--                                totalPages: data.pagination.total_pages || 1,--}}
{{--                                hasPrevious: data.pagination.has_previous || false,--}}
{{--                                hasNext: data.pagination.has_next || false--}}
{{--                            };--}}
{{--                            this.renderContents(data.contents);--}}
{{--                            this.updatePaginationControls();--}}
{{--                            this.observeLazyContainers();--}}
{{--                        } else {--}}
{{--                            throw new Error(data.message || 'Failed to load trash contents');--}}
{{--                        }--}}
{{--                    })--}}
{{--                    .catch(error => this.showError(error.message))--}}
{{--                    .finally(() => this.hideLoading());--}}
{{--            },--}}

{{--            updatePaginationControls() {--}}
{{--                const { currentPage, totalPages, hasPrevious, hasNext } = this.state.pagination;--}}
{{--                this.elements.prevPage.disabled = !hasPrevious;--}}
{{--                this.elements.nextPage.disabled = !hasNext;--}}
{{--                this.elements.pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;--}}
{{--            },--}}

{{--            renderBreadcrumbs(breadcrumbs) {--}}
{{--                this.elements.breadcrumbs.innerHTML = '';--}}
{{--                if (this.state.isTrashView) {--}}
{{--                    return;--}}
{{--                }--}}
{{--                breadcrumbs.forEach((crumb, index) => {--}}
{{--                    if (index > 0) {--}}
{{--                        const separator = document.createElement('span');--}}
{{--                        separator.className = 'breadcrumb-separator';--}}
{{--                        separator.innerHTML = '<i class="fas fa-chevron-right"></i>';--}}
{{--                        this.elements.breadcrumbs.appendChild(separator);--}}
{{--                    }--}}

{{--                    const button = document.createElement('button');--}}
{{--                    button.className = 'breadcrumb-button';--}}
{{--                    button.dataset.path = crumb.path;--}}
{{--                    button.innerHTML = `--}}
{{--                        ${crumb.icon ? `<i class="fas fa-${crumb.icon}"></i>` : ''}--}}
{{--                        <span>${crumb.name}</span>--}}
{{--                    `;--}}
{{--                    button.addEventListener('click', (e) => {--}}
{{--                        e.preventDefault();--}}
{{--                        this.navigateToPath(crumb.path);--}}
{{--                    });--}}
{{--                    this.elements.breadcrumbs.appendChild(button);--}}
{{--                });--}}
{{--            },--}}

{{--            renderContents(contents) {--}}
{{--                this.elements.itemsContainer.innerHTML = '';--}}
{{--                this.clearSelections();--}}

{{--                if (this.state.isTrashView) {--}}
{{--                    this.renderTrashView(contents);--}}
{{--                }--}}
{{--                else {--}}
{{--                    this.renderNormalView(contents);--}}
{{--                }--}}
{{--            },--}}

{{--            renderTrashView(contents) {--}}
{{--                if ((!contents.folders || contents.folders.length === 0) &&--}}
{{--                    (!contents.files || contents.files.length === 0)) {--}}
{{--                    this.showEmptyState('Trash is empty', 'fa-trash-alt');--}}
{{--                    return;--}}
{{--                }--}}

{{--                if (contents.folders?.length) {--}}
{{--                    contents.folders.forEach(folder => this.createFolderItem(folder, true));--}}
{{--                }--}}
{{--                if (contents.files?.length) {--}}
{{--                    contents.files.forEach(file => this.createFileItem(file, true));--}}
{{--                }--}}
{{--            },--}}

{{--            renderNormalView(contents) {--}}
{{--                if ((!contents.folders || contents.folders.length === 0) &&--}}
{{--                    (!contents.files || contents.files.length === 0)) {--}}
{{--                    this.showEmptyState('This folder is empty', 'fa-folder-open');--}}
{{--                    return;--}}
{{--                }--}}

{{--                if (this.state.currentPath) {--}}
{{--                    this.createGoUpItem();--}}
{{--                }--}}

{{--                if (contents.folders?.length) {--}}
{{--                    contents.folders.forEach(folder => this.createFolderItem(folder, false));--}}
{{--                }--}}
{{--                if (contents.files?.length) {--}}
{{--                    contents.files.forEach(file => this.createFileItem(file, false));--}}
{{--                }--}}
{{--            },--}}

{{--            showEmptyState(message, icon) {--}}
{{--                const emptyState = document.createElement('div');--}}
{{--                emptyState.className = 'empty-state';--}}
{{--                emptyState.innerHTML = `--}}
{{--                    <i class="fas ${icon} text-5xl mb-4"></i>--}}
{{--                    <p>${message}</p>--}}
{{--                `;--}}
{{--                this.elements.itemsContainer.appendChild(emptyState);--}}
{{--            },--}}

{{--            createGoUpItem() {--}}
{{--                const goUpItem = document.createElement('div');--}}
{{--                goUpItem.className = 'gallery-item';--}}
{{--                goUpItem.dataset.id = 'go-up';--}}
{{--                goUpItem.dataset.type = 'folder';--}}
{{--                goUpItem.innerHTML = `--}}
{{--                    <div class="item-thumbnail">--}}
{{--                        <div class="h-full flex items-center justify-center">--}}
{{--                            <i class="fas fa-level-up-alt folder-icon text-4xl"></i>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="item-info">--}}
{{--                        <div class="item-name">Go Up</div>--}}
{{--                    </div>--}}
{{--                `;--}}

{{--                goUpItem.addEventListener('click', (e) => {--}}
{{--                    e.preventDefault();--}}
{{--                    const parts = this.state.currentPath.split('/').filter(Boolean);--}}
{{--                    parts.pop();--}}
{{--                    this.state.currentPath = parts.join('/');--}}
{{--                    this.state.currentFolderId = null;--}}
{{--                    this.loadContents();--}}
{{--                });--}}

{{--                this.elements.itemsContainer.appendChild(goUpItem);--}}
{{--            },--}}

{{--            createFolderItem(folder, isTrash) {--}}
{{--                const folderItem = document.createElement('div');--}}
{{--                folderItem.className = `gallery-item ${this.isSelected(folder.id, 'folder') ? 'selected' : ''}`;--}}
{{--                folderItem.dataset.id = folder.id;--}}
{{--                folderItem.dataset.type = 'folder';--}}
{{--                folderItem.dataset.path = folder.path;--}}
{{--                if (folder.parent_id) folderItem.dataset.parent_id = folder.parent_id;--}}

{{--                folderItem.innerHTML = `--}}
{{--                    <div class="item-thumbnail relative">--}}
{{--                        <div class="absolute top-3 left-1 text-xs text-gray-600 bg-white/70 px-1 rounded">--}}
{{--                            <span class="bg-blue-50 text-blue-700 font-medium px-2 py-0.5 rounded-md shadow-sm border border-blue-200">--}}
{{--                                ${this.formatDate(folder.created_at)}--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                        <div class="item-checkbox absolute top-1 right-1">--}}
{{--                            <input type="checkbox" class="align-middle" ${this.isSelected(folder.id, 'folder') ? 'checked' : ''}>--}}
{{--                        </div>--}}
{{--                        <div class="h-24 flex items-center justify-center">--}}
{{--                            <i class="fas fa-folder folder-icon text-4xl text-yellow-400"></i>--}}
{{--                        </div>--}}
{{--                        <div class="absolute bottom-1 left-1 text-xs text-gray-600 bg-white/70 px-1 rounded">--}}
{{--                            ${folder.item_count || 0} items--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="item-info">--}}
{{--                        <div class="item-name">${folder.name}</div>--}}
{{--                    </div>--}}
{{--                `;--}}

{{--                if (isTrash) folderItem.classList.add('deleted-item');--}}
{{--                this.elements.itemsContainer.appendChild(folderItem);--}}
{{--            },--}}

{{--            createFileItem(file, isTrash) {--}}
{{--                const fileItem = document.createElement('div');--}}
{{--                fileItem.className = `gallery-item ${this.isSelected(file.id, 'file') ? 'selected' : ''}`;--}}
{{--                fileItem.dataset.id = file.id;--}}
{{--                fileItem.dataset.type = 'file';--}}

{{--                const thumbnail = file.thumb_url || file.url;--}}
{{--                const thumbnailContent = thumbnail ?--}}
{{--                    `<div class="lazy-load-container" data-src="${thumbnail}" data-alt="${file.name}">--}}
{{--                        <i class="fas fa-${this.getFileIcon(file.mime_type)} folder-icon text-4xl text-blue-400"></i>--}}
{{--                    </div>` :--}}
{{--                    `<i class="fas fa-${this.getFileIcon(file.mime_type)} folder-icon text-4xl text-blue-400"></i>`;--}}

{{--                fileItem.innerHTML = `--}}
{{--                    <div class="item-thumbnail">--}}
{{--                        ${thumbnailContent}--}}
{{--                        ${file.is_featured ? '<i class="fas fa-star absolute top-1 left-1 text-yellow-400"></i>' : ''}--}}
{{--                        <div class="absolute top-3 left-1 text-xs">--}}
{{--                            <span class="bg-white/70 text-gray-700 font-medium px-2 py-0.5 rounded-md shadow-sm border border-gray-300">--}}
{{--                                ${this.formatDate(file.created_at)}--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                        <div class="item-checkbox">--}}
{{--                            <input type="checkbox" ${this.isSelected(file.id, 'file') ? 'checked' : ''}>--}}
{{--                        </div>--}}
{{--                        <div class="absolute bottom-1 left-1 text-xs text-gray-600 bg-white/70 px-1 rounded">--}}
{{--                            ${this.formatFileSize(file.size_bytes)}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="item-info">--}}
{{--                        <div class="item-name">${file.name}</div>--}}
{{--                    </div>--}}
{{--                `;--}}

{{--                if (isTrash) fileItem.classList.add('deleted-item');--}}
{{--                this.elements.itemsContainer.appendChild(fileItem);--}}
{{--            },--}}

{{--            // Item Interactions--}}
{{--            handleItemClick(item, event) {--}}
{{--                const id = item.dataset.id;--}}
{{--                const type = item.dataset.type;--}}

{{--                if (event.shiftKey) {--}}
{{--                    this.toggleItemSelection(id, type, item);--}}
{{--                } else {--}}
{{--                    if (!this.isSelected(id, type)) {--}}
{{--                        this.clearSelections();--}}
{{--                        this.toggleItemSelection(id, type, item);--}}
{{--                    }--}}
{{--                }--}}

{{--                if (type === 'file') {--}}
{{--                    this.updatePreview(id, type);--}}
{{--                }--}}
{{--            },--}}

{{--            toggleItemSelection(id, type, element) {--}}
{{--                const itemKey = `${type}:${id}`;--}}
{{--                const wasSelected = this.state.selectedItems.has(itemKey);--}}

{{--                if (!wasSelected) {--}}
{{--                    this.state.selectedItems.add(itemKey);--}}
{{--                    element.classList.add('selected');--}}
{{--                    const checkbox = element.querySelector('.item-checkbox input');--}}
{{--                    if (checkbox) checkbox.checked = true;--}}
{{--                } else {--}}
{{--                    this.state.selectedItems.delete(itemKey);--}}
{{--                    element.classList.remove('selected');--}}
{{--                    const checkbox = element.querySelector('.item-checkbox input');--}}
{{--                    if (checkbox) checkbox.checked = false;--}}
{{--                }--}}

{{--                this.updateSelectionDisplay();--}}
{{--            },--}}

{{--            isSelected(id, type) {--}}
{{--                return this.state.selectedItems.has(`${type}:${id}`);--}}
{{--            },--}}

{{--            clearSelections() {--}}
{{--                this.elements.itemsContainer.querySelectorAll('.gallery-item.selected').forEach(el => {--}}
{{--                    el.classList.remove('selected');--}}
{{--                    const checkbox = el.querySelector('.item-checkbox input');--}}
{{--                    if (checkbox) checkbox.checked = false;--}}
{{--                });--}}
{{--                this.state.selectedItems.clear();--}}
{{--                this.updateSelectionDisplay();--}}
{{--                this.clearPreview();--}}
{{--            },--}}

{{--            updateSelectionDisplay() {--}}
{{--                const hasSelection = this.state.selectedItems.size > 0;--}}
{{--                const singleSelection = this.state.selectedItems.size === 1;--}}
{{--                const isTrashView = this.state.isTrashView;--}}

{{--                this.elements.deleteButton.classList.toggle('hidden', !hasSelection || isTrashView);--}}
{{--                this.elements.restoreButton.classList.toggle('hidden', !isTrashView || !hasSelection);--}}
{{--                this.elements.permanentDeleteButton.classList.toggle('hidden', !isTrashView || !hasSelection);--}}

{{--                if (singleSelection && Array.from(this.state.selectedItems)[0].startsWith('file:')) {--}}
{{--                    const fileId = Array.from(this.state.selectedItems)[0].split(':')[1];--}}
{{--                    this.fetchFileDetails(fileId);--}}
{{--                } else if (!hasSelection) {--}}
{{--                    this.clearPreview();--}}
{{--                }--}}
{{--            },--}}

{{--            // Preview Management--}}
{{--            updatePreview(id, type) {--}}
{{--                if (this.state.currentPreviewId === id) return;--}}
{{--                this.state.currentPreviewId = id;--}}
{{--                this.clearPreview();--}}
{{--                this.showLoadingPreview();--}}

{{--                const endpoint = type === 'folder' ?--}}
{{--                    `{{ route("gallery.folder.show", ":id") }}`.replace(':id', id) :--}}
{{--                    `{{ route("gallery.file.show", ":id") }}`.replace(':id', id);--}}

{{--                fetch(endpoint, {--}}
{{--                    headers: {--}}
{{--                        'Accept': 'application/json',--}}
{{--                        'X-Requested-With': 'XMLHttpRequest'--}}
{{--                    }--}}
{{--                })--}}
{{--                    .then(response => response.json())--}}
{{--                    .then(data => {--}}
{{--                        if (data.success) {--}}
{{--                            type === 'file' ? this.showFilePreview(data.file) : this.showFolderPreview(data.folder);--}}
{{--                        } else {--}}
{{--                            throw new Error(data.message || 'Failed to load preview');--}}
{{--                        }--}}
{{--                    })--}}
{{--                    .catch(error => {--}}
{{--                        this.showError(error.message);--}}
{{--                        this.clearPreview();--}}
{{--                    })--}}
{{--                    .finally(() => this.hideLoadingPreview());--}}
{{--            },--}}

{{--            showFilePreview(file) {--}}
{{--                if (!file) {--}}
{{--                    this.clearPreview();--}}
{{--                    return;--}}
{{--                }--}}

{{--                this.elements.previewContent.innerHTML = '';--}}
{{--                const previewContainer = document.createElement('div');--}}
{{--                previewContainer.className = 'preview-image-container';--}}

{{--                const mimeType = file.mime_type || '';--}}
{{--                const url = file.thumb_url || file.url;--}}
{{--                const altText = file.name || 'Preview';--}}

{{--                if (mimeType.startsWith('image/')) {--}}
{{--                    const img = new Image();--}}
{{--                    img.src = url;--}}
{{--                    img.alt = altText;--}}
{{--                    img.className = 'preview-image';--}}
{{--                    const loadingOverlay = document.createElement('div');--}}
{{--                    loadingOverlay.className = 'loading-overlay';--}}
{{--                    loadingOverlay.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';--}}
{{--                    previewContainer.appendChild(loadingOverlay);--}}

{{--                    img.onload = () => {--}}
{{--                        loadingOverlay.remove();--}}
{{--                        img.classList.add('loaded');--}}
{{--                    };--}}
{{--                    img.onerror = () => {--}}
{{--                        loadingOverlay.remove();--}}
{{--                        previewContainer.innerHTML = `--}}
{{--                            <div class="text-red-500 text-center p-4">--}}
{{--                                <i class="fas fa-exclamation-triangle mr-2"></i>--}}
{{--                                Failed to load image--}}
{{--                            </div>`;--}}
{{--                    };--}}
{{--                    previewContainer.appendChild(img);--}}
{{--                } else if (mimeType.startsWith('video/')) {--}}
{{--                    previewContainer.innerHTML = `--}}
{{--                        <div class="flex flex-col items-center justify-center h-full">--}}
{{--                            <i class="fas fa-video text-6xl text-gray-400 mb-2"></i>--}}
{{--                            <span class="text-sm text-gray-600">Video Preview</span>--}}
{{--                        </div>`;--}}
{{--                } else if (mimeType.includes('pdf')) {--}}
{{--                    previewContainer.innerHTML = `--}}
{{--                        <div class="flex flex-col items-center justify-center h-full">--}}
{{--                            <i class="fas fa-file-pdf text-6xl text-red-400 mb-2"></i>--}}
{{--                            <span class="text-sm text-gray-600">PDF Document</span>--}}
{{--                        </div>`;--}}
{{--                } else {--}}
{{--                    previewContainer.innerHTML = `--}}
{{--                        <div class="flex flex-col items-center justify-center h-full">--}}
{{--                            <i class="fas fa-${this.getFileIcon(mimeType)} text-6xl text-gray-400 mb-2"></i>--}}
{{--                            <span class="text-sm text-gray-600">No preview available</span>--}}
{{--                        </div>`;--}}
{{--                }--}}

{{--                this.updatePreviewDetails(file);--}}
{{--                this.elements.previewContent.appendChild(previewContainer);--}}
{{--                this.elements.previewDetails.classList.remove('hidden');--}}
{{--                this.elements.actionButtons.classList.remove('hidden');--}}
{{--            },--}}

{{--            showFolderPreview(folder) {--}}
{{--                if (!folder) {--}}
{{--                    this.clearPreview();--}}
{{--                    return;--}}
{{--                }--}}

{{--                this.elements.previewContent.innerHTML = '';--}}
{{--                const previewContainer = document.createElement('div');--}}
{{--                previewContainer.className = 'preview-image-container flex items-center justify-center';--}}
{{--                previewContainer.innerHTML = '<i class="fas fa-folder text-6xl text-yellow-400"></i>';--}}
{{--                this.elements.previewContent.appendChild(previewContainer);--}}

{{--                this.elements.detailName.textContent = folder.name;--}}
{{--                this.elements.detailType.textContent = 'Folder';--}}
{{--                this.elements.detailSize.textContent = '-';--}}
{{--                this.elements.detailDimensions.textContent = '-';--}}
{{--                this.elements.detailUploaded.textContent = this.formatDate(folder.created_at);--}}

{{--                this.elements.previewDetails.classList.remove('hidden');--}}
{{--                this.elements.actionButtons.classList.remove('hidden');--}}
{{--            },--}}

{{--            showLoadingPreview() {--}}
{{--                const loadingOverlay = document.createElement('div');--}}
{{--                loadingOverlay.className = 'loading-overlay flex items-center justify-center';--}}
{{--                loadingOverlay.innerHTML = '<i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>';--}}
{{--                this.elements.previewContent.appendChild(loadingOverlay);--}}
{{--            },--}}

{{--            hideLoadingPreview() {--}}
{{--                const loadingOverlay = this.elements.previewContent.querySelector('.loading-overlay');--}}
{{--                if (loadingOverlay) loadingOverlay.remove();--}}
{{--            },--}}

{{--            clearPreview() {--}}
{{--                this.elements.previewContent.innerHTML = `--}}
{{--                    <div class="preview-empty">--}}
{{--                        <i class="fas fa-image text-4xl mb-2"></i>--}}
{{--                        <p>No item selected</p>--}}
{{--                    </div>`;--}}
{{--                this.elements.previewDetails.classList.add('hidden');--}}
{{--                this.elements.actionButtons.classList.add('hidden');--}}
{{--            },--}}

{{--            fetchFileDetails(fileId) {--}}
{{--                fetch(`{{ route("gallery.file.show", ":id") }}`.replace(':id', fileId), {--}}
{{--                    headers: {--}}
{{--                        'Accept': 'application/json',--}}
{{--                        'X-Requested-With': 'XMLHttpRequest'--}}
{{--                    }--}}
{{--                })--}}
{{--                    .then(response => response.json())--}}
{{--                    .then(data => {--}}
{{--                        if (data.success) {--}}
{{--                            this.showFilePreview(data.file);--}}
{{--                        } else {--}}
{{--                            throw new Error(data.message || 'Failed to load file details');--}}
{{--                        }--}}
{{--                    })--}}
{{--                    .catch(error => {--}}
{{--                        this.showError(error.message);--}}
{{--                        this.clearPreview();--}}
{{--                    });--}}
{{--            },--}}

{{--            updatePreviewDetails(file) {--}}
{{--                this.elements.detailName.textContent = file.name || 'Unknown';--}}
{{--                this.elements.detailType.textContent = file.mime_type || 'Unknown';--}}
{{--                this.elements.detailSize.textContent = file.size_bytes ? this.formatFileSize(file.size_bytes) : 'N/A';--}}
{{--                this.elements.detailDimensions.textContent = file.dimensions ?--}}
{{--                    `${file.dimensions.width} × ${file.dimensions.height}` : 'N/A';--}}
{{--                this.elements.detailUploaded.textContent = this.formatDate(file.created_at);--}}
{{--            },--}}

{{--            // Gallery Operations--}}
{{--            toggleTrashView() {--}}
{{--                this.state.isTrashView = !this.state.isTrashView;--}}
{{--                this.state.currentPath = '';--}}
{{--                this.state.selectedItems.clear();--}}
{{--                document.querySelector('.gallery-container').classList.toggle('trash-view', this.state.isTrashView);--}}
{{--                this.elements.trashButtonText.textContent = this.state.isTrashView ? 'Back to Gallery' : 'Trash';--}}
{{--                this.state.isTrashView ? this.loadTrashContents() : this.loadContents();--}}
{{--            },--}}

{{--            refreshContents() {--}}
{{--                this.state.pagination.currentPage = 1;--}}
{{--                this.state.isTrashView ? this.loadTrashContents(this.state.currentTrashParent) : this.loadContents();--}}
{{--            },--}}

{{--            showUploadDialog() {--}}
{{--                const input = document.createElement('input');--}}
{{--                input.type = 'file';--}}
{{--                input.multiple = true;--}}
{{--                input.accept = 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx';--}}
{{--                input.onchange = (e) => {--}}
{{--                    const files = Array.from(e.target.files);--}}
{{--                    if (files.length > 0) this.uploadFiles(files);--}}
{{--                };--}}
{{--                input.click();--}}
{{--            },--}}

{{--            uploadFiles(files) {--}}
{{--                this.showProgress('Uploading Files');--}}
{{--                const formData = new FormData();--}}
{{--                files.forEach(file => formData.append('files[]', file));--}}
{{--                if (this.state.currentPath) formData.append('path', this.state.currentPath);--}}

{{--                const xhr = new XMLHttpRequest();--}}
{{--                xhr.upload.addEventListener('progress', (e) => {--}}
{{--                    if (e.lengthComputable) {--}}
{{--                        const percent = Math.round((e.loaded / e.total) * 100);--}}
{{--                        this.updateProgress(percent, `Uploading ${percent}%`);--}}
{{--                    }--}}
{{--                });--}}

{{--                xhr.addEventListener('load', () => {--}}
{{--                    try {--}}
{{--                        const data = JSON.parse(xhr.responseText);--}}
{{--                        if (data.success) {--}}
{{--                            this.updateProgress(100, 'Processing files...');--}}
{{--                            setTimeout(() => {--}}
{{--                                this.hideProgress();--}}
{{--                                this.showSuccessNotification('Files uploaded successfully');--}}
{{--                                this.loadContents();--}}
{{--                            }, 500);--}}
{{--                        } else {--}}
{{--                            throw new Error(data.message || 'Upload failed');--}}
{{--                        }--}}
{{--                    } catch (error) {--}}
{{--                        this.hideProgress();--}}
{{--                        this.showError(error.message);--}}
{{--                    }--}}
{{--                });--}}

{{--                xhr.addEventListener('error', () => {--}}
{{--                    this.hideProgress();--}}
{{--                    this.showError('Upload failed');--}}
{{--                });--}}

{{--                xhr.open('POST', '{{ route("gallery.upload") }}');--}}
{{--                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);--}}
{{--                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');--}}
{{--                xhr.send(formData);--}}
{{--            },--}}

{{--            createNewFolder() {--}}
{{--                Swal.fire({--}}
{{--                    title: 'Create New Folder',--}}
{{--                    input: 'text',--}}
{{--                    inputLabel: 'Folder Name',--}}
{{--                    inputPlaceholder: 'Enter folder name...',--}}
{{--                    inputAttributes: { autocapitalize: 'off' },--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonText: 'Create',--}}
{{--                    cancelButtonText: 'Cancel',--}}
{{--                    inputValidator: (value) => {--}}
{{--                        if (!value) return 'You need to enter a folder name!';--}}
{{--                        if (!/^[a-zA-Z0-9\-_ ]+$/.test(value)) return 'Folder name can only contain letters, numbers, spaces, hyphens, and underscores';--}}
{{--                    }--}}
{{--                }).then(result => {--}}
{{--                    if (result.isConfirmed) {--}}
{{--                        const folderName = result.value;--}}
{{--                        this.showProgress('Creating Folder');--}}
{{--                        fetch('{{ route("gallery.folder.create") }}', {--}}
{{--                            method: 'POST',--}}
{{--                            headers: {--}}
{{--                                'Content-Type': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,--}}
{{--                                'X-Requested-With': 'XMLHttpRequest'--}}
{{--                            },--}}
{{--                            body: JSON.stringify({--}}
{{--                                name: folderName,--}}
{{--                                parent_id: this.state.currentFolderId,--}}
{{--                                current_path: this.state.currentPath--}}
{{--                            })--}}
{{--                        })--}}
{{--                            .then(response => response.json())--}}
{{--                            .then(data => {--}}
{{--                                if (data.success) {--}}
{{--                                    this.updateProgress(100, 'Completed');--}}
{{--                                    setTimeout(() => {--}}
{{--                                        this.hideProgress();--}}
{{--                                        this.showSuccessNotification('Folder created successfully');--}}
{{--                                        this.loadContents();--}}
{{--                                    }, 500);--}}
{{--                                } else {--}}
{{--                                    throw new Error(data.message || 'Folder creation failed');--}}
{{--                                }--}}
{{--                            })--}}
{{--                            .catch(error => {--}}
{{--                                this.showError(error.message);--}}
{{--                                Swal.fire({ icon: 'error', title: 'Error', text: error.message });--}}
{{--                            })--}}
{{--                            .finally(() => this.hideProgress());--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            deleteSelected(permanent = false) {--}}
{{--                if (this.state.selectedItems.size === 0) return;--}}

{{--                const items = Array.from(this.state.selectedItems).map(key => {--}}
{{--                    const [type, id] = key.split(':');--}}
{{--                    return { id, type };--}}
{{--                });--}}

{{--                Swal.fire({--}}
{{--                    title: permanent ? 'Delete Permanently?' : 'Move to Trash?',--}}
{{--                    text: permanent ?--}}
{{--                        `Are you sure you want to permanently delete ${items.length} item(s)? This cannot be undone.` :--}}
{{--                        `Move ${items.length} item(s) to trash?`,--}}
{{--                    icon: 'warning',--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonColor: '#ef4444',--}}
{{--                    confirmButtonText: permanent ? 'Delete Permanently' : 'Move to Trash'--}}
{{--                }).then(result => {--}}
{{--                    if (result.isConfirmed) {--}}
{{--                        this.showProgress(permanent ? 'Deleting Items' : 'Moving to Trash');--}}
{{--                        fetch('{{ route("gallery.delete") }}', {--}}
{{--                            method: 'POST',--}}
{{--                            headers: {--}}
{{--                                'Content-Type': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,--}}
{{--                                'X-Requested-With': 'XMLHttpRequest'--}}
{{--                            },--}}
{{--                            body: JSON.stringify({ items, permanent })--}}
{{--                        })--}}
{{--                            .then(response => response.json())--}}
{{--                            .then(data => {--}}
{{--                                if (data.success) {--}}
{{--                                    this.updateProgress(100, 'Completed');--}}
{{--                                    setTimeout(() => {--}}
{{--                                        this.hideProgress();--}}
{{--                                        this.showSuccessNotification(data.message);--}}
{{--                                        this.clearPreview();--}}
{{--                                        this.refreshContents();--}}
{{--                                    }, 500);--}}
{{--                                } else {--}}
{{--                                    throw new Error(data.message || 'Delete failed');--}}
{{--                                }--}}
{{--                            })--}}
{{--                            .catch(error => {--}}
{{--                                this.hideProgress();--}}
{{--                                this.showError(error.message);--}}
{{--                            });--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            restoreSelected() {--}}
{{--                if (this.state.selectedItems.size === 0) return;--}}

{{--                const items = Array.from(this.state.selectedItems).map(key => {--}}
{{--                    const [type, id] = key.split(':');--}}
{{--                    return { id, type };--}}
{{--                });--}}

{{--                Swal.fire({--}}
{{--                    title: 'Restore Items?',--}}
{{--                    text: `Restore ${items.length} item(s) from trash?`,--}}
{{--                    icon: 'question',--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonColor: '#4f46e5',--}}
{{--                    confirmButtonText: 'Restore'--}}
{{--                }).then(result => {--}}
{{--                    if (result.isConfirmed) {--}}
{{--                        this.showProgress('Restoring Items');--}}
{{--                        fetch('{{ route("gallery.restore") }}', {--}}
{{--                            method: 'POST',--}}
{{--                            headers: {--}}
{{--                                'Content-Type': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,--}}
{{--                                'X-Requested-With': 'XMLHttpRequest'--}}
{{--                            },--}}
{{--                            body: JSON.stringify({ items })--}}
{{--                        })--}}
{{--                            .then(response => response.json())--}}
{{--                            .then(data => {--}}
{{--                                if (data.success) {--}}
{{--                                    this.updateProgress(100, 'Completed');--}}
{{--                                    setTimeout(() => {--}}
{{--                                        this.hideProgress();--}}
{{--                                        this.showSuccessNotification(data.message);--}}
{{--                                        this.clearPreview();--}}
{{--                                        this.refreshContents();--}}
{{--                                    }, 500);--}}
{{--                                } else {--}}
{{--                                    throw new Error(data.message || 'Restore failed');--}}
{{--                                }--}}
{{--                            })--}}
{{--                            .catch(error => {--}}
{{--                                this.showError(error.message);--}}
{{--                            });--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            emptyTrash() {--}}
{{--                Swal.fire({--}}
{{--                    title: 'Empty Trash?',--}}
{{--                    text: 'Are you sure you want to permanently delete all items in the trash? This cannot be undone.',--}}
{{--                    icon: 'warning',--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonColor: '#ef4444',--}}
{{--                    confirmButtonText: 'Empty Trash'--}}
{{--                }).then(result => {--}}
{{--                    if (result.isConfirmed) {--}}
{{--                        this.showProgress('Emptying Trash');--}}
{{--                        fetch('{{ route("gallery.empty-trash") }}', {--}}
{{--                            method: 'POST',--}}
{{--                            headers: {--}}
{{--                                'Content-Type': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,--}}
{{--                                'X-Requested-With': 'XMLHttpRequest'--}}
{{--                            }--}}
{{--                        })--}}
{{--                            .then(response => response.json())--}}
{{--                            .then(data => {--}}
{{--                                if (data.success) {--}}
{{--                                    this.updateProgress(100, 'Completed');--}}
{{--                                    setTimeout(() => {--}}
{{--                                        this.hideProgress();--}}
{{--                                        this.showSuccessNotification(data.message);--}}
{{--                                        this.clearPreview();--}}
{{--                                        this.loadTrashContents();--}}
{{--                                    }, 500);--}}
{{--                                } else {--}}
{{--                                    throw new Error(data.message || 'Failed to empty trash');--}}
{{--                                }--}}
{{--                            })--}}
{{--                            .catch(error => {--}}
{{--                                this.showError(error.message);--}}
{{--                            });--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            getFileIcon(mimeType) {--}}
{{--                if (mimeType.startsWith('image/')) {--}}
{{--                    return 'image';--}}
{{--                } else if (mimeType.startsWith('video/')) {--}}
{{--                    return 'video';--}}
{{--                } else if (mimeType.includes('pdf')) {--}}
{{--                    return 'file-pdf';--}}
{{--                } else if (mimeType.includes('word') || mimeType.includes('document')) {--}}
{{--                    return 'file-word';--}}
{{--                } else if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) {--}}
{{--                    return 'file-excel';--}}
{{--                } else if (mimeType.includes('zip') || mimeType.includes('compressed')) {--}}
{{--                    return 'file-archive';--}}
{{--                } else {--}}
{{--                    return 'file';--}}
{{--                }--}}
{{--            },--}}

{{--            formatFileSize(bytes) {--}}
{{--                if (bytes === 0) return '0 Bytes';--}}
{{--                const k = 1024;--}}
{{--                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];--}}
{{--                const i = Math.floor(Math.log(bytes) / Math.log(k));--}}
{{--                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];--}}
{{--            },--}}

{{--            formatDate(dateString) {--}}
{{--                const options = { year: 'numeric', month: 'short', day: 'numeric' };--}}
{{--                return new Date(dateString).toLocaleDateString(undefined, options);--}}
{{--            },--}}

{{--            showLoading() {--}}
{{--                const loadingOverlay = document.createElement('div');--}}
{{--                loadingOverlay.className = 'loading-overlay';--}}
{{--                loadingOverlay.innerHTML = '<i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>';--}}
{{--                this.elements.itemsContainer.appendChild(loadingOverlay);--}}
{{--            },--}}

{{--            hideLoading() {--}}
{{--                const loadingOverlay = this.elements.itemsContainer.querySelector('.loading-overlay');--}}
{{--                if (loadingOverlay) loadingOverlay.remove();--}}
{{--            },--}}

{{--            showProgress(title) {--}}
{{--                this.elements.progressTitle.textContent = title;--}}
{{--                this.elements.progressBar.style.width = '0%';--}}
{{--                this.elements.progressStatus.textContent = '0% Complete';--}}
{{--                this.elements.progressModal.classList.add('show');--}}
{{--            },--}}

{{--            updateProgress(percent, statusText) {--}}
{{--                this.elements.progressBar.style.width = `${percent}%`;--}}
{{--                this.elements.progressStatus.textContent = statusText;--}}
{{--            },--}}

{{--            hideProgress() {--}}
{{--                this.elements.progressModal.classList.remove('show');--}}
{{--            },--}}

{{--            showSuccessNotification(message) {--}}
{{--                this.elements.notificationArea.innerHTML = `--}}
{{--                    <div class="gallery-notification success">--}}
{{--                        <i class="fas fa-check-circle mr-2"></i>--}}
{{--                        <span>${message}</span>--}}
{{--                    </div>--}}
{{--                `;--}}
{{--                setTimeout(() => {--}}
{{--                    const notification = this.elements.notificationArea.querySelector('.gallery-notification');--}}
{{--                    if (notification) {--}}
{{--                        notification.classList.add('fade-out');--}}
{{--                        notification.addEventListener('animationend', () => notification.remove());--}}
{{--                    }--}}
{{--                }, 3000);--}}
{{--            },--}}

{{--            showError(message) {--}}
{{--                this.elements.notificationArea.innerHTML = `--}}
{{--                    <div class="gallery-notification error">--}}
{{--                        <i class="fas fa-exclamation-circle mr-2"></i>--}}
{{--                        <span>${message}</span>--}}
{{--                    </div>--}}
{{--                `;--}}
{{--                setTimeout(() => {--}}
{{--                    const notification = this.elements.notificationArea.querySelector('.gallery-notification');--}}
{{--                    if (notification) {--}}
{{--                        notification.classList.add('fade-out');--}}
{{--                        notification.addEventListener('animationend', () => notification.remove());--}}
{{--                    }--}}
{{--                }, 5000);--}}
{{--            },--}}

{{--            handleContextMenu(e) {--}}
{{--                e.preventDefault();--}}
{{--                this.closeContextMenu(); // Close any existing context menu--}}

{{--                const item = e.target.closest('.gallery-item');--}}
{{--                if (!item) return;--}}

{{--                const id = item.dataset.id;--}}
{{--                const type = item.dataset.type;--}}

{{--                const contextMenu = document.createElement('div');--}}
{{--                contextMenu.className = 'context-menu';--}}
{{--                contextMenu.style.left = `${e.pageX}px`;--}}
{{--                contextMenu.style.top = `${e.pageY}px`;--}}

{{--                let menuItems = [];--}}

{{--                if (type === 'file') {--}}
{{--                    menuItems.push({ text: 'Download', icon: 'fas fa-download', action: () => this.downloadFile(id) });--}}
{{--                    menuItems.push({ text: 'Rename', icon: 'fas fa-edit', action: () => this.renameItem(id, type) });--}}
{{--                    menuItems.push({ text: 'Copy URL', icon: 'fas fa-copy', action: () => this.copyFileUrl(id) });--}}
{{--                    menuItems.push({ text: 'Move', icon: 'fas fa-arrows-alt', action: () => this.moveItem(id, type) });--}}
{{--                    menuItems.push({ text: 'Delete', icon: 'fas fa-trash', class: 'danger', action: () => this.deleteSelected() });--}}
{{--                } else if (type === 'folder') {--}}
{{--                    menuItems.push({ text: 'Open', icon: 'fas fa-folder-open', action: () => this.navigateToFolder(item) });--}}
{{--                    menuItems.push({ text: 'Rename', icon: 'fas fa-edit', action: () => this.renameItem(id, type) });--}}
{{--                    menuItems.push({ text: 'Move', icon: 'fas fa-arrows-alt', action: () => this.moveItem(id, type) });--}}
{{--                    menuItems.push({ text: 'Delete', icon: 'fas fa-trash', class: 'danger', action: () => this.deleteSelected() });--}}
{{--                }--}}

{{--                menuItems.forEach(itemData => {--}}
{{--                    const menuItem = document.createElement('button');--}}
{{--                    menuItem.className = `context-menu-item ${itemData.class || ''}`;--}}
{{--                    menuItem.innerHTML = `<i class="${itemData.icon} mr-2"></i><span>${itemData.text}</span>`;--}}
{{--                    menuItem.addEventListener('click', () => {--}}
{{--                        itemData.action();--}}
{{--                        this.closeContextMenu();--}}
{{--                    });--}}
{{--                    contextMenu.appendChild(menuItem);--}}
{{--                });--}}

{{--                document.body.appendChild(contextMenu);--}}
{{--                this.state.contextMenu = contextMenu;--}}
{{--            },--}}

{{--            closeContextMenu() {--}}
{{--                if (this.state.contextMenu) {--}}
{{--                    this.state.contextMenu.remove();--}}
{{--                    this.state.contextMenu = null;--}}
{{--                }--}}
{{--            },--}}

{{--            handleKeyboardShortcuts(e) {--}}
{{--                if (e.key === 'Escape') {--}}
{{--                    this.closeContextMenu();--}}
{{--                }--}}
{{--                // Add other shortcuts as needed--}}
{{--            },--}}

{{--            downloadFile(fileId) {--}}
{{--                const downloadUrl = `{{ route("gallery.file.download", ":id") }}`.replace(':id', fileId);--}}
{{--                window.open(downloadUrl, '_blank');--}}
{{--            },--}}

{{--            copyFileUrl(fileId) {--}}
{{--                fetch(`{{ route("gallery.file.show", ":id") }}`.replace(':id', fileId), {--}}
{{--                    headers: {--}}
{{--                        'Accept': 'application/json',--}}
{{--                        'X-Requested-With': 'XMLHttpRequest'--}}
{{--                    }--}}
{{--                })--}}
{{--                .then(response => response.json())--}}
{{--                .then(data => {--}}
{{--                    if (data.success && data.file && data.file.url) {--}}
{{--                        navigator.clipboard.writeText(data.file.url).then(() => {--}}
{{--                            this.showSuccessNotification('URL copied to clipboard!');--}}
{{--                        }).catch(err => {--}}
{{--                            this.showError('Failed to copy URL.');--}}
{{--                            console.error('Failed to copy URL: ', err);--}}
{{--                        });--}}
{{--                    } else {--}}
{{--                        this.showError('Failed to get file URL.');--}}
{{--                    }--}}
{{--                })--}}
{{--                .catch(error => {--}}
{{--                    this.showError('Error fetching file URL: ' + error.message);--}}
{{--                });--}}
{{--            },--}}

{{--            renameItem(id, type) {--}}
{{--                Swal.fire({--}}
{{--                    title: `Rename ${type === 'file' ? 'File' : 'Folder'}`,--}}
{{--                    input: 'text',--}}
{{--                    inputLabel: 'New Name',--}}
{{--                    inputPlaceholder: 'Enter new name...',--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonText: 'Rename',--}}
{{--                    inputValidator: (value) => {--}}
{{--                        if (!value) return 'You need to enter a new name!';--}}
{{--                        if (!/^[a-zA-Z0-9\-_ .]+$/.test(value)) return 'Name can only contain letters, numbers, spaces, hyphens, underscores, and periods';--}}
{{--                    }--}}
{{--                }).then(result => {--}}
{{--                    if (result.isConfirmed) {--}}
{{--                        const newName = result.value;--}}
{{--                        this.showProgress('Renaming...');--}}
{{--                        fetch(`{{ route("gallery.rename") }}`, {--}}
{{--                            method: 'POST',--}}
{{--                            headers: {--}}
{{--                                'Content-Type': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,--}}
{{--                                'X-Requested-With': 'XMLHttpRequest'--}}
{{--                            },--}}
{{--                            body: JSON.stringify({ id, type, new_name: newName })--}}
{{--                        })--}}
{{--                        .then(response => response.json())--}}
{{--                        .then(data => {--}}
{{--                            if (data.success) {--}}
{{--                                this.showSuccessNotification(data.message);--}}
{{--                                this.refreshContents();--}}
{{--                            } else {--}}
{{--                                throw new Error(data.message || 'Rename failed');--}}
{{--                            }--}}
{{--                        })--}}
{{--                        .catch(error => {--}}
{{--                            this.showError(error.message);--}}
{{--                        })--}}
{{--                        .finally(() => this.hideProgress());--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            moveItem(id, type) {--}}
{{--                Swal.fire({--}}
{{--                    title: `Move ${type === 'file' ? 'File' : 'Folder'}`,--}}
{{--                    input: 'text',--}}
{{--                    inputLabel: 'New Path (e.g., /new-folder)',--}}
{{--                    inputPlaceholder: 'Enter new path...',--}}
{{--                    showCancelButton: true,--}}
{{--                    confirmButtonText: 'Move',--}}
{{--                    inputValidator: (value) => {--}}
{{--                        if (!value) return 'You need to enter a new path!';--}}
{{--                        // Basic path validation, adjust regex as needed--}}
{{--                        if (!/^[a-zA-Z0-9\-_ /]+$/.test(value)) return 'Path can only contain letters, numbers, spaces, hyphens, underscores, and forward slashes';--}}
{{--                    }--}}
{{--                }).then(result => {--}}
{{--                    if (result.isConfirmed) {--}}
{{--                        const newPath = result.value;--}}
{{--                        this.showProgress('Moving...');--}}
{{--                        fetch(`{{ route("gallery.move") }}`, {--}}
{{--                            method: 'POST',--}}
{{--                            headers: {--}}
{{--                                'Content-Type': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,--}}
{{--                                'X-Requested-With': 'XMLHttpRequest'--}}
{{--                            },--}}
{{--                            body: JSON.stringify({ id, type, new_path: newPath })--}}
{{--                        })--}}
{{--                        .then(response => response.json())--}}
{{--                        .then(data => {--}}
{{--                            if (data.success) {--}}
{{--                                this.showSuccessNotification(data.message);--}}
{{--                                this.refreshContents();--}}
{{--                            } else {--}}
{{--                                throw new Error(data.message || 'Move failed');--}}
{{--                            }--}}
{{--                        })--}}
{{--                        .catch(error => {--}}
{{--                            this.showError(error.message);--}}
{{--                        })--}}
{{--                        .finally(() => this.hideProgress());--}}
{{--                    }--}}
{{--                });--}}
{{--            },--}}

{{--            // Initial load--}}
{{--            initialLoad() {--}}
{{--                this.loadContents();--}}
{{--            }--}}
{{--        };--}}

{{--        // Initialize gallery--}}
{{--        gallery.init();--}}
{{--        gallery.initialLoad(); // Load content on page load--}}
{{--    });--}}
{{--</script>--}}
{{--@endsection--}}
