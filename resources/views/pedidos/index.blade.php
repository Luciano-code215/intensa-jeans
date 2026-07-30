@extends('layouts.app')

@section('content')
    <div class="container my-5">

        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="font-titulo fw-bold m-0" style="color: #1a3352;">
                <i class="bi bi-box-seam me-2"></i>Mis Pedidos
            </h2>
        </div>

        @if ($pedidos->isEmpty())
            <div class="text-center py-5 my-4 bg-light rounded-4 shadow-sm">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-3" style="color: #1a3352;">No tenés pedidos aún</h4>
                <p class="text-muted mb-4">Tus pedidos aparecerán acá una vez que realices tu primera compra.</p>
                <a href="{{ route('catalogo.index') }}" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm"
                    style="background-color: #1a3352;">
                    Explorar Catálogo
                </a>
            </div>
        @else
            <div class="row g-4">
                @foreach ($pedidos as $pedido)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center px-4 py-3">
                                <div>
                                    <span class="text-muted small">Pedido #{{ $pedido->id }}</span>
                                    <span class="mx-2 text-muted">|</span>
                                    <span class="text-muted small">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    @php
                                        $badges = [
                                            'creada' => ['bg-warning text-dark', 'Creada'],
                                            'pagada' => ['bg-info text-white', 'Pagada'],
                                            'entregada' => ['bg-success text-white', 'Entregada'],
                                            'cancelada' => ['bg-danger text-white', 'Cancelada'],
                                        ];
                                        $badge = $badges[$pedido->estado] ?? ['bg-secondary text-white', ucfirst($pedido->estado)];
                                    @endphp
                                    <span class="badge {{ $badge[0] }} rounded-pill px-3 py-2">{{ $badge[1] }}</span>
                                </div>
                            </div>
                            <div class="card-body px-4 py-3">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0 small">
                                        <thead class="text-muted">
                                            <tr>
                                                <th>Producto</th>
                                                <th class="text-center">Cant.</th>
                                                <th class="text-end">Precio Unit.</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pedido->items as $item)
                                                <tr>
                                                    <td>{{ $item->producto->nombre ?? 'Producto eliminado' }}</td>
                                                    <td class="text-center">{{ $item->cantidad }}</td>
                                                    <td class="text-end">${{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                                                    <td class="text-end fw-semibold">${{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-top px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">{{ $pedido->items->count() }} prenda(s)</span>
                                <span class="fw-bold fs-5" style="color: #1a3352;">
                                    Total: ${{ number_format($pedido->total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
