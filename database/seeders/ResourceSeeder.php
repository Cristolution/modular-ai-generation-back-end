<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::with('profile')->get();

        foreach ($users as $user) {
            Resource::factory()
                ->prompt()
                ->public()
                ->for($user)
                ->create();

            Resource::factory()
                ->skill()
                ->for($user)
                ->create(['visibility' => 'private']);
        }
    }
}