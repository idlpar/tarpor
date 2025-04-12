<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class DeleteFolderContents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300; // 5 minutes
    public $maxExceptions = 1;

    protected $folderPath;
    protected $files;
    protected $conversions;

    public function __construct(string $folderPath, $files, array $conversions)
    {
        $this->folderPath = $folderPath;
        $this->files = $files;
        $this->conversions = $conversions;
    }

    public function handle()
    {
        try {
            // Delete all files and their conversions
            foreach ($this->files as $file) {
                $this->deleteFileAndConversions($file);
            }

            // Delete the folder and all subfolders
            $this->deleteFolderStructure();

        } catch (\Exception $e) {
            \Log::error('Failed to delete folder contents: '.$e->getMessage());
            throw $e;
        }
    }

    protected function deleteFileAndConversions(Media $media)
    {
        try {
            // Delete original file
            $filePath = $media->directory
                ? $media->directory.'/'.$media->file_name
                : $media->file_name;

            if (Storage::disk($media->disk)->exists($filePath)) {
                Storage::disk($media->disk)->delete($filePath);
            }

            // Delete conversions
            foreach ($this->conversions as $conversion => $settings) {
                $conversionPath = ($media->directory
                        ? $media->directory.'/'.$conversion
                        : $conversion).'/'.$media->file_name;

                if (Storage::disk($media->disk)->exists($conversionPath)) {
                    Storage::disk($media->disk)->delete($conversionPath);
                }
            }

            // Delete any thumbnails (for videos)
            $thumbPath = $media->directory
                ? $media->directory.'/thumb/'.$media->file_name.'.jpg'
                : 'thumb/'.$media->file_name.'.jpg';

            if (Storage::disk($media->disk)->exists($thumbPath)) {
                Storage::disk($media->disk)->delete($thumbPath);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to delete files for media ID: '.$media->id);
            throw $e;
        }
    }

    protected function deleteFolderStructure()
    {
        try {
            $disk = Storage::disk('public'); // or whatever disk you're using

            // Delete all conversion subdirectories first
            foreach ($this->conversions as $conversion => $settings) {
                $conversionPath = $this->folderPath.'/'.$conversion;
                if ($disk->exists($conversionPath)) {
                    $disk->deleteDirectory($conversionPath);
                }
            }

            // Delete thumb directory if exists
            $thumbPath = $this->folderPath.'/thumb';
            if ($disk->exists($thumbPath)) {
                $disk->deleteDirectory($thumbPath);
            }

            // Finally delete the main folder
            if ($disk->exists($this->folderPath)) {
                $disk->deleteDirectory($this->folderPath);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to delete folder structure: '.$this->folderPath);
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('DeleteFolderContents job failed for folder: '.$this->folderPath, [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
