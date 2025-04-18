<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RenameFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    public $maxExceptions = 2;

    protected $media;
    protected $oldFileName;
    protected $newFileName;
    protected $conversions;

    public function __construct(Media $media, string $oldFileName, string $newFileName, array $conversions)
    {
        $this->media = $media->withoutRelations();
        $this->oldFileName = $oldFileName;
        $this->newFileName = $newFileName;
        $this->conversions = $conversions;
    }

    public function handle()
    {
        try {
            $disk = Storage::disk($this->media->disk);
            $basePath = $this->media->directory ? $this->media->directory.'/' : '';

            // 1. Rename original file
            $this->renameFile($disk, $basePath, $this->oldFileName, $this->newFileName);

            // 2. Rename all conversions
            foreach ($this->conversions as $conversion => $settings) {
                $conversionPath = $basePath.$conversion.'/';
                $this->renameFile($disk, $conversionPath, $this->oldFileName, $this->newFileName);
            }

            // 3. For videos, rename thumbnail
            if (str_starts_with($this->media->mime_type, 'video/')) {
                $thumbPath = $basePath.'thumb/';
                $this->renameFile($disk, $thumbPath, $this->oldFileName.'.jpg', $this->newFileName.'.jpg');
            }

            Log::info("File rename completed successfully", [
                'media_id' => $this->media->id,
                'old_name' => $this->oldFileName,
                'new_name' => $this->newFileName
            ]);

        } catch (\Exception $e) {
            Log::error("File rename job failed", [
                'media_id' => $this->media->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function renameFile($disk, $path, $oldName, $newName)
    {
        $oldPath = $path.$oldName;
        $newPath = $path.$newName;

        if ($disk->exists($oldPath)) {
            $disk->move($oldPath, $newPath);
            Log::info("Renamed file", [
                'from' => $oldPath,
                'to' => $newPath
            ]);
        } else {
            Log::warning("File not found for rename", ['path' => $oldPath]);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('RenameFileJob failed permanently', [
            'media_id' => $this->media->id,
            'error' => $exception->getMessage()
        ]);
    }
}
