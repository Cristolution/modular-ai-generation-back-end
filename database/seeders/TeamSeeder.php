<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAiProvider;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds six canonical team-member accounts so the deployed dev environment
 * can be exercised by name without sharing a single login:
 *
 *   crist@example.com    (Crist)
 *   issa@example.com     (Issa)
 *   aya@example.com      (Aya)
 *   abdallah@example.com (Abdallah)
 *   joudy@example.com    (Joudy)
 *   sally@example.com    (Sally)
 *
 * All use the password `password` and the regular `user` role.
 *
 * Each user is given a single MGF provider row whose base_url + model match
 * the existing `projects@example.com` setup, but with `api_key_encrypted`
 * left null. The project owner enters the real key per user through the
 * `/api/v1/me/ai-providers` UI after deployment. This keeps the key out of
 * git and out of any freshly-seeded database, matching the
 * `per-user-ai-provider-architecture` rule that keys must never be
 * server-held.
 *
 * Re-runnable: users and providers are created via firstOrCreate, and any
 * stray `openai` provider row that `UserAiProviderSeeder` may have left
 * behind for these emails is removed so each team member ends up with
 * exactly one provider (the MGF one with an empty key).
 */
class TeamSeeder extends BaseSeeder
{
    private const TEAM = [
        ['name' => 'Crist',    'email' => 'crist@example.com'],
        ['name' => 'Issa',     'email' => 'issa@example.com'],
        ['name' => 'Aya',      'email' => 'aya@example.com'],
        ['name' => 'Abdallah', 'email' => 'abdallah@example.com'],
        ['name' => 'Joudy',    'email' => 'joudy@example.com'],
        ['name' => 'Sally',    'email' => 'sally@example.com'],
    ];

    protected function seed(): void
    {
        foreach (self::TEAM as $row) {
            $this->createTeamUser($row['name'], $row['email']);
        }
    }

    private function createTeamUser(string $name, string $email): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'          => $name,
                'password_hash' => Hash::make('password'),
                'role'          => 'user',
            ]
        );

        UserProfile::firstOrCreate(['user_id' => $user->id]);

        // `UserAiProviderSeeder` runs first and attaches a random OpenAI
        // provider to every user that existed at the time. Remove any
        // such row for this email so the user ends up with only the
        // MGF provider below.
        UserAiProvider::where('user_id', $user->id)
            ->where('provider', 'openai')
            ->delete();

        UserAiProvider::firstOrCreate(
            [
                'user_id'  => $user->id,
                'provider' => 'anthropic',
            ],
            [
                'display_name'      => 'MiniMax (Anthropic-compatible)',
                'api_key_encrypted' => null,
                'base_url'          => 'https://api.minimax.io/anthropic',
                'default_model'     => 'MiniMax-M3',
                'is_active'         => true,
                'created_at'        => now(),
            ]
        );
    }
}
