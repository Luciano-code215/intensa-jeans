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

        $cuenta = \App\Models\User::where('email', $credentials['email'])->first();

        if ($cuenta && !$cuenta->activo) {
            return back()->withErrors(['email' => 'Tu cuenta está desactivada. Contactanos por WhatsApp para reactivarla.']);
        }

        $user = \App\Models\User::login($credentials['email'], $credentials['password']);

        if ($user) {
            // Iniciar sesión
            \Illuminate\Support\Facades\Auth::login($user);

            // Aviso de bienvenida según si ya inició sesión hoy
            $fechaHoy = now()->toDateString();
            if ($request->cookie('ultimo_login') === $fechaHoy) {
                session()->flash('login_msg', '¡Bueno verte de nuevo aquí hoy, ' . $user->name . '!');
            } else {
                session()->flash('login_msg', '¡Inicio de sesión exitoso! Bienvenido/a, ' . $user->name . '.');
            }

            return redirect()->intended('/')
                ->withCookie(cookie('ultimo_login', $fechaHoy, 60 * 24 * 365)); // Cookie por 1 año
        } else {
            return back()->withErrors(['email' => 'Credenciales inválidas.']);
        }
    }

    static function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'required|string|max:20',
        ]);

        $user = \App\Models\User::registrarUsuario(
            $validatedData['name'],
            $validatedData['email'],
            $validatedData['password'],
            $validatedData['telefono']
        );

        // Iniciar sesión automáticamente después del registro
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect('/') // Redirigir a la página deseada después del registro
            ->withCookie(cookie('ultimo_login', now()->toDateString(), 60 * 24 * 365));
    }

    static function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect('/'); // Redirigir a la página deseada después del logout
    }
}
