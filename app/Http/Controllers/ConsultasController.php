<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class ConsultasController extends Controller
{
    public function index(Request $request)
    {
        $query = Consulta::with('user');

        // Aplicar el filtro según lo enviado por el <select>
        if ($request->filled('filtro_cuenta')) {
            if ($request->filtro_cuenta === 'pendientes') {
                $query->where('estado', 'pendiente'); // O la columna/condición que uses
            } elseif ($request->filtro_cuenta === 'respondidas') {
                $query->where('estado', 'respondida');
            }
        }

        $consultas = $query->latest()->get();

        return view('admin.consultas.index', compact('consultas'));
    }

    public function create()
    {
        return view('consultas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string|max:2000',
        ]);

        Consulta::create([
            'user_id' => auth()->id(),
            'asunto' => $validated['asunto'],
            'mensaje' => $validated['mensaje'],
            'estado' => 'pendiente',
        ]);

        return redirect()->route('consultas.create')->with('consulta_enviada', 'Tu consulta fue enviada correctamente. Te responderemos a la brevedad.');
    }
}
