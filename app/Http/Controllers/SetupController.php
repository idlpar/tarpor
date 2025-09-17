<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SetupController extends Controller
{
    public function linkStorage()
    {
        try {
            $publicStoragePath = public_path('storage');
            \Log::info('Public storage path: ' . $publicStoragePath);

            if (file_exists($publicStoragePath)) {
                \Log::info('public/storage exists.');
                if (is_link($publicStoragePath)) {
                    \Log::info('public/storage is a symlink.');
                    if (is_dir($publicStoragePath)) {
                        \Log::info('public/storage is a directory symlink.');
                        $command = 'rmdir "' . $publicStoragePath . '"';
                        \Log::info('Executing command: ' . $command);
                        $output = null;
                        $returnVar = null;
                        exec($command, $output, $returnVar);
                        if ($returnVar !== 0) {
                            \Log::error('Failed to remove directory symlink.', ['output' => $output]);
                            throw new \Exception('Failed to remove existing directory symbolic link: ' . implode("\n", $output));
                        }
                        \Log::info('Directory symlink removed successfully.');
                    } else {
                        \Log::info('public/storage is a file symlink.');
                        if (unlink($publicStoragePath)) {
                            \Log::info('File symlink removed successfully.');
                        } else {
                            \Log::error('Failed to remove file symlink.');
                            throw new \Exception('Failed to remove existing file symbolic link.');
                        }
                    }
                } elseif (is_dir($publicStoragePath)) {
                    \Log::warning('public/storage is a real directory.');
                    return 'Error: public/storage is a real directory. Please delete it manually if you wish to create a symbolic link. Its contents will NOT be deleted by this script.';
                } else {
                    \Log::info('public/storage is a regular file.');
                    if (unlink($publicStoragePath)) {
                        \Log::info('Regular file removed successfully.');
                    } else {
                        \Log::error('Failed to remove regular file.');
                        throw new \Exception('Failed to remove existing file.');
                    }
                }
            }

            \Log::info('Calling storage:link artisan command.');
            Artisan::call('storage:link');
            \Log::info('storage:link command executed successfully.');
            return 'Storage link created/updated successfully.';
        } catch (\Exception $e) {
            \Log::error('Failed to create/update storage link.', ['error' => $e->getMessage()]);
            return 'Failed to create/update storage link: ' . $e->getMessage();
        }
    }
}
