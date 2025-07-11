<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Console\Command;

class FixMediaDirectoryPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:fix-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds media records with missing directory paths and attempts to fix them.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mediaWithoutDirectory = Media::whereNull('directory')->orWhere('directory', '')->get();

        if ($mediaWithoutDirectory->isEmpty()) {
            $this->info('No media records with missing directory paths found. Nothing to do.');
            return;
        }

        $this->info("Found {$mediaWithoutDirectory->count()} media records with missing directory paths. Attempting to fix...");

        $bar = $this->output->createProgressBar($mediaWithoutDirectory->count());
        $bar->start();

        foreach ($mediaWithoutDirectory as $media) {
            if ($media->model_type === Product::class && $media->model_id) {
                $directory = '';
                if ($media->collection_name === 'thumbnails') {
                    $directory = 'products/thumbnails/' . $media->model_id;
                } else {
                    $directory = 'products/' . $media->model_id;
                }

                $media->directory = $directory;
                $media->save();
            } else {
                $this->warn("Skipping Media ID: {$media->id} (unsupported model_type '{$media->model_type}' or missing model_id)");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Finished fixing media directory paths.');
    }
}