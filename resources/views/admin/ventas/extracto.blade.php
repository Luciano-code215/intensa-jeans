@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <i class="bi bi-file-earmark-bar-graph fs-4 text-success"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Extracto de Ventas</h1>
                    <p class="text-muted small mb-0">
                        Órdenes activas (excluye entregadas y canceladas)
                        @if (request()->hasAny(['estado', 'fecha_inicio', 'fecha_fin', 'buscar']))
                            <span class="badge bg-warning text-dark ms-2">Filtros aplicados</span>
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.ventas.index') }}" class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-denim mb-0">
                    <i class="bi bi-list-check text-secondary me-2"></i> {{ $ordenes->count() }} registro(s)
                </h5>
                <span class="fw-bold fs-5" style="color: #1a3352;">
                    Total: ${{ number_format($totalGeneral, 0, ',', '.') }}
                </span>
            </div>

            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3">N°</th>
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Origen</th>
                            <th class="py-3">Método de Pago</th>
                            <th class="py-3 text-end">Importe</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        @forelse ($ordenes as $orden)
                            <tr class="border-bottom border-light">
                                <td class="fw-bold text-dark">#{{ $orden->id }}</td>
                                <td class="text-muted">{{ $orden->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-capitalize text-secondary">
                                    {{ $orden->nombre_contacto ?? optional($orden->user)->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2
                                        {{ $orden->origen === 'mostrador' ? 'bg-info bg-opacity-25 text-info' : 'bg-primary bg-opacity-10 text-primary' }}">
                                        {{ $mapaOrigen[$orden->origen] ?? $orden->origen ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-secondary">{{ $mapaPago[$orden->metodo_pago] ?? $orden->metodo_pago ?? '-' }}</td>
                                <td class="fw-bold text-end text-dark">
                                    ${{ number_format($orden->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No hay órdenes activas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end fw-bold py-3">TOTAL</td>
                            <td class="fw-bold text-end py-3 fs-5" style="color: #1a3352;">
                                ${{ number_format($totalGeneral, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
@endsection