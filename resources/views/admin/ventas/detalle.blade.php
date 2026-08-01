@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.ventas.index') }}"
                    class="btn btn-outline-secondary rounded-3 p-2 d-inline-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 42px; height: 42px;">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h1 class="h4 fw-bold text-denim mb-0 font-titulo">Orden #{{ $orden->id }}</h1>
                    <p class="text-muted small mb-0">Detalle completo del pedido</p>
                </div>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <span class="badge rounded-pill px-3 py-2 fs-6
                    {{ $orden->estado === 'creada' ? 'bg-warning text-dark' : '' }}
                    {{ $orden->estado === 'pagada' ? 'bg-success' : '' }}
                    {{ $orden->estado === 'entregada' ? 'bg-info' : '' }}
                    {{ $orden->estado === 'cancelada' ? 'bg-danger' : '' }}
                    {{ $orden->estado === 'devuelta' ? 'bg-dark' : '' }}">
                    {{ ucfirst($orden->estado) }}
                </span>
                @if (in_array($orden->estado, ['pagada', 'entregada']))
                    <form action="{{ route('admin.ventas.devolver', $orden->id) }}" method="POST" class="form-devolver-detalle">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 fw-semibold">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Registrar Devolución
                        </button>
                    </form>
                @elseif ($orden->estado === 'devuelta')
                    <a href="{{ route('admin.ventas.reabrirForm', $orden->id) }}"
                        class="btn btn-outline-success btn-sm rounded-3 fw-semibold">
                        <i class="bi bi-cart-plus me-1"></i>Reabrir venta
                    </a>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-denim mb-0"><i class="bi bi-box-seam me-2"></i>Productos</h5>
                    </div>
                    <div class="table-responsive px-4 pb-4">
                        <table class="table align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                                <tr>
                                    <th class="py-2">SKU</th>
                                    <th class="py-2">Producto</th>
                                    <th class="py-2">Talle</th>
                                    <th class="py-2 text-center">Cant.</th>
                                    <th class="py-2 text-end">Precio</th>
                                    <th class="py-2 text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $aplicaDesc = in_array($orden->metodo_pago, ['efectivo', 'transferencia']);
                                    $totalConDesc = 0;
                                @endphp
                                @foreach ($orden->items as $item)
                                    @php
                                        $precioUnitario = $item->precio_unitario;
                                        $descItem = 0;
                                        if ($aplicaDesc && $item->producto && $item->producto->porc_desc_ef > 0) {
                                            $precioUnitario = $item->precio_unitario * (1 - $item->producto->porc_desc_ef / 100);
                                            $descItem = $item->precio_unitario - $precioUnitario;
                                        }
                                        $subtotalItem = $precioUnitario * $item->cantidad;
                                        $totalConDesc += $subtotalItem;
                                    @endphp
                                    <tr>
                                        <td class="font-monospace text-muted small">{{ $item->producto->sku ?? '—' }}</td>
                                        <td class="fw-semibold">{{ $item->producto->nombre }}</td>
                                        <td>{{ $item->talle ?? '—' }}</td>
                                        <td class="text-center">{{ $item->cantidad }}</td>
                                        <td class="text-end">
                                            ${{ number_format($precioUnitario, 0, ',', '.') }}
                                            @if ($descItem > 0)
                                                <br><small class="text-muted" style="text-decoration: line-through; font-size: 0.75rem;">${{ number_format($item->precio_unitario, 0, ',', '.') }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">${{ number_format($subtotalItem, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold py-2">TOTAL</td>
                                    <td class="text-end fw-bold fs-5 py-2" style="color:#1a3352;">
                                        @if ($totalConDesc < $orden->total)
                                            <span class="text-success">${{ number_format($totalConDesc, 0, ',', '.') }}</span>
                                            <br><small class="text-muted" style="text-decoration: line-through; font-size: 0.8rem;">${{ number_format($orden->total, 0, ',', '.') }}</small>
                                        @else
                                            ${{ number_format($orden->total, 0, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-denim mb-0"><i class="bi bi-person me-2"></i>Cliente</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <p class="mb-1 fw-semibold">{{ $orden->nombre_contacto ?? optional($orden->user)->name ?? 'N/A' }}</p>
                        @if ($orden->telefono_contacto)
                            <p class="mb-1 text-muted small"><i class="bi bi-whatsapp me-1"></i>{{ $orden->telefono_contacto }}</p>
                        @endif
                        @if ($orden->user)
                            <p class="mb-0 text-muted small"><i class="bi bi-envelope me-1"></i>{{ $orden->user->email }}</p>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-white mt-3">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-denim mb-0"><i class="bi bi-info-circle me-2"></i>Detalles</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Origen:</span>
                            <span>{{ $orden->origen === 'mostrador' ? 'Mostrador' : 'Web' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Método de pago:</span>
                            <span>
                                @php
                                    $mapa = ['efectivo'=>'Efectivo','tarjeta'=>'Tarjeta','transferencia'=>'Transferencia','tarjeta_debito'=>'Tarjeta Débito','tarjeta_credito'=>'Tarjeta Crédito'];
                                @endphp
                                {{ $mapa[$orden->metodo_pago] ?? $orden->metodo_pago ?? '—' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Estado:</span>
                            <span class="fw-semibold">{{ ucfirst($orden->estado) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Fecha:</span>
                            <span>{{ $orden->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.form-devolver-detalle').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = '¿Registrar devolución de esta venta?\n\nSe revertirá el ingreso y se restablecerá el stock de los productos.\nDespués podés volver a armar la venta con los productos que quieras.';
                if (confirm(msg)) {
                    this.submit();
                }
            });
        });
    </script>
@endsection