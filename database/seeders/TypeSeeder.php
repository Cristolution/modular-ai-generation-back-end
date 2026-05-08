<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'presentation', 'description' => 'Multi-slide deck like PowerPoint/Google Slides', 'icon' => 'slides'],
            ['name' => 'carousel', 'description' => 'Sequential swipeable cards for social media', 'icon' => 'cards'],
            ['name' => 'poster', 'description' => 'Single-page visual in A4 or custom sizes', 'icon' => 'image'],
            ['name' => 'infographic', 'description' => 'Data-driven visual storytelling', 'icon' => 'chart'],
            ['name' => 'document', 'description' => 'Structured text-heavy document with visual polish', 'icon' => 'file-text'],
            ['name' => 'website', 'description' => 'Single-page web output (HTML+CSS)', 'icon' => 'globe'],
        ];

        foreach ($types as $type) {
            Type::create($type);
        }
    }
}