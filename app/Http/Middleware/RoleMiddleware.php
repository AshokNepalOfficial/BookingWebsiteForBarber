<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role ?? null;

        // If roles were passed as a single string with commas, flatten them
        $flattenedRoles = [];
        foreach ($roles as $role) {
            if (str_contains($role, ',')) {
                $flattenedRoles = array_merge($flattenedRoles, explode(',', $role));
            } else {
                $flattenedRoles[] = $role;
            }
        }
        $roles = array_map('trim', $flattenedRoles);

        if (!$userRole || !in_array($userRole, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
