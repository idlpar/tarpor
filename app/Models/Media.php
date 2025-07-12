<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'model_type',
        'model_id',
        'uuid',
        'collection_name',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'conversions_disk',
        'size',
        'dimensions',
        'duration',
        'alt_text',
        'order_column',
        'caption',
        'title',
        'manipulations',
        'custom_properties',
        'generated_conversions',
        'responsive_images',
        'directory',
        'file_hash',
        'is_featured',
    ];

    protected $casts = [
        'dimensions' => 'array',
        'manipulations' => 'array',
        'custom_properties' => 'array',
        'generated_conversions' => 'array',
        'responsive_images' => 'array',
        'is_featured' => 'boolean',
        'size' => 'integer',
    ];

    protected $appends = ['url', 'thumb_url', 'medium_url'];

    /**
     * Get the owning model.
     */
    public function model()
    {
        return $this->morphTo();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_media')->withPivot('type', 'order');
    }

    /**
     * Get the full URL to the original file.
     */
    public function getUrlAttribute()
    {
        return $this->getFullUrl();
    }

    /**
     * Get the full URL to the thumbnail conversion.
     */
    public function getThumbUrlAttribute()
    {
        return $this->getFullUrl('thumb');
    }

    /**
     * Get the full URL to the medium conversion.
     */
    public function getMediumUrlAttribute()
    {
        return $this->getFullUrl('medium');
    }

    /**
     * Get the full URL for the file.
     */
    // In Media model
    public function getFullUrl(string $conversion = '')
    {
        $path = $this->directory ? $this->directory.'/' : '';

        if ($conversion && isset($this->generated_conversions[$conversion])) {
            $path .= "{$conversion}/{$this->file_name}";
        } else {
            $path .= $this->file_name;
        }

        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Get the full path to the file.
     */
    public function getFullPath(string $conversion = ''): string
    {
        $path = $this->directory ? "{$this->directory}/" : '';

        if ($conversion && isset($this->generated_conversions[$conversion]) && $this->generated_conversions[$conversion]) {
            $path .= "{$conversion}/{$this->file_name}";
        } else {
            $path .= $this->file_name;
        }

        return Storage::disk($this->disk)->path($path);
    }

    /**
     * Scope a query to only include featured media.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include media in a specific directory.
     */
    public function scopeInDirectory($query, string $directory)
    {
        return $query->where('directory', $directory);
    }

    /**
     * Scope a query to only include media of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('mime_type', 'like', "{$type}/%");
    }

    /**
     * Get the folder that contains this media.
     */
    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'directory', 'path');
    }

    /**
     * Determine if the media is an image.
     */
    public function isImage(): bool
    {
        return strpos($this->mime_type, 'image/') === 0;
    }

    /**
     * Determine if the media is a video.
     */
    public function isVideo(): bool
    {
        return strpos($this->mime_type, 'video/') === 0;
    }

    /**
     * Determine if the media is a document.
     */
    public function isDocument(): bool
    {
        $documentTypes = ['application/pdf', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint'];
        return in_array($this->mime_type, $documentTypes) ||
            strpos($this->mime_type, 'application/vnd.openxmlformats-officedocument') === 0;
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

//    public function verifyFiles()
//    {
//        $disk = Storage::disk($this->disk);
//        $basePath = $this->directory ? $this->directory.'/' : '';
//
//        $missing = [];
//
//        // Check original file
//        if (!$disk->exists($basePath.$this->file_name)) {
//            $missing[] = 'Original file';
//        }
//
//        // Check conversions
//        foreach ($this->manipulations ?? [] as $conversion => $settings) {
//            if (!$disk->exists($basePath.$conversion.'/'.$this->file_name)) {
//                $missing[] = $conversion.' conversion';
//            }
//        }
//
//        // Check video thumbnail
//        if (str_starts_with($this->mime_type, 'video/')) {
//            $thumbPath = $basePath.'thumb/'.$this->file_name.'.jpg';
//            if (!$disk->exists($thumbPath)) {
//                $missing[] = 'Video thumbnail';
//            }
//        }
//
//        return [
//            'valid' => empty($missing),
//            'missing' => $missing
//        ];
//    }
}
