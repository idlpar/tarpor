<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class QueueController extends Controller
{
    public function process(Request $request)
    {
        if (config('queue.default') !== 'database') {
            Log::info('Queue processing skipped: Not using database driver.');
            return response()->json(['status' => 'skipped', 'message' => 'Queue driver is not database.']);
        }

        try {
            Artisan::call('queue:work', [
                '--queue' => 'default',
                '--once' => true,
                '--stop-when-empty' => true,
            ]);
            Log::info('Queue processed successfully via HTTP.');
            return response()->json(['status' => 'success', 'message' => 'Queue processed successfully.']);
        } catch (\Exception $e) {
            Log::error('Queue processing failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Queue processing failed: ' . $e->getMessage()], 500);
        }
    }
}
