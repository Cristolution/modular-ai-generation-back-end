<?php

namespace Database\Seeders;

use App\Models\UserAiProvider;
use Illuminate\Database\Seeder;

class UserAiProviderSeeder extends Seeder
{
    public function run(): void
    {
        $users = \App\Models\User::with('profile')->get();

        foreach ($users as $user) {
            UserAiProvider::factory()
                ->openai()
                ->for($user)
                ->create();
        }
    }
}