<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Llamamos a los seeders en orden
        $this->call([
            MunicipioSeeder::class,  // Primero municipios
            EuskalSpotSeeder::class, // Luego los spots
            ReviewSeeder::class,     // Finalmente las reseñas
        ]);

        // 2. Crear el usuario Administrador
        User::firstOrCreate(
            ['email' => 'admin@euskalspot.com'], 
            [
                'name' => 'Administrador',       
                'password' => bcrypt('password'),
                'role' => 'admin',              
            ]
        );
    }
}