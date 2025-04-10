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
        $currentPath = $request->get('path', '');
        $searchTerm = $request->get('search', '');
        $isTrash = $request->get('trash', false);

        if ($isTrash) {
            return $this->getTrashedItems($request);
        }

        if ($searchTerm) {
            return $this->searchItems($searchTerm, $currentPath);
        }

        $contents = $this->getFolderContents($currentPath);

        // Get counts for current folder
        $folderCount = count($contents['folders']);
        $fileCount = count($contents['files']);

        return response()->json([
            'success' => true,
            'currentPath' => $currentPath,
            'contents' => $contents,
            'counts' => [
                'folders' => $folderCount,
                'files' => $fileCount,
            ],
            'breadcrumbs' => $this->getBreadcrumbs($currentPath),
            'contextMenu' => $this->getContextMenuOptions($currentPath)
        ]);
    }

    /**
     * Get folder contents
     */

    protected function getFolderContents($path = '')
    {
        $contents = [
            'folders' => [],
            'files' => []
        ];

        // Get current folder with parent relationship
        $currentFolder = $path ? MediaFolder::with('parent')->where('path', $path)->first() : null;

        // Get child folders (direct children only)
        $childFolders = MediaFolder::withCount(['children', 'media'])
            ->where('parent_id', $currentFolder ? $currentFolder->id : null)
            ->whereNull('deleted_at')
            ->orderBy('name')
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
                'item_count' => $folder->media_count + $folder->children_count
            ];
        }

        // Get files in current directory
        $files = Media::where('directory', $path)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        foreach ($files as $file) {
            $contents['files'][] = [
                'id' => $file->id,
                'name' => $file->name,
                'file_name' => $file->file_name,
                'path' => $file->directory ? $file->directory.'/'.$file->file_name : $file->file_name,
                'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory.'/'.$file->file_name : $file->file_name),
                'thumb_url' => $this->getThumbUrl($file),
                'type' => 'file',
                'mime_type' => $file->mime_type,
                'size' => $this->formatFileSize($file->size),
                'size_bytes' => $file->size,
                'dimensions' => $file->dimensions,
                'created_at' => $file->created_at->format('d-m-Y'),
                'updated_at' => $file->updated_at->format('d-m-Y'),
                'is_featured' => $file->is_featured
            ];
        }

        return $contents;
    }

    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }


    /**
     * Get thumbnail URL for a file
     */
    protected function getThumbUrl($file)
    {
        if (Str::startsWith($file->mime_type, 'image/')) {
            $thumbPath = $file->directory ? $file->directory.'/thumb/'.$file->file_name : 'thumb/'.$file->file_name;
            return Storage::disk($this->disk)->exists($thumbPath) ?
                Storage::disk($this->disk)->url($thumbPath) :
                Storage::disk($this->disk)->url($file->directory ? $file->directory.'/'.$file->file_name : $file->file_name);
        }
        return null;
    }

    /**
     * Count items in a folder
     */
    protected function getFolderItemCount($path)
    {
        $fileCount = Media::where('directory', $path)->count();
        $folderCount = MediaFolder::where('parent_id', function($query) use ($path) {
            $query->select('id')
                ->from('media_folders')
                ->where('path', $path);
        })->count();

        return $fileCount + $folderCount;
    }

    /**
     * Generate breadcrumbs
     */
    // Update the breadcrumb generation
    protected function getBreadcrumbs($currentPath)
    {
        $breadcrumbs = [[
            'id' => null,
            'name' => 'Root',
            'path' => '',
            'icon' => 'home'
        ]];

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
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $accumulatedPath,
                    'icon' => 'folder'
                ];
            }
        }

        return $breadcrumbs;
    }


    /**
     * Get context menu options
     */
    protected function getContextMenuOptions($currentPath = '', $selectedItems = [])
    {
        $hasSelection = !empty($selectedItems);
        $isRoot = empty($currentPath);

        return [
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
                'available' => $hasSelection
            ],
            'copy' => [
                'label' => 'Copy',
                'icon' => 'copy',
                'action' => 'copyItems',
                'available' => $hasSelection
            ],
            'paste' => [
                'label' => 'Paste',
                'icon' => 'paste',
                'action' => 'pasteItems',
                'available' => session()->has('clipboard')
            ],
            'separator2' => ['type' => 'separator'],
            'rename' => [
                'label' => 'Rename',
                'icon' => 'i-cursor',
                'action' => 'renameItem',
                'available' => $hasSelection && count($selectedItems) === 1
            ],
            'delete' => [
                'label' => 'Delete',
                'icon' => 'trash',
                'action' => 'deleteItems',
                'available' => $hasSelection
            ]
        ];
    }

    /**
     * Create a new folder
     */
    public function createFolder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\-_ ]+$/',
            'parent_id' => 'nullable|exists:media_folders,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $folder = MediaFolder::createWithPath($request->name, $request->parent_id);

            return response()->json([
                'success' => true,
                'folder' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $folder->path,
                    'parent_id' => $folder->parent_id,
                    'depth' => $folder->depth
                ],
                'message' => 'Folder created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Folder creation failed: '.$e->getMessage()
            ], 500);
        }
    }


    public function getFolderInfo($id)
    {
        $folder = MediaFolder::withTrashed()->find($id);

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found'
            ], 404);
        }

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
     * Upload files with all fields populated
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:'.(1024 * 20), // 20MB max
            'path' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->input('path', '');
        $uploadedFiles = [];

        DB::beginTransaction();

        try {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = strtolower($file->getClientOriginalExtension());
                $fileName = $this->generateUniqueFilename($path, Str::slug($baseName), $extension);
                $fileHash = md5_file($file->getRealPath());
                $mimeType = $file->getMimeType();
                $size = $file->getSize();

                // Store file
                $storedPath = $file->storeAs(
                    $path,
                    $fileName,
                    $this->disk
                );

                // Create media record with all fields
                $media = new Media([
                    'model_type' => null, // Set to your model if applicable
                    'model_id' => null,   // Set to your model ID if applicable
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

                // Process image/video if needed
                if (Str::startsWith($mimeType, 'image/')) {
                    $this->processImage($file, $path, $fileName, $media);
                } elseif (Str::startsWith($mimeType, 'video/')) {
                    $this->processVideo($file, $media);
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
                'message' => 'File upload failed: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Process image and create conversions with all metadata
     */
    protected function processImage($file, $path, $fileName, $media)
    {
        try {
            $image = $this->imageManager->read($file->getRealPath());

            // Set dimensions
            $media->dimensions = [
                'width' => $image->width(),
                'height' => $image->height(),
                'aspect_ratio' => $image->width() / $image->height()
            ];

            $generatedConversions = [];
            $manipulations = [];
            $responsiveImages = [];

            foreach ($this->conversions as $conversionName => $settings) {
                $conversionPath = $path.'/'.$conversionName;
                Storage::disk($this->disk)->makeDirectory($conversionPath);

                $conversionImage = $image->scaleDown($settings['width'], $settings['height']);
                $encodedImage = $conversionImage->encodeByExtension(
                    pathinfo($fileName, PATHINFO_EXTENSION),
                    $settings['quality']
                );

                Storage::disk($this->disk)->put(
                    $conversionPath.'/'.$fileName,
                    $encodedImage
                );

                // Record conversion details
                $generatedConversions[$conversionName] = true;
                $manipulations[$conversionName] = [
                    'width' => $settings['width'],
                    'height' => $settings['height'],
                    'quality' => $settings['quality'],
                    'format' => pathinfo($fileName, PATHINFO_EXTENSION),
                    'created_at' => now()->toDateTimeString()
                ];

                // Generate responsive images data
                $responsiveImages[$conversionName] = [
                    'urls' => [
                        Storage::disk($this->disk)->url($conversionPath.'/'.$fileName)
                    ],
                    'base64svg' => '', // Can be filled with placeholder SVG if needed
                    'width' => $settings['width'],
                    'height' => $settings['height']
                ];
            }

            // Save all processing data
            $media->manipulations = $manipulations;
            $media->generated_conversions = $generatedConversions;
            $media->responsive_images = $responsiveImages;

        } catch (\Exception $e) {
            throw new \Exception("Image processing failed: ".$e->getMessage());
        }
    }

    /**
     * Process video file metadata
     */
    protected function processVideo($file, $media)
    {
        try {
            $duration = FFMpeg::fromDisk($this->disk)
                ->open($media->directory.'/'.$media->file_name)
                ->getDurationInSeconds();

            $media->duration = $duration;
            $media->custom_properties = array_merge(
                $media->custom_properties ?? [],
                [
                    'type' => 'video',
                    'duration_formatted' => gmdate('H:i:s', $duration),
                    'frame_rate' => null, // Can be extracted similarly
                    'bitrate' => null     // Can be extracted similarly
                ]
            );
        } catch (\Exception $e) {
            // Fallback if FFmpeg not available
            $media->duration = 0;
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename($path, $baseName, $extension)
    {
        $counter = 1;
        $fileName = "{$baseName}.{$extension}";

        while (
            Media::where('file_name', $fileName)
                ->where('directory', $path)
                ->exists() ||
            Storage::disk($this->disk)->exists($path.'/'.$fileName)
        ) {
            $fileName = "{$baseName}-{$counter}.{$extension}";
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
                'message' => 'Failed to move items: '.$e->getMessage()
            ], 500);
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

        $oldPath = $media->directory ? $media->directory.'/'.$media->file_name : $media->file_name;
        $newPath = $targetPath ? $targetPath.'/'.$media->file_name : $media->file_name;

        if (Storage::disk($this->disk)->exists($newPath)) {
            throw new \Exception("A file with this name already exists in the target folder");
        }

        // Move original file
        Storage::disk($this->disk)->move($oldPath, $newPath);

        // Move conversions
        foreach ($this->conversions as $conversion => $settings) {
            $oldConversionPath = ($media->directory ? $media->directory.'/'.$conversion : $conversion).'/'.$media->file_name;
            $newConversionPath = ($targetPath ? $targetPath.'/'.$conversion : $conversion).'/'.$media->file_name;

            if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
            }
        }

        // Update record
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
        $newPath = $targetPath ? $targetPath.'/'.basename($oldPath) : basename($oldPath);

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

        // Update all files in this folder
        Media::where('directory', $oldPath)
            ->update(['directory' => $newPath]);

        // Update all subfolders
        MediaFolder::where('path', 'like', $oldPath.'/%')
            ->update(['path' => DB::raw("REPLACE(path, '$oldPath', '$newPath')")]);

        // Move physical folder
        Storage::disk($this->disk)->move($oldPath, $newPath);

        // Move conversions
        foreach ($this->conversions as $conversion => $settings) {
            $oldConversionPath = $oldPath.'/'.$conversion;
            $newConversionPath = $newPath.'/'.$conversion;

            if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
            }
        }

        // Fix tree structure
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

        $parts = explode('/', $path);
        if (count($parts) === 1) {
            return null;
        }

        $parentPath = implode('/', array_slice($parts, 0, -1));
        $folder = MediaFolder::where('path', $parentPath)->first();
        return $folder ? $folder->id : null;
    }

    /**
     * Rename an item
     */
    public function renameItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:file,folder',
            'id' => 'required',
            'new_name' => 'required|string|max:255'
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
                'message' => 'Failed to rename item: '.$e->getMessage()
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
        $newFileName = $newBaseName.'.'.$extension;

        if ($media->file_name === $newFileName) {
            return response()->json(['success' => true, 'message' => 'No changes made']);
        }

        $oldPath = $media->directory ? $media->directory.'/'.$media->file_name : $media->file_name;
        $newPath = $media->directory ? $media->directory.'/'.$newFileName : $newFileName;

        if (Storage::disk($this->disk)->exists($newPath)) {
            throw new \Exception("A file with this name already exists in the folder");
        }

        DB::beginTransaction();

        try {
            // Rename original file
            Storage::disk($this->disk)->move($oldPath, $newPath);

            // Rename conversions
            foreach ($this->conversions as $conversion => $settings) {
                $oldConversionPath = ($media->directory ? $media->directory.'/'.$conversion : $conversion).'/'.$media->file_name;
                $newConversionPath = ($media->directory ? $media->directory.'/'.$conversion : $conversion).'/'.$newFileName;

                if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                    Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
                }
            }

            // Update record
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
        $newPath = ($parentPath !== '.' ? $parentPath.'/' : '').$newFolderName;

        if ($folder->path === $newPath) {
            return response()->json(['success' => true, 'message' => 'No changes made']);
        }

        if (MediaFolder::where('path', $newPath)->exists()) {
            throw new \Exception("A folder with this name already exists in the parent directory");
        }

        DB::beginTransaction();

        try {
            // Rename physical folder
            Storage::disk($this->disk)->move($oldPath, $newPath);

            // Update folder and all its children in database
            $this->updateFolderPaths($oldPath, $newPath);

            // Update all media records in this folder and subfolders
            Media::where('directory', 'like', $oldPath.'%')
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
     * Update folder paths after rename/move
     */
    protected function updateFolderPaths($oldPath, $newPath)
    {
        $folders = MediaFolder::where('path', 'like', $oldPath.'%')->get();

        foreach ($folders as $folder) {
            $folder->path = str_replace($oldPath, $newPath, $folder->path);
            $folder->save();
        }
    }

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
                'message' => 'Failed to delete items: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a file (soft delete)
     */
    protected function deleteFile($fileId, $permanent = false)
    {
        $media = Media::withTrashed()->findOrFail($fileId);

        if ($permanent) {
            // Delete the actual file
            $filePath = $media->directory ? $media->directory.'/'.$media->file_name : $media->file_name;
            Storage::disk($this->disk)->delete($filePath);

            // Delete conversions if they exist
            foreach ($this->conversions as $conversion => $settings) {
                $conversionPath = ($media->directory ? $media->directory.'/'.$conversion : $conversion).'/'.$media->file_name;
                if (Storage::disk($this->disk)->exists($conversionPath)) {
                    Storage::disk($this->disk)->delete($conversionPath);
                }
            }

            // Permanently delete the file record
            $media->forceDelete();
            return $media;
        } else {
            if ($media->trashed()) {
                throw new \Exception('File is already in trash');
            }

            // Soft delete by setting deleted_at timestamp
            $media->deleted_at = now();
            $media->save();
            return $media;
        }
    }



    /**
     * Delete a folder (soft delete)
     */
    protected function deleteFolder($folderId, $permanent = false)
    {
        $folder = MediaFolder::withTrashed()->findOrFail($folderId);

        if ($permanent) {
            // Permanently delete all files in the folder and subfolders
            $files = Media::where('directory', 'like', $folder->path.'%')->withTrashed()->get();
            foreach ($files as $file) {
                $this->deleteFile($file->id, true);
            }

            // Delete the folder and all subfolders from database
            MediaFolder::where('path', 'like', $folder->path.'%')->forceDelete();

            // Delete the physical folder and all conversions
            Storage::disk($this->disk)->deleteDirectory($folder->path);
            foreach ($this->conversions as $conversion => $settings) {
                $conversionPath = $folder->path.'/'.$conversion;
                if (Storage::disk($this->disk)->exists($conversionPath)) {
                    Storage::disk($this->disk)->deleteDirectory($conversionPath);
                }
            }
        } else {
            if ($folder->trashed()) {
                throw new \Exception('Folder is already in trash');
            }

            // Soft delete the folder and all subfolders by setting deleted_at timestamp
            MediaFolder::where('path', 'like', $folder->path.'%')
                ->update(['deleted_at' => now()]);
        }

        return $folder;
    }
    /**
     * Get trashed items
     */
    public function getTrashedItems(Request $request)
    {
        $parentId = $request->input('parent_id', null);

        // Get trashed files in current folder
        $trashedFiles = Media::onlyTrashed()
            ->when($parentId, function($query) use ($parentId) {
                // For specific folder, filter by directory path
                $folder = MediaFolder::onlyTrashed()->find($parentId);
                if (!$folder) {
                    return $query->whereRaw('1=0'); // Return empty result if folder not found
                }
                return $query->where('directory', $folder->path);
            }, function($query) {
                // For root trash, get files that were in root
                return $query->where(function($q) {
                    $q->whereNull('directory')
                        ->orWhere('directory', '');
                });
            })
            ->orderBy('deleted_at', 'desc')
            ->get()
            ->map(function($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->name,
                    'type' => 'file',
                    'path' => $file->directory ? $file->directory.'/'.$file->file_name : $file->file_name,
                    'mime_type' => $file->mime_type,
                    'size' => $this->formatFileSize($file->size),
                    'size_bytes' => $file->size,
                    'deleted_at' => $file->deleted_at->format('d-m-Y H:i:s'),
                    'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory.'/'.$file->file_name : $file->file_name),
                    'thumb_url' => $this->getThumbUrl($file),
                    'created_at' => $file->created_at->format('d-m-Y')
                ];
            });

        // Get trashed folders (only direct children if parent_id is provided)
        $trashedFolders = MediaFolder::onlyTrashed()
            ->withCount([
                'children as trashed_children_count' => function($query) {
                    $query->onlyTrashed();
                },
                'media as trashed_media_count' => function($query) {
                    $query->onlyTrashed();
                }
            ])
            ->when($parentId, function($query) use ($parentId) {
                // For specific folder, get direct children
                return $query->where('parent_id', $parentId);
            }, function($query) {
                // For root trash, get all root folders (parent_id is null)
                return $query->whereNull('parent_id');
            })
            ->orderBy('deleted_at', 'desc')
            ->get()
            ->map(function($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'type' => 'folder',
                    'path' => $folder->path,
                    'parent_id' => $folder->parent_id,
                    'deleted_at' => $folder->deleted_at->format('d-m-Y H:i:s'),
                    'folder_count' => $folder->trashed_children_count,
                    'file_count' => $folder->trashed_media_count,
                    'item_count' => $folder->trashed_children_count + $folder->trashed_media_count,
                    'created_at' => $folder->created_at->format('d-m-Y')
                ];
            });

        // Add "Go Up" folder if we're in a subfolder
        $goUpFolder = null;
        if ($parentId) {
            $currentFolder = MediaFolder::onlyTrashed()->find($parentId);
            if ($currentFolder && $currentFolder->parent_id) {
                $parentFolder = MediaFolder::onlyTrashed()->find($currentFolder->parent_id);
                if ($parentFolder) {
                    $goUpFolder = [
                        'id' => $parentFolder->id, // Use actual parent folder ID
                        'name' => 'Go Up',
                        'type' => 'folder',
                        'path' => $parentFolder->path,
                        'parent_id' => $parentFolder->parent_id,
                        'deleted_at' => null,
                        'is_go_up' => true,
                        'folder_count' => $parentFolder->trashed_children_count ?? 0,
                        'file_count' => $parentFolder->trashed_media_count ?? 0,
                        'item_count' => ($parentFolder->trashed_children_count ?? 0) + ($parentFolder->trashed_media_count ?? 0),
                        'created_at' => $parentFolder->created_at->format('d-m-Y')
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'contents' => [
                'files' => $trashedFiles,
                'folders' => $goUpFolder ? [$goUpFolder, ...$trashedFolders] : $trashedFolders
            ],
            'parent_id' => $parentId,
            'contextMenu' => $this->getTrashContextMenuOptions()
        ]);
    }

    /**
     * Get trash context menu options
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
                'message' => 'Failed to restore items: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a file from trash
     */
    protected function restoreFile($fileId)
    {
        $media = Media::onlyTrashed()->findOrFail($fileId);
        $media->deleted_at = null;
        $media->save();
        return $media;
    }

    /**
     * Restore a folder from trash
     */
    protected function restoreFolder($folderId)
    {
        $folder = MediaFolder::onlyTrashed()->findOrFail($folderId);
        $folder->deleted_at = null;
        $folder->save();

        // Restore all files in this folder
        Media::onlyTrashed()
            ->where('directory', $folder->path)
            ->update(['deleted_at' => null]);

        // Restore all subfolders
        MediaFolder::onlyTrashed()
            ->where('path', 'like', $folder->path.'/%')
            ->update(['deleted_at' => null]);

        return $folder;
    }

    /**
     * Empty the trash (permanently delete all trashed items)
     */
    public function emptyTrash()
    {
        DB::beginTransaction();

        try {
            // Permanently delete all trashed files and their conversions
            $files = Media::onlyTrashed()->get();
            foreach ($files as $file) {
                $this->deleteFile($file->id, true);
            }

            // Permanently delete all trashed folders
            $folders = MediaFolder::onlyTrashed()->get();
            foreach ($folders as $folder) {
                $this->deleteFolder($folder->id, true);
            }

            // Clean up trash directories
            Storage::disk($this->disk)->deleteDirectory('trash/files');
            Storage::disk($this->disk)->deleteDirectory('trash/conversions');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trash has been emptied'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to empty trash: '.$e->getMessage()
            ], 500);
        }
    }


    /**
     * Search items
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
                'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory.'/'.$file->file_name : $file->file_name),
                'thumb_url' => $this->getThumbUrl($file),
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

        session(['clipboard' => [
            'action' => 'copy',
            'items' => $request->input('items'),
            'timestamp' => now()
        ]]);

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

        session(['clipboard' => [
            'action' => 'cut',
            'items' => $request->input('items'),
            'timestamp' => now()
        ]]);

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

        $clipboard = session('clipboard');
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
                session()->forget('clipboard');
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
                'message' => 'Failed to paste items: '.$e->getMessage()
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
            $media->directory ? $media->directory.'/'.$media->file_name : $media->file_name,
            $targetPath ? $targetPath.'/'.$newFileName : $newFileName
        );

        // Copy conversions
        foreach ($this->conversions as $conversion => $settings) {
            $oldConversionPath = ($media->directory ? $media->directory.'/'.$conversion : $conversion).'/'.$media->file_name;
            $newConversionPath = ($targetPath ? $targetPath.'/'.$conversion : $conversion).'/'.$newFileName;

            if (Storage::disk($this->disk)->exists($oldConversionPath)) {
                Storage::disk($this->disk)->copy($oldConversionPath, $newConversionPath);
            }
        }

        // Create new media record
        $newMedia = $media->replicate();
        $newMedia->file_name = $newFileName;
        $newMedia->directory = $targetPath;
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

        // Create physical folder
        Storage::disk($this->disk)->makeDirectory($newPath);

        // Create folder record
        $newFolder = new MediaFolder([
            'name' => $folder->name,
            'path' => $newPath,
            'parent_id' => $this->getParentIdFromPath($targetPath)
        ]);

        if ($newFolder->parent_id) {
            $newFolder->appendToNode(MediaFolder::find($newFolder->parent_id))->save();
        } else {
            $newFolder->saveAsRoot();
        }

        // Copy all files in the folder
        $files = Media::where('directory', $folder->path)->get();
        foreach ($files as $file) {
            $this->copyFile($file->id, $newPath);
        }

        // Copy all subfolders
        $subfolders = MediaFolder::where('parent_id', $folder->id)->get();
        foreach ($subfolders as $subfolder) {
            $this->copyFolder($subfolder->id, $newPath);
        }

        return $newFolder;
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
                'message' => 'Failed to get properties: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate folder size
     */
    protected function getFolderSize($path)
    {
        $files = Media::where('directory', 'like', $path.'%')->get();
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

        return round($bytes, $precision).' '.$units[$pow];
    }

    // In GalleryController.php
    public function showFolder($id)
    {
        $folder = MediaFolder::with(['files', 'children'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'type' => 'folder',
            'data' => $folder
        ]);
    }

    public function showFile($id)
    {
        $file = Media::findOrFail($id);
        return response()->json([
            'success' => true,
            'type' => 'file',
            'data' => $file
        ]);
    }

    /**
     * Generate a public URL for a media item
     */
    public function generateUrl($id)
    {
        $media = Media::findOrFail($id);

        return response()->json([
            'success' => true,
            'url' => Storage::disk($this->disk)->url(
                $media->directory ? $media->directory.'/'.$media->file_name : $media->file_name
            )
        ]);
    }

    /**
     * Set a media item as featured
     */
    public function setFeatured($id)
    {
        // First remove featured status from any other items
        Media::where('is_featured', true)->update(['is_featured' => false]);

        // Set the selected item as featured
        $media = Media::findOrFail($id);
        $media->is_featured = true;
        $media->save();

        return response()->json([
            'success' => true,
            'message' => 'Media set as featured',
            'media' => $media
        ]);
    }

    /**
     * Remove featured status from a media item
     */
    public function removeFeatured($id)
    {
        $media = Media::findOrFail($id);
        $media->is_featured = false;
        $media->save();

        return response()->json([
            'success' => true,
            'message' => 'Featured status removed',
            'media' => $media
        ]);
    }

    /**
     * Build context menu options based on current state
     */
    protected function buildContextMenuOptions($currentPath, $selectedItems, $isTrash)
    {
        $hasSelection = !empty($selectedItems);
        $isRoot = empty($currentPath);

        $options = [
            'newFolder' => [
                'label' => 'New Folder',
                'icon' => 'folder-plus',
                'action' => 'createFolder',
                'available' => !$isTrash
            ],
            'upload' => [
                'label' => 'Upload Files',
                'icon' => 'upload',
                'action' => 'uploadFiles',
                'available' => !$isTrash
            ],
            'goUp' => [
                'label' => 'Go Up',
                'icon' => 'level-up-alt',
                'action' => 'navigateUp',
                'available' => !$isRoot && !$isTrash
            ],
            'separator1' => ['type' => 'separator'],
            'cut' => [
                'label' => 'Cut',
                'icon' => 'cut',
                'action' => 'cutItems',
                'available' => $hasSelection && !$isTrash,
                'shortcut' => 'Ctrl+X'
            ],
            'copy' => [
                'label' => 'Copy',
                'icon' => 'copy',
                'action' => 'copyItems',
                'available' => $hasSelection && !$isTrash,
                'shortcut' => 'Ctrl+C'
            ],
            'paste' => [
                'label' => 'Paste',
                'icon' => 'paste',
                'action' => 'pasteItems',
                'available' => session()->has('clipboard') && !$isTrash,
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
                'label' => $isTrash ? 'Delete Permanently' : 'Delete',
                'icon' => 'trash',
                'action' => 'deleteItems',
                'available' => $hasSelection,
                'danger' => true,
                'shortcut' => 'Del'
            ],
            'restore' => [
                'label' => 'Restore',
                'icon' => 'trash-restore',
                'action' => 'restoreItems',
                'available' => $isTrash && $hasSelection
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
     * Force delete an item (bypass soft delete)
     */
    public function forceDelete($id)
    {
        $item = Media::withTrashed()->findOrFail($id);

        // Delete the actual file
        Storage::disk($this->disk)->delete(
            $item->directory ? $item->directory.'/'.$item->file_name : $item->file_name
        );

        // Delete conversions if they exist
        foreach ($this->conversions as $conversion => $settings) {
            $path = ($item->directory ? $item->directory.'/'.$conversion : $conversion).'/'.$item->file_name;
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
            }
        }

        // Permanently delete the record
        $item->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Item permanently deleted'
        ]);
    }

    /**
     * Dedicated method for folder rename
     */
    public function updateFolder(Request $request, $id)
    {
        return $this->renameItem(new Request([
            'type' => 'folder',
            'id' => $id,
            'new_name' => $request->input('name')
        ]));
    }

    /**
     * Dedicated method for file rename
     */
    public function update(Request $request, $id)
    {
        return $this->renameItem(new Request([
            'type' => 'file',
            'id' => $id,
            'new_name' => $request->input('name')
        ]));
    }

    /**
     * Get file details for insertion
     */
    public function getFileForInsertion($id)
    {
        $file = Media::findOrFail($id);

        return response()->json([
            'success' => true,
            'file' => [
                'id' => $file->id,
                'name' => $file->name,
                'url' => Storage::disk($this->disk)->url($file->directory ? $file->directory.'/'.$file->file_name : $file->file_name),
                'thumb_url' => $this->getThumbUrl($file),
                'type' => 'file',
                'mime_type' => $file->mime_type,
                'size' => $this->formatFileSize($file->size),
                'dimensions' => $file->dimensions,
                'created_at' => $file->created_at->format('d-m-Y'),
                'updated_at' => $file->updated_at->format('d-m-Y')
            ]
        ]);
    }

}
