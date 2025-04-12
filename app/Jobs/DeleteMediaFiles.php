<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteMediaFiles implements ShouldQueue
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
                    $this->deleteMediaFile($item);
                } else {
                    // Handle folder deletion if needed
                }
            }
        } catch (\Exception $e) {
            \Log::error('DeleteMediaFiles job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function deleteMediaFile(Media $media)
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
            \Log::error('Failed to delete files for media ID: '.$media->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('DeleteMediaFiles job failed completely', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
