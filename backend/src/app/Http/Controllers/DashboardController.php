<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; // <--- ¡IMPORTANTE! Asegúrate de tener esta línea arriba

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Miramos si en la URL viene una fecha (?date=2026-01-XX)
        // Si no viene nada, usamos la fecha de HOY.
        $fecha = $request->has('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::now();

        // 2. Calculamos el inicio de esa semana
        $startOfWeek = $fecha->copy()->startOfWeek();

        // 3. Calculamos las fechas para los botones
        $prevWeek = $startOfWeek->copy()->subWeek()->format('Y-m-d');
        $nextWeek = $startOfWeek->copy()->addWeek()->format('Y-m-d');

        // 4. Generamos los días para el calendario (para no ensuciar la vista)
        $diasSemana = [];
        $nombresDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        foreach ($nombresDias as $index => $nombre) {
            $diaFecha = $startOfWeek->copy()->addDays($index);
            $diasSemana[] = [
                'nombre' => $nombre,
                'numero' => $diaFecha->format('d'),
                'fecha_completa' => $diaFecha->format('Y-m-d'),
                'es_hoy' => $diaFecha->isToday()
            ];
        }

        // 5. Enviamos todo a la vista
        return view('dashboard', compact('startOfWeek', 'prevWeek', 'nextWeek', 'diasSemana'));
    }
}