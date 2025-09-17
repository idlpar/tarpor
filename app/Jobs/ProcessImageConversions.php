<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessImageConversions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    public $maxExceptions = 2;

    protected $media;
    protected $conversions;

    public function __construct(Media $media, array $conversions)
    {
        $this->media = $media;
        $this->conversions = $conversions;
    }

    public function handle()
    {
        try {
            $imageManager = new ImageManager(new Driver());
            $filePath = $this->media->directory
                ? $this->media->directory.'/'.$this->media->file_name
                : $this->media->file_name;

            if (!Storage::disk($this->media->disk)->exists($filePath)) {
                throw new \Exception("Original image file not found");
            }

            $image = $imageManager->read(Storage::disk($this->media->disk)->get($filePath));

            $generatedConversions = [];
            $manipulations = [];
            $responsiveImages = [];

            foreach ($this->conversions as $conversionName => $settings) {
                $conversionPath = $this->media->directory
                    ? $this->media->directory.'/'.$conversionName
                    : $conversionName;

                Storage::disk($this->media->disk)->makeDirectory($conversionPath);

                $conversionImage = $image->scaleDown($settings['width'], $settings['height']);
                $encodedImage = $conversionImage->encodeByExtension(
                    pathinfo($this->media->file_name, PATHINFO_EXTENSION),
                    $settings['quality']
                );

                Storage::disk($this->media->disk)->put(
                    $conversionPath.'/'.$this->media->file_name,
                    $encodedImage
                );

                $generatedConversions[$conversionName] = true;
                $manipulations[$conversionName] = [
                    'width' => $settings['width'],
                    'height' => $settings['height'],
                    'quality' => $settings['quality'],
                    'format' => pathinfo($this->media->file_name, PATHINFO_EXTENSION),
                    'created_at' => now()->toDateTimeString()
                ];

                $responsiveImages[$conversionName] = [
                    'urls' => [
                        Storage::disk($this->media->disk)->url($conversionPath.'/'.$this->media->file_name)
                    ],
                    'base64svg' => '',
                    'width' => $settings['width'],
                    'height' => $settings['height']
                ];
            }

            // Update the media record with conversion data
            $this->media->update([
                'manipulations' => $manipulations,
                'generated_conversions' => $generatedConversions,
                'responsive_images' => $responsiveImages,
                'dimensions' => [
                    'width' => $image->width(),
                    'height' => $image->height(),
                    'aspect_ratio' => $image->width() / $image->height()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Image conversion failed for media ID: '.$this->media->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('ProcessImageConversions job failed for media ID: '.$this->media->id, [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
