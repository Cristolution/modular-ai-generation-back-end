<?php

namespace Tests\Feature;

use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_all_types(): void
    {
        Type::factory()->create(['name' => 'presentation']);
        Type::factory()->create(['name' => 'carousel']);
        Type::factory()->create(['name' => 'poster']);

        $response = $this->getJson('/api/v1/types');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'icon'],
                ],
            ]);
    }

    public function test_types_are_publicly_accessible(): void
    {
        Type::factory()->create(['name' => 'presentation']);

        $response = $this->getJson('/api/v1/types');

        $response->assertStatus(200);
    }
}