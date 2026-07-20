<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::query();

        // Aplicamos los filtros de búsqueda si existen
        if ($request->filled('buscar')) {
            $palabra = $request->buscar;

            $query->where(function ($q) use ($palabra) {
                $q->where('name', 'LIKE', "%{$palabra}%")
                    ->orWhere('id', 'LIKE', "%{$palabra}%")
                    ->orWhere('email', 'LIKE', "%{$palabra}%")
                    ->orWhere('role', 'LIKE', "%{$palabra}%");
            });
        }

        // Ejecutamos la consulta una sola vez para traer los usuarios filtrados
        $usuariosFiltrados = $query->get();

        // Dividimos la colección en dos usando partition() basándonos en tu método isAdmin()
        list($admins, $clientes) = $usuariosFiltrados->partition(function ($user) {
            // Si la columna role dice 'admin', va a la colección $admins. Si no, va a $clientes.
            return $user->role === 'admin';
        });

        // Enviamos las dos colecciones limpias a la vista en lugar de enviar 'usuarios'
        return view('admin.usuarios.index', compact('admins', 'clientes'));
    }

    public function cambiarEstado($id)
    {
        $usuario = \App\Models\User::findOrFail($id);
        $usuario->activo = !$usuario->activo; // Cambia el estado
        $usuario->save();

        return redirect()->route('admin.usuarios.index')->with('estado_alterado', 'Estado del usuario actualizado correctamente.');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // O como manejes los roles en tu sistema
            'activo' => true
        ]);

        return redirect()->back()->with('success', 'Administrador creado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed', // 'confirmed' busca automáticamente el campo password_confirmation
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Tu contraseña se actualizó correctamente.');
    }
}
