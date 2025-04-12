<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessImageConversions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $media;
    protected $conversions;

    public function __construct(Media $media, array $conversions)
    {
        $this->media = $media;
        $this->conversions = $conversions;
    }

    public function handle()
    {
        $this->media->update(['processing_status' => 'processing']);

        try {
            $imageManager = new ImageManager(new Driver());
            $filePath = $this->media->directory
                ? $this->media->directory.'/'.$this->media->file_name
                : $this->media->file_name;

            $image = $imageManager->read(Storage::disk($this->media->disk)->path($filePath));

            // Set dimensions
            $this->media->dimensions = [
                'width' => $image->width(),
                'height' => $image->height(),
                'aspect_ratio' => $image->width() / $image->height()
            ];

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

            $this->media->manipulations = $manipulations;
            $this->media->generated_conversions = $generatedConversions;
            $this->media->responsive_images = $responsiveImages;
            $this->media->processing_status = 'completed';
            $this->media->save();

        } catch (\Exception $e) {
            $this->media->update([
                'processing_status' => 'failed',
                'custom_properties' => array_merge(
                    $this->media->custom_properties ?? [],
                    ['processing_error' => $e->getMessage()]
                )
            ]);
            throw $e;
        }
    }
}
