<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteFolderContents;
use App\Jobs\RenameFileJob;
use App\Jobs\RenameFolderJob;
use App\Jobs\ProcessImageConversions;
use App\Jobs\ProcessVideoThumbnail;
use App\Jobs\DeleteMediaFiles;
use App\Jobs\RestoreMediaFiles;
use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GalleryController extends Controller
{
    protected $disk = 'public';
    protected $rootPath = '';
    protected $conversions = [
        'thumb' => ['width' => 150, 'height' => 150, 'quality' => 70],
        'small' => ['width' => 300, 'height' => 300, 'quality' => 75],
        'medium' => ['width' => 800, 'height' => 800, 'quality' => 80],
        'large' => ['width' => 1200, 'height' => 1200, 'quality' => 85],
    ];
    protected $allowedMimeTypes = [
        'image' => ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'],
        'video' => ['mp4', 'mov', 'avi', 'mkv', 'webm'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
    ];
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Get folder contents
     */
    public function index(Request $request)
    {
        return view('dashboard.admin.gallery.index');
    }

    /**
     * Get folder contents (API endpoint)
     */
    public function getContents(Request $request)
    {
        try {
            if (!auth()->check()) {
                \Log::warning('GalleryController@getContents: User not authenticated.');
                return response()->json(['success' => false, 'message' => 'Unauthorized access. User not authenticated.'], 401);
            }

            if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('staff')) {
                \Log::warning('GalleryController@getContents: User authenticated but lacks required roles.', ['user_id' => auth()->id(), 'roles' => auth()->user()->getRoleNames()]);
                return response()->json(['success' => false, 'message' => 'Unauthorized access. User lacks required roles.'], 403);
            }

            $currentPath = $request->get('path', '');
            $searchTerm = $request->get('search', '');
            $isTrash = $request->get('trash', false);
            $page = (int) $request->get('page', 1);
            $perPage = $request->get('per_page');

            // Ensure perPage is a positive integer, default to 10 if invalid
            if (!is_numeric($perPage) || (int)$perPage <= 0) {
                $perPage = 10;
            } else {
                $perPage = (int)$perPage;
            }

            if ($isTrash) {
                return $this->getTrashedItems($request);
            }

            if ($searchTerm) {
                return $this->searchItems($searchTerm, $currentPath, $page, $perPage);
            }

            $result = $this->getFolderContents($currentPath, $page, $perPage);

            \Log::info('GalleryController@getContents: Returning data', [
                'currentPath' => $currentPath,
                'contents_summary' => [
                    'folders_count' => count($result['contents']['folders']),
                    'files_count' => count($result['contents']['files'])
                ],
                'pagination' => $result['pagination']
            ]);
            return response()->json([
                'success' => true,
                'currentPath' => $currentPath,
                'contents' => $result['contents'],
                'pagination' => $result['pagination'],
                'breadcrumbs' => $this->getBreadcrumbs($currentPath)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getContents: ' . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Failed to load gallery contents.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get folder contents
     */
    protected function getFolderContents($path = '', $page = 1, $perPage = 10)
    {
        $contents = ['folders' => [], 'files' => []];

        // Get current folder
        $currentFolder = $path ? MediaFolder::where('path', $path)->first() : null;

        // Get child folders with pagination
        $childFoldersQuery = MediaFolder::withCount(['children', 'media'])
            ->where('parent_id', $currentFolder ? $currentFolder->id : null)
            ->whereNull('deleted_at')
            ->orderBy('name');

        $totalFolders = $childFoldersQuery->count();
        $childFolders = $childFoldersQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        foreach ($childFolders as $folder) {
            $contents['folders'][] = [
                'id' => $folder->id,
                'name' => $folder->name,
                'path' => $folder->path,
                'parent_id' => $folder->parent_id,
                'type' => 'folder',
                'has_children' => $folder->children_count > 0,
                'created_at' => $folder->created_at->format('d-m-Y'),
                'updated_at' => $folder->updated_at->format('d-m-Y'),
                'folder_count' => $folder->children_count,
                'file_count' => $folder->media_count,
                'item_count' => $folder->children_count + $folder->media_count
            ];
        }

        // Get files with pagination
        $filesQuery = Media::query();
        if (empty($path)) {
            $filesQuery->where(function ($q) {
                $q->where('directory', '')->orWhereNull('directory');
            });
        } else {
            $filesQuery->where('directory', $path);
        }

        $filesQuery->whereNull('deleted_at')->orderBy('name');

        $totalFiles = $filesQuery->count();
        $files = $filesQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        foreach ($files as $file) {
            $contents['files'][] = [
                'id' => $file->id,
                'name' => $file->name,
                'file_name' => $file->file_name,
                'path' => $file->directory ? $file->directory . '/' . $file->file_name : $file->file_name,
                'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory . '/' . $file->file_name : $file->file_name),
                'thumb_url' => $this->getThumbUrl($file),
                'type' => 'file',
                'mime_type' => $file->mime_type,
                'size' => $this->formatFileSize($file->size),
                'size_bytes' => $file->size,
                'dimensions' => $file->dimensions ? $file->dimensions['width'] . 'x' . $file->dimensions['height'] : null,
                'created_at' => $file->created_at->format('d-m-Y'),
                'updated_at' => $file->updated_at->format('d-m-Y'),
                'is_featured' => $file->is_featured
            ];
        }

        $totalItems = $totalFolders + $totalFiles;
        $totalPages = ceil($totalItems / $perPage);

        \Log::info('GalleryController@getFolderContents: Returning data', [
            'path' => $path,
            'page' => $page,
            'perPage' => $perPage,
            'totalFolders' => $totalFolders,
            'totalFiles' => $totalFiles,
            'totalItems' => $totalItems
        ]);
        return [
            'contents' => $contents,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalPages,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages
            ]
        ];
    }

    /**
     * Format file size for display
     */
    protected function formatFileSize($bytes)
    {
        if ($bytes === null || $bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    /**
     * Get thumbnail URL for a file
     */
    protected function getThumbUrl($file)
    {
        if (Str::startsWith($file->mime_type, 'image/')) {
            $thumbPath = $file->directory ? $file->directory . '/thumb/' . $file->file_name : 'thumb/' . $file->file_name;
            return Storage::disk($this->disk)->exists($thumbPath)
                ? Storage::disk($this->disk)->url($thumbPath)
                : Storage::disk($this->disk)->url($file->directory ? $file->directory . '/' . $file->file_name : $file->file_name);
        }
        return null;
    }

    /**
     * Get folder item count
     */
    protected function getFolderItemCount($path)
    {
        $fileCount = Media::where('directory', $path)->whereNull('deleted_at')->count();
        $folderCount = MediaFolder::where('path', $path . '%')->whereNull('deleted_at')->count();
        return $fileCount + $folderCount;
    }

    /**
     * Generate breadcrumbs
     */
    protected function getBreadcrumbs($currentPath)
    {
        $breadcrumbs = [['id' => null, 'name' => 'Root', 'path' => '', 'icon' => 'home']];
        if (empty($currentPath)) {
            return $breadcrumbs;
        }

        $currentPath = rtrim($currentPath, '/');
        $parts = explode('/', $currentPath);
        $accumulatedPath = '';

        foreach ($parts as $part) {
            if (empty($part)) continue;
            $accumulatedPath = $accumulatedPath ? "$accumulatedPath/$part" : $part;
            $folder = MediaFolder::where('path', $accumulatedPath)->first();
            if ($folder) {
                $breadcrumbs[] = [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'icon' => 'folder'
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Navigate to parent directory
     */
    public function navigateUp(Request $request)
    {
        $currentPath = $request->input('path', '');
        if (empty($currentPath)) {
            return response()->json(['success' => false, 'message' => 'Already at root directory']);
        }

        $currentPath = rtrim($currentPath, '/');
        $parentPath = dirname($currentPath) === '.' ? '' : dirname($currentPath);

        if (!empty($parentPath) && !MediaFolder::where('path', $parentPath)->exists()) {
            return response()->json(['success' => false, 'message' => 'Parent directory not found']);
        }

        $contents = $this->getFolderContents($parentPath);

        return response()->json([
            'success' => true,
            'currentPath' => $parentPath,
            'contents' => $contents['contents'],
            'pagination' => $contents['pagination'],
            'breadcrumbs' => $this->getBreadcrumbs($parentPath),
            'message' => 'Navigated to parent directory'
        ]);
    }



    /**
     * Create a new folder
     */
    public function createFolder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\-_ ]+$/',
            'current_path' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $parentId = null;
            if ($request->current_path) {
                $parentFolder = MediaFolder::where('path', $request->current_path)->first();
                $parentId = $parentFolder ? $parentFolder->id : null;
            }

            $folder = MediaFolder::create([
                'name' => $request->name,
                'path' => $parentId ? ($parentFolder->path . '/' . Str::slug($request->name)) : Str::slug($request->name),
                'parent_id' => $parentId
            ]);

            return response()->json([
                'success' => true,
                'folder' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'parent_id' => $folder->parent_id,
                    'depth' => $folder->depth ?? 0
                ],
                'message' => 'Folder created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Folder creation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get folder info
     */
    public function getFolderInfo($id)
    {
        $folder = MediaFolder::withTrashed()->findOrFail($id);
        return response()->json([
            'success' => true,
            'folder' => [
                'id' => $folder->id,
                'name' => $folder->name,
                'path' => $folder->path,
                'parent_id' => $folder->parent_id,
                'deleted_at' => $folder->deleted_at?->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Update a folder
     */
    public function updateFolder(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\-_ ]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $folder = MediaFolder::findOrFail($id);
            $oldPath = $folder->path;
            $newFolderName = Str::slug($request->name);
            $parentPath = dirname($oldPath);
            $newPath = $parentPath !== '.' ? "$parentPath/$newFolderName" : $newFolderName;

            if ($folder->name !== $request->name) {
                if (MediaFolder::where('path', $newPath)->where('id', '!=', $id)->exists()) {
                    return response()->json(['success' => false, 'message' => 'A folder with this name already exists in the target location'], 422);
                }
                $folder->name = $request->name;
                $folder->path = $newPath;
                $folder->save();

                // Dispatch job for renaming folder and updating media/subfolder paths
                RenameFolderJob::dispatch($oldPath, $newPath)->onQueue('media');
            }

            return response()->json([
                'success' => true,
                'message' => 'Folder updated successfully',
                'folder' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'parent_id' => $folder->parent_id
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Folder update failed: ' . $e->getMessage(), ['folder_id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update folder: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Upload files
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:' . (1024 * 20), // 20MB max
            'path' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $path = $request->input('path', '');
        $uploadedFiles = [];
        $duplicateFiles = [];

        try {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = strtolower($file->getClientOriginalExtension());
                $fileHash = md5_file($file->getRealPath());

                // Check if a file with the same hash already exists
                $existingMedia = Media::where('file_hash', $fileHash)->first();

                if ($existingMedia) {
                    // If duplicate, add existing media to uploadedFiles and mark as duplicate
                    $uploadedFiles[] = [
                        'id' => $existingMedia->id,
                        'name' => $existingMedia->name,
                        'file_name' => $existingMedia->file_name,
                        'path' => $existingMedia->directory ? $existingMedia->directory . '/' . $existingMedia->file_name : $existingMedia->file_name,
                        'url' => Storage::disk($this->disk)->url($existingMedia->directory ? $existingMedia->directory . '/' . $existingMedia->file_name : $existingMedia->file_name),
                        'thumb_url' => $this->getThumbUrl($existingMedia),
                        'mime_type' => $existingMedia->mime_type,
                        'size' => $this->formatFileSize($existingMedia->size), // Formatted size
                        'size_bytes' => $existingMedia->size, // Raw size in bytes
                        'created_at' => $existingMedia->created_at->format('d-m-Y'),
                        'is_duplicate' => true
                    ];
                    $duplicateFiles[] = $originalName;
                    continue; // Skip to the next file
                }

                $fileName = $this->generateUniqueFilename($path, Str::slug($baseName), $extension);
                $mimeType = $file->getMimeType();
                $size = $file->getSize();

                $storedPath = $file->storeAs($path, $fileName, $this->disk);

                $media = Media::create([
                    'model_type' => null,
                    'model_id' => null,
                    'uuid' => Str::uuid(),
                    'collection_name' => 'default',
                    'name' => $baseName,
                    'file_name' => $fileName,
                    'file_hash' => $fileHash,
                    'mime_type' => $mimeType,
                    'disk' => $this->disk,
                    'conversions_disk' => $this->disk,
                    'size' => $size,
                    'directory' => $path,
                    'order_column' => Media::max('order_column') + 1,
                    'alt_text' => $baseName,
                    'caption' => $baseName,
                    'title' => $baseName,
                    'custom_properties' => [
                        'original_name' => $originalName,
                        'uploaded_by' => auth()->id() ?? null
                    ]
                ]);

                if (Str::startsWith($mimeType, 'image/')) {
                    ProcessImageConversions::dispatch($media, $this->conversions)->onQueue('media');
                } elseif (Str::startsWith($mimeType, 'video/')) {
                    ProcessVideoThumbnail::dispatch($media)->onQueue('media');
                }

                $uploadedFiles[] = [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'path' => $storedPath,
                    'url' => Storage::disk($this->disk)->url($storedPath),
                    'thumb_url' => $this->getThumbUrl($media),
                    'mime_type' => $media->mime_type,
                    'size' => $this->formatFileSize($media->size), // Formatted size
                    'size_bytes' => $media->size, // Raw size in bytes
                    'created_at' => $media->created_at->format('d-m-Y')
                ];
            }

            $message = 'Files uploaded successfully.';
            if (!empty($duplicateFiles)) {
                $message .= ' Some files were already present: ' . implode(', ', $duplicateFiles) . '.';
            }

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            \Log::error('Upload failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'File upload failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename($path, $baseName, $extension)
    {
        $fileName = $extension ? "$baseName.$extension" : $baseName;
        $counter = 1;

        while (
            Media::where('file_name', $fileName)->where('directory', $path)->exists() ||
            Storage::disk($this->disk)->exists($path ? "$path/$fileName" : $fileName)
        ) {
            $fileName = $extension ? "$baseName-$counter.$extension" : "$baseName-$counter";
            $counter++;
        }

        return $fileName;
    }

    /**
     * Move items
     */
    public function moveItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.type' => 'required|in:file,folder',
            'items.*.id' => 'required',
            'target_path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            foreach ($request->items as $item) {
                if ($item['type'] === 'file') {
                    $this->moveFile($item['id'], $request->target_path);
                } else {
                    $this->moveFolder($item['id'], $request->target_path);
                }
            }
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Items moved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to move items: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Move a file
     */
    protected function moveFile($fileId, $targetPath)
    {
        $media = Media::findOrFail($fileId);
        if ($media->directory === $targetPath) {
            return;
        }

        $oldPath = $media->directory ? $media->directory . '/' . $media->file_name : $media->file_name;
        $newPath = $targetPath ? $targetPath . '/' . $media->file_name : $media->file_name;

        if (Storage::disk($this->disk)->exists($newPath)) {
            throw new \Exception("A file with this name already exists in the target folder");
        }

        Storage::disk($this->disk)->move($oldPath, $newPath);
        foreach ($this->conversions as $conversion => $settings) {
            $oldConversionPath = ($media->directory ? $media->directory . '/' . $conversion : $conversion) . '/' . $media->file_name;
            $newConversionPath = ($targetPath ? $targetPath . '/' . $conversion : $conversion) . '/' . $media->file_name;
            if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
            }
        }

        $media->directory = $targetPath;
        $media->save();
    }

    /**
     * Move a folder
     */
    protected function moveFolder($folderId, $targetPath)
    {
        $folder = MediaFolder::findOrFail($folderId);
        $oldPath = $folder->path;
        $newPath = $targetPath ? $targetPath . '/' . basename($oldPath) : basename($oldPath);

        if ($oldPath === $newPath) {
            return;
        }

        if (MediaFolder::where('path', $newPath)->exists()) {
            throw new \Exception("A folder with this name already exists in the target location");
        }

        $folder->path = $newPath;
        $folder->parent_id = $this->getParentIdFromPath($targetPath);
        $folder->save();

        Media::where('directory', $oldPath)->update(['directory' => $newPath]);
        MediaFolder::where('path', 'like', $oldPath . '/%')
            ->update(['path' => DB::raw("REPLACE(path, '$oldPath', '$newPath')")]);

        Storage::disk($this->disk)->move($oldPath, $newPath);
        foreach ($this->conversions as $conversion => $settings) {
            $oldConversionPath = $oldPath . '/' . $conversion;
            $newConversionPath = $newPath . '/' . $conversion;
            if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
            }
        }

        MediaFolder::fixTree();
    }

    /**
     * Get parent folder ID from path
     */
    protected function getParentIdFromPath($path)
    {
        if (empty($path)) {
            return null;
        }

        $parentFolder = MediaFolder::where('path', $path)->first();
        return $parentFolder ? $parentFolder->id : null;
    }

    /**
     * Rename an item (unified endpoint)
     */
    public function rename(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required|in:file,folder',
            'newName' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $id = $request->input('id');
        $type = $request->input('type');
        $newName = $request->input('newName');

        try {
            if ($type === 'file') {
                return $this->renameFile($id, $newName);
            } else {
                return $this->renameFolder($id, $newName);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while renaming the item.'], 500);
        }
    }

    public function paste(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:file,folder',
            'id' => 'required',
            'action' => 'required|in:copy,cut',
            'destination' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $type = $request->input('type');
        $id = $request->input('id');
        $action = $request->input('action');
        $destination = $request->input('destination');

        try {
            if ($type === 'file') {
                $item = Media::findOrFail($id);
                if ($action === 'copy') {
                    $newItem = $item->replicate();
                    $newItem->folder_id = $destination;
                    $newItem->save();
                } else {
                    $item->folder_id = $destination;
                    $item->save();
                }
            }
            else {
                $item = MediaFolder::findOrFail($id);
                if ($action === 'copy') {
                    $newItem = $item->replicate();
                    $newItem->parent_id = $destination;
                    $newItem->save();
                } else {
                    $item->parent_id = $destination;
                    $item->save();
                }
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while pasting the item.'], 500);
        }
    }

    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'restoreContent' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $items = $request->input('items');
        $restoreContent = $request->input('restoreContent');

        try {
            foreach ($items as $itemData) {
                $type = $itemData['type'];
                $id = $itemData['id'];

                if ($type === 'file') {
                    $item = Media::onlyTrashed()->findOrFail($id);
                    $item->restore();
                } else {
                    $item = MediaFolder::onlyTrashed()->findOrFail($id);
                    $item->restore();

                    if ($restoreContent) {
                        // Restore all files and subfolders within the folder
                        Media::onlyTrashed()->where('directory', 'like', $item->path . '%')->restore();
                        MediaFolder::onlyTrashed()->where('path', 'like', $item->path . '%')->restore();
                    }
                }
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while restoring the items.'], 500);
        }
    }

    /**
     * Rename a file
     */
    protected function renameFile($fileId, $newName)
    {
        $media = Media::findOrFail($fileId);
        if ($media->trashed()) {
            return response()->json(['success' => false, 'message' => 'Cannot rename trashed file'], 403);
        }

        $displayName = $newName;
        $newBaseName = Str::slug(pathinfo($newName, PATHINFO_FILENAME));
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
        $newFileName = $extension ? "$newBaseName.$extension" : $newBaseName;

        if ($media->file_name === $newFileName && $media->name === $displayName) {
            return response()->json(['success' => true, 'message' => 'No changes made']);
        }

        try {
            DB::beginTransaction();
            $oldFileName = $media->file_name;
            $media->file_name = $newFileName;
            $media->name = $displayName;
            $media->save();

            RenameFileJob::dispatch($media->fresh(), $oldFileName, $newFileName, $this->conversions)->onQueue('media');
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'File rename has been queued',
                'media' => [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'path' => $media->directory ? $media->directory . '/' . $media->file_name : $media->file_name,
                    'url' => Storage::disk($this->disk)->url($media->directory ? $media->directory . '/' . $media->file_name : $media->file_name)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("File rename failed", ['media_id' => $fileId, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Rename failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Rename a folder
     */
    protected function renameFolder($folderId, $newName)
    {
        $folder = MediaFolder::findOrFail($folderId);
        if ($folder->trashed()) {
            return response()->json(['success' => false, 'message' => 'Cannot rename trashed folder'], 403);
        }

        $displayName = $newName;
        $newFolderName = Str::slug($newName);
        $oldPath = $folder->path;
        $parentPath = dirname($oldPath);
        $newPath = $parentPath !== '.' ? "$parentPath/$newFolderName" : $newFolderName;

        if ($folder->path === $newPath && $folder->name === $displayName) {
            return response()->json(['success' => true, 'message' => 'No changes made']);
        }

        if (MediaFolder::where('path', $newPath)->where('id', '!=', $folder->id)->exists() || Storage::disk($this->disk)->exists($newPath)) {
            return response()->json(['success' => false, 'message' => 'A folder with this name already exists'], 422);
        }

        try {
            DB::beginTransaction();
            $folder->name = $displayName;
            $folder->path = $newPath;
            $folder->save();

            Media::where('directory', $oldPath)->update(['directory' => $newPath]);
            MediaFolder::where('path', 'like', $oldPath . '/%')
                ->update(['path' => DB::raw("REPLACE(path, '$oldPath', '$newPath')")]);

            MediaFolder::fixTree();
            RenameFolderJob::dispatch($oldPath, $newPath)->onQueue('media');
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Folder rename has been queued',
                'folder' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'parent_id' => $folder->parent_id
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Folder rename failed", ['folder_id' => $folderId, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Folder rename failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete items
     */
    public function deleteItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.type' => 'required|in:file,folder',
            'items.*.id' => 'required',
            'permanent' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $permanent = $request->input('permanent', false);
        $deletedItems = [];

        try {
            DB::beginTransaction();
            foreach ($request->items as $item) {
                $deletedItems[] = $item['type'] === 'file'
                    ? $this->deleteFile($item['id'], $permanent)
                    : $this->deleteFolder($item['id'], $permanent);
            }
            DB::commit();

            if ($permanent) {
                DeleteMediaFiles::dispatch($deletedItems, $this->conversions)->onQueue('media');
            }

            return response()->json([
                'success' => true,
                'items' => $deletedItems,
                'message' => $permanent ? 'Items permanently deleted' : 'Items moved to trash'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete items: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a file
     */
    protected function deleteFile($fileId, $permanent = false)
    {
        $media = Media::withTrashed()->findOrFail($fileId);
        if ($permanent) {
            $media->forceDelete();
        } elseif (!$media->trashed()) {
            $media->delete();
            \Log::info('File soft-deleted', ['file_id' => $fileId, 'deleted_at' => $media->deleted_at]);
        } else {
            throw new \Exception('File is already in trash');
        }
        return $media;
    }

    /**
     * Delete a folder
     */
    protected function deleteFolder($folderId, $permanent = false)
    {
        $folder = MediaFolder::withTrashed()->findOrFail($folderId);
        if ($permanent) {
            $files = Media::withTrashed()->where('directory', 'like', $folder->path . '%')->get();
            Media::where('directory', 'like', $folder->path . '%')->forceDelete();
            MediaFolder::where('path', 'like', $folder->path . '%')->forceDelete();
            DeleteFolderContents::dispatch($folder->path, $files, $this->conversions)->onQueue('media');
        } elseif (!$folder->trashed()) {
            MediaFolder::where('path', 'like', $folder->path . '%')->update(['deleted_at' => now()]);
            Media::where('directory', 'like', $folder->path . '%')->update(['deleted_at' => now()]);
            \Log::info('Folder and its contents soft-deleted', ['folder_id' => $folderId, 'path' => $folder->path]);
        } else {
            throw new \Exception('Folder is already in trash');
        }
        return $folder;
    }

    /**
     * Get trashed items
     */
    public function getTrashedItems(Request $request)
    {
        $parentId = $request->input('parent_id', null);
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 10);

        try {
            // Fetch all trashed files and folders
            $allTrashedFiles = Media::onlyTrashed()->get();
            $allTrashedFolders = MediaFolder::onlyTrashed()->withCount(['trashedChildren', 'trashedMedia'])->get();

            $currentTrashedFolder = null;
            if ($parentId) {
                $currentTrashedFolder = $allTrashedFolders->firstWhere('id', $parentId);
            }

            $filteredFolders = collect();
            $filteredFiles = collect();

            if ($currentTrashedFolder) {
                // If inside a trashed folder, show files and folders directly in it
                $filteredFolders = $allTrashedFolders->filter(function ($folder) use ($currentTrashedFolder) {
                    return $folder->parent_id === $currentTrashedFolder->id;
                });
                $filteredFiles = $allTrashedFiles->filter(function ($file) use ($currentTrashedFolder) {
                    return $file->directory === $currentTrashedFolder->path;
                });
            } else {
                // Root trash view:
                // Get IDs of all trashed folders for efficient lookup
                $trashedFolderIds = $allTrashedFolders->pluck('id')->toArray();

                // Filter files: only show files whose direct parent folder is NOT trashed
                $filteredFiles = $allTrashedFiles->filter(function ($file) use ($allTrashedFolders) {
                    // Find the MediaFolder object for the file's directory
                    $parentFolder = $allTrashedFolders->firstWhere('path', $file->directory);
                    // If the parent folder exists AND is trashed, then this file should NOT be shown individually
                    return !($parentFolder && $parentFolder->trashed());
                });

                // Filter folders: only show folders whose direct parent folder is NOT trashed
                $filteredFolders = $allTrashedFolders->filter(function ($folder) use ($allTrashedFolders) {
                    // If the folder has no parent, it's a top-level trashed folder
                    if ($folder->parent_id === null) {
                        return true;
                    }
                    // Find the MediaFolder object for the folder's parent_id
                    $parentFolder = $allTrashedFolders->firstWhere('id', $folder->parent_id);
                    // If the parent folder exists AND is trashed, then this folder should NOT be shown individually
                    return !($parentFolder && $parentFolder->trashed());
                });
            }

            $trashedFiles = $filteredFiles->map(function ($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->name,
                    'type' => 'file',
                    'path' => $file->directory ? $file->directory . '/' . $file->file_name : $file->file_name,
                    'mime_type' => $file->mime_type,
                    'size' => $this->formatFileSize($file->size),
                    'size_bytes' => $file->size,
                    'deleted_at' => $file->deleted_at ? $file->deleted_at->format('d-m-y H:i:s') : null,
                    'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory . '/' . $file->file_name : $file->file_name),
                    'thumb_url' => $this->getThumbUrl($file),
                    'created_at' => $file->created_at ? $file->created_at->format('d-m-y') : null
                ];
            });

            $trashedFolders = $filteredFolders->map(function ($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'type' => 'folder',
                    'path' => $folder->path,
                    'parent_id' => $folder->parent_id,
                    'deleted_at' => $folder->deleted_at ? $folder->deleted_at->format('d-m-y H:i:s') : null,
                    'folder_count' => $folder->trashed_children_count,
                    'file_count' => $folder->trashed_media_count,
                    'item_count' => $folder->trashed_children_count + $folder->trashed_media_count,
                    'created_at' => $folder->created_at ? $folder->created_at->format('d-m-y') : null
                ];
            });

            $goUpFolder = null;
            if ($parentId) {
                $parentOfCurrentTrashedFolder = MediaFolder::withTrashed()->find($currentTrashedFolder->parent_id ?? null);
                $goUpFolder = [
                    'id' => $parentOfCurrentTrashedFolder ? $parentOfCurrentTrashedFolder->id : null,
                    'name' => '..',
                    'type' => 'folder',
                    'path' => $parentOfCurrentTrashedFolder ? $parentOfCurrentTrashedFolder->path : '',
                    'parent_id' => $parentOfCurrentTrashedFolder ? $parentOfCurrentTrashedFolder->parent_id : null,
                    'deleted_at' => null,
                    'folder_count' => 0,
                    'file_count' => 0,
                    'item_count' => 0,
                    'created_at' => null
                ];
            }

            $totalItems = $trashedFiles->count() + $trashedFolders->count();
            $totalPages = ceil($totalItems / $perPage);

            return response()->json([
                'success' => true,
                'contents' => [
                    'files' => $trashedFiles->all(), // Convert to array here
                    'folders' => $goUpFolder ? [$goUpFolder, ...$trashedFolders->all()] : $trashedFolders->all() // Convert to array here
                ],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total_items' => $totalItems,
                    'total_pages' => $totalPages,
                    'has_previous' => $page > 1,
                    'has_next' => $page < $totalPages
                ],
                'parent_id' => $parentId,
                'breadcrumbs' => $this->getBreadcrumbs($currentTrashedFolder ? $currentTrashedFolder->path : '')
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getTrashedItems: ' . $e->getMessage(), ['exception' => $e, 'parent_id' => $parentId]);
            return response()->json(['success' => false, 'message' => 'Error loading trash contents', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get trash context menu options
     */
    protected function getTrashContextMenuOptions()
    {
        return [
            'restore' => ['label' => 'Restore', 'icon' => 'trash-restore', 'action' => 'restoreItems', 'available' => true],
            'delete' => ['label' => 'Delete Permanently', 'icon' => 'trash-alt', 'action' => 'deleteItems', 'available' => true, 'permanent' => true],
            'separator1' => ['type' => 'separator'],
            'emptyTrash' => ['label' => 'Empty Trash', 'icon' => 'broom', 'action' => 'emptyTrash', 'available' => true],
            'separator2' => ['type' => 'separator'],
            'refresh' => ['label' => 'Refresh', 'icon' => 'sync', 'action' => 'refresh', 'available' => true]
        ];
    }

    /**
     * Restore items from trash
     */
    public function restoreItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.type' => 'required|in:file,folder',
            'items.*.id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $restoredItems = [];

        try {
            DB::beginTransaction();
            foreach ($request->items as $item) {
                $restoredItems[] = $item['type'] === 'file'
                    ? $this->restoreFile($item['id'])
                    : $this->restoreFolder($item['id']);
            }
            DB::commit();

            RestoreMediaFiles::dispatch($restoredItems, $this->conversions)->onQueue('media');

            return response()->json([
                'success' => true,
                'items' => $restoredItems,
                'message' => 'Items restored successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to restore items: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Restore a file from trash
     */
    protected function restoreFile($fileId)
    {
        $media = Media::onlyTrashed()->findOrFail($fileId);
        $media->restore();
        return $media;
    }

    /**
     * Restore a folder from trash
     */
    protected function restoreFolder($folderId)
    {
        $folder = MediaFolder::onlyTrashed()->findOrFail($folderId);
        $folder->restore();
        Media::onlyTrashed()->where('directory', $folder->path)->restore();
        MediaFolder::onlyTrashed()->where('path', 'like', $folder->path . '/%')->restore();
        return $folder;
    }

    /**
     * Empty the trash
     */
    public function emptyTrash()
    {
        try {
            DB::beginTransaction();
            $files = Media::onlyTrashed()->get();
            $folders = MediaFolder::onlyTrashed()->get();
            Media::onlyTrashed()->forceDelete();
            MediaFolder::onlyTrashed()->forceDelete();
            DB::commit();

            DeleteMediaFiles::dispatch($files, $this->conversions)->onQueue('media');
            foreach ($folders as $folder) {
                DeleteFolderContents::dispatch($folder->path, [], $this->conversions)->onQueue('media');
            }

            return response()->json(['success' => true, 'message' => 'Trash has been emptied']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to empty trash: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Search items
     */
    protected function searchItems($searchTerm, $currentPath = '', $page = 1, $perPage = 10)
    {
        $results = ['folders' => [], 'files' => []];

        $foldersQuery = MediaFolder::where('name', 'like', "%$searchTerm%")
            ->whereNull('deleted_at')
            ->when($currentPath, fn($q) => $q->where('path', 'like', "$currentPath%"))
            ->orderBy('name');

        $totalFolders = $foldersQuery->count();
        $folders = $foldersQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        foreach ($folders as $folder) {
            $results['folders'][] = [
                'id' => $folder->id,
                'name' => $folder->name,
                'path' => $folder->path,
                'type' => 'folder',
                'created_at' => $folder->created_at->format('d-m-Y'),
                'updated_at' => $folder->updated_at->format('d-m-Y')
            ];
        }

        $filesQuery = Media::where('name', 'like', "%$searchTerm%")
            ->whereNull('deleted_at')
            ->when($currentPath, fn($q) => $q->where('directory', 'like', "$currentPath%"))
            ->orderBy('name');

        $totalFiles = $filesQuery->count();
        $files = $filesQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        foreach ($files as $file) {
            $results['files'][] = [
                'id' => $file->id,
                'name' => $file->name,
                'file_name' => $file->file_name,
                'path' => $file->directory ? $file->directory . '/' . $file->file_name : $file->file_name,
                'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory . '/' . $file->file_name : $file->file_name),
                'thumb_url' => $this->getThumbUrl($file),
                'type' => 'file',
                'mime_type' => $file->mime_type,
                'size' => $this->formatFileSize($file->size),
                'dimensions' => $file->dimensions ? $file->dimensions['width'] . 'x' . $file->dimensions['height'] : null,
                'created_at' => $file->created_at->format('d-m-Y'),
                'updated_at' => $file->updated_at->format('d-m-Y')
            ];
        }

        $totalItems = $totalFolders + $totalFiles;
        $totalPages = ceil($totalItems / $perPage);

        return response()->json([
            'success' => true,
            'searchTerm' => $searchTerm,
            'contents' => $results,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalPages,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages
            ]
        ]);
    }

    /**
     * Copy items to clipboard
     */
    public function copyItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.type' => 'required|in:file,folder',
            'items.*.id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        session(['clipboard' => [
            'action' => 'copy',
            'items' => $request->items,
            'timestamp' => now()
        ]]);

        return response()->json(['success' => true, 'message' => 'Items copied to clipboard']);
    }

    /**
     * Cut items to clipboard
     */
    public function cutItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.type' => 'required|in:file,folder',
            'items.*.id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        session(['clipboard' => [
            'action' => 'cut',
            'items' => $request->items,
            'timestamp' => now()
        ]]);

        return response()->json(['success' => true, 'message' => 'Items cut to clipboard']);
    }

    /**
     * Paste items from clipboard
     */
    public function pasteItems(Request $request)
    {
        return $this->batchPaste($request); // Delegate to batchPaste for consistency
    }

    /**
     * Batch paste items (copy/cut)
     */
    public function batchPaste(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_path' => 'required|string',
            'items' => 'sometimes|array',
            'items.*.type' => 'sometimes|required|in:file,folder',
            'items.*.id' => 'sometimes|required',
            'action' => 'sometimes|in:copy,cut'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $clipboard = $request->items && $request->action ? [
            'action' => $request->action,
            'items' => $request->items
        ] : session('clipboard');

        if (!$clipboard) {
            return response()->json(['success' => false, 'message' => 'Clipboard is empty'], 400);
        }

        $targetPath = $request->target_path;

        try {
            DB::beginTransaction();
            $newItems = [];
            foreach ($clipboard['items'] as $item) {
                if ($item['type'] === 'file') {
                    $newItems[] = $clipboard['action'] === 'copy'
                        ? $this->copyFile($item['id'], $targetPath)
                        : $this->moveFile($item['id'], $targetPath);
                } else {
                    $newItems[] = $clipboard['action'] === 'copy'
                        ? $this->copyFolder($item['id'], $targetPath)
                        : $this->moveFolder($item['id'], $targetPath);
                }
            }
            if ($clipboard['action'] === 'cut') {
                session()->forget('clipboard');
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $clipboard['action'] === 'copy' ? 'Items copied successfully' : 'Items moved successfully',
                'items' => $newItems
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to paste items: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Copy a file
     */
    protected function copyFile($fileId, $targetPath)
    {
        $media = Media::findOrFail($fileId);
        if ($media->trashed()) {
            return null;
        }

        $newBaseName = pathinfo($media->file_name, PATHINFO_FILENAME);
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
        $newFileName = $this->generateUniqueFilename($targetPath, $newBaseName, $extension);

        $oldPath = $media->directory ? $media->directory . '/' . $media->file_name : $media->file_name;
        $newPath = $targetPath ? $targetPath . '/' . $newFileName : $newFileName;

        Storage::disk($this->disk)->copy($oldPath, $newPath);
        foreach ($this->conversions as $conversion => $settings) {
            $oldConversionPath = ($media->directory ? $media->directory . '/' . $conversion : $conversion) . '/' . $media->file_name;
            $newConversionPath = ($targetPath ? $targetPath . '/' . $conversion : $conversion) . '/' . $newFileName;
            if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                Storage::disk($this->disk)->copy($oldConversionPath, $newConversionPath);
            }
        }

        $newMedia = $media->replicate();
        $newMedia->file_name = $newFileName;
        $newMedia->directory = $targetPath;
        $newMedia->uuid = Str::uuid();
        $newMedia->save();

        return [
            'id' => $newMedia->id,
            'name' => $newMedia->name,
            'file_name' => $newMedia->file_name,
            'path' => $newPath,
            'url' => Storage::disk($this->disk)->url($newPath),
            'thumb_url' => $this->getThumbUrl($newMedia)
        ];
    }

    /**
     * Copy a folder
     */
    protected function copyFolder($folderId, $targetPath)
    {
        $folder = MediaFolder::findOrFail($folderId);
        if ($folder->trashed()) {
            return null;
        }

        $newFolderName = $folder->name;
        $newPath = $targetPath ? "$targetPath/$newFolderName" : $newFolderName;

        if (MediaFolder::where('path', $newPath)->exists()) {
            $newPath = $this->generateUniqueFolderPath($targetPath, $newFolderName);
            $newFolderName = basename($newPath);
        }

        $newFolder = MediaFolder::create([
            'name' => $folder->name,
            'path' => $newPath,
            'parent_id' => $this->getParentIdFromPath($targetPath)
        ]);

        Storage::disk($this->disk)->makeDirectory($newPath);
        $files = Media::where('directory', $folder->path)->whereNull('deleted_at')->get();
        foreach ($files as $file) {
            $this->copyFile($file->id, $newPath);
        }

        $subfolders = MediaFolder::where('parent_id', $folder->id)->whereNull('deleted_at')->get();
        foreach ($subfolders as $subfolder) {
            $this->copyFolder($subfolder->id, $newPath);
        }

        return [
            'id' => $newFolder->id,
            'name' => $newFolder->name,
            'path' => $newFolder->path,
            'parent_id' => $newFolder->parent_id
        ];
    }

    /**
     * Get item properties
     */
    public function getProperties($type, $id)
    {
        try {
            if ($type === 'file') {
                $item = Media::withTrashed()->findOrFail($id);
                $properties = [
                    'name' => $item->name,
                    'type' => 'File',
                    'size' => $this->formatFileSize($item->size),
                    'location' => $item->directory ?: 'Root',
                    'created' => $item->created_at->format('M d, Y H:i'),
                    'modified' => $item->updated_at->format('M d, Y H:i'),
                    'mime_type' => $item->mime_type,
                    'dimensions' => $item->dimensions ? $item->dimensions['width'] . ' × ' . $item->dimensions['height'] : 'N/A',
                    'status' => $item->trashed() ? 'In Trash' : 'Active'
                ];
            } else {
                $item = MediaFolder::withTrashed()->findOrFail($id);
                $properties = [
                    'name' => $item->name,
                    'type' => 'Folder',
                    'size' => $this->formatFileSize($this->getFolderSize($item->path)),
                    'location' => $item->parent ? $item->parent->path : 'Root',
                    'created' => $item->created_at->format('M d, Y H:i'),
                    'modified' => $item->updated_at->format('M d, Y H:i'),
                    'item_count' => $this->getFolderItemCount($item->path),
                    'status' => $item->trashed() ? 'In Trash' : 'Active'
                ];
            }

            return response()->json(['success' => true, 'properties' => $properties, 'item' => $item]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to get properties: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Calculate folder size
     */
    protected function getFolderSize($path)
    {
        return Media::where('directory', 'like', $path . '%')->sum('size');
    }

    /**
     * Show folder details
     */
    public function showFolder($id)
    {
        $folder = MediaFolder::with(['parent'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'folder' => [
                'id' => $folder->id,
                'name' => $folder->name,
                'path' => $folder->path,
                'parent_id' => $folder->parent_id
            ],
            'breadcrumbs' => $this->getBreadcrumbs($folder->path)
        ]);
    }

    /**
     * Show file details
     */
    public function showFile($id)
    {
        try {
            if (empty($id)) {
                return response()->json(['success' => false, 'message' => 'File ID is missing.'], 400);
            }
            $file = Media::withTrashed()->findOrFail($id);

            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $file->id,
                    'name' => $file->name,
                    'file_name' => $file->file_name,
                    'path' => $file->directory ? $file->directory . '/' . $file->file_name : $file->file_name,
                    'url' => Storage::disk($this->disk)->url(
                        $file->directory ? $file->directory . '/' . $file->file_name : $file->file_name
                    ),
                    'thumb_url' => $this->getThumbUrl($file),
                    'mime_type' => $file->mime_type,
                    'size' => $this->formatFileSize($file->size),

                    // ✅ Add these raw fields for JS compatibility:
                    'size_bytes' => $file->size,
                    'created_at' => $file->created_at->format('d-m-Y'),
                    'dimensions' => $file->dimensions ? [
                        'width' => $file->dimensions['width'],
                        'height' => $file->dimensions['height']
                    ] : null,
                ],
                'properties' => $this->getFileProperties($file)
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        } catch (\Exception $e) {
            \Log::error('Error in showFile: ' . $e->getMessage(), ['exception' => $e, 'file_id' => $id]);
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
        }
    }


    /**
     * Get file properties
     */
    protected function getFileProperties(Media $file)
    {
        return [
            'name' => $file->name,
            'type' => 'File',
            'size' => $this->formatFileSize($file->size),
            'location' => $file->directory ?: 'Root',
            'created' => $file->created_at->format('M d, Y H:i'),
            'modified' => $file->updated_at->format('M d, Y H:i'),
            'mime_type' => $file->mime_type,
            'dimensions' => $file->dimensions ? $file->dimensions['width'] . 'x' . $file->dimensions['height'] : 'N/A'
        ];
    }

    /**
     * Get file details for insertion
     */
    public function getFileForInsertion($id)
    {
        try {
            $file = Media::withTrashed()->findOrFail($id);

            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $file->id,
                    'name' => $file->name,
                    'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory . '/' . $file->file_name : $file->file_name),
                    'thumb_url' => $this->getThumbUrl($file),
                    'type' => 'file',
                    'mime_type' => $file->mime_type,
                    'size' => $this->formatFileSize($file->size),
                    'dimensions' => $file->dimensions ? $file->dimensions['width'] . 'x' . $file->dimensions['height'] : null,
                    'created_at' => $file->created_at->format('d-m-Y')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'File not found or error occurred'], 404);
        }
    }

    /**
     * Fetch multiple files for insertion (batch endpoint)
     */
    public function getFilesForInsertion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_ids' => 'required|array',
            'file_ids.*' => 'exists:media,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $files = Media::whereIn('id', $request->file_ids)
                ->whereNull('deleted_at')
                ->get()
                ->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->name,
                        'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory . '/' . $file->file_name : $file->file_name),
                        'thumb_url' => $this->getThumbUrl($file),
                        'type' => 'file',
                        'mime_type' => $file->mime_type,
                        'size' => $this->formatFileSize($file->size),
                        'dimensions' => $file->dimensions ? $file->dimensions['width'] . 'x' . $file->dimensions['height'] : null,
                        'created_at' => $file->created_at->format('d-m-Y')
                    ];
                });

            if ($files->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No valid files found'], 404);
            }

            return response()->json(['success' => true, 'files' => $files]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching files: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download a file
     */
    public function downloadFile($id)
    {
        try {
            $media = Media::findOrFail($id);
            if ($media->trashed()) {
                return response()->json(['success' => false, 'message' => 'Cannot download trashed file'], 403);
            }
            $path = $media->directory ? $media->directory . '/' . $media->file_name : $media->file_name;
            return Storage::disk($this->disk)->download($path, $media->name);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'File not found or error occurred'], 404);
        }
    }

    /**
     * Generate a public URL for a media item
     */
    public function generateUrl($id)
    {
        try {
            $media = Media::findOrFail($id);
            return response()->json([
                'success' => true,
                'url' => Storage::disk($this->disk)->url($media->directory ? $media->directory . '/' . $media->file_name : $media->file_name)
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }
    }

    /**
     * Set a media item as featured
     */
    public function setFeatured($id)
    {
        try {
            DB::beginTransaction();
            Media::where('is_featured', true)->update(['is_featured' => false]);
            $media = Media::findOrFail($id);
            $media->is_featured = true;
            $media->save();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Media set as featured', 'media' => $media]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to set featured: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove featured status from a media item
     */
    public function removeFeatured($id)
    {
        try {
            $media = Media::findOrFail($id);
            $media->is_featured = false;
            $media->save();
            return response()->json(['success' => true, 'message' => 'Featured status removed', 'media' => $media]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to remove featured status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Force delete an item
     */
    public function forceDelete($id)
    {
        try {
            $item = Media::withTrashed()->findOrFail($id);
            $item->forceDelete();
            DeleteMediaFiles::dispatch([$item], $this->conversions)->onQueue('media');
            return response()->json(['success' => true, 'message' => 'Item permanently deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Build context menu options (helper method)
     */
    protected function _buildContextMenuOptions($currentPath, $selectedItems, $isTrash, $clipboardContent = null)
    {
        $hasSelection = !empty($selectedItems);
        $singleSelection = count($selectedItems) === 1;
        $isRoot = empty($currentPath);
        $hasFileSelection = collect($selectedItems)->contains(fn($item) => $item['type'] === 'file');
        $hasFolderSelection = collect($selectedItems)->contains(fn($item) => $item['type'] === 'folder');

        $options = [
            [
                'label' => 'New Folder',
                'icon' => 'folder-plus',
                'action' => 'createFolder',
                'available' => !$isTrash
            ],
            [
                'label' => 'Upload Files',
                'icon' => 'upload',
                'action' => 'uploadFiles',
                'available' => !$isTrash
            ],
            [
                'label' => 'Go Up',
                'icon' => 'level-up-alt',
                'action' => 'navigateUp',
                'available' => !$isRoot && !$isTrash
            ],
            ['type' => 'separator'],
            [
                'label' => 'Cut',
                'icon' => 'cut',
                'action' => 'cutItems',
                'available' => $hasSelection && !$isTrash,
                'shortcut' => 'Ctrl+X'
            ],
            [
                'label' => 'Copy',
                'icon' => 'copy',
                'action' => 'copyItems',
                'available' => $hasSelection && !$isTrash,
                'shortcut' => 'Ctrl+C'
            ],
            [
                'label' => 'Paste',
                'icon' => 'paste',
                'action' => 'pasteItems',
                'available' => ($clipboardContent && !$isTrash),
                'shortcut' => 'Ctrl+V'
            ],
            ['type' => 'separator'],
            [
                'label' => 'Rename',
                'icon' => 'i-cursor',
                'action' => 'renameItem',
                'available' => $singleSelection && !$isTrash,
                'shortcut' => 'F2'
            ],
            [
                'label' => 'Download',
                'icon' => 'download',
                'action' => 'downloadItem',
                'available' => $singleSelection && $hasFileSelection && !$isTrash
            ],
            [
                'label' => $isTrash ? 'Delete Permanently' : 'Delete',
                'icon' => 'trash',
                'action' => 'deleteItems',
                'available' => $hasSelection,
                'danger' => true,
                'shortcut' => 'Del'
            ],
            [
                'label' => 'Restore',
                'icon' => 'trash-restore',
                'action' => 'restoreItems',
                'available' => $isTrash && $hasSelection
            ],
            ['type' => 'separator'],
            [
                'label' => 'Select All',
                'icon' => 'check-square',
                'action' => 'selectAll',
                'available' => true,
                'shortcut' => 'Ctrl+A'
            ],
            [
                'label' => 'Properties',
                'icon' => 'info-circle',
                'action' => 'showProperties',
                'available' => $singleSelection
            ]
        ];

        return array_values(array_filter($options, fn($option) => !isset($option['available']) || $option['available']));
    }

    /**
     * Get context menu options (API endpoint)
     */
    public function getContextMenuOptions(Request $request)
    {
        $currentPath = $request->input('current_path', '');
        $selectedItems = $request->input('selected_items', []);
        $isTrash = $request->input('is_trash', false);
        $clipboardContent = session('clipboard');

        $options = $this->_buildContextMenuOptions($currentPath, $selectedItems, $isTrash, $clipboardContent);

        return response()->json(['success' => true, 'options' => $options]);
    }

    /**
     * Generate unique folder path
     */
    protected function generateUniqueFolderPath($basePath, $folderName)
    {
        $path = $basePath ? "$basePath/$folderName" : $folderName;
        $counter = 1;

        while (MediaFolder::where('path', $path)->exists()) {
            $path = $basePath ? "$basePath/$folderName-$counter" : "$folderName-$counter";
            $counter++;
        }

        return $path;
    }
}
