<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            TypeSeeder::class,
            UserSeeder::class,
            UserAiProviderSeeder::class,
            TeamSeeder::class,
            AdminSeeder::class,
            TemplateSeeder::class,
            KonkretTemplateSeeder::class,
            ProjectSeeder::class,
            FileSeeder::class,
            AiJobSeeder::class,
            ResourceSeeder::class,
            CommentSeeder::class,
        ]);
    }
}