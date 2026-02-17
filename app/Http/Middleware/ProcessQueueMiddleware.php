<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessQueueMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get response first
        $response = $next($request);
        
        // Process queue jobs after response (non-blocking for user)
        register_shutdown_function(function() {
            try {
                // Only process if there are jobs in queue
                if (DB::table('jobs')->count() > 0) {
                    Artisan::call('queue:work', [
                        '--stop-when-empty' => true,
                        '--max-time' => 30,
                        '--tries' => 3,
                    ]);
                }
            } catch (\Exception $e) {
                // Silently fail - don't affect user experience
                Log::error('Queue processing failed: ' . $e->getMessage());
            }
        });
        
        return $response;
    }
}
