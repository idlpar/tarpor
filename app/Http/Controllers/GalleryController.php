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
    // Default disk and root path
    protected $disk = 'public';
    protected $rootPath = 'gallery';

    // Conversion sizes
    protected $conversions = [
        'thumb' => ['width' => 150, 'height' => 150, 'quality' => 70],
        'small' => ['width' => 300, 'height' => 300, 'quality' => 75],
        'medium' => ['width' => 800, 'height' => 800, 'quality' => 80],
        'large' => ['width' => 1200, 'height' => 1200, 'quality' => 85],
    ];

    // Allowed mime types
    protected $allowedMimeTypes = [
        'image' => ['jpeg', 'jpg', 'png', 'gif', 'webp', 'svg'],
        'video' => ['mp4', 'mov', 'avi', 'mkv', 'webm'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
    ];

    // Image manager instance
    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Display the main gallery interface
     */
    public function index(Request $request)
    {
        $currentFolder = $request->get('folder', '');
        $searchTerm = $request->get('search', '');

        // Get folders and files for the current directory
        $folders = MediaFolder::where('path', 'like', $currentFolder . '%')
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $files = Media::where('directory', $currentFolder)
            ->when($searchTerm, function($query) use ($searchTerm) {
                return $query->where('file_name', 'like', "%{$searchTerm}%")
                    ->orWhere('name', 'like', "%{$searchTerm}%");
            })
            ->whereNull('deleted_at')
            ->orderByDesc('is_featured')
            ->orderBy('created_at', 'desc')
            ->paginate(48);

        $breadcrumbs = $this->getBreadcrumbs($currentFolder);

        return response()->json([
            'folders' => $folders,
            'files' => $files,
            'breadcrumbs' => $breadcrumbs,
            'current_folder' => $currentFolder,
            'storage_url' => Storage::disk($this->disk)->url('')
        ]);
    }

    /**
     * Upload files to the gallery
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:' . (1024 * 20), // 20MB max
            'folder' => 'nullable|string',
            'model_type' => 'nullable|string',
            'model_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $folder = $request->input('folder', '');
        $uploadedFiles = [];
        $currentMaxOrder = Media::max('order_column') ?? 0;

        DB::beginTransaction();

        try {
            foreach ($request->file('files') as $file) {
                // Sanitize filename (convert "Red Car.jpg" to "red-car.jpg")
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
                $fileName = $this->generateUniqueFilename($folder, $baseName, $extension);

                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();
                $fileHash = md5_file($file->getRealPath());

                // Check for duplicates
                if ($existingFile = Media::where('file_hash', $fileHash)->first()) {
                    $uploadedFiles[] = $existingFile;
                    continue;
                }

                // Store the original file
                $path = $file->storeAs(
                    $this->rootPath . ($folder ? '/' . $folder : ''),
                    $fileName,
                    $this->disk
                );

                // Create complete media record with ALL fields
                $media = new Media([
                    'model_type' => $request->input('model_type', 'App\Models\User'), // Nullable
                    'model_id' => $request->input('model_id', auth()->id()),     // Nullable
                    'uuid' => Str::uuid(),
                    'collection_name' => 'gallery',
                    'name' => $baseName,
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
                    'is_featured' => false,
                    'alt_text' => null,
                    'caption' => null,
                    'title' => null,
                    'duration' => null,
                    'dimensions' => null,
                    'generated_conversions' => []
                ]);

                // Process image/video
                if (Str::startsWith($mimeType, 'image/')) {
                    $this->processImage($file, $folder, $fileName, $media);
                } elseif (Str::startsWith($mimeType, 'video/')) {
                    $this->processVideo($file, $folder, $fileName, $media);
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

    /**
     * Process image files and create conversions
     */
    protected function processImage($file, $folder, $fileName, $media)
    {
        try {
            $image = $this->imageManager->read($file->getRealPath());
            $dimensions = ['width' => $image->width(), 'height' => $image->height()];
            $media->dimensions = $dimensions;

            $conversions = [];
            $extension = strtolower($file->getClientOriginalExtension());

            foreach ($this->conversions as $conversionName => $settings) {
                $conversionPath = $this->rootPath . ($folder ? '/' . $folder : '') . '/' . $conversionName;

                if (!Storage::disk($this->disk)->exists($conversionPath)) {
                    Storage::disk($this->disk)->makeDirectory($conversionPath);
                }

                // Create the conversion
                $conversionImage = $image->scaleDown($settings['width'], $settings['height']);

                // Encode and save the image
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
     * Get SVG dimensions if possible
     */
    protected function getSvgDimensions($file)
    {
        try {
            $content = file_get_contents($file->getRealPath());
            if (preg_match('/<svg[^>]*width="([^"]*)"[^>]*height="([^"]*)"/i', $content, $matches)) {
                return [
                    'width' => (int)$matches[1],
                    'height' => (int)$matches[2]
                ];
            }
            return ['width' => null, 'height' => null];
        } catch (\Exception $e) {
            return ['width' => null, 'height' => null];
        }
    }

    /**
     * Process video files
     */
    protected function processVideo($file, $folder, $fileName, $media)
    {
        // You'll need to install a package like php-ffmpeg/php-ffmpeg
        // This is just a placeholder for actual implementation
        $media->duration = $this->getVideoDuration($file);
        $media->generated_conversions = ['thumb' => true]; // If you generate thumbnails
        $media->dimensions = $this->getVideoDimensions($file);
    }

    /**
     * Generate a unique filename handling duplicates
     */
    protected function generateUniqueFilename($folder, $baseName, $extension)
    {
        $counter = 1;
        $originalFileName = "{$baseName}.{$extension}";
        $fileName = $originalFileName;

        while (
            Media::where('file_name', $fileName)
                ->where('directory', $folder)
                ->exists() ||
            Storage::disk($this->disk)->exists($this->rootPath . ($folder ? '/' . $folder : '') . '/' . $fileName)
        ) {
            $fileName = "{$baseName}-{$counter}.{$extension}";
            $counter++;
        }

        return $fileName;
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

        $parent = $request->input('parent', '');
        $folderName = Str::slug($request->input('name'));
        $fullPath = $this->rootPath . ($parent ? '/' . $parent : '') . '/' . $folderName;

        // Check if folder already exists
        if (Storage::disk($this->disk)->exists($fullPath)) {
            return response()->json(['error' => 'Folder already exists'], 409);
        }

        try {
            // Create physical folder
            Storage::disk($this->disk)->makeDirectory($fullPath);

            // Create database record
            $folder = MediaFolder::create([
                'name' => $request->input('name'),
                'path' => ($parent ? $parent . '/' : '') . $folderName,
                'parent_id' => $this->getParentFolderId($parent),
            ]);

            return response()->json([
                'success' => true,
                'folder' => $folder,
                'message' => 'Folder created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Folder creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get parent folder ID from path
     */
    protected function getParentFolderId($path)
    {
        if (empty($path)) return null;

        $folder = MediaFolder::where('path', $path)->first();
        return $folder ? $folder->id : null;
    }

    /**
     * Get breadcrumbs for current folder
     */
    protected function getBreadcrumbs($currentFolder)
    {
        $breadcrumbs = [];
        $parts = explode('/', $currentFolder);
        $accumulatedPath = '';

        foreach ($parts as $part) {
            if (empty($part)) continue;

            $accumulatedPath .= ($accumulatedPath ? '/' : '') . $part;
            $folder = MediaFolder::where('path', $accumulatedPath)->first();

            if ($folder) {
                $breadcrumbs[] = [
                    'name' => $folder->name,
                    'path' => $accumulatedPath
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Delete a media item
     */
    public function destroy(Media $media)
    {
        try {
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media moved to trash'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a media item from trash
     */
    public function restore($id)
    {
        try {
            $media = Media::withTrashed()->findOrFail($id);
            $media->restore();

            return response()->json([
                'success' => true,
                'message' => 'Media restored successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore media: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a media item
     */
    public function forceDelete($id)
    {
        try {
            $media = Media::withTrashed()->findOrFail($id);

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

            return response()->json([
                'success' => true,
                'message' => 'Media permanently deleted'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete media: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set a media item as featured
     */
    public function setFeatured(Media $media)
    {
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
     * Get contents of a specific folder
     */
    public function getFolderContents($folderPath)
    {
        try {
            $files = Media::where('directory', $folderPath)
                ->whereNull('deleted_at')
                ->orderByDesc('is_featured')
                ->orderBy('created_at', 'desc')
                ->get();

            $subfolders = MediaFolder::where('path', 'like', $folderPath . '/%')
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'files' => $files,
                'subfolders' => $subfolders,
                'current_folder' => $folderPath,
                'breadcrumbs' => $this->getBreadcrumbs($folderPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load folder contents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View trashed items
     */
    public function trash()
    {
        try {
            $trashedItems = Media::onlyTrashed()
                ->orderBy('deleted_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'items' => $trashedItems
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load trash: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a public URL for a media item
     */
    public function generateUrl(Media $media)
    {
        try {
            $url = Storage::disk($media->disk)->url(
                $this->rootPath .
                ($media->directory ? '/' . $media->directory : '') .
                '/' . $media->file_name
            );

            return response()->json([
                'success' => true,
                'url' => $url,
                'html' => '<img src="' . $url . '" alt="' . ($media->alt_text ?? $media->name) . '">'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate URL: ' . $e->getMessage()
            ], 500);
        }
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
            'target_folder' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            foreach ($request->input('items') as $item) {
                if ($item['type'] === 'file') {
                    $this->moveFile($item['id'], $request->input('target_folder'));
                } else {
                    $this->moveFolder($item['id'], $request->input('target_folder'));
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
    protected function moveFile($fileId, $targetFolder)
    {
        $media = Media::findOrFail($fileId);

        // Skip if already in target folder
        if ($media->directory === $targetFolder) {
            return;
        }

        $oldPath = $this->rootPath . ($media->directory ? '/' . $media->directory : '') . '/' . $media->file_name;
        $newPath = $this->rootPath . ($targetFolder ? '/' . $targetFolder : '') . '/' . $media->file_name;

        // Check for filename conflicts
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
                    ($targetFolder ? '/' . $targetFolder : '') .
                    '/' . $conversion . '/' . $media->file_name;

                Storage::disk($this->disk)->move($oldConversionPath, $newConversionPath);
            }
        }

        // Update database record
        $media->directory = $targetFolder;
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

        // Skip if already in target location
        if ($oldPath === $newPath) {
            return;
        }

        // Check if target path exists
        if (MediaFolder::where('path', $newPath)->exists()) {
            throw new \Exception("A folder with this name already exists in the target location");
        }

        // Update folder path in database
        $folder->path = $newPath;
        $folder->parent_id = $this->getParentFolderId($targetPath);
        $folder->save();

        // Move all files in the folder
        $mediaInFolder = Media::where('directory', $oldPath)->get();
        foreach ($mediaInFolder as $media) {
            $media->directory = $newPath;
            $media->save();
        }

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
    }
}
