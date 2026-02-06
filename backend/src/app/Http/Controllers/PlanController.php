<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Favorito;
use App\Models\Municipio;

class PlanController extends Controller
{
    /**
     * Muestra el Dashboard (Calendario + Favoritos).
     * Prepara latitudes y longitudes para que el JS pinte el clima.
     */
    public function index(Request $request)
    {
        // Configurar fechas
        $fechaBase = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $inicioSemana = $fechaBase->copy()->startOfWeek(); // Lunes
        $finSemana = $fechaBase->copy()->endOfWeek();     // Domingo
        $user = Auth::user();

        // Cargar Planes de la semana actual
        $planes = Plan::where('user_id', $user->id)
            ->whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
            ->with('spot')
            ->get();

        // Cargar Favoritos (para el sidebar)
        $favoritos = Favorito::where('user_id', $user->id)
            ->with('spot.municipio')
            ->get();

        // Obtener coordenadas por defecto del usuario (o Zarautz)
        $coordsDefault = $this->getUserCoordinates($user);

        // Construir el array del Calendario para la vista
        $calendario = [];
        $diaIterador = $inicioSemana->copy();

        for ($i = 0; $i < 7; $i++) {
            $fechaString = $diaIterador->format('Y-m-d');

            // Buscar si ya hay un plan creado para este día
            $planDelDia = $planes->first(function ($plan) use ($fechaString) {
                return Carbon::parse($plan->fecha)->format('Y-m-d') === $fechaString;
            });

            // LÓGICA DE UBICACIÓN PARA EL CLIMA
            // Si hay plan -> Usamos coordenadas del Spot.
            // Si NO hay plan -> Usamos coordenadas de municipio de residencia (o default).
            if ($planDelDia) {
                $lat = $planDelDia->spot->latitud;
                $lon = $planDelDia->spot->longitud;
                $lugar = "Plan: " . $planDelDia->spot->nombre;
                $esSpot = true;
            } else {
                $lat = $coordsDefault['lat'];
                $lon = $coordsDefault['lon'];
                $lugar = $coordsDefault['nombre'] ? "Tu municipio: " . $coordsDefault['nombre'] : "Costa (Zarautz)";
                $esSpot = false;
            }

            $calendario[] = [
                'fecha_completa' => $fechaString,
                'nombre' => $diaIterador->locale('es')->dayName,
                'numero' => $diaIterador->format('d'),
                'es_hoy' => $diaIterador->isToday(),
                'planes' => $planes->filter(fn($p) => Carbon::parse($p->fecha)->format('Y-m-d') === $fechaString),

                // Datos para que el JS consulte la API del clima
                'lat' => $lat,
                'lon' => $lon,
                'lugar_clima' => $lugar,
                'es_clima_spot' => $esSpot
            ];

            $diaIterador->addDay();
        }

        // Variables para los botones de "Semana anterior/siguiente"
        $semanaAnterior = $inicioSemana->copy()->subWeek()->format('Y-m-d');
        $semanaSiguiente = $inicioSemana->copy()->addWeek()->format('Y-m-d');

        return view('dashboard', compact('calendario', 'favoritos', 'inicioSemana', 'semanaAnterior', 'semanaSiguiente'));
    }

    /**
     * Guarda un nuevo plan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'spot_id' => 'required|exists:spots,id',
            'fecha' => 'required|date_format:Y-m-d',
        ]);

        $plan = Plan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'spot_id' => $request->spot_id,
                'fecha' => $request->fecha
            ]
        );

        $plan->load('spot');

        return response()->json(['success' => true, 'id' => $plan->id, 'spot_nombre' => $plan->spot->nombre]);
    }

    /**
     * Elimina un plan.
     */
    public function destroy($id)
    {
        $plan = Plan::where('user_id', Auth::id())->where('id', $id)->first();

        if ($plan) {
            $plan->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
    }
    /**
     * Obtiene lat/lon base del usuario.
     * Si no tiene municipio configurado, devuelve Zarautz por defecto.
     */
    private function getUserCoordinates($user)
    {
        // Valores por defecto (Zarautz)
        $lat = 43.284;
        $lon = -2.172;
        $nombre = null;

        // Comprobamos si el usuario tiene perfil y municipio guardado
        if ($user->profile && $user->profile->municipio_residencia) {
            $muni = Municipio::where('nombre', $user->profile->municipio_residencia)->first();

            if ($muni) {
                $lat = $muni->latitud;
                $lon = $muni->longitud;
                $nombre = $muni->nombre;
            }
        }

        return compact('lat', 'lon', 'nombre');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date_format:Y-m-d',
        ]);

        // Buscamos el plan asegurándonos de que pertenezca al usuario
        $plan = Plan::where('user_id', Auth::id())->where('id', $id)->first();

        if ($plan) {
            $plan->fecha = $request->fecha;
            $plan->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Plan no encontrado'], 404);
    }
}