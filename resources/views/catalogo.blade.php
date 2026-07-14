@extends('layouts.app')

@section('title', 'Intensa Jeans - Catálogo de Productos')

@section('content')

    <div class="bg-denim text-white py-5 text-center" style="background-color: #1a3352;">
        <div class="container py-3">
            <h1 class="font-titulo display-5 fw-bold mb-2">Nuestro Catálogo</h1>
            <p class="lead text-white-50 small text-uppercase tracking-widest mb-0">Encuentra tu calce perfecto</p>
            <div class="mx-auto bg-oro mt-3" style="width: 50px; height: 3px; background-color: #c9a054;"></div>
        </div>
    </div>

    <div class="container py-5" style="background-color: #fcfbf7;">

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary-subtle">
            <p class="text-muted small mb-3 mb-md-0">
                Mostrando <span class="fw-bold text-denim">{{ $productos->count() }}</span> productos espectaculares
            </p>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm border-secondary-subtle text-muted"
                    style="max-width: 200px; font-size: 0.8rem;">
                    <option selected>Ordenar por: Destacados</option>
                    <option>Precio: Menor a Mayor</option>
                    <option>Precio: Mayor a Menor</option>
                    <option>Novedades</option>
                </select>
            </div>
        </div>

        {{-- Grilla Dinámica de Productos con Control de Stock --}}
        <div class="row g-4">
            @if ($productos->isEmpty())
                <div class="col-12 my-5 text-center">
                    <div class="alert alert-info d-inline-block px-5">
                        <i class="bi bi-info-circle me-2"></i> No encontramos jeans con esa descripción o categoría.
                    </div>
                </div>
            @else
                @foreach ($productos as $producto)
                    {{-- LÓGICA DE STOCK: Sumamos el stock de todos sus talles --}}
                    @php
                        $stockTotal = $producto->talles->sum('pivot.stock');
                        $isAgotado = $producto->esAgotado() || $stockTotal <= 0;
                    @endphp

                    <div class="col-6 col-md-4 col-lg-3">
                        <div
                            class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative bg-white custom-card-hover {{ $isAgotado ? 'opacity-75' : '' }}">

                            {{-- 1. ETIQUETA: AGOTADO --}}
                            @if ($isAgotado)
                                <span
                                    class="position-absolute top-50 start-50 translate-middle bg-dark text-white fw-bold px-4 py-2 rounded-3 shadow-lg text-uppercase tracking-wider"
                                    style="font-size: 0.8rem; z-index: 15; letter-spacing: 1px; opacity: 0.9;">
                                    Agotado
                                </span>
                            @endif

                            {{-- 2. ETIQUETA: LIQUIDACIÓN 🔥 (Solo si está en liquidación y tiene stock) --}}
                            @if ($producto->liquidacion && $producto->porc_liquidacion > 0 && !$isAgotado)
                                <span
                                    class="position-absolute top-0 start-0 bg-danger text-white small fw-bold px-3 py-1 m-3 rounded-pill shadow-sm"
                                    style="font-size: 0.65rem; z-index: 10; letter-spacing: 0.5px;">
                                    <i class="bi bi-fire"></i> {{ $producto->porc_liquidacion }}% OFF LIQ
                                </span>
                                {{-- 3. ETIQUETA: NUEVO (Solo si no está en liquidación, hay stock y es nuevo) --}}
                            @elseif ($producto->esNuevo() && !$isAgotado)
                                <span
                                    class="position-absolute top-0 start-0 text-dark small fw-bold px-3 py-1 m-3 rounded-pill shadow-sm"
                                    style="font-size: 0.65rem; background-color: #c9a054; z-index: 10;">
                                    NUEVO
                                </span>
                            @endif

                            {{-- Contenedor de Imagen (Aplica filtro gris si está agotado) --}}
                            <div class="w-100" style="height: 280px; background-color: #f8f9fa;">
                                @if ($producto->url_imagen)
                                    <img src="{{ str_starts_with($producto->url_imagen, 'http') ? $producto->url_imagen : asset($producto->url_imagen) }}"
                                        class="w-100 h-100 object-fit-cover transition-img"
                                        style="{{ $isAgotado ? 'filter: grayscale(100%) opacity(40%);' : '' }}"
                                        alt="{{ $producto->nombre }}">
                                @else
                                    <div
                                        class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted bg-light">
                                        <i class="bi bi-image fs-2"></i>
                                        <span style="font-size: 0.7rem;">Sin foto disponible</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Cuerpo de la Tarjeta --}}
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="text-muted text-uppercase fw-semibold tracking-wider d-block mb-1"
                                        style="font-size: 0.6rem;">
                                        {{ $producto->categoria->nombre ?? 'Denim' }}
                                    </span>
                                    <h5 class="card-title text-denim font-titulo fw-bold h6 mb-2 text-truncate"
                                        style="color: #1a3352;" title="{{ $producto->nombre }}">
                                        {{ $producto->nombre }}
                                    </h5>
                                </div>

                                {{-- SECCIÓN DE PRECIOS ADAPTADA EN EL CATÁLOGO --}}
                                <div class="mt-2">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @if ($producto->liquidacion && $producto->porc_liquidacion > 0)
                                            {{-- Caso: Producto en Liquidación --}}
                                            <span class="text-muted text-decoration-line-through small"
                                                style="font-size: 0.75rem;">
                                                ${{ number_format($producto->precio, 0, ',', '.') }}
                                            </span>
                                            <span class="fw-bold fs-5" style="color: #1a3352;">
                                                ${{ number_format($producto->precio_lista_actual, 0, ',', '.') }}
                                            </span>
                                        @else
                                            {{-- Caso: Producto Normal --}}
                                            <span class="fw-bold fs-5" style="color: #1a3352;">
                                                ${{ number_format($producto->precio, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- PRECIO PROMO EN EFECTIVO CON MARGEN INFERIOR ASEGURADO (mb-3) --}}
                                    @if ($producto->porc_desc_ef > 0 && !$isAgotado)
                                        <div class="bg-success-subtle text-success-emphasis rounded-3 px-2.5 py-2 mb-3"
                                            style="font-size: 0.75rem; line-height: 1.25;">
                                            <div>
                                                <span
                                                    class="fw-bold fs-6">${{ number_format($producto->precio_ef_actual, 0, ',', '.') }}</span>
                                                efectivo/transf.
                                            </div>
                                            <div class="text-success small fw-semibold mt-0.5">
                                                {{ $producto->porc_desc_ef }}% OFF adicional
                                            </div>
                                        </div>
                                    @else
                                        {{-- Mantiene el espacio constante si no hay descuento para que las tarjetas no se desalineen --}}
                                        <div class="mb-3" style="height: 38px;"></div>
                                    @endif
                                </div>

                                {{-- SECCIÓN DE BOTONES: Separados correctamente usando una estructura de pila (d-grid gap-2) --}}
                                <div class="d-grid gap-2 mt-2">
                                    <a href="{{ route('productos.show', $producto->id) }}"
                                        class="btn text-white fw-semibold py-2"
                                        style="background-color: #1a3352; border-radius: 8px;">
                                        Ver Detalles
                                    </a>

                                    @if (auth()->check() && auth()->user()->isAdmin())
                                        <a href="{{ route('admin.productos.edit', $producto->id) }}"
                                            class="btn btn-outline-secondary fw-semibold py-2" style="border-radius: 8px;">
                                            <i class="bi bi-pencil-square me-1"></i> Editar Producto
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <style>
        /* Efecto de elevación elegante en las tarjetas al pasar el mouse */
        .custom-card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(26, 51, 82, 0.12) !important;
        }

        /* Efecto suave de zoom en la imagen al hacer hover */
        .custom-card-hover:hover .transition-img {
            transform: scale(1.05);
        }

        .transition-img {
            transition: transform 0.4s ease;
        }

        /* Botón personalizado con tu color Denim */
        .btn-denim-action {
            background-color: #1a3352;
            color: #ffffff;
            border: 1px solid #1a3352;
            transition: all 0.2s ease;
        }

        .btn-denim-action:hover {
            background-color: #c9a054;
            border-color: #c9a054;
            color: #1a3352;
        }
    </style>

@endsection
