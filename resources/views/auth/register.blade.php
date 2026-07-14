@extends('layouts.app')

@section('content')
    <div class="container my-5 py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">

                    {{-- Encabezado --}}
                    <div class="text-center mb-4">
                        <h2 class="font-titulo fw-bold mb-1" style="color: #1a3352;">Crear Cuenta</h2>
                        <p class="text-muted small">Completá tus datos para formar parte de la comunidad</p>
                    </div>

                    {{-- Formulario --}}
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        {{-- Nombre Completo --}}
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-semibold text-secondary">Nombre
                                Completo</label>
                            <input type="text" name="name" id="name"
                                class="form-control rounded-3 py-2 @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Juan Pérez" required autofocus>
                            @error('name')
                                <span class="invalid-feedback small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">Correo
                                Electrónico</label>
                            <input type="email" name="email" id="email"
                                class="form-control rounded-3 py-2 @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="ejemplo@correo.com" required>
                            @error('email')
                                <span class="invalid-feedback small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Contraseña --}}
                            <div class="col-sm-6 mb-3">
                                <label for="password" class="form-label small fw-semibold text-secondary">Contraseña</label>
                                <input type="password" name="password" id="password"
                                    class="form-control rounded-3 py-2 @error('password') is-invalid @enderror"
                                    placeholder="Mínimo 8 caracteres" required>
                                @error('password')
                                    <span class="invalid-feedback small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Confirmar Contraseña --}}
                            <div class="col-sm-6 mb-4">
                                <label for="password-confirm" class="form-label small fw-semibold text-secondary">Confirmar
                                    Contraseña</label>
                                <input type="password" name="password_confirmation" id="password-confirm"
                                    class="form-control rounded-3 py-2" placeholder="Repetir contraseña" required>
                            </div>
                        </div>

                        {{-- Botón Registrarse --}}
                        <button type="submit"
                            class="btn btn-lg w-100 py-2.5 rounded-3 text-white fw-bold text-uppercase tracking-wider shadow-sm mb-3"
                            style="background-color: #1a3352; font-size: 0.85rem;">
                            Registrarme
                        </button>

                    </form>

                    {{-- Link a Login --}}
                    <div class="text-center mt-3 pt-3 border-top">
                        <p class="small text-muted mb-0">
                            ¿Ya tenés una cuenta?
                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #1a3352;">
                                Iniciá sesión acá
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
