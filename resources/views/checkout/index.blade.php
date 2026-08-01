@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="font-titulo fw-bold m-0" style="color: #1a3352;">
                <i class="bi bi-credit-card me-2"></i>Finalizar Compra
            </h2>
        </div>

        @if (session('error_stock'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {!! session('error_stock') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="row g-4">

                {{-- COLUMNA IZQUIERDA: DATOS DE CONTACTO --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3 pb-2 border-bottom" style="color: #1a3352;">
                            <i class="bi bi-person me-2"></i>Datos de Contacto
                        </h5>

                        @guest
                            <div class="mb-3">
                                <label for="nombre" class="form-label small fw-semibold text-secondary">Nombre Completo</label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control rounded-3 py-2 @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}" placeholder="Tu nombre" required>
                                @error('nombre')
                                    <span class="invalid-feedback small"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label small fw-semibold text-secondary">Teléfono (WhatsApp)</label>
                                <input type="tel" name="telefono" id="telefono"
                                    class="form-control rounded-3 py-2 @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono') }}" placeholder="+54 379 500-0000" required>
                                @error('telefono')
                                    <span class="invalid-feedback small"><strong>{{ $message }}</strong></span>
                                @enderror
                                <div class="form-text text-muted small">Te vamos a notificar por WhatsApp cuando recibamos tu pedido.</div>
                            </div>
                        @endguest

                        @auth
                            <div class="bg-light rounded-3 p-3">
                                <p class="mb-1 fw-semibold">{{ auth()->user()->name }}</p>
                                <p class="mb-0 text-muted small">
                                    <i class="bi bi-whatsapp me-1"></i>{{ auth()->user()->telefono ?? 'Sin teléfono registrado' }}
                                </p>
                                <p class="mb-0 text-muted small mt-1">
                                    <i class="bi bi-envelope me-1"></i>{{ auth()->user()->email }}
                                </p>
                            </div>
                            <p class="text-muted small mt-2 mb-0">
                                <i class="bi bi-info-circle me-1"></i>Te notificaremos a este WhatsApp cuando recibamos tu pedido.
                            </p>
                        @endauth

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3" style="color: #1a3352;">
                            <i class="bi bi-bag-check me-2"></i>Resumen del Pedido
                        </h5>

                        @foreach ($cart as $key => $item)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                                <img src="{{ asset($item['imagen']) }}" alt="{{ $item['nombre'] }}"
                                    class="rounded-3 object-fit-cover shadow-sm"
                                    style="width: 50px; height: 60px;">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0" style="color: #1a3352;">{{ $item['nombre'] }}</h6>
                                    <small class="text-muted">
                                        Talle: <strong>{{ $item['talle'] }}</strong> |
                                        Cant: <strong>{{ $item['cantidad'] }}</strong> |
                                        ${{ number_format($item['precio'], 0, ',', '.') }} c/u
                                    </small>
                                </div>
                                <div class="fw-bold text-end" style="color: #1a3352;">
                                    ${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- COLUMNA DERECHA: RESUMEN Y PAGO --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                        <h5 class="fw-bold mb-3 pb-2 border-bottom" style="color: #1a3352;">Resumen</h5>

                        <div class="d-flex justify-content-between mb-2 text-secondary">
                            <span>Subtotal de Lista:</span>
                            <span class="fw-bold">${{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="card border-success bg-success-subtle p-3 rounded-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-success small">Precio Efectivo / Transferencia:</span>
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

                        <div class="bg-light rounded-3 p-3 mb-3 border">
                            <p class="fw-bold text-denim small mb-2"><i class="bi bi-info-circle me-1"></i> Así trabajamos:</p>
                            <p class="text-muted small mb-1">🔹 No procesamos pagos automáticos en la web.</p>
                            <p class="text-muted small mb-1">🔹 Una vez que confirmes tu pedido, te contactamos por WhatsApp.</p>
                            <p class="text-muted small mb-1">🔹 Coordinamos el método de pago (efectivo, transferencia o tarjeta) y el envío.</p>
                            <p class="text-muted small mb-0">🔹 Tu pedido queda registrado y el dueño recibe una notificación automática.</p>
                        </div>

                        <button type="submit"
                            class="btn btn-lg w-100 py-3 fw-bold rounded-3 text-white text-uppercase shadow-sm mb-2"
                            style="background-color: #1a3352;">
                            <i class="bi bi-whatsapp me-2"></i>Confirmar Pedido
                        </button>

                        <a href="{{ route('carrito.index') }}"
                            class="btn btn-outline-secondary w-100 rounded-3 py-2">
                            <i class="bi bi-arrow-left me-1"></i> Volver al Carrito
                        </a>

                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
