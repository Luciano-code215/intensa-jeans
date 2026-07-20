<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('admin.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'activo' => true,
        ]);

        return redirect()->route('admin.categorias.index')->with('categoria_creada', 'Categoría creada exitosamente.');
    }

    public function alterarEstado($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->activo = !$categoria->activo;
        $categoria->save();

        $mensaje = $categoria->activo ? 'La categoría ha sido reactivada y publicada en la tienda con éxito.' : 'La categoría ha sido desactivada y pausada de la tienda con éxito.';

        return redirect()->route('admin.categorias.index')->with('estado_alterado', $mensaje);
    }
}
