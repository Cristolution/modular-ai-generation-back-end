<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    abstract protected function seed(): void;

    public function run(): void
    {
        if ($this->shouldSeed()) {
            $this->seed();
        }
    }

    protected function shouldSeed(): bool
    {
        return true;
    }
}
