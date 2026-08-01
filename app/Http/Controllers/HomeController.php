<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        // Obtenemos los últimos 10 productos agregados (solo activos)
        $novedades = Producto::where('activo', true)->latest()->take(10)->get();

        return view('home', compact('novedades'));
    }
}
