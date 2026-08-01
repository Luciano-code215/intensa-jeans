@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h1 class="font-titulo fw-bold text-denim h2 mb-1">Resumen del Negocio</h1>
            <p class="text-muted small mb-0">{{ now()->format('d/m/Y') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.productos.create') }}" class="btn btn-sm btn-denim px-3 py-2 rounded-3 fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Ventas del Mes</span>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                        <i class="bi bi-currency-dollar fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color: #1a3352;">${{ number_format($ventasMes, 0, ',', '.') }}</h3>
                @if ($pctVentas != 0)
                    <span class="small fw-medium {{ $pctVentas >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="bi bi-arrow-{{ $pctVentas >= 0 ? 'up' : 'down' }}-short"></i>
                        {{ $pctVentas > 0 ? '+' : '' }}{{ $pctVentas }}% vs mes anterior
                    </span>
                @endif
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Pedidos Pendientes</span>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                        <i class="bi bi-bag-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color: #1a3352;">{{ $pedidosPendientes }}</h3>
                <span class="small text-muted">{{ $pendientesEnvio }} pendientes de envío</span>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Prendas</span>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color: #1a3352;">{{ $prendasActivas }}</h3>
                @if ($sinStock > 0)
                    <span class="small text-danger fw-medium">{{ $sinStock }} sin stock</span>
                @else
                    <span class="small text-success">Todo con stock</span>
                @endif
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small text-uppercase fw-bold tracking-wider">Consultas</span>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-2">
                        <i class="bi bi-chat-dots fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color: #1a3352;">{{ $consultasPendientes }}</h3>
                <span class="small text-muted">{{ $usuariosRegistrados }} usuarios registrados</span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-denim mb-0">Últimos Pedidos</h5>
            <a href="{{ route('admin.ventas.index') }}" class="btn btn-link text-decoration-none btn-sm fw-semibold p-0" style="color: #d4af37;">Ver todas</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-nowrap">
                <thead class="table-light text-secondary small text-uppercase font-monospace">
                    <tr>
                        <th class="ps-4">N°</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    @forelse ($ultimasOrdenes as $o)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $o->id }}</td>
                            <td>{{ $o->nombre_contacto ?? optional($o->user)->name ?? 'N/A' }}</td>
                            <td>{{ $o->created_at->format('d/m H:i') }}</td>
                            <td class="fw-semibold">${{ number_format($o->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-1.5
                                    {{ $o->estado === 'creada' ? 'bg-warning text-dark' : '' }}
                                    {{ $o->estado === 'pagada' ? 'bg-success' : '' }}
                                    {{ $o->estado === 'entregada' ? 'bg-info' : '' }}
                                    {{ $o->estado === 'cancelada' ? 'bg-danger' : '' }}">
                                    {{ ucfirst($o->estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Sin pedidos aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection