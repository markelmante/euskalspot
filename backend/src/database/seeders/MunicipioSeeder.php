<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use Illuminate\Support\Facades\File;

class MunicipioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ruta al archivo JSON
        $jsonPath = database_path('data/municipios.json');

        // 2. Comprobar que existe
        if (!File::exists($jsonPath)) {
            $this->command->error("No se encontró el archivo municipios.json en database/data/");
            return;
        }

        // 3. Leer y decodificar el JSON
        $json = File::get($jsonPath);
        $municipios = json_decode($json, true);

        // 4. Insertar en la base de datos
        foreach ($municipios as $muni) {
            Municipio::updateOrCreate(
                [
                    'nombre' => $muni['nombre'],
                    'provincia' => $muni['provincia']
                ],
                [
                    'latitud' => $muni['latitud'],
                    'longitud' => $muni['longitud']
                ]
            );
        }

        $this->command->info('¡Municipios de Euskadi cargados correctamente!');
    }
}