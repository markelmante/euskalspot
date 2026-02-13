<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Comprobamos si está logueado Y si es admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // ¡Pase usted!
        }

        // 2. Si no cumple, error 403 (Prohibido)
        abort(403, 'ACCESO DENEGADO: Se requieren permisos de Administrador.');
    }
}