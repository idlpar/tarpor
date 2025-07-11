<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RenameFolderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    protected $oldPath;
    protected $newPath;

    public function __construct(string $oldPath, string $newPath)
    {
        $this->oldPath = $oldPath;
        $this->newPath = $newPath;
    }

    public function handle()
    {
        $disk = Storage::disk('public');

        try {
            Log::info("Attempting to rename folder", [
                'old_path' => $this->oldPath,
                'new_path' => $this->newPath,
                'exists' => $disk->exists($this->oldPath)
            ]);

            // Verify paths are different
            if ($this->oldPath === $this->newPath) {
                Log::warning("Old and new paths are identical, skipping");
                return;
            }

            // Check if source exists
            if (!$disk->exists($this->oldPath)) {
                throw new \Exception("Source folder does not exist: {$this->oldPath}");
            }

            // Check if destination exists
            if ($disk->exists($this->newPath)) {
                throw new \Exception("Target folder already exists: {$this->newPath}");
            }

            Log::info("Attempting disk move operation", [
                'old_path' => $this->oldPath,
                'new_path' => $this->newPath
            ]);
            // Perform the actual rename
            $success = $disk->move($this->oldPath, $this->newPath);
            Log::info("Disk move operation result", [
                'success' => $success,
                'old_path' => $this->oldPath,
                'new_path' => $this->newPath
            ]);

            if (!$success) {
                throw new \Exception("Failed to rename folder from {$this->oldPath} to {$this->newPath}");
            }

            Log::info("Folder renamed successfully", [
                'old_path' => $this->oldPath,
                'new_path' => $this->newPath
            ]);

        } catch (\Exception $e) {
            Log::error("Folder rename failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
