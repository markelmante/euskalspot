<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Spot;
use App\Models\Municipio;

class AdminController extends Controller
{
    /**
     * Muestra el panel principal de administración (Dashboard).
     */
    public function index()
    {
        // Obtenemos los totales desde la base de datos
        $usersCount = User::count();
        $spotsCount = Spot::count();
        $municipiosCount = Municipio::has('spots')->count();

        // Pasamos las variables a la vista
        return view('admin.panel', compact('usersCount', 'spotsCount', 'municipiosCount'));
    }
}