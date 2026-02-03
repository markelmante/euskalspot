<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamamos a los seeders en orden
        $this->call([
            MunicipioSeeder::class,  // 1. Primero municipios (necesarios para los spots)
            EuskalSpotSeeder::class, // 2. Luego los spots
            ReviewSeeder::class,     // 3. Finalmente las reseñas
        ]);
    }
}