<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Favorito;
use App\Models\Municipio;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Configurar fechas
        $fechaBase = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $inicioSemana = $fechaBase->copy()->startOfWeek();
        $finSemana = $fechaBase->copy()->endOfWeek();
        $user = Auth::user();

        // 2. Cargar Planes y Favoritos
        $planes = Plan::where('user_id', $user->id)
            ->whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
            ->with('spot')
            ->get();

        $favoritos = Favorito::where('user_id', $user->id)
            ->with('spot.municipio')
            ->get();

        // --- LÓGICA DE COORDENADAS POR DEFECTO (RESIDENCIA) ---
        // Coordenadas base (Zarautz) por si falla todo
        $defaultLat = 43.284;
        $defaultLon = -2.172;
        $nombreMuniResidencia = null; // Para guardar el nombre si existe

        // Si el usuario tiene perfil y municipio, buscamos las coordenadas de ese municipio
        if ($user->profile && $user->profile->municipio_residencia) {
            $muniResidencia = Municipio::where('nombre', $user->profile->municipio_residencia)->first();

            if ($muniResidencia) {
                $defaultLat = $muniResidencia->latitud;
                $defaultLon = $muniResidencia->longitud;
                $nombreMuniResidencia = $muniResidencia->nombre;
            }
        }

        // 3. Construir el Calendario
        $calendario = [];
        $diaIterador = $inicioSemana->copy();

        for ($i = 0; $i < 7; $i++) {
            $fechaString = $diaIterador->format('Y-m-d');

            // Filtramos el plan del día
            $planesDelDia = $planes->filter(function ($plan) use ($fechaString) {
                return Carbon::parse($plan->fecha)->format('Y-m-d') === $fechaString;
            });

            $planDelDia = $planesDelDia->first();
            $lugarClima = ""; // Variable para el texto del tooltip

            // --- LÓGICA DEL CLIMA ---
            if ($planDelDia) {
                // PRIORIDAD 1: Si hay plan, usamos coordenadas del Spot
                $lat = $planDelDia->spot->latitud;
                $lon = $planDelDia->spot->longitud;
                $esClimaSpot = true;
                $lugarClima = "Plan: " . $planDelDia->spot->nombre;
            } else {
                // PRIORIDAD 2: Si no hay plan, usamos coordenadas de Residencia (o Zarautz si no tiene)
                $lat = $defaultLat;
                $lon = $defaultLon;
                $esClimaSpot = false;

                if ($nombreMuniResidencia) {
                    $lugarClima = "Tu municipio: " . $nombreMuniResidencia;
                } else {
                    $lugarClima = "Costa (Zarautz) - Por defecto";
                }
            }

            // Llamada a API
            $climaIcono = $this->getWeatherIconForDate($lat, $lon, $fechaString);

            $calendario[] = [
                'fecha_completa' => $fechaString,
                'nombre' => $diaIterador->locale('es')->dayName,
                'numero' => $diaIterador->format('d'),
                'es_hoy' => $diaIterador->isToday(),
                'planes' => $planesDelDia,
                'clima_icono' => $climaIcono,
                'es_clima_spot' => $esClimaSpot,
                'lugar_clima' => $lugarClima // Pasamos el nombre del lugar a la vista
            ];

            $diaIterador->addDay();
        }

        $semanaAnterior = $inicioSemana->copy()->subWeek()->format('Y-m-d');
        $semanaSiguiente = $inicioSemana->copy()->addWeek()->format('Y-m-d');

        return view('dashboard', compact('calendario', 'favoritos', 'inicioSemana', 'semanaAnterior', 'semanaSiguiente'));
    }

    // --- FUNCIONES AUXILIARES ---

    private function getWeatherIconForDate($lat, $lon, $date)
    {
        try {
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&daily=weather_code&timezone=auto&start_date={$date}&end_date={$date}";
            $response = Http::timeout(1)->get($url);

            if ($response->successful()) {
                $code = $response->json()['daily']['weather_code'][0] ?? null;
                return $this->codeToIcon($code);
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    private function codeToIcon($code)
    {
        if ($code === null)
            return null;
        if ($code == 0)
            return '☀️';
        if ($code <= 3)
            return '⛅';
        if ($code <= 48)
            return '🌫️';
        if ($code <= 57)
            return '🌧️';
        if ($code <= 67)
            return '🌧️';
        if ($code <= 77)
            return '❄️';
        if ($code <= 82)
            return '🌦️';
        if ($code <= 86)
            return '🌨️';
        if ($code >= 95)
            return '⛈️';
        return '🌡️';
    }
}