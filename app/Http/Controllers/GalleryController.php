<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Media;
use App\Models\MediaFolder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GalleryController extends Controller
{
    protected $disk = 'public';
    protected $rootPath = ''; // Empty for root
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

        // Ensure root folder exists using proper nested set method
        if (!MediaFolder::root()->exists()) {
            MediaFolder::create([
                'name' => 'Root',
                'path' => '',
                'parent_id' => null
            ])->saveAsRoot();
        }
    }

    /**
     * Get folder contents with parent/child relationships
     */
    public function index(Request $request)
    {
        $currentPath = $request->get('path', '');
        $searchTerm = $request->get('search', '');
        $isTrash = $request->get('trash', false);

        if ($isTrash) {
            return $this->getTrashedItems();
        }

        if ($searchTerm) {
            return $this->searchItems($searchTerm, $currentPath);
        }

        $contents = $this->getFolderContents($currentPath);

        return response()->json([
            'success' => true,
            'currentPath' => $currentPath,
            'contents' => $contents,
            'breadcrumbs' => $this->getBreadcrumbs($currentPath),
            'contextMenu' => $this->getContextMenuOptions($currentPath)
        ]);
    }

    /**
     * Get detailed folder contents
     */
    protected function getFolderContents($path = '')
    {
        $contents = [
            'folders' => [],
            'files' => []
        ];

        // Normalize the path (remove leading/trailing slashes)
        $path = trim($path, '/');

        // 1. Get files from storage (filesystem)
        $storagePath = $path === '' ? '' : $path.'/';
        $storageFolders = [];

        try {
            $storageFiles = Storage::disk('public')->files($storagePath);
            $storageFolders = Storage::disk('public')->directories($storagePath);
        } catch (\Exception $e) {
            \Log::error("Error accessing storage path {$storagePath}: " . $e->getMessage());
        }

        // 2. Get folders from database that match this path structure
        $dbFolders = MediaFolder::query()
            ->where(function($query) use ($path) {
                // Folders directly in this path
                if ($path === '') {
                    $query->whereNull('parent_id');
                } else {
                    $parent = MediaFolder::where('path', $path)->first();
                    $query->where('parent_id', $parent ? $parent->id : -1);
                }
            })
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        // 3. Merge filesystem folders with database folders
        $allFolders = collect();

        // Add database folders first
        foreach ($dbFolders as $folder) {
            $allFolders->push([
                'type' => 'folder',
                'id' => $folder->id,
                'name' => $folder->name,
                'path' => $folder->path,
                'source' => 'database'
            ]);
        }

        // Add filesystem folders that aren't in database
        foreach ($storageFolders as $folderPath) {
            $folderName = basename($folderPath);

            if (!$allFolders->where('name', $folderName)->first()) {
                $allFolders->push([
                    'type' => 'folder',
                    'id' => null, // No database ID
                    'name' => $folderName,
                    'path' => $folderPath,
                    'source' => 'filesystem'
                ]);
            }
        }

        // 4. Build the folders array for response
        foreach ($allFolders as $folder) {
            $contents['folders'][] = [
                'id' => $folder['id'],
                'name' => $folder['name'],
                'path' => $folder['path'],
                'type' => 'folder',
                'has_children' => $this->folderHasContents($folder['path']),
                'created_at' => $folder['source'] === 'database' ? $dbFolders->firstWhere('path', $folder['path'])->created_at : null,
                'updated_at' => $folder['source'] === 'database' ? $dbFolders->firstWhere('path', $folder['path'])->updated_at : null,
                'item_count' => $this->getFolderItemCount($folder['path'])
            ];
        }

        // 5. Get files from both database and filesystem
        $dbFiles = Media::where('directory', $path)
            ->whereNull('deleted_at')
            ->get();

        foreach ($dbFiles as $file) {
            $contents['files'][] = [
                'id' => $file->id,
                'name' => $file->name,
                'file_name' => $file->file_name,
                'path' => $file->directory ? $file->directory.'/'.$file->file_name : $file->file_name,
                'url' => $file->url,
                'thumb_url' => $file->thumb_url,
                'type' => 'file',
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'dimensions' => $file->dimensions,
                'is_featured' => $file->is_featured,
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at
            ];
        }

        // Add files from filesystem that aren't in database
        foreach ($storageFiles as $filePath) {
            $fileName = basename($filePath);

            if (!$dbFiles->where('file_name', $fileName)->first()) {
                $contents['files'][] = [
                    'id' => null,
                    'name' => pathinfo($fileName, PATHINFO_FILENAME),
                    'file_name' => $fileName,
                    'path' => $filePath,
                    'url' => Storage::disk('public')->url($filePath),
                    'type' => 'file',
                    'mime_type' => Storage::disk('public')->mimeType($filePath),
                    'size' => Storage::disk('public')->size($filePath),
                    'created_at' => null,
                    'updated_at' => null
                ];
            }
        }

        return $contents;
    }

    protected function folderHasContents($path)
    {
        // Check database
        $hasDbChildren = MediaFolder::where('parent_id', function($query) use ($path) {
            $query->select('id')
                ->from('media_folders')
                ->where('path', $path);
        })->exists();

        // Check filesystem
        try {
            $hasFsChildren = count(Storage::disk('public')->directories($path)) > 0 ||
                count(Storage::disk('public')->files($path)) > 0;
        } catch (\Exception $e) {
            $hasFsChildren = false;
        }

        return $hasDbChildren || $hasFsChildren;
    }

    /**
     * Get parent folder ID from path
     */
    protected function getParentIdFromPath($path)
    {
        if (empty($path)) {
            return null;
        }

        $parts = explode('/', $path);
        $parentPath = implode('/', array_slice($parts, 0, -1));

        $folder = MediaFolder::where('path', $parentPath)->first();
        return $folder ? $folder->id : null;
    }

    /**
     * Count items in a folder
     */
    protected function getFolderItemCount($path)
    {
        $fileCount = Media::where('directory', $path)->count();
        $folderCount = MediaFolder::where('path', 'like', $path.'/%')
            ->whereRaw("path NOT LIKE ?", [$path.'/%/%']) // Only direct children
            ->count();

        return $fileCount + $folderCount;
    }

    /**
     * Generate breadcrumbs for navigation
     */
    protected function getBreadcrumbs($currentPath)
    {
        $breadcrumbs = [];

        // Root breadcrumb
        $breadcrumbs[] = [
            'name' => 'Root',
            'path' => '',
            'icon' => 'home'
        ];

        if (empty($currentPath)) {
            return $breadcrumbs;
        }

        $parts = explode('/', $currentPath);
        $accumulatedPath = '';

        foreach ($parts as $part) {
            $accumulatedPath = $accumulatedPath ? "$accumulatedPath/$part" : $part;
            $folder = MediaFolder::where('path', $accumulatedPath)->first();

            if ($folder) {
                $breadcrumbs[] = [
                    'name' => $folder->name,
                    'path' => $accumulatedPath,
                    'icon' => 'folder'
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Get context menu options based on current path and selection
     */
    protected function getContextMenuOptions($currentPath = '', $selectedItems = [])
    {
        $hasSelection = !empty($selectedItems);
        $isRoot = empty($currentPath);

        $options = [
            'newFolder' => [
                'label' => 'New Folder',
                'icon' => 'folder-plus',
                'action' => 'createFolder',
                'available' => true
            ],
            'upload' => [
                'label' => 'Upload Files',
                'icon' => 'upload',
                'action' => 'uploadFiles',
                'available' => true
            ],
            'goUp' => [
                'label' => 'Go Up',
                'icon' => 'level-up-alt',
                'action' => 'navigateUp',
                'available' => !$isRoot
            ],
            'refresh' => [
                'label' => 'Refresh',
                'icon' => 'sync',
                'action' => 'refresh',
                'available' => true
            ],
            'separator1' => ['type' => 'separator'],
            'cut' => [
                'label' => 'Cut',
                'icon' => 'cut',
                'action' => 'cutItems',
                'available' => $hasSelection,
                'shortcut' => 'Ctrl+X'
            ],
            'copy' => [
                'label' => 'Copy',
                'icon' => 'copy',
                'action' => 'copyItems',
                'available' => $hasSelection,
                'shortcut' => 'Ctrl+C'
            ],
            'paste' => [
                'label' => 'Paste',
                'icon' => 'paste',
                'action' => 'pasteItems',
                'available' => session()->has('clipboard'),
                'shortcut' => 'Ctrl+V'
            ],
            'separator2' => ['type' => 'separator'],
            'rename' => [
                'label' => 'Rename',
                'icon' => 'i-cursor',
                'action' => 'renameItem',
                'available' => $hasSelection && count($selectedItems) === 1,
                'shortcut' => 'F2'
            ],
            'delete' => [
                'label' => 'Delete',
                'icon' => 'trash',
                'action' => 'deleteItems',
                'available' => $hasSelection,
                'shortcut' => 'Del'
            ],
            'separator3' => ['type' => 'separator'],
            'selectAll' => [
                'label' => 'Select All',
                'icon' => 'check-square',
                'action' => 'selectAll',
                'available' => true,
                'shortcut' => 'Ctrl+A'
            ],
            'properties' => [
                'label' => 'Properties',
                'icon' => 'info-circle',
                'action' => 'showProperties',
                'available' => $hasSelection && count($selectedItems) === 1
            ]
        ];

        return array_filter($options, function($option) {
            return !isset($option['available']) || $option['available'];
        });
    }

    /**
     * Create a new folder
     */
    public function createFolder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9-_ ]+$/',
            'parent' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $parentPath = $request->input('parent', '');
        $folderName = Str::slug($request->input('name'));
        $fullPath = $parentPath ? "$parentPath/$folderName" : $folderName;

        if (MediaFolder::where('path', $fullPath)->exists()) {
            return response()->json(['error' => 'Folder already exists'], 409);
        }

        DB::beginTransaction();

        try {
            $parentId = $this->getParentIdFromPath($parentPath);
            $parent = $parentId ? MediaFolder::find($parentId) : null;

            // Create the folder using proper nested set methods
            $folder = new MediaFolder([
                'name' => $request->input('name'),
                'path' => $fullPath,
            ]);

            if ($parent) {
                $folder->appendToNode($parent)->save();
            } else {
                $folder->saveAsRoot();
            }

            // Create physical folder
            Storage::disk($this->disk)->makeDirectory($this->rootPath.'/'.$fullPath);

            DB::commit();

            return response()->json([
                'success' => true,
                'folder' => $folder,
                'message' => 'Folder created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Folder creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload files to the gallery
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:' . (1024 * 20), // 20MB max
            'path' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->input('path', '');
        $folder = $request->input('folder', '');
        $folderRecord = $this->ensureFolderRecordExists($folder); // This creates folder if needed
        $folderPath = $this->rootPath . ($path ? '/' . $path : '');
        $uploadedFiles = [];
        $currentMaxOrder = Media::max('order_column') ?? 0;

        DB::beginTransaction();

        try {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $originalBaseName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = strtolower($file->getClientOriginalExtension());
                $baseName = Str::slug($originalBaseName);
                $fileName = $this->generateUniqueFilename($path, $baseName, $extension);

                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();
                $fileHash = md5_file($file->getRealPath());

                // Check for duplicates
                if ($existingFile = Media::where('file_hash', $fileHash)->first()) {
                    $uploadedFiles[] = $existingFile;
                    continue;
                }

                // Store the file
                $storedPath = $file->storeAs(
                    $folderPath,
                    $fileName,
                    $this->disk
                );

                // Create media record
                $media = new Media([
                    'uuid' => Str::uuid(),
                    'collection_name' => 'gallery',
                    'name' => $originalBaseName,
                    'file_name' => $fileName,
                    'mime_type' => $mimeType,
                    'size' => $fileSize,
                    'disk' => $this->disk,
                    'conversions_disk' => $this->disk,
                    'directory' => $folder,
                    'file_hash' => $fileHash,
                    'manipulations' => [],
                    'custom_properties' => [],
                    'responsive_images' => [],
                    'order_column' => ++$currentMaxOrder,
                    'alt_text' => $originalBaseName,
                ]);

                // Process image/video
                if (Str::startsWith($mimeType, 'image/')) {
                    $this->processImage($file, $path, $fileName, $media);
                } elseif (Str::startsWith($mimeType, 'video/')) {
                    $this->processVideo($file, $path, $fileName, $media);
                }

                $media->save();
                $uploadedFiles[] = $media;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles,
                'message' => 'Files uploaded successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function ensureFolderRecordExists($folderPath)
    {
        if (empty($folderPath)) {
            return MediaFolder::firstOrCreate(
                ['path' => ''],
                ['name' => 'Root', 'parent_id' => null]
            );
        }

        $parts = explode('/', $folderPath);
        $currentPath = '';
        $parentId = null;

        foreach ($parts as $part) {
            $currentPath = $currentPath ? "$currentPath/$part" : $part;

            $folder = MediaFolder::firstOrCreate(
                ['path' => $currentPath],
                [
                    'name' => $part,
                    'parent_id' => $parentId,
                    'lft' => 0,
                    'rgt' => 0,
                    'depth' => substr_count($currentPath, '/')
                ]
            );

            $parentId = $folder->id;
        }

        MediaFolder::fixTree();
        return $folder;
    }

    /**
     * Process image files and create conversions
     */
    protected function processImage($file, $path, $fileName, $media)
    {
        try {
            $image = $this->imageManager->read($file->getRealPath());
            $dimensions = ['width' => $image->width(), 'height' => $image->height()];
            $media->dimensions = $dimensions;

            $conversions = [];
            $extension = strtolower($file->getClientOriginalExtension());

            foreach ($this->conversions as $conversionName => $settings) {
                $conversionPath = $this->rootPath . ($path ? '/' . $path : '') . '/' . $conversionName;

                if (!Storage::disk($this->disk)->exists($conversionPath)) {
                    Storage::disk($this->disk)->makeDirectory($conversionPath);
                }

                $conversionImage = $image->scaleDown($settings['width'], $settings['height']);
                $encodedImage = $conversionImage->encodeByExtension($extension, $settings['quality']);

                Storage::disk($this->disk)->put(
                    $conversionPath . '/' . $fileName,
                    $encodedImage
                );

                $conversions[$conversionName] = true;
            }

            $media->generated_conversions = $conversions;

        } catch (\Exception $e) {
            throw new \Exception("Image processing failed: " . $e->getMessage());
        }
    }

    /**
     * Process video files
     */
    protected function processVideo($file, $path, $fileName, $media)
    {
        // Placeholder for video processing
        $media->duration = $this->getVideoDuration($file);
        $media->generated_conversions = ['thumb' => true];
        $media->dimensions = $this->getVideoDimensions($file);
    }

    /**
     * Get video duration
     */
    protected function getVideoDuration($file)
    {
        // Implement using FFMpeg or other library
        return null;
    }

    /**
     * Get video dimensions
     */
    protected function getVideoDimensions($file)
    {
        // Implement using FFMpeg or other library
        return null;
    }

    /**
     * Generate a unique filename
     */
    protected function generateUniqueFilename($path, $baseName, $extension)
    {
        $counter = 1;
        $originalFileName = "{$baseName}.{$extension}";
        $fileName = $originalFileName;

        while (
            Media::where('file_name', $fileName)
                ->where('directory', $path)
                ->exists() ||
            Storage::disk($this->disk)->exists($this->rootPath . ($path ? '/' . $path : '') . '/' . $fileName)
        ) {
            $fileName = "{$baseName}-{$counter}.{$extension}";
            $counter++;
        }

        return $fileName;
    }

    /**
     * Move items between folders
     */
    public function moveItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.type' => 'required|in:file,folder',
            'items.*.id' => 'required',
            'target_path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            foreach ($request->input('items') as $item) {
                if ($item['type'] === 'file') {
                    $this->moveFile($item['id'], $request->input('target_path'));
                } else {
                    $this->moveFolder($item['id'], $request->input('target_path'));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Items moved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to move items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move a file to a new folder
     */
    protected function moveFile($fileId, $targetPath)
    {
        $media = Media::findOrFail($fileId);

        if ($media->directory === $targetPath) {
            return;
        }

        $oldPath = $this->rootPath . ($media->directory ? '/' . $media->directory : '') . '/' . $media->file_name;
        $newPath = $this->rootPath . ($targetPath ? '/' . $targetPath : '') . '/' . $media->file_name;

        if (Storage::disk($this->disk)->exists($newPath)) {
            throw new \Exception("A file with this name already exists in the target folder");
        }

        // Move the original file
        Storage::disk($this->disk)->move($oldPath, $newPath);

        // Move all conversions
        foreach ($media->generated_conversions as $conversion => $generated) {
            if ($generated) {
                $oldConversionPath = $this->rootPath .
                    ($media->directory ? '/' . $media->directory : '') .
                    '/' . $conversion . '/' . $media->file_name;
                $newConversionPath = $this->rootPath .
                    ($targetPath ? '/' . $targetPath : '') .
                    '/' . $conversion . '/' . $media->file_name;

                Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
            }
        }

        // Update database record
        $media->directory = $targetPath;
        $media->save();
    }

    /**
     * Move a folder to a new location
     */
    protected function moveFolder($folderId, $targetPath)
    {
        $folder = MediaFolder::findOrFail($folderId);
        $oldPath = $folder->path;
        $newPath = ($targetPath ? $targetPath . '/' : '') . basename($oldPath);

        if ($oldPath === $newPath) {
            return;
        }

        if (MediaFolder::where('path', $newPath)->exists()) {
            throw new \Exception("A folder with this name already exists in the target location");
        }

        // Update folder path in database
        $folder->path = $newPath;
        $folder->parent_id = $this->getParentIdFromPath($targetPath);
        $folder->save();

        // Move all files in the folder
        Media::where('directory', $oldPath)->update(['directory' => $newPath]);

        // Move all subfolders
        $subfolders = MediaFolder::where('path', 'like', $oldPath . '/%')->get();
        foreach ($subfolders as $subfolder) {
            $subfolderNewPath = str_replace($oldPath, $newPath, $subfolder->path);
            $subfolder->path = $subfolderNewPath;
            $subfolder->save();
        }

        // Move physical folder and contents
        $oldFullPath = $this->rootPath . '/' . $oldPath;
        $newFullPath = $this->rootPath . '/' . $newPath;

        Storage::disk($this->disk)->move($oldFullPath, $newFullPath);

        // Move all conversion folders
        foreach (array_keys($this->conversions) as $conversion) {
            $oldConversionPath = $this->rootPath . '/' . $oldPath . '/' . $conversion;
            $newConversionPath = $this->rootPath . '/' . $newPath . '/' . $conversion;

            if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
            }
        }

        // Fix tree structure
        MediaFolder::fixTree();
    }

    /**
     * Rename an item
     */
    public function renameItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:file,folder',
            'id' => 'required',
            'new_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            if ($request->input('type') === 'file') {
                return $this->renameFile($request->input('id'), $request->input('new_name'));
            } else {
                return $this->renameFolder($request->input('id'), $request->input('new_name'));
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to rename item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rename a file
     */
    protected function renameFile($fileId, $newName)
    {
        $media = Media::findOrFail($fileId);
        $newBaseName = Str::slug(pathinfo($newName, PATHINFO_FILENAME));
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
        $newFileName = $newBaseName . '.' . $extension;

        if ($media->file_name === $newFileName) {
            return response()->json(['success' => true, 'message' => 'No changes made']);
        }

        $oldPath = $this->rootPath . ($media->directory ? '/' . $media->directory : '') . '/' . $media->file_name;
        $newPath = $this->rootPath . ($media->directory ? '/' . $media->directory : '') . '/' . $newFileName;

        if (Storage::disk($this->disk)->exists($newPath)) {
            throw new \Exception("A file with this name already exists in the folder");
        }

        DB::beginTransaction();

        try {
            // Rename original file
            Storage::disk($this->disk)->move($oldPath, $newPath);

            // Rename all conversions
            foreach ($media->generated_conversions as $conversion => $generated) {
                if ($generated) {
                    $oldConversionPath = $this->rootPath .
                        ($media->directory ? '/' . $media->directory : '') .
                        '/' . $conversion . '/' . $media->file_name;
                    $newConversionPath = $this->rootPath .
                        ($media->directory ? '/' . $media->directory : '') .
                        '/' . $conversion . '/' . $newFileName;

                    Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
                }
            }

            // Update database record
            $media->name = pathinfo($newName, PATHINFO_FILENAME);
            $media->file_name = $newFileName;
            $media->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'media' => $media,
                'message' => 'File renamed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Rename a folder
     */
    protected function renameFolder($folderId, $newName)
    {
        $folder = MediaFolder::findOrFail($folderId);
        $newFolderName = Str::slug($newName);

        $oldPath = $folder->path;
        $parentPath = dirname($oldPath);
        $newPath = ($parentPath !== '.' ? $parentPath . '/' : '') . $newFolderName;

        if ($folder->path === $newPath) {
            return response()->json(['success' => true, 'message' => 'No changes made']);
        }

        if (MediaFolder::where('path', $newPath)->exists()) {
            throw new \Exception("A folder with this name already exists in the parent directory");
        }

        DB::beginTransaction();

        try {
            // Rename physical folder
            $oldFullPath = $this->rootPath . '/' . $oldPath;
            $newFullPath = $this->rootPath . '/' . $newPath;
            Storage::disk($this->disk)->move($oldFullPath, $newFullPath);

            // Update folder and all its children in database
            $this->updateFolderPaths($oldPath, $newPath);

            // Update all media records in this folder and subfolders
            Media::where('directory', 'like', $oldPath . '%')
                ->update([
                    'directory' => DB::raw("REPLACE(directory, '$oldPath', '$newPath')")
                ]);

            // Fix tree structure
            MediaFolder::fixTree();

            DB::commit();

            return response()->json([
                'success' => true,
                'folder' => $folder->fresh(),
                'message' => 'Folder renamed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update folder paths recursively after rename/move
     */
    protected function updateFolderPaths($oldPath, $newPath)
    {
        $folders = MediaFolder::where('path', 'like', $oldPath . '%')->get();

        foreach ($folders as $folder) {
            $folder->path = str_replace($oldPath, $newPath, $folder->path);
            $folder->save();
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $permanent = $request->input('permanent', false);
        $deletedItems = [];

        DB::beginTransaction();

        try {
            foreach ($request->input('items') as $item) {
                if ($item['type'] === 'file') {
                    $deletedItems[] = $this->deleteFile($item['id'], $permanent);
                } else {
                    $deletedItems[] = $this->deleteFolder($item['id'], $permanent);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'items' => $deletedItems,
                'message' => $permanent ? 'Items permanently deleted' : 'Items moved to trash'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a file
     */
    protected function deleteFile($fileId, $permanent = false)
    {
        $media = Media::withTrashed()->findOrFail($fileId);

        if ($permanent) {
            // Delete all file conversions
            foreach ($media->generated_conversions as $conversion => $generated) {
                if ($generated) {
                    $path = $this->rootPath .
                        ($media->directory ? '/' . $media->directory : '') .
                        '/' . $conversion . '/' . $media->file_name;
                    Storage::disk($this->disk)->delete($path);
                }
            }

            // Delete original file
            $originalPath = $this->rootPath .
                ($media->directory ? '/' . $media->directory : '') .
                '/' . $media->file_name;
            Storage::disk($this->disk)->delete($originalPath);

            // Delete database record
            $media->forceDelete();
        } else {
            // Soft delete
            if ($media->trashed()) {
                throw new \Exception('File is already in trash');
            }
            $media->delete();
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
            // Delete all files in the folder and subfolders
            $files = Media::where('directory', 'like', $folder->path . '%')->get();
            foreach ($files as $file) {
                $this->deleteFile($file->id, true);
            }

            // Delete the folder and all subfolders from database
            MediaFolder::where('path', 'like', $folder->path . '%')->forceDelete();

            // Delete physical folder
            Storage::disk($this->disk)->deleteDirectory($this->rootPath . '/' . $folder->path);
        } else {
            // Soft delete
            if ($folder->trashed()) {
                throw new \Exception('Folder is already in trash');
            }

            // Soft delete all files in the folder and subfolders
            Media::where('directory', 'like', $folder->path . '%')->delete();

            // Soft delete the folder and all subfolders
            MediaFolder::where('path', 'like', $folder->path . '%')->delete();
        }

        return $folder;
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $restoredItems = [];

        DB::beginTransaction();

        try {
            foreach ($request->input('items') as $item) {
                if ($item['type'] === 'file') {
                    $restoredItems[] = $this->restoreFile($item['id']);
                } else {
                    $restoredItems[] = $this->restoreFolder($item['id']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'items' => $restoredItems,
                'message' => 'Items restored successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore items: ' . $e->getMessage()
            ], 500);
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

        // Restore all files in this folder
        Media::onlyTrashed()
            ->where('directory', $folder->path)
            ->restore();

        // Restore all subfolders
        $subfolders = MediaFolder::onlyTrashed()
            ->where('path', 'like', $folder->path . '/%')
            ->get();

        foreach ($subfolders as $subfolder) {
            $this->restoreFolder($subfolder->id);
        }

        return $folder;
    }

    /**
     * Get trashed items
     */
    protected function getTrashedItems()
    {
        $trashedFiles = Media::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get()
            ->map(function($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->name,
                    'type' => 'file',
                    'path' => $file->directory ? $file->directory.'/'.$file->file_name : $file->file_name,
                    'mime_type' => $file->mime_type,
                    'size' => $file->size,
                    'deleted_at' => $file->deleted_at,
                    'url' => $file->url
                ];
            });

        $trashedFolders = MediaFolder::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get()
            ->map(function($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'type' => 'folder',
                    'path' => $folder->path,
                    'deleted_at' => $folder->deleted_at
                ];
            });

        return response()->json([
            'success' => true,
            'contents' => [
                'files' => $trashedFiles,
                'folders' => $trashedFolders
            ],
            'contextMenu' => $this->getTrashContextMenuOptions()
        ]);
    }

    /**
     * Get context menu options for trash view
     */
    protected function getTrashContextMenuOptions()
    {
        return [
            'restore' => [
                'label' => 'Restore',
                'icon' => 'trash-restore',
                'action' => 'restoreItems',
                'available' => true
            ],
            'delete' => [
                'label' => 'Delete Permanently',
                'icon' => 'trash-alt',
                'action' => 'deleteItems',
                'available' => true,
                'permanent' => true
            ],
            'separator1' => ['type' => 'separator'],
            'emptyTrash' => [
                'label' => 'Empty Trash',
                'icon' => 'broom',
                'action' => 'emptyTrash',
                'available' => true
            ],
            'separator2' => ['type' => 'separator'],
            'refresh' => [
                'label' => 'Refresh',
                'icon' => 'sync',
                'action' => 'refresh',
                'available' => true
            ]
        ];
    }

    /**
     * Empty the trash
     */
    public function emptyTrash()
    {
        DB::beginTransaction();

        try {
            // Permanently delete all trashed files
            $files = Media::onlyTrashed()->get();
            foreach ($files as $file) {
                $this->deleteFile($file->id, true);
            }

            // Permanently delete all trashed folders
            $folders = MediaFolder::onlyTrashed()->get();
            foreach ($folders as $folder) {
                $this->deleteFolder($folder->id, true);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trash has been emptied'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to empty trash: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search for items
     */
    protected function searchItems($searchTerm, $currentPath = '')
    {
        $results = [
            'folders' => [],
            'files' => []
        ];

        // Search folders
        $folders = MediaFolder::where('name', 'like', "%$searchTerm%")
            ->when($currentPath, function($query) use ($currentPath) {
                return $query->where('path', 'like', "$currentPath%");
            })
            ->orderBy('name')
            ->get();

        foreach ($folders as $folder) {
            $results['folders'][] = [
                'id' => $folder->id,
                'name' => $folder->name,
                'path' => $folder->path,
                'type' => 'folder',
                'created_at' => $folder->created_at,
                'updated_at' => $folder->updated_at
            ];
        }

        // Search files
        $files = Media::where('name', 'like', "%$searchTerm%")
            ->when($currentPath, function($query) use ($currentPath) {
                return $query->where('directory', 'like', "$currentPath%");
            })
            ->orderBy('name')
            ->get();

        foreach ($files as $file) {
            $results['files'][] = [
                'id' => $file->id,
                'name' => $file->name,
                'file_name' => $file->file_name,
                'path' => $file->directory ? $file->directory.'/'.$file->file_name : $file->file_name,
                'url' => $file->url,
                'thumb_url' => $file->thumb_url,
                'type' => 'file',
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'dimensions' => $file->dimensions,
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at
            ];
        }

        return response()->json([
            'success' => true,
            'searchTerm' => $searchTerm,
            'contents' => $results
        ]);
    }

    /**
     * Set a media item as featured
     */
    public function setFeatured(Request $request, $mediaId)
    {
        $media = Media::findOrFail($mediaId);

        try {
            // Remove featured status from all other media in the same directory
            Media::where('directory', $media->directory)
                ->where('id', '!=', $media->id)
                ->update(['is_featured' => false]);

            // Set this media as featured
            $media->is_featured = true;
            $media->save();

            return response()->json([
                'success' => true,
                'message' => 'Media set as featured'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set featured: ' . $e->getMessage()
            ], 500);
        }
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
                    'size' => $this->formatBytes($item->size),
                    'location' => $item->directory ?: 'Root',
                    'created' => $item->created_at->format('M d, Y H:i'),
                    'modified' => $item->updated_at->format('M d, Y H:i'),
                    'mime_type' => $item->mime_type,
                    'dimensions' => $item->dimensions ? $item->dimensions['width'].' × '.$item->dimensions['height'] : 'N/A',
                    'status' => $item->trashed() ? 'In Trash' : 'Active'
                ];
            } else {
                $item = MediaFolder::withTrashed()->findOrFail($id);

                $properties = [
                    'name' => $item->name,
                    'type' => 'Folder',
                    'size' => $this->formatBytes($this->getFolderSize($item->path)),
                    'location' => $item->parent ? $item->parent->path : 'Root',
                    'created' => $item->created_at->format('M d, Y H:i'),
                    'modified' => $item->updated_at->format('M d, Y H:i'),
                    'item_count' => $this->getFolderItemCount($item->path),
                    'status' => $item->trashed() ? 'In Trash' : 'Active'
                ];
            }

            return response()->json([
                'success' => true,
                'properties' => $properties,
                'item' => $item
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get properties: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate folder size
     */
    protected function getFolderSize($path)
    {
        $files = Media::where('directory', 'like', $path . '%')->get();
        return $files->sum('size');
    }

    /**
     * Format bytes to human-readable format
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $clipboard = [
            'action' => 'copy',
            'items' => $request->input('items'),
            'timestamp' => now()
        ];

        session(['gallery_clipboard' => $clipboard]);

        return response()->json([
            'success' => true,
            'message' => 'Items copied to clipboard'
        ]);
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $clipboard = [
            'action' => 'cut',
            'items' => $request->input('items'),
            'timestamp' => now()
        ];

        session(['gallery_clipboard' => $clipboard]);

        return response()->json([
            'success' => true,
            'message' => 'Items cut to clipboard'
        ]);
    }

    /**
     * Paste items from clipboard
     */
    public function pasteItems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $clipboard = session('gallery_clipboard');
        if (!$clipboard) {
            return response()->json(['error' => 'Clipboard is empty'], 400);
        }

        $targetPath = $request->input('target_path');

        DB::beginTransaction();

        try {
            if ($clipboard['action'] === 'copy') {
                foreach ($clipboard['items'] as $item) {
                    if ($item['type'] === 'file') {
                        $this->copyFile($item['id'], $targetPath);
                    } else {
                        $this->copyFolder($item['id'], $targetPath);
                    }
                }
                $message = 'Items copied successfully';
            } else { // cut
                foreach ($clipboard['items'] as $item) {
                    if ($item['type'] === 'file') {
                        $this->moveFile($item['id'], $targetPath);
                    } else {
                        $this->moveFolder($item['id'], $targetPath);
                    }
                }
                session()->forget('gallery_clipboard');
                $message = 'Items moved successfully';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to paste items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Copy a file
     */
    protected function copyFile($fileId, $targetPath)
    {
        $media = Media::findOrFail($fileId);
        $newBaseName = pathinfo($media->file_name, PATHINFO_FILENAME);
        $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);
        $newFileName = $this->generateUniqueFilename($targetPath, $newBaseName, $extension);

        // Copy original file
        Storage::disk($this->disk)->copy(
            $this->rootPath . ($media->directory ? '/' . $media->directory : '') . '/' . $media->file_name,
            $this->rootPath . ($targetPath ? '/' . $targetPath : '') . '/' . $newFileName
        );

        // Copy all conversions
        foreach ($media->generated_conversions as $conversion => $generated) {
            if ($generated) {
                Storage::disk($this->disk)->copy(
                    $this->rootPath . ($media->directory ? '/' . $media->directory : '') . '/' . $conversion . '/' . $media->file_name,
                    $this->rootPath . ($targetPath ? '/' . $targetPath : '') . '/' . $conversion . '/' . $newFileName
                );
            }
        }

        // Create new media record
        $newMedia = $media->replicate();
        $newMedia->file_name = $newFileName;
        $newMedia->directory = $targetPath;
        $newMedia->file_hash = md5_file(
            Storage::disk($this->disk)->path($this->rootPath . ($targetPath ? '/' . $targetPath : '') . '/' . $newFileName)
        );
        $newMedia->save();

        return $newMedia;
    }

    /**
     * Copy a folder
     */
    protected function copyFolder($folderId, $targetPath)
    {
        $folder = MediaFolder::findOrFail($folderId);
        $newFolderName = $folder->name;
        $newPath = $targetPath ? "$targetPath/$newFolderName" : $newFolderName;

        if (MediaFolder::where('path', $newPath)->exists()) {
            throw new \Exception("A folder with this name already exists in the target location");
        }

        // Create new folder
        Storage::disk($this->disk)->makeDirectory($this->rootPath.'/'.$newPath);

        // Create database record
        $newFolder = MediaFolder::create([
            'name' => $folder->name,
            'path' => $newPath,
            'parent_id' => $this->getParentIdFromPath($targetPath),
        ]);

        // Copy all files in the folder
        $files = Media::where('directory', $folder->path)->get();
        foreach ($files as $file) {
            $this->copyFile($file->id, $newPath);
        }

        // Copy all subfolders
        $subfolders = MediaFolder::where('path', 'like', $folder->path.'/%')
            ->whereRaw("path NOT LIKE ?", [$folder->path.'/%/%']) // Only direct children
            ->get();

        foreach ($subfolders as $subfolder) {
            $this->copyFolder($subfolder->id, $newPath);
        }

        // Fix tree structure
        MediaFolder::fixTree();

        return $newFolder;
    }

    /**
     * Generate a URL for a media item
     */
    public function generateUrl($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        $url = $media->url;

        return response()->json([
            'success' => true,
            'url' => $url,
            'html' => $media->isImage()
                ? '<img src="'.$url.'" alt="'.htmlspecialchars($media->alt_text ?? $media->name).'">'
                : '<a href="'.$url.'" target="_blank">'.$media->name.'</a>'
        ]);
    }
}
