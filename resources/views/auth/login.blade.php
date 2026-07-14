@extends('layouts.app') {{-- Reemplazá por tu layout principal si es otro --}}

@section('content')
    <div class="container my-5 py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">

                    {{-- Encabezado --}}
                    <div class="text-center mb-4">
                        <h2 class="font-titulo fw-bold mb-1" style="color: #1a3352;">¡Hola de nuevo!</h2>
                        <p class="text-muted small">Ingresá a tu cuenta para gestionar tus compras</p>
                    </div>

                    {{-- Formulario --}}
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">Correo
                                Electrónico</label>
                            <input type="email" name="email" id="email"
                                class="form-control rounded-3 py-2 @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="ejemplo@correo.com" required autofocus>
                            @error('email')
                                <span class="invalid-feedback small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password"
                                    class="form-label small fw-semibold text-secondary mb-0">Contraseña</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none"
                                        style="color: #c9a054; font-size: 0.75rem;">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>
                            <input type="password" name="password" id="password"
                                class="form-control rounded-3 py-2 @error('password') is-invalid @enderror"
                                placeholder="••••••••" required>
                            @error('password')
                                <span class="invalid-feedback small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Recordarme --}}
                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted" for="remember">Recordar mi sesión</label>
                        </div>

                        {{-- Botón Ingresar --}}
                        <button type="submit"
                            class="btn btn-lg w-100 py-2.5 rounded-3 text-white fw-bold text-uppercase tracking-wider shadow-sm mb-3"
                            style="background-color: #1a3352; font-size: 0.85rem;">
                            Iniciar Sesión
                        </button>

                    </form>

                    {{-- Link a Registro --}}
                    <div class="text-center mt-3 pt-3 border-top">
                        <p class="small text-muted mb-0">
                            ¿No tenés cuenta?
                            <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: #1a3352;">
                                Registrate acá
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
