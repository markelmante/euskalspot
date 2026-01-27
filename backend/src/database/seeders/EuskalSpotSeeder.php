<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Spot;
use App\Models\Etiqueta;
use App\Models\Review; // <--- Importamos el modelo Review

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

        // 2. DATASET: 25 PLAYAS + 25 MONTES (Total 50)
        $data = [
            // --- GIPUZKOA ---
            'Donostia' => [
                ['n' => 'Playa de la Zurriola', 't' => 'playa', 'lat' => 43.3274, 'lng' => -1.9751, 'd' => 'El corazón del surf en San Sebastián.', 'tg' => ['duchas', 'surf_escuela', 'socorrista']],
                ['n' => 'Monte Ulia', 't' => 'monte', 'lat' => 43.3325, 'lng' => -1.9542, 'd' => 'Senderos costeros con vistas al Cantábrico.', 'tg' => ['fuente', 'facil']]
            ],
            'Zarautz' => [
                ['n' => 'Playa de Zarautz', 't' => 'playa', 'lat' => 43.2844, 'lng' => -2.1644, 'd' => 'La playa más extensa y surfera.', 'tg' => ['duchas', 'surf_escuela', 'parking']],
                ['n' => 'Monte Pagoeta', 't' => 'monte', 'lat' => 43.2500, 'lng' => -2.1500, 'd' => 'Parque natural con gran biodiversidad.', 'tg' => ['fuente', 'facil']]
            ],
            'Zumaia' => [
                ['n' => 'Playa de Itzurun', 't' => 'playa', 'lat' => 43.3011, 'lng' => -2.2588, 'd' => 'Surf entre los famosos Flysch.', 'tg' => ['socorrista', 'parking']],
                ['n' => 'Monte San Telmo', 't' => 'monte', 'lat' => 43.3025, 'lng' => -2.2595, 'd' => 'Acantilados impresionantes sobre el mar.', 'tg' => ['facil']]
            ],
            'Deba' => [
                ['n' => 'Playa de Santiago', 't' => 'playa', 'lat' => 43.2985, 'lng' => -2.3550, 'd' => 'Olas de derecha potentes.', 'tg' => ['duchas', 'socorrista']],
                ['n' => 'Monte Arno', 't' => 'monte', 'lat' => 43.2510, 'lng' => -2.3700, 'd' => 'Bosques de encinar cantábrico.', 'tg' => ['dificultad_alta']]
            ],
            'Oñati' => [
                ['n' => 'Monte Aizkorri', 't' => 'monte', 'lat' => 42.9555, 'lng' => -2.3300, 'd' => 'Techo de Gipuzkoa y cima mítica.', 'tg' => ['dificultad_alta']],
                ['n' => 'Campas de Urbia', 't' => 'monte', 'lat' => 42.9774, 'lng' => -2.3164, 'd' => 'Llanuras de altura ideales para caminar.', 'tg' => ['fuente', 'facil']]
            ],
            'Hondarribia' => [
                ['n' => 'Playa de Hondarribia', 't' => 'playa', 'lat' => 43.3772, 'lng' => -1.7894, 'd' => 'Ideal para principiantes y SUP.', 'tg' => ['duchas', 'facil']],
                ['n' => 'Monte Jaizkibel', 't' => 'monte', 'lat' => 43.3500, 'lng' => -1.8500, 'd' => 'El monte que guarda la costa.', 'tg' => ['fuente', 'parking']]
            ],
            'Azpeitia' => [
                ['n' => 'Monte Erlo', 't' => 'monte', 'lat' => 43.1780, 'lng' => -2.2450, 'd' => 'Macizo de Izarraitz, vistas al valle.', 'tg' => ['dificultad_alta']],
                ['n' => 'Monte Xoxote', 't' => 'monte', 'lat' => 43.1667, 'lng' => -2.2667, 'd' => 'Refugio clásico de montaña.', 'tg' => ['fuente', 'facil']]
            ],
            'Getaria' => [
                ['n' => 'Playa de Gaztetape', 't' => 'playa', 'lat' => 43.3050, 'lng' => -2.2060, 'd' => 'Playa expuesta con buenas olas.', 'tg' => ['socorrista']],
                ['n' => 'Monte San Anton (Ratón)', 't' => 'monte', 'lat' => 43.3080, 'lng' => -2.2030, 'd' => 'El perfil más famoso de la costa.', 'tg' => ['facil']]
            ],

            // --- BIZKAIA ---
            'Mundaka' => [
                ['n' => 'Barra de Mundaka', 't' => 'playa', 'lat' => 43.4072, 'lng' => -2.6978, 'd' => 'La mejor ola izquierda del mundo.', 'tg' => ['parking', 'socorrista']],
                ['n' => 'Monte Katillotxu', 't' => 'monte', 'lat' => 43.3850, 'lng' => -2.7000, 'd' => 'Ruta de dólmenes y vistas de Urdaibai.', 'tg' => ['facil']]
            ],
            'Sopela' => [
                ['n' => 'Playa de Arrietara', 't' => 'playa', 'lat' => 43.3881, 'lng' => -2.9944, 'd' => 'Sede de campeonatos internacionales.', 'tg' => ['surf_escuela', 'duchas']],
                ['n' => 'Acantilados de Sopela', 't' => 'monte', 'lat' => 43.3910, 'lng' => -2.9850, 'd' => 'Rutas costeras de gran belleza.', 'tg' => ['facil']]
            ],
            'Bakio' => [
                ['n' => 'Playa de Bakio', 't' => 'playa', 'lat' => 43.4310, 'lng' => -2.8100, 'd' => 'Surf consistente todo el año.', 'tg' => ['duchas', 'parking']],
                ['n' => 'Monte Jata', 't' => 'monte', 'lat' => 43.4000, 'lng' => -2.8300, 'd' => 'El vigía de Bakio y San Juan.', 'tg' => ['fuente']]
            ],
            'Bermeo' => [
                ['n' => 'Aritzatxu', 't' => 'playa', 'lat' => 43.4230, 'lng' => -2.7250, 'd' => 'Cala pequeña con encanto salvaje.', 'tg' => ['socorrista']],
                ['n' => 'San Juan de Gaztelugatxe', 't' => 'monte', 'lat' => 43.4472, 'lng' => -2.7850, 'd' => 'Ascenso mítico a la ermita.', 'tg' => ['parking', 'facil']]
            ],
            'Getxo' => [
                ['n' => 'Playa de Barinatxe', 't' => 'playa', 'lat' => 43.3820, 'lng' => -3.0120, 'd' => 'Conocida como La Salvaje.', 'tg' => ['duchas', 'surf_escuela']],
                ['n' => 'Monte Serantes', 't' => 'monte', 'lat' => 43.3420, 'lng' => -3.0620, 'd' => 'Vistas de toda la ría de Bilbao.', 'tg' => ['fuente', 'facil']]
            ],
            'Lekeitio' => [
                ['n' => 'Playa de Karraspio', 't' => 'playa', 'lat' => 43.3620, 'lng' => -2.4950, 'd' => 'Surfea con vistas a la isla.', 'tg' => ['socorrista', 'duchas']],
                ['n' => 'Monte Otoio', 't' => 'monte', 'lat' => 43.3550, 'lng' => -2.5200, 'd' => 'Atalaya sobre el puerto de Lekeitio.', 'tg' => ['dificultad_alta']]
            ],
            'Atxondo' => [
                ['n' => 'Monte Anboto', 't' => 'monte', 'lat' => 43.0883, 'lng' => -2.5878, 'd' => 'Hogar de la Dama Mari.', 'tg' => ['dificultad_alta']],
                ['n' => 'Monte Alluitz', 't' => 'monte', 'lat' => 43.1020, 'lng' => -2.6100, 'd' => 'Cresterío para montañeros expertos.', 'tg' => ['dificultad_alta']]
            ],
            'Zeanuri' => [
                ['n' => 'Monte Gorbea', 't' => 'monte', 'lat' => 43.0333, 'lng' => -2.7833, 'd' => 'La cruz más alta y querida.', 'tg' => ['fuente', 'dificultad_alta']],
                ['n' => 'Monte Lekanda', 't' => 'monte', 'lat' => 43.0450, 'lng' => -2.7900, 'd' => 'Paredes calizas en el macizo del Gorbea.', 'tg' => ['dificultad_alta']]
            ],
            'Muskiz' => [
                ['n' => 'Playa de la Arena', 't' => 'playa', 'lat' => 43.3500, 'lng' => -3.1200, 'd' => 'Final de la costa vizcaína.', 'tg' => ['duchas', 'parking']],
                ['n' => 'Monte Mello', 't' => 'monte', 'lat' => 43.3250, 'lng' => -3.1350, 'd' => 'Vistas del Abra y de Cantabria.', 'tg' => ['facil']]
            ],
            'Gorliz' => [
                ['n' => 'Playa de Gorliz', 't' => 'playa', 'lat' => 43.4160, 'lng' => -2.9400, 'd' => 'Bahía tranquila y segura.', 'tg' => ['facil', 'duchas']],
                ['n' => 'Monte Ermua', 't' => 'monte', 'lat' => 43.4350, 'lng' => -2.9450, 'd' => 'Ruta por los acantilados del faro.', 'tg' => ['parking', 'facil']]
            ],

            // --- ARABA (Interior: Playas de Embalse y Montes) ---
            'Legutio' => [
                ['n' => 'Playa de Landa', 't' => 'playa', 'lat' => 42.9600, 'lng' => -2.5900, 'd' => 'Playa de interior con bandera azul.', 'tg' => ['duchas', 'facil']],
                ['n' => 'Monte Albertia', 't' => 'monte', 'lat' => 42.9800, 'lng' => -2.6100, 'd' => 'Bosques cargados de historia.', 'tg' => ['facil']]
            ],
            'Elburgo' => [
                ['n' => 'Playa de Garaio', 't' => 'playa', 'lat' => 42.9200, 'lng' => -2.5400, 'd' => 'Entorno natural privilegiado.', 'tg' => ['parking', 'duchas']],
                ['n' => 'Cimas de Garaio', 't' => 'monte', 'lat' => 42.9150, 'lng' => -2.5500, 'd' => 'Pequeñas colinas con avistamiento de aves.', 'tg' => ['facil']]
            ],
            'Urduña' => [
                ['n' => 'Monte Txarlazo', 't' => 'monte', 'lat' => 42.9800, 'lng' => -3.0100, 'd' => 'Monumento a la Virgen de la Antigua.', 'tg' => ['fuente', 'facil']],
                ['n' => 'Salto del Nervión', 't' => 'monte', 'lat' => 42.9460, 'lng' => -2.9900, 'd' => 'El mayor salto de agua de la península.', 'tg' => ['parking', 'facil']]
            ],
            'Bernedo' => [
                ['n' => 'Monte San Tirso', 't' => 'monte', 'lat' => 42.6100, 'lng' => -2.5200, 'd' => 'Sierra de Cantabria, roca caliza.', 'tg' => ['dificultad_alta']],
                ['n' => 'Monte Palomares', 't' => 'monte', 'lat' => 42.6150, 'lng' => -2.5400, 'd' => 'Pico técnico con vistas a la Rioja.', 'tg' => ['dificultad_alta']]
            ],
            'Zigoitia' => [
                ['n' => 'Monte Oketa', 't' => 'monte', 'lat' => 43.0050, 'lng' => -2.7200, 'd' => 'Llamado el Gorbea de los txikis.', 'tg' => ['facil']],
                ['n' => 'Playa de Gopegi (Embalse)', 't' => 'playa', 'lat' => 42.9850, 'lng' => -2.7100, 'd' => 'Zona de baño en el embalse de Albina.', 'tg' => ['facil']]
            ]
        ];

        // 3. INSERCIÓN DE DATOS
        foreach ($data as $muni => $spots) {
            $m = Municipio::create(['nombre' => $muni]);
            foreach ($spots as $s) {
                $newSpot = Spot::create([
                    'nombre' => $s['n'],
                    'tipo' => $s['t'],
                    'municipio_id' => $m->id,
                    'descripcion' => $s['d'],
                    'latitud' => $s['lat'],
                    'longitud' => $s['lng'],
                ]);
                foreach ($s['tg'] as $tName) {
                    $newSpot->etiquetas()->attach($tags[$tName]->id);
                }
            }
        }
        // Esto creará 6 reseñas aleatorias cada vez que se ejecute el seeder
        Review::factory(6)->create();
    }
}
