<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        Media::create([
            'model_type' => 'App\Models\Product',
            'model_id' => 1,
            'uuid' => \Str::uuid(),
            'collection_name' => 'images',
            'name' => 'kids-t-shirt',
            'file_name' => 'kids-t-shirt.jpg',
            'file_hash' => md5('kids-t-shirt'),
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'size' => 102400,
            'dimensions' => json_encode(['width' => 800, 'height' => 600]),
            'alt_text' => 'Kids T-Shirt Image',
        ]);
    }
}
