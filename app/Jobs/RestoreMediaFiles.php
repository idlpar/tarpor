<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RestoreMediaFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    public $maxExceptions = 2;

    protected $items;
    protected $conversions;

    public function __construct($items, array $conversions)
    {
        $this->items = $items;
        $this->conversions = $conversions;
    }

    public function handle()
    {
        try {
            foreach ($this->items as $item) {
                if ($item instanceof Media) {
                    $this->restoreMediaFile($item);
                }
            }
        } catch (\Exception $e) {
            \Log::error('RestoreMediaFiles job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function restoreMediaFile(Media $media)
    {
        try {
            $filePath = $media->directory
                ? $media->directory.'/'.$media->file_name
                : $media->file_name;

            // Restore the media record
            $media->restore();

            // Check if original file exists
            if (!Storage::disk($media->disk)->exists($filePath)) {
                \Log::warning('Original file not found for restoration. Media record restored, but physical file is missing.', [
                    'media_id' => $media->id,
                    'file_path' => $filePath,
                    'disk' => $media->disk
                ]);
                // Still restore the media record in the database
                $media->restore();
                return;
            }

            // For images, regenerate conversions if missing
            if (Str::startsWith($media->mime_type, 'image/')) {
                $this->regenerateImageConversions($media);
            }

            // For videos, regenerate thumbnail if missing
            if (Str::startsWith($media->mime_type, 'video/')) {
                $this->regenerateVideoThumbnail($media);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to restore files for media ID: '.$media->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function regenerateImageConversions(Media $media)
    {
        $imageManager = new ImageManager(new Driver());
        $filePath = $media->directory
            ? $media->directory.'/'.$media->file_name
            : $media->file_name;

        $image = $imageManager->read(Storage::disk($media->disk)->get($filePath));

        foreach ($this->conversions as $conversionName => $settings) {
            $conversionPath = $media->directory
                ? $media->directory.'/'.$conversionName
                : $conversionName;

            $conversionFilePath = $conversionPath.'/'.$media->file_name;

            if (!Storage::disk($media->disk)->exists($conversionFilePath)) {
                Storage::disk($media->disk)->makeDirectory($conversionPath);

                $conversionImage = $image->scaleDown($settings['width'], $settings['height']);
                $encodedImage = $conversionImage->encodeByExtension(
                    pathinfo($media->file_name, PATHINFO_EXTENSION),
                    $settings['quality']
                );

                Storage::disk($media->disk)->put($conversionFilePath, $encodedImage);
            }
        }
    }

    protected function regenerateVideoThumbnail(Media $media)
    {
        $thumbPath = $media->directory
            ? $media->directory.'/thumb/'.$media->file_name.'.jpg'
            : 'thumb/'.$media->file_name.'.jpg';

        if (!Storage::disk($media->disk)->exists($thumbPath)) {
            // Implement similar thumbnail generation as in ProcessVideoThumbnail
            // You might want to dispatch a new ProcessVideoThumbnail job instead
            ProcessVideoThumbnail::dispatch($media)->onQueue('default');
        }
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('RestoreMediaFiles job failed completely', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
