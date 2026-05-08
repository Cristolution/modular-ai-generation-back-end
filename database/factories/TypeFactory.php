<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeFactory extends Factory
{
    protected $model = Type::class;

    public function definition(): array
    {
        $types = [
            ['name' => 'presentation', 'description' => 'Multi-slide deck like PowerPoint', 'icon' => 'slides'],
            ['name' => 'carousel', 'description' => 'Sequential swipeable cards', 'icon' => 'cards'],
            ['name' => 'poster', 'description' => 'Single-page visual', 'icon' => 'image'],
            ['name' => 'infographic', 'description' => 'Data-driven visual storytelling', 'icon' => 'chart'],
            ['name' => 'document', 'description' => 'Structured text-heavy document', 'icon' => 'file-text'],
            ['name' => 'website', 'description' => 'Single-page web output', 'icon' => 'globe'],
        ];

        $type = fake()->randomElement($types);

        return [
            'name' => $type['name'],
            'description' => $type['description'],
            'icon' => $type['icon'],
        ];
    }
}