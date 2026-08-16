<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // The API is pure JSON — there is no 'login' web route to redirect
        // guests to. Returning null for /api/* makes Laravel throw
        // AuthenticationException, which the framework's exception handler
        // renders as a 401 JSON response (even for Accept: text/event-stream,
        // which would otherwise make expectsJson() false and trigger the
        // missing-named-route 500 we were seeing).
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }

            return null;
        });

        // Route alias for admin-only endpoints. Always run AFTER auth:sanctum
        // so $request->user() is the authenticated user. Non-admin users
        // (including guest tokens) get a clean 403 JSON response — never
        // let an unauthorized caller reach the controller body.
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Render AuthenticationException as JSON for API consumers regardless
        // of their Accept header (the SSE client sends text/event-stream,
        // which Laravel wouldn't otherwise classify as a JSON request).
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        });
    })->create();
