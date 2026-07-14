@extends('layouts.admin') {{-- Hereda el nuevo layout exclusivo de administración --}}

@section('admin_content')
    {{-- Esto se inyecta automáticamente en la parte derecha --}}

    {{-- Encabezado del Dashboard --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-5">
        <div>
            <h1 class="font-titulo fw-bold text-denim h2 mb-1">Resumen del Negocio</h1>
            <p class="text-muted small mb-0">Aquí tenés las métricas clave de Intensa Jeans hoy.</p>
        </div>
        <div>
            <a href="#" class="btn btn-sm btn-denim px-3 py-2 rounded-3 fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Cargar Nuevo Jean
            </a>
        </div>
    </div>

    {{-- FILA DE TARJETAS DE MÉTRICAS (KPIs) --}}
    <div class="row g-4 mb-5">
        <!-- Ventas Mensuales -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Ventas Mes</span>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                        <i class="bi bi-currency-dollar fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1" style="color: #1a3352;">$840.000</h3>
                <span class="text-success small fw-medium"><i class="bi bi-arrow-up-short"></i> +12% vs anterior</span>
            </div>
        </div>

        <!-- Pedidos Hoy -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Pedidos Hoy</span>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                        <i class="bi bi-bag-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1" style="color: #1a3352;">18</h3>
                <span class="text-muted small">4 listos para despachar</span>
            </div>
        </div>

        <!-- Prendas Activas -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Prendas Activas</span>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1" style="color: #1a3352;">42</h3>
                <span class="text-danger small fw-medium"><i class="bi bi-exclamation-triangle-short"></i> 3 sin
                    stock</span>
            </div>
        </div>

        <!-- Mensajes -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Mensajes</span>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2">
                        <i class="bi bi-chat-dots fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1" style="color: #1a3352;">5</h3>
                <span class="text-danger small fw-medium">Requieren atención</span>
            </div>
        </div>
    </div>

    {{-- SECCIÓN INFERIOR: TABLA DE ÚLTIMAS ÓRDENES --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-denim mb-0">Últimos Pedidos Recibidos</h5>
            <a href="#" class="btn btn-link text-decoration-none btn-sm fw-semibold p-0" style="color: #d4af37;">Ver
                todas</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 text-nowrap">
                <thead class="table-light text-secondary small text-uppercase font-monospace">
                    <tr>
                        <th class="ps-4">N° Orden</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    <tr>
                        <td class="ps-4 fw-bold">#1024</td>
                        <td>Mariana Benítez</td>
                        <td>Hoy, 14:32</td>
                        <td class="fw-semibold">$57.000</td>
                        <td><span
                                class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 rounded-pill">Pendiente</span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-light btn-sm rounded-3 px-2"><i class="bi bi-eye"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
