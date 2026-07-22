<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function index(Request $request)
    {
        $ventas = \App\Models\Orden::query();

        // 1. Filtro por Estado
        if ($request->filled('estado')) {
            $ventas->where('estado', $request->estado);
        }

        // 2. Filtro por Rango de Fechas
        if ($request->filled('fecha_inicio')) {
            $ventas->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $ventas->whereDate('created_at', '<=', $request->fecha_fin);
        }

        // 3. Buscador por Palabra Clave (ID de orden o Nombre de Usuario)
        if ($request->filled('buscar')) {
            $palabra = $request->buscar;

            // Agrupamos en una clausula where para no romper la lógica con los otros filtros
            $ventas->where(function ($query) use ($palabra) {
                $query->where('id', 'like', "%{$palabra}%")
                    ->orWhereHas('user', function ($q) use ($palabra) {
                        $q->where('name', 'like', "%{$palabra}%"); // Ajusta 'name' según tu columna
                    });
            });
        }

        // Ordenamos por fecha descendente y traemos resultados
        $ventas = $ventas->latest()->get();

        return view('admin.ventas.index', compact('ventas'));
    }
}
