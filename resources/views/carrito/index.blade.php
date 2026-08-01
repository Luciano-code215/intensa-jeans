@extends('layouts.app')

@section('content')
    <div class="container my-5">

        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="font-titulo fw-bold m-0" style="color: #1a3352;">
                <i class="bi bi-bag-heart me-2"></i>Tu Carrito de Compras
            </h2>
            <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Seguir comprando
            </a>
        </div>

        {{-- Alertas Flash --}}
        @if (session('agregado_carrito'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('agregado_carrito') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error_stock'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {!! session('error_stock') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (empty($cart) || count($cart) === 0)
            {{-- ESTADO: CARRITO VACÍO --}}
            <div class="text-center py-5 my-4 bg-light rounded-4 shadow-sm">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-3" style="color: #1a3352;">Tu carrito está vacío</h4>
                <p class="text-muted mb-4">Aún no agregaste ninguna prenda de Intensa Jeans.</p>
                <a href="{{ route('catalogo.index') }}" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm"
                    style="background-color: #1a3352;">
                    Explorar Catálogo
                </a>
            </div>
        @else
            {{-- ESTADO: CARRITO CON PRODUCTOS --}}
            @php
                $tieneNoDisponible = collect($cart)->contains(function ($item) use ($productosBD) {
                    $p = $productosBD[$item['id']] ?? null;
                    return !$p || !$p->activo;
                });
            @endphp
            <div class="row g-4">

                {{-- COLUMNA IZQUIERDA: LISTA DE PRODUCTOS --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3">Producto</th>
                                        <th scope="col" class="text-center py-3">Talle</th>
                                        <th scope="col" class="text-center py-3">Precio</th>
                                        <th scope="col" class="text-center py-3">Cantidad</th>
                                        <th scope="col" class="text-center py-3">Subtotal</th>
                                        <th scope="col" class="text-end pe-4 py-3">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $key => $item)
                                        @php
                                            $productoBD = $productosBD[$item['id']] ?? null;
                                            $noDisponible = !$productoBD || !$productoBD->activo;
                                            $stockReal = $productoBD ? $productoBD->stockPorTalle($item['talle']) : 1;
                                        @endphp
                                        <tr class="{{ $noDisponible ? 'table-danger' : '' }}">
                                            {{-- Foto + Nombre --}}
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset($item['imagen']) }}" alt="{{ $item['nombre'] }}"
                                                        class="rounded-3 object-fit-cover shadow-sm"
                                                        style="width: 65px; height: 75px; {{ $noDisponible ? 'opacity: 0.4;' : '' }}">
                                                    <div>
                                                        <h6 class="fw-bold mb-1 text-truncate"
                                                            style="max-width: 200px; color: #1a3352;">
                                                            {{ $item['nombre'] }}
                                                        </h6>
                                                        <span class="badge bg-light text-dark border">Ref:
                                                            {{ $item['id'] }}</span>
                                                        @if ($noDisponible)
                                                            <div class="mt-1">
                                                                <span class="badge bg-danger text-white">
                                                                    <i class="bi bi-x-circle me-1"></i>Ya no está disponible
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Talle --}}
                                            <td class="text-center fw-bold text-secondary">
                                                <span class="badge bg-dark px-2 py-1">{{ $item['talle'] }}</span>
                                            </td>

                                            {{-- Precio Unitario --}}
                                            <td class="text-center fw-semibold">
                                                ${{ number_format($item['precio'], 0, ',', '.') }}
                                            </td>

                                            {{-- Cantidad (Formulario para actualizar con límite de Stock desde Modelo) --}}
                                            <td class="text-center" style="min-width: 140px;">
                                                @if ($noDisponible)
                                                    <span class="text-danger small fw-semibold">No disponible</span>
                                                @else
                                                    <form action="{{ route('carrito.update', $key) }}" method="POST"
                                                        class="d-flex flex-column align-items-center justify-content-center gap-1">
                                                        @csrf

                                                        <input type="number" name="cantidad" value="{{ $item['cantidad'] }}"
                                                            min="1" max="{{ $stockReal }}"
                                                            class="form-control form-control-sm text-center fw-bold rounded-2"
                                                            style="width: 65px;" onchange="this.form.submit()"
                                                            oninput="if(parseInt(this.value) > {{ $stockReal }}) this.value = {{ $stockReal }};">

                                                        <small class="text-muted" style="font-size: 0.75rem;">
                                                            Disponibles: <strong>{{ $stockReal }}</strong>
                                                        </small>
                                                    </form>
                                                @endif
                                            </td>

                                            {{-- Subtotal por ítem --}}
                                            <td class="text-center fw-bold" style="color: #1a3352;">
                                                ${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}
                                            </td>

                                            {{-- Eliminar --}}
                                            <td class="text-end pe-4">
                                                <form action="{{ route('carrito.remove', $key) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0"
                                                        title="Eliminar del carrito">
                                                        <i class="bi bi-trash3 fs-5"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Botón de vaciar carrito --}}
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('carrito.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill"
                                onclick="return confirm('¿Estás segura de vaciar todo el carrito?')">
                                <i class="bi bi-trash me-1"></i> Vaciar Carrito
                            </button>
                        </form>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: RESUMEN DE COMPRA --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                        <h5 class="fw-bold mb-3 pb-2 border-bottom" style="color: #1a3352;">Resumen de la Compra</h5>

                        <div class="d-flex justify-content-between mb-2 text-secondary">
                            <span>Prendas en carrito:</span>
                            <span class="fw-bold">{{ array_sum(array_column($cart, 'cantidad')) }} u.</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 text-secondary">
                            <span>Subtotal de Lista:</span>
                            <span class="fw-bold">${{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        {{-- Caja Promocional Efectivo --}}
                        <div class="card border-success bg-success-subtle p-3 rounded-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-success small">Precio en Efectivo / Transferencia:</span>
                                @if ($ahorro > 0)
                                    <span class="badge bg-success text-white">AHORRÁS</span>
                                @endif
                            </div>
                            <div class="fs-3 fw-bold text-success">
                                ${{ number_format($totalEfectivo, 0, ',', '.') }}
                            </div>
                            @if ($ahorro > 0)
                                <span class="text-success-emphasis" style="font-size: 0.75rem;">¡Ahorrás
                                    ${{ number_format($ahorro, 0, ',', '.') }}!</span>
                            @endif
                        </div>

                        <hr class="text-muted my-3">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5" style="color: #1a3352;">Total General:</span>
                            <span class="fw-bold fs-3"
                                style="color: #1a3352;">${{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        @if ($tieneNoDisponible)
                            <div class="alert alert-danger rounded-3 py-2 px-3 mb-3 small">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Hay un producto que ya no está disponible. Eliminalo para continuar con la compra.
                            </div>
                            <button type="button" disabled
                                class="btn btn-lg w-100 py-3 fw-bold rounded-3 text-white text-uppercase shadow-sm d-flex align-items-center justify-content-center gap-2"
                                style="background-color: #1a3352; opacity: 0.5;">
                                <i class="bi bi-credit-card fs-5"></i> Finalizar Compra
                            </button>
                        @else
                            {{-- Finalizar Compra (lleva al checkout) --}}
                            <a href="{{ route('checkout.index') }}"
                                class="btn btn-lg w-100 py-3 fw-bold rounded-3 text-white text-uppercase shadow-sm d-flex align-items-center justify-content-center gap-2"
                                style="background-color: #1a3352;">
                                <i class="bi bi-credit-card fs-5"></i> Finalizar Compra
                            </a>
                        @endif

                    </div>
                </div>

            </div>
        @endif

    </div>
@endsection
