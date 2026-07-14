@extends('layouts.app')

@section('title', 'Intensa Jeans - Catálogo de Productos')

@section('content')

    <!-- SECCIÓN: ENCABEZADO DE LA PÁGINA (CORREGIDO) -->

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
            <p class="text-muted small mb-3 mb-md-0">Mostrando <span class="fw-bold text-denim">6</span> productos
                espectaculares</p>
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

                            {{-- 1. ETIQUETA: AGOTADO (Prioridad visual absoluta, se ubica al centro o arriba) --}}
                            @if ($isAgotado)
                                <span
                                    class="position-absolute top-50 start-50 translate-middle bg-dark text-white fw-bold px-4 py-2 rounded-3 shadow-lg text-uppercase tracking-wider"
                                    style="font-size: 0.8rem; z-index: 15; letter-spacing: 1px; opacity: 0.9;">
                                    Agotado
                                </span>
                            @endif

                            {{-- 2. ETIQUETA: NUEVO (Arriba a la izquierda, solo si hay stock y es menor a 7 días) --}}
                            @if ($producto->esNuevo() && !$isAgotado)
                                <span
                                    class="position-absolute top-0 start-0 text-dark small fw-bold px-3 py-1 m-3 rounded-pill shadow-sm"
                                    style="font-size: 0.65rem; background-color: #c9a054; z-index: 10;">
                                    NUEVO
                                </span>
                            @endif

                            {{-- 3. ETIQUETA: DESCUENTO (Arriba a la derecha, se muestra siempre que tenga %, haya o no stock) --}}
                            @if ($producto->tieneDescuento())
                                <span
                                    class="position-absolute top-0 end-0 bg-danger text-white small fw-bold px-3 py-1 m-3 rounded-pill shadow-sm"
                                    style="font-size: 0.65rem; z-index: 10;">
                                    {{ $producto->porc_desc }}% OFF
                                </span>
                            @endif

                            {{-- Contenedor de Imagen (Aplica filtro gris si está agotado) --}}
                            <div class="w-100" style="height: 280px; background-color: #f8f9fa;">
                                @if ($producto->url_imagen)
                                    <img src="{{ str_starts_with($producto->url_imagen, 'http') ? $producto->url_imagen : asset($producto->url_imagen) }}"
                                        class="w-100 h-100 object-fit-cover" {{-- Si está agotado, le aplicamos el filtro CSS inline para hacerlo gris --}}
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

                                <div class="mt-2">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="fw-bold text-denim fs-5" style="color: #1a3352;">
                                            ${{ number_format($producto->precio_final, 0, ',', '.') }}
                                        </span>

                                        @if ($producto->porc_desc > 0)
                                            <span class="text-muted text-decoration-line-through small"
                                                style="font-size: 0.75rem;">
                                                ${{ number_format($producto->precio, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Botón de Acción dinámico según el stock --}}
                                    @if ($isAgotado)
                                        <button
                                            class="btn btn-sm w-100 py-2 fw-bold text-uppercase tracking-wider btn-secondary disabled"
                                            style="font-size: 0.7rem;" disabled>
                                            Sin Stock
                                        </button>
                                    @else
                                        <a href="/productosPub/{{ $producto->id }}"
                                            class="btn btn-sm w-100 py-2 fw-bold text-uppercase tracking-wider btn-denim-action"
                                            style="font-size: 0.7rem;">
                                            Ver Detalles
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
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
