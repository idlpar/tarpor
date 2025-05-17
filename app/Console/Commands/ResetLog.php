<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ResetLog extends Command
{
    protected $signature = 'log:reset';
    protected $description = 'Delete and recreate laravel.log file';

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');

        if (File::exists($logPath)) {
            File::delete($logPath);
            $this->info('Log file deleted.');
        }

        File::put($logPath, '');
        $this->info('Log file recreated.');

        // Add a test log entry
        Log::info('Laravel log file has been reset.');
    }
}
