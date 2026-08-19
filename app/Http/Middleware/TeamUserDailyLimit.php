<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Caps the daily number of upstream-AI requests per team-member account.
 *
 * Background: the six seeded team accounts (crist/issa/aya/abdallah/
 * joudy/sally@example.com) all share the same MGF API key once the
 * project owner pastes it via `/api/v1/me/ai-providers` post-deploy.
 * Without a cap, a runaway teammate (or an accidental loop in the
 * frontend) can drain the daily key in a few minutes. This middleware
 * is the guard rail — it does NOT change controller behavior, it just
 * short-circuits at 429 once the user crosses the daily budget.
 *
 * Scope:
 *   - Pure no-op for any user NOT in TEAM_EMAILS (admin, test user, the
 *     10 random fakes, the 4 named users in ProjectSeeder, etc.).
 *   - Applied to /api/v1/ai/chat AND the async /generate endpoints so
 *     every path that hits the upstream API is covered.
 *
 * Counting:
 *   - Calendar-day bucket keyed on `team-daily:{userId}:{YYYY-MM-DD}`.
 *     The date in the key changes at midnight in the app timezone, so
 *     the counter naturally resets without a scheduled job.
 *   - `RateLimiter::hit($key, 86400)` just bounds the cache TTL — the
 *     real reset is the date in the key.
 *   - Each teammate gets their own 10/day, not a shared pool — one
 *     teammate cannot starve the others.
 *
 * Response on cap:
 *   - 429 with `Retry-After` (seconds) and a JSON body that matches the
 *     existing error shape ({ error, code, retry_after }).
 */
class TeamUserDailyLimit
{
    /**
     * The six canonical team-member accounts seeded by TeamSeeder.
     * Keep in sync with the `TEAM` constant in
     * database/seeders/TeamSeeder.php.
     */
    private const TEAM_EMAILS = [
        'crist@example.com',
        'issa@example.com',
        'aya@example.com',
        'abdallah@example.com',
        'joudy@example.com',
        'sally@example.com',
    ];

    public function handle(Request $request, Closure $next, int $maxAttempts = 10): Response
    {
        $user = $request->user();

        if ($user === null || ! in_array($user->email, self::TEAM_EMAILS, true)) {
            return $next($request);
        }

        $key = 'team-daily:'.$user->id.':'.now()->toDateString();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'error'       => "Daily limit of {$maxAttempts} AI requests reached. Try again in {$retryAfter} seconds.",
                'code'        => 'daily_limit_exceeded',
                'retry_after' => $retryAfter,
            ], 429)->header('Retry-After', (string) $retryAfter);
        }

        RateLimiter::hit($key, 86400);

        return $next($request);
    }
}
