<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si el usuario está logueado y si su rol es 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Lo dejamos pasar
        }

        // 2. Si no es admin, lo rebotamos a la página de inicio con un mensaje
        return redirect('/')->with('error', 'No tenés permisos para ingresar al panel de administración.');
    }
}