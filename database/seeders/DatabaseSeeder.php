<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
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

        User::factory(10)->create()->each(function ($user) {
            UserProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}