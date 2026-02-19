<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de comentarios reales sobre la web
        $comentarios = [
            '¡Me encanta la web! Muy útil para encontrar spots nuevos en Bizkaia.',
            'La información sobre los parkings me salvó el día. Gracias.',
            'Diseño muy limpio y fácil de usar. Seguid así.',
            'Estaría bien añadir más fotos de los spots, pero la info es correcta.',
            'La mejor guía de surf de Euskadi que he encontrado.',
            'Muy buena iniciativa. Me gusta que indiquéis si hay duchas.',
            'Falta algún spot de la zona de Orio, pero por lo demás genial.',
            'Sencilla y directa. Justo lo que buscaba para mi viaje.',
            'Los filtros funcionan muy bien. Encontré playa para principiantes rápido.',
            '¡Aupa! Gran trabajo recopilando toda esta info.',
            'La uso siempre antes de salir a surfear para chequear los accesos.',
            'Información muy precisa sobre las coordenadas GPS.'
        ];

        // Mezcla de nombres vascos y comunes para darle realismo al contexto
        $nombres = [
            'Mikel',
            'Ane',
            'Jon',
            'Gorka',
            'Nerea',
            'Iker',
            'Amaia',
            'David',
            'Laura',
            'Carlos',
            'Maite',
            'Asier',
            'Sara',
            'Aitor',
            'Leire',
            'Unai',
            'Elena',
            'Javier',
            'Irati',
            'Eneko'
        ];

        return [
            'nombre' => $this->faker->randomElement($nombres),
            'texto' => $this->faker->randomElement($comentarios),
            'puntuacion' => $this->faker->numberBetween(4, 5),
        ];
    }
}