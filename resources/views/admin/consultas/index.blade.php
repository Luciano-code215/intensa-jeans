@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ENCABEZADO --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <i class="bi bi-chat-square-text-fill fs-4 text-primary"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Consultas de Clientes (Logueados)</h1>
                    <p class="text-muted small mb-0 d-none d-sm-block">Interacciones con usuarios registrados del sitio.</p>
                </div>
            </div>
        </div>

        {{-- 1. TARJETAS DE MÉTRICAS (Estilo corregido con espaciado óptimo) --}}
        <div class="row g-3 mb-4">
            <!-- Total Consultas -->
            <div class="col-12 col-md-4">
                <div class="p-4 rounded-4 position-relative overflow-hidden shadow-sm text-white d-flex flex-column justify-content-between"
                    style="background-color: #0d6efd; min-height: 150px;">
                    <div>
                        <span class="text-uppercase fw-bold tracking-wider lh-1"
                            style="font-size: 0.78rem; letter-spacing: 0.5px; opacity: 0.9;">Total Consultas</span>
                        <h1 class="fw-bold my-1 display-5 lh-1">{{ \App\Models\Consulta::total() }}</h1>
                    </div>
                    <p class="small mb-0 opacity-75 fw-medium" style="font-size: 0.85rem;">Historial acumulado de preguntas
                    </p>
                    <i class="bi bi-chat-dots-fill position-absolute end-0 bottom-0 mb-2 me-3 opacity-25"
                        style="font-size: 3.5rem; line-height: 1; pointer-events: none;"></i>
                </div>
            </div>

            <!-- Sin Responder -->
            <div class="col-12 col-md-4">
                <div class="p-4 rounded-4 position-relative overflow-hidden shadow-sm text-white d-flex flex-column justify-content-between"
                    style="background-color: #dc3545; min-height: 150px;">
                    <div>
                        <span class="text-uppercase fw-bold tracking-wider lh-1"
                            style="font-size: 0.78rem; letter-spacing: 0.5px; opacity: 0.9;">Sin Responder</span>
                        <h1 class="fw-bold my-1 display-5 lh-1">{{ \App\Models\Consulta::cantPendientes() }}</h1>
                    </div>
                    <p class="small mb-0 opacity-75 fw-medium" style="font-size: 0.85rem;">Mensajes críticos que esperan
                        feedback</p>
                    <i class="bi bi-exclamation-triangle-fill position-absolute end-0 bottom-0 mb-2 me-3 opacity-25"
                        style="font-size: 3.5rem; line-height: 1; pointer-events: none;"></i>
                </div>
            </div>

            <!-- Respondidas -->
            <div class="col-12 col-md-4">
                <div class="p-4 rounded-4 position-relative overflow-hidden shadow-sm text-white d-flex flex-column justify-content-between"
                    style="background-color: #0dcaf0; min-height: 150px;">
                    <div>
                        <span class="text-uppercase fw-bold tracking-wider lh-1"
                            style="font-size: 0.78rem; letter-spacing: 0.5px; opacity: 0.9;">Respondidas</span>
                        <h1 class="fw-bold my-1 display-5 lh-1">{{ \App\Models\Consulta::cantRespondidas() }}</h1>
                    </div>
                    <p class="small mb-0 opacity-75 fw-medium" style="font-size: 0.85rem;">Consultas atendidas correctamente
                    </p>
                    <i class="bi bi-check-all position-absolute end-0 bottom-0 mb-2 me-2 opacity-25"
                        style="font-size: 4rem; line-height: 1; pointer-events: none;"></i>
                </div>
            </div>
        </div>

        {{-- 2. FILTRADO (Bandeja de Entrada) --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <h6 class="fw-bold text-denim d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-filter-left fs-5 text-secondary"></i> Bandeja de Entrada
            </h6>

            <form action="{{ route('admin.consultas.index') }}" method="GET" class="row">
                <div class="col-12">
                    <select class="form-select rounded-3 py-2.5 text-secondary bg-white" name="filtro_cuenta"
                        style="border-color: #0d6efd !important;" onchange="this.form.submit()"> {{-- Enviamos el formulario al cambiar de opción --}}

                        <option value="todas" {{ request('filtro_cuenta') == 'todas' ? 'selected' : '' }}>
                            Mostrar todas las consultas de la cuenta
                        </option>
                        <option value="pendientes" {{ request('filtro_cuenta') == 'pendientes' ? 'selected' : '' }}>
                            Mostrar solo pendientes
                        </option>
                        <option value="respondidas" {{ request('filtro_cuenta') == 'respondidas' ? 'selected' : '' }}>
                            Mostrar solo respondidas
                        </option>

                    </select>
                </div>
            </form>
        </div>

        {{-- 3. TABLA DE CONSULTAS --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive px-4 py-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3 ps-2">ID Usuario</th>
                            <th class="py-3">Cliente Logueado</th>
                            <th class="py-3">Producto / Motivo</th>
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3 text-center" style="width: 140px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">

                        @foreach ($consultas as $consulta)
                            <tr class="border-bottom border-light">
                                <td class="text-muted font-monospace fw-bold ps-2">{{ $consulta->user_id }}</td>
                                <td class="text-lowercase text-secondary">
                                    {{ optional($consulta->user)->name ?? 'Invitado' }}</td>
                                <td class="text-dark">{{ $consulta->asunto }}</td>
                                <td class="text-muted small">
                                    {{ $consulta->created_at ? $consulta->created_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <span
                                        class="badge bg-success px-2.5 py-1.5 rounded-2 d-inline-flex align-items-center gap-1"
                                        style="font-size: 0.8rem; background-color: #198754 !important;">
                                        <i class="bi bi-check-circle-fill" style="font-size: 0.75rem;"></i> Respondida
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{-- BOTÓN QUE ABRE EL MODAL --}}
                                    <button type="button"
                                        class="btn btn-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                        style="background-color: #6c757d; font-size: 0.85rem; border: 0;"
                                        data-bs-toggle="modal" data-bs-target="#modalConsulta{{ $consulta->id }}">
                                        <i class="bi bi-eye-fill"></i> Ver mensaje
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL DETALLE DE LA CONSULTA --}}
                            <div class="modal fade" id="modalConsulta{{ $consulta->id }}" tabindex="-1"
                                aria-labelledby="modalConsultaLabel{{ $consulta->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg rounded-4">

                                        {{-- Encabezado del Modal --}}
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2"
                                                id="modalConsultaLabel{{ $consulta->id }}">
                                                <i class="bi bi-envelope-open-text text-primary fs-4"></i> Detalle de la
                                                Consulta
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body p-4">

                                            {{-- Tarjeta con Datos del Usuario --}}
                                            <div class="bg-light p-3 rounded-3 mb-3 border">
                                                <h6 class="fw-bold text-secondary mb-2 small text-uppercase font-monospace">
                                                    Información del Remitente</h6>
                                                <div class="row g-2 text-start">
                                                    <div class="col-12 col-md-4">
                                                        <span class="text-muted d-block small">Usuario:</span>
                                                        <strong
                                                            class="text-dark">{{ optional($consulta->user)->name ?? ($consulta->nombre ?? 'N/A') }}</strong>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <span class="text-muted d-block small">Email:</span>
                                                        <strong
                                                            class="text-dark">{{ optional($consulta->user)->email ?? ($consulta->email ?? 'N/A') }}</strong>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <span class="text-muted d-block small">Teléfono:</span>
                                                        <strong
                                                            class="text-dark">{{ optional($consulta->user)->telefono ?? ($consulta->telefono ?? 'N/A') }}</strong>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Asunto y Fecha --}}
                                            <div class="mb-3 text-start">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold text-dark fs-6">{{ $consulta->asunto }}</span>
                                                    <span class="badge bg-secondary opacity-75 font-monospace fw-normal">
                                                        {{ $consulta->created_at ? $consulta->created_at->format('d/m/Y H:i') : '-' }}
                                                        hs
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Mensaje / Contenido de la consulta --}}
                                            <div class="text-start">
                                                <label class="form-label small fw-bold text-muted mb-1">Mensaje:</label>
                                                <div class="p-3 bg-white rounded-3 border text-secondary"
                                                    style="white-space: pre-line; min-height: 100px;">
                                                    {{ $consulta->mensaje ?? ($consulta->contenido ?? 'Sin contenido especificado.') }}
                                                </div>
                                            </div>

                                        </div>

                                        {{-- Pie del Modal --}}
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary rounded-3 px-4"
                                                data-bs-dismiss="modal">Cerrar</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <style>
        .form-select:focus {
            box-shadow: none !important;
        }
    </style>
@endsection
