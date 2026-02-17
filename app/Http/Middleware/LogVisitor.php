<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Auth;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Skip admin routes, API routes, and AJAX requests
        if (!$request->is('admin/*') && !$request->ajax() && $request->method() === 'GET') {
            $userAgent = $request->userAgent() ?? '';
            
            VisitorLog::create([
                'ip_address' => $request->ip(),
                'user_id' => Auth::id(),
                'session_id' => session()->getId(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => substr($userAgent, 0, 500),
                'device_type' => VisitorLog::detectDevice($userAgent),
                'browser' => VisitorLog::detectBrowser($userAgent),
                'platform' => VisitorLog::detectPlatform($userAgent),
                'referrer' => $request->header('referer') ? substr($request->header('referer'), 0, 500) : null,
            ]);
        }

        return $next($request);
    }
}
