@extends('layouts.app')

@section('content')
    <div class="container my-5 py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">

                    <div class="text-center mb-4">
                        <h2 class="font-titulo fw-bold mb-1" style="color: #1a3352;">Consultas</h2>
                        <p class="text-muted small">Envianos tu consulta y te responderemos a la brevedad</p>
                    </div>

                    @if (session('consulta_enviada'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('consulta_enviada') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('consultas.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="asunto" class="form-label small fw-semibold text-secondary">Asunto</label>
                            <input type="text" name="asunto" id="asunto"
                                class="form-control rounded-3 py-2 @error('asunto') is-invalid @enderror"
                                value="{{ old('asunto') }}" placeholder="Ej: Consulta sobre talle" required>
                            @error('asunto')
                                <span class="invalid-feedback small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="mensaje" class="form-label small fw-semibold text-secondary">Mensaje</label>
                            <textarea name="mensaje" id="mensaje" rows="6"
                                class="form-control rounded-3 py-2 @error('mensaje') is-invalid @enderror"
                                placeholder="Escribí tu consulta aquí..." required>{{ old('mensaje') }}</textarea>
                            @error('mensaje')
                                <span class="invalid-feedback small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit"
                            class="btn btn-lg w-100 py-2.5 rounded-3 text-white fw-bold text-uppercase tracking-wider shadow-sm mb-3"
                            style="background-color: #1a3352; font-size: 0.85rem;">
                            Enviar Consulta
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
