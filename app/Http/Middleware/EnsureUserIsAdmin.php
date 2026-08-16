<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate /admin/* routes to authenticated users whose role is "admin".
 *
 * Runs AFTER auth:sanctum so $request->user() is guaranteed. Non-admins
 * get 403 (NOT 401) — they're authenticated, just not authorized.
 * Unauthenticated callers never reach this middleware (auth:sanctum short
 * circuits with 401 in the framework's exception handler).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'admin') {
            return response()->json([
                'error' => 'Admin role required to access this endpoint.',
                'code' => 'forbidden',
            ], 403);
        }

        return $next($request);
    }
}