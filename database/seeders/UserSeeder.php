<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends BaseSeeder
{
    protected function seed(): void
    {
        $this->createTestUser();
        $this->createFakeUsers();
    }

    private function createTestUser(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password_hash' => Hash::make('password'),
        ]);

        UserProfile::factory()->create([
            'user_id' => $user->id,
            'bio' => 'This is a test user profile.',
            'location' => 'Test City, Country',
        ]);
    }

    private function createFakeUsers(): void
    {
        User::factory(10)->create()->each(function ($user) {
            UserProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
