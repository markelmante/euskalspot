<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
                // 1. Primero cargamos los datos de referencia (Los 252 municipios)
            MunicipioSeeder::class,

                // 2. Después cargamos tus datos de prueba (Spots, etc.)
            EuskalSpotSeeder::class,
        ]);
    }
}