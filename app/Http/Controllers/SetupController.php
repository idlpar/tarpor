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

            if (file_exists($publicStoragePath)) {
                if (is_link($publicStoragePath)) {
                    // It's a symbolic link (either file or directory)
                    // On Windows, unlink() fails for directory symlinks (junctions).
                    // rmdir is needed for directory symlinks/junctions.
                    if (is_dir($publicStoragePath)) { // Check if it's a directory link (junction)
                        $command = 'rmdir "' . $publicStoragePath . '"';
                        $output = null;
                        $returnVar = null;
                        exec($command, $output, $returnVar);
                        if ($returnVar !== 0) {
                            throw new \Exception('Failed to remove existing directory symbolic link: ' . implode("\n", $output));
                        }
                    } else {
                        // It's a file symbolic link, unlink() should work
                        unlink($publicStoragePath);
                    }
                } elseif (is_dir($publicStoragePath)) {
                    // It's a real directory, not a symbolic link. User wants to keep its contents.
                    return 'Error: public/storage is a real directory. Please delete it manually if you wish to create a symbolic link. Its contents will NOT be deleted by this script.';
                } else {
                    // It's a regular file, not a directory or link.
                    unlink($publicStoragePath);
                }
            }

            // Then create the link
            Artisan::call('storage:link');
            return 'Storage link created/updated successfully.';
        } catch (\Exception $e) {
            return 'Failed to create/update storage link: ' . $e->getMessage();
        }
    }
}
