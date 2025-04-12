<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class ProcessVideoThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 180;
    public $maxExceptions = 2;

    protected $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    public function handle()
    {
        try {
            $filePath = $this->media->directory
                ? $this->media->directory.'/'.$this->media->file_name
                : $this->media->file_name;

            if (!Storage::disk($this->media->disk)->exists($filePath)) {
                throw new \Exception("Original video file not found");
            }

            // Create thumbnails directory if it doesn't exist
            $thumbPath = $this->media->directory
                ? $this->media->directory.'/thumb'
                : 'thumb';
            Storage::disk($this->media->disk)->makeDirectory($thumbPath);

            // Generate thumbnail
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg', // Path may vary on your server
                'ffprobe.binaries' => '/usr/bin/ffprobe' // Path may vary on your server
            ]);

            $video = $ffmpeg->open(Storage::disk($this->media->disk)->path($filePath));

            // Get duration to capture frame at 10% of video
            $duration = $video->getFFProbe()->format(Storage::disk($this->media->disk)->path($filePath))->get('duration');
            $frameTime = $duration * 0.1;

            $thumbnailPath = $thumbPath.'/'.$this->media->file_name.'.jpg';

            $video
                ->frame(TimeCode::fromSeconds($frameTime))
                ->save(Storage::disk($this->media->disk)->path($thumbnailPath));

            // Update media record with thumbnail info
            $this->media->update([
                'custom_properties' => array_merge(
                    $this->media->custom_properties ?? [],
                    [
                        'thumbnail' => $thumbnailPath,
                        'duration' => $duration,
                        'duration_formatted' => gmdate('H:i:s', $duration)
                    ]
                )
            ]);

        } catch (\Exception $e) {
            \Log::error('Video thumbnail generation failed for media ID: '.$this->media->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('ProcessVideoThumbnail job failed for media ID: '.$this->media->id, [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
