<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    static function showLoginForm()
    {
        return view('auth.login');
    }

    static function showRegistrationForm()
    {
        return view('auth.register');
    }

    static function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = \App\Models\User::login($credentials['email'], $credentials['password']);

        if ($user) {
            // Iniciar sesión
            \Illuminate\Support\Facades\Auth::login($user);
            return redirect()->intended('/'); // Redirigir a la página deseada después del login
        } else {
            return back()->withErrors(['email' => 'Credenciales inválidas.']);
        }
    }

    static function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::registrarUsuario(
            $validatedData['name'],
            $validatedData['email'],
            $validatedData['password']
        );

        // Iniciar sesión automáticamente después del registro
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect('/'); // Redirigir a la página deseada después del registro
    }

    static function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect('/'); // Redirigir a la página deseada después del logout
    }
}
