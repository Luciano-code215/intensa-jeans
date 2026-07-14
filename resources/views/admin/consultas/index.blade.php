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
                        <h1 class="fw-bold my-1 display-5 lh-1">4</h1>
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
                        <h1 class="fw-bold my-1 display-5 lh-1">0</h1>
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
                        <h1 class="fw-bold my-1 display-5 lh-1">4</h1>
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

            <form action="#" method="GET" class="row">
                <div class="col-12">
                    <select class="form-select rounded-3 py-2.5 text-secondary bg-white" name="filtro_cuenta"
                        style="border-color: #0d6efd !important;">
                        <option value="todas">Mostrar todas las consultas de la cuenta</option>
                        <option value="pendientes">Mostrar solo pendientes</option>
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

                        {{-- Registro 1 --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-bold ps-2">#USR-003</td>
                            <td class="text-lowercase text-secondary">luciano</td>
                            <td class="text-dark">Soporte técnico / Falla en pasarela</td>
                            <td class="text-muted small">16/06/2026 23:09</td>
                            <td>
                                <span
                                    class="badge bg-success px-2.5 py-1.5 rounded-2 d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.8rem; background-color: #198754 !important;">
                                    <i class="bi bi-check-circle-fill" style="font-size: 0.75rem;"></i> Respondida
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="#"
                                    class="btn btn-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                    style="background-color: #6c757d; font-size: 0.85rem; border: 0;">
                                    <i class="bi bi-eye-fill"></i> Ver Respuesta
                                </a>
                            </td>
                        </tr>

                        {{-- Registro 2 --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-bold ps-2">#USR-003</td>
                            <td class="text-lowercase text-secondary">luciano</td>
                            <td class="text-dark">Jean Mom talle 42 - Cambio de talle</td>
                            <td class="text-muted small">16/06/2026 22:13</td>
                            <td>
                                <span
                                    class="badge bg-success px-2.5 py-1.5 rounded-2 d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.8rem; background-color: #198754 !important;">
                                    <i class="bi bi-check-circle-fill" style="font-size: 0.75rem;"></i> Respondida
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="#"
                                    class="btn btn-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                    style="background-color: #6c757d; font-size: 0.85rem; border: 0;">
                                    <i class="bi bi-eye-fill"></i> Ver Respuesta
                                </a>
                            </td>
                        </tr>

                        {{-- Registro 3 --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-bold ps-2">#USR-003</td>
                            <td class="text-lowercase text-secondary">luciano</td>
                            <td class="text-dark">Recibí en malas condiciones el paquete</td>
                            <td class="text-muted small">16/06/2026 18:25</td>
                            <td>
                                <span
                                    class="badge bg-success px-2.5 py-1.5 rounded-2 d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.8rem; background-color: #198754 !important;">
                                    <i class="bi bi-check-circle-fill" style="font-size: 0.75rem;"></i> Respondida
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="#"
                                    class="btn btn-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                    style="background-color: #6c757d; font-size: 0.85rem; border: 0;">
                                    <i class="bi bi-eye-fill"></i> Ver Respuesta
                                </a>
                            </td>
                        </tr>

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
