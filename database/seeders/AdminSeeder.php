<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds a single canonical admin account — `admin@example.com` /
 * `password` — so the frontend can log in as admin and exercise the
 * `/api/v1/admin/*` endpoints (user moderation, role updates, full
 * template + resource listings).
 *
 * The `role` column is gated by `EnsureUserIsAdmin` middleware, so
 * without this row the admin views are unreachable in a fresh DB.
 *
 * Re-runnable: uses firstOrCreate by email so re-seeding won't create
 * duplicates or clobber a role that was changed manually.
 */
class AdminSeeder extends BaseSeeder
{
    protected function seed(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'          => 'Admin',
                'password_hash' => Hash::make('password'),
                'role'          => 'admin',
            ]
        );

        // Promote to admin if the row pre-existed with role='user'
        // (e.g. after a partial seed, or a manual firstOrCreate without
        // the role override).
        if ($user->role !== 'admin') {
            $user->update(['role' => 'admin']);
        }

        // Empty profile so the UserResource always has a counterpart.
        UserProfile::firstOrCreate(['user_id' => $user->id]);
    }
}