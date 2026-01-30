<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar
        $request->validate([
            'spot_id' => 'required|exists:spots,id',
            'fecha' => 'required|date_format:Y-m-d',
        ]);

        // 2. Guardar (Evitando duplicados exactos)
        // Usamos firstOrCreate: Si ya existe este plan, no lo crea de nuevo, pero devuelve éxito.
        $plan = Plan::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'spot_id' => $request->spot_id,
                'fecha' => $request->fecha
            ]
        );

        // Cargamos el spot para devolverlo al JS (por si quisieras actualizar el DOM sin recargar)
        $plan->load('spot');

        return response()->json(['success' => true, 'id' => $plan->id, 'spot_nombre' => $plan->spot->nombre]);
    }

    public function destroy($id)
    {
        // Solo borrar si pertenece al usuario actual
        $plan = Plan::where('user_id', Auth::id())->where('id', $id)->first();

        if ($plan) {
            $plan->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
    }
}