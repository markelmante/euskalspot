<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Spot;
use App\Models\Etiqueta;

class EuskalSpotSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ETIQUETAS
        $tags = [
            'duchas' => Etiqueta::firstOrCreate(['nombre' => 'Duchas']),
            'parking' => Etiqueta::firstOrCreate(['nombre' => 'Parking']),
            'fuente' => Etiqueta::firstOrCreate(['nombre' => 'Fuente']),
            'socorrista' => Etiqueta::firstOrCreate(['nombre' => 'Socorrista']),
            'dificultad_alta' => Etiqueta::firstOrCreate(['nombre' => 'Dificultad Alta']),
            'facil' => Etiqueta::firstOrCreate(['nombre' => 'Apto Familias']),
            'surf_escuela' => Etiqueta::firstOrCreate(['nombre' => 'Escuela de Surf']),
        ];

        // 2. DATOS (SOLO SURF Y MONTAÑA)
        // Usamos 'img' como la raíz del nombre. El código buscará _1.jpg, _2.jpg, _3.jpg
        $data = [
            // --- GIPUZKOA ---
            'Donostia' => [
                [
                    'n' => 'Playa de la Zurriola',
                    't' => 'playa',
                    'lat' => 43.3274,
                    'lng' => -1.9751,
                    'd' => 'El corazón del surf en San Sebastián, olas consistentes.',
                    'tg' => ['duchas', 'surf_escuela'],
                    'img' => 'zurriola'
                ],
                [
                    'n' => 'Monte Ulia',
                    't' => 'monte',
                    'lat' => 43.3325,
                    'lng' => -1.9542,
                    'd' => 'Travesía costera con las mejores vistas al mar.',
                    'tg' => ['fuente', 'facil'],
                    'img' => 'ulia'
                ]
            ],
            'Zarautz' => [
                [
                    'n' => 'Playa de Zarautz',
                    't' => 'playa',
                    'lat' => 43.2844,
                    'lng' => -2.1644,
                    'd' => 'La cantera de surfistas vascos. Olas para todos los niveles.',
                    'tg' => ['duchas', 'surf_escuela'],
                    'img' => 'zarautz'
                ],
                [
                    'n' => 'Monte Pagoeta',
                    't' => 'monte',
                    'lat' => 43.2500,
                    'lng' => -2.1500,
                    'd' => 'Parque natural ideal para senderismo entre hayas.',
                    'tg' => ['fuente', 'facil'],
                    'img' => 'pagoeta'
                ]
            ],
            'Zumaia' => [
                [
                    'n' => 'Playa de Itzurun',
                    't' => 'playa',
                    'lat' => 43.3011,
                    'lng' => -2.2588,
                    'd' => 'Surf rodeado de acantilados verticales (Flysch).',
                    'tg' => ['socorrista', 'parking'],
                    'img' => 'itzurun'
                ],
                [
                    'n' => 'Ermita San Telmo',
                    't' => 'monte',
                    'lat' => 43.3025,
                    'lng' => -2.2595,
                    'd' => 'Paseo por el borde del acantilado con vistas de cine.',
                    'tg' => ['facil'],
                    'img' => 'santelmo'
                ]
            ],

            // --- BIZKAIA ---
            'Mundaka' => [
                [
                    'n' => 'Barra de Mundaka',
                    't' => 'playa',
                    'lat' => 43.4072,
                    'lng' => -2.6978,
                    'd' => 'La mejor ola izquierda de Europa. Solo expertos.',
                    'tg' => ['parking', 'socorrista'],
                    'img' => 'mundaka'
                ],
                [
                    'n' => 'Monte Katillotxu',
                    't' => 'monte',
                    'lat' => 43.3850,
                    'lng' => -2.7000,
                    'd' => 'Mirador natural sobre la reserva de Urdaibai.',
                    'tg' => ['facil'],
                    'img' => 'katillotxu'
                ]
            ],
            'Bakio' => [ // Sustituye a Aritzatxu
                [
                    'n' => 'Playa de Bakio',
                    't' => 'playa',
                    'lat' => 43.4310,
                    'lng' => -2.8100,
                    'd' => 'Playa abierta con oleaje fuerte todo el año.',
                    'tg' => ['duchas', 'surf_escuela'],
                    'img' => 'bakio'
                ]
            ],
            'Bermeo' => [
                [
                    'n' => 'San Juan de Gaztelugatxe',
                    't' => 'monte',
                    'lat' => 43.4472,
                    'lng' => -2.7850,
                    'd' => 'Ruta de escaleras y ascenso hasta la ermita.',
                    'tg' => ['parking', 'facil'],
                    'img' => 'gaztelugatxe'
                ]
            ],
            'Sopela' => [
                [
                    'n' => 'Playa de Arrietara',
                    't' => 'playa',
                    'lat' => 43.3881,
                    'lng' => -2.9944,
                    'd' => 'Epicentro del surf en Bizkaia junto con La Salvaje.',
                    'tg' => ['surf_escuela', 'duchas'],
                    'img' => 'sopela'
                ],
                [
                    'n' => 'Acantilados de Sopela',
                    't' => 'monte',
                    'lat' => 43.3910,
                    'lng' => -2.9850,
                    'd' => 'Rutas costeras de gran belleza.',
                    'tg' => ['facil'],
                    'img' => 'acantilados'
                ]
            ],
            'Zeanuri' => [
                [
                    'n' => 'Monte Gorbea',
                    't' => 'monte',
                    'lat' => 43.0333,
                    'lng' => -2.7833,
                    'd' => 'La cima más emblemática. Cruz y vistas 360.',
                    'tg' => ['fuente', 'dificultad_alta'],
                    'img' => 'gorbea'
                ],
                [
                    'n' => 'Monte Lekanda',
                    't' => 'monte',
                    'lat' => 43.0450,
                    'lng' => -2.7900,
                    'd' => 'Mole caliza en el macizo de Itxina.',
                    'tg' => ['dificultad_alta'],
                    'img' => 'lekanda'
                ]
            ],

            // --- ARABA (Nervión asignado a Urduña por cercanía) ---
            'Urduña' => [
                [
                    'n' => 'Salto del Nervión',
                    't' => 'monte',
                    'lat' => 42.9460,
                    'lng' => -2.9900,
                    'd' => 'Ruta al borde del cañón con caída de 222m.',
                    'tg' => ['parking', 'facil'],
                    'img' => 'nervion'
                ]
            ],
        ];

        // 3. RECORRER Y CREAR
        foreach ($data as $muniKey => $spots) {

            // Buscamos el municipio
            $municipio = Municipio::where('nombre', 'like', "%{$muniKey}%")->first();
            if (!$municipio)
                continue;

            foreach ($spots as $s) {

                // GENERAR ARRAY DE 3 FOTOS AUTOMÁTICAMENTE
                $fotosArray = null;
                if (isset($s['img'])) {
                    $fotosArray = [
                        "spots/{$s['img']}_1.jpg", // Foto Principal
                        "spots/{$s['img']}_2.jpg", // Detalle 1
                        "spots/{$s['img']}_3.jpg", // Detalle 2
                    ];
                }

                $newSpot = Spot::create([
                    'nombre' => $s['n'],
                    'tipo' => $s['t'],
                    'municipio_id' => $municipio->id,
                    'descripcion' => $s['d'],
                    'latitud' => $s['lat'],
                    'longitud' => $s['lng'],
                    // IMPORTANTE: Guardamos el Array como JSON
                    'foto' => $fotosArray ? json_encode($fotosArray) : null,
                ]);

                // Asignar etiquetas
                foreach ($s['tg'] as $tName) {
                    if (isset($tags[$tName])) {
                        $newSpot->etiquetas()->attach($tags[$tName]->id);
                    }
                }
            }
        }
    }
}