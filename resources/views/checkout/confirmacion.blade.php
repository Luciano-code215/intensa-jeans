@extends('layouts.app')

@section('content')
    <div class="container my-5 py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                {{-- Cabecera de éxito --}}
                <div class="text-center mb-4">
                    <div class="display-1 text-success mb-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="font-titulo fw-bold" style="color: #1a3352;">¡Pedido Registrado!</h2>
                    <p class="text-muted">Tu pedido <strong>#{{ $orden->id }}</strong> fue registrado correctamente.</p>
                </div>

                {{-- Datos del pedido --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom" style="color: #1a3352;">
                        <i class="bi bi-receipt me-2"></i>Detalle del Pedido
                    </h5>

                    @foreach ($orden->items as $item)
                        <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                            <div>
                                <span class="fw-semibold">{{ $item->producto->nombre ?? 'Producto eliminado' }}</span>
                                <span class="text-muted small">
                                    @if ($item->talle)
                                        | Talle: <strong>{{ $item->talle }}</strong>
                                    @endif
                                    | x{{ $item->cantidad }}
                                </span>
                                <div class="mt-1" style="font-size: 0.8rem;">
                                    <span class="text-muted">Lista: ${{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    @if ($item->precio_efectivo < $item->precio_unitario)
                                        <span class="text-success ms-2">Ef: ${{ number_format($item->subtotal_efectivo, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="fw-bold">${{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-2 pt-2">
                        <span class="fw-bold" style="color: #1a3352;">Total Lista:</span>
                        <span class="fw-bold" style="color: #1a3352;">${{ number_format($orden->total, 0, ',', '.') }}</span>
                    </div>

                    <div class="card border-success bg-success-subtle p-3 rounded-3 mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-success">Total Efectivo / Transferencia:</span>
                            @if ($ahorro > 0)
                                <span class="badge bg-success text-white">AHORRÁS</span>
                            @endif
                        </div>
                        <div class="fs-3 fw-bold text-success">
                            ${{ number_format($totalEfectivo, 0, ',', '.') }}
                        </div>
                        @if ($ahorro > 0)
                            <span class="text-success-emphasis" style="font-size: 0.75rem;">
                                Ahorrás ${{ number_format($ahorro, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Enviar por WhatsApp --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="text-center mb-3">
                        <i class="bi bi-whatsapp text-success" style="font-size: 2.5rem;"></i>
                        <h5 class="fw-bold mt-2" style="color: #1a3352;">Enviar Pedido por WhatsApp</h5>
                        <p class="text-muted small">
                            Envianos los detalles del pedido por WhatsApp para que podamos coordinar el pago y el envío.
                        </p>
                    </div>

                    <a href="{{ $urlWhatsapp }}" target="_blank"
                        class="btn btn-success btn-lg w-100 py-3 fw-bold rounded-3 shadow-sm text-uppercase d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-whatsapp fs-4"></i> Enviar Pedido Ahora
                    </a>
                </div>

                {{-- Mensaje de confirmación con número --}}
                <div class="card border-0 bg-light rounded-4 p-4 mb-4">
                    <p class="mb-2 fw-semibold" style="color: #1a3352;">
                        <i class="bi bi-check2-all me-1 text-success"></i>¿Qué sigue?
                    </p>
                    <ol class="text-muted small mb-0" style="line-height: 1.8;">
                        <li>Hacé clic en <strong>"Enviar Pedido Ahora"</strong> para enviarnos los detalles por WhatsApp.</li>
                        <li>Te vamos a responder a la brevedad para coordinar el pago y el envío.</li>
                        <li>Si elegiste pago por transferencia, te daremos nuestro alias.</li>
                        <li>Si estás en Corrientes, podemos coordinar encuentro personal.</li>
                    </ol>
                </div>

                <div class="text-center">
                    <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i> Seguir comprando
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
