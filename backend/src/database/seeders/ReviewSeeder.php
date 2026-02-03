<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Esto usa tu ReviewFactory para crear 20 reseñas falsas
        Review::factory()->count(20)->create();
    }
}