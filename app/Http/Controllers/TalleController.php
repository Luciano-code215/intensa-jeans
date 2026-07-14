<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Talle;

class TalleController extends Controller
{
    public function store(Request $request)
    {
        // Validamos que el nombre sea único en la tabla 'talles'
        $request->validate([
            'nombre' => 'required|string|unique:talles,nombre|max:10',
        ], [
            'nombre.unique' => 'Este talle ya está registrado en el sistema.'
        ]);

        // Creamos el talle en la base de datos
        Talle::create([
            'nombre' => strtoupper($request->nombre) // Lo guardamos en mayúsculas
        ]);

        // Te regresa a la pantalla de "Nuevo Producto" con el talle ya cargado
        return redirect()->back()->with('producto_creado', '¡Talle creado con éxito! Ya podés asignarle stock.');
    }
}
