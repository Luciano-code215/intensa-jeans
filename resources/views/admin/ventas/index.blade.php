@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <i class="bi bi-receipt-cutoff fs-4 text-success"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Historial de Ventas y Pedidos</h1>
                    <p class="text-muted small mb-0 d-none d-sm-block">Monitoreo de ventas de la tienda en tiempo real.</p>
                </div>
            </div>
        </div>

        {{-- 1. TARJETAS DE MÉTRICAS SUPERIORES --}}
        <div class="row g-3 mb-4">
            <!-- Pendientes de Envío -->
            <div class="col-12 col-md-5 col-lg-4">
                <div class="p-4 rounded-4 position-relative overflow-hidden shadow-sm d-flex flex-column justify-content-between"
                    style="background-color: #ffc107; color: #1a3352; min-height: 160px;">
                    <div>
                        <span class="text-uppercase fw-bold tracking-wider lh-1"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">Pendientes de Envío</span>
                        <h1 class="fw-bold my-1 display-5 lh-1">{{ \App\Models\Orden::cantidadVentasPorEstado('creada') }}
                        </h1>
                    </div>
                    <p class="small mb-0 opacity-75 fw-medium" style="font-size: 0.85rem; max-width: 80%;">Paquetes por
                        armar o despachar</p>
                    <i class="bi bi-truck position-absolute end-0 bottom-0 mb-3 me-3 opacity-25"
                        style="font-size: 3.8rem; line-height: 1; pointer-events: none; color: #1a3352;"></i>
                </div>
            </div>
        </div>

        {{-- 2. BUSCADOR Y FILTROS AVANZADOS --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <h6 class="fw-bold text-denim d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-funnel text-secondary"></i> Buscador y Filtros Avanzados
            </h6>

            <form action="{{ route('admin.ventas.index') }}" method="GET">
                <div class="row g-3 align-items-end">

                    {{-- 1. Select de Estados --}}
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="estado" class="form-label small fw-bold text-secondary">Estado</label>
                        <select name="estado" id="estado" class="form-select rounded-3" onchange="this.form.submit()">
                            <option value="">-- Todos los estados --</option>
                            <option value="creada" {{ request('estado') == 'creada' ? 'selected' : '' }}>Creada</option>
                            <option value="pagada" {{ request('estado') == 'pagada' ? 'selected' : '' }}>Pagada</option>
                            <option value="entregada" {{ request('estado') == 'entregada' ? 'selected' : '' }}>Entregada
                            </option>
                            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada
                            </option>
                        </select>
                    </div>

                    {{-- 2. Fecha Desde --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label for="fecha_inicio" class="form-label small fw-bold text-secondary">Desde</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control rounded-3"
                            value="{{ request('fecha_inicio') }}" onchange="this.form.submit()">
                    </div>

                    {{-- 3. Fecha Hasta --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label for="fecha_fin" class="form-label small fw-bold text-secondary">Hasta</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control rounded-3"
                            value="{{ request('fecha_fin') }}" onchange="this.form.submit()">
                    </div>

                    {{-- 4. Buscador por Palabra Clave --}}
                    <div class="col-12 col-sm-6 col-lg-5">
                        <label for="buscar" class="form-label small fw-bold text-secondary">Buscar Cliente u Orden</label>
                        <div class="input-group">
                            <input type="text" name="buscar" id="buscar" class="form-control rounded-start-3"
                                placeholder="ID o nombre de cliente..." value="{{ request('buscar') }}">

                            <button class="btn btn-dark bg-denim rounded-end-3" type="submit">
                                <i class="bi bi-search"></i> Buscar
                            </button>

                            {{-- Botón opcional para limpiar filtros si hay alguno activo --}}
                            @if (request()->hasAny(['estado', 'fecha_inicio', 'fecha_fin', 'buscar']))
                                <a href="{{ route('admin.ventas.index') }}" class="btn btn-outline-secondary rounded-3 ms-1"
                                    title="Limpiar Filtros">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- 3. TABLA: ÚLTIMAS TRANSACCIONES REGISTRADAS --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-list-task text-secondary"></i> Transacciones Registradas
                </h5>
            </div>

            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3">N° Pedido</th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Total</th>
                            <th class="py-3" style="width: 220px;">Estado</th>
                            <th class="py-3 text-end" style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        @forelse ($ventas as $venta)
                            <tr class="border-bottom border-light">
                                <td class="fw-bold text-dark">#{{ $venta->id }}</td>
                                <td class="text-capitalize text-secondary">
                                    {{ optional($venta->user)->name ?? ($venta->nombre ?? 'N/A') }}</td>
                                <td class="text-muted">
                                    {{ $venta->created_at ? $venta->created_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="fw-bold text-dark">${{ number_format($venta->total, 0, ',', '.') }}</td>
                                <td>
                                    <select
                                        class="form-select form-select-sm rounded-3 fw-medium bg-light bg-opacity-70 text-dark border-0 py-1.5 px-3">
                                        <option value="creada" {{ $venta->estado === 'creada' ? 'selected' : '' }}>Creada
                                        </option>
                                        <option value="pagada" {{ $venta->estado === 'pagada' ? 'selected' : '' }}>Pagada
                                        </option>
                                        <option value="entregada" {{ $venta->estado === 'entregada' ? 'selected' : '' }}>
                                            Entregada</option>
                                        <option value="cancelada" {{ $venta->estado === 'cancelada' ? 'selected' : '' }}>
                                            Cancelada</option>
                                    </select>
                                </td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                        style="font-size: 0.85rem;">
                                        <i class="bi bi-eye"></i> Detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No se encontraron ventas que coincidan con los filtros aplicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <style>
        .form-control:focus,
        .form-select:focus {
            box-shadow: none !important;
        }
    </style>
@endsection
