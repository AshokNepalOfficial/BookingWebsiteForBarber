<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class QueueProcessorController extends Controller
{
     /**
     * Process the email queue via web trigger
     * URL: /run-queue/{secret_key}
     */
    public function process($secretKey)
    {
        // Verify secret key
        $expectedKey = config('app.queue_secret_key');
        
        if (!$expectedKey || $secretKey !== $expectedKey) {
            Log::warning('Unauthorized queue processor access attempt', [
                'ip' => request()->ip(),
                'provided_key' => $secretKey
            ]);
            
            // Return 1x1 transparent pixel for failed auth (so browser doesn't show error)
            return response()->make(
                base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'),
                403,
                ['Content-Type' => 'image/gif']
            );
        }
        
        // If this is an image request, process async and return pixel immediately
        if (request()->wantsJson() === false) {
            // Spawn background process (works on most shared hosting)
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows
                pclose(popen('start /B php "' . base_path('artisan') . '" queue:work --stop-when-empty --max-time=30 --tries=3', 'r'));
            } else {
                // Linux/Unix
                exec('php ' . base_path('artisan') . ' queue:work --stop-when-empty --max-time=30 --tries=3 > /dev/null 2>&1 &');
            }
            
            // Return 1x1 transparent GIF immediately
            return response()->make(
                base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'),
                200,
                ['Content-Type' => 'image/gif', 'Cache-Control' => 'no-cache, no-store, must-revalidate']
            );
        }
        
        // For API/JSON requests, process synchronously
        try {
            // Run Laravel scheduler (which processes the queue)
            Artisan::call('schedule:run');
            
            $output = Artisan::output();
            
            Log::info('Scheduler triggered successfully via web', [
                'ip' => request()->ip()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Scheduler executed successfully',
                'output' => $output,
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Scheduler execution failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Scheduler execution failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
