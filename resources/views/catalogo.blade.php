@extends('layouts.app')

@section('title', 'Intensa Jeans - Catálogo de Productos')

@section('content')

    {{-- Banner de Encabezado --}}
    <div class="bg-denim text-white py-5 text-center" style="background-color: #1a3352;">
        <div class="container py-3">
            <h1 class="font-titulo display-5 fw-bold mb-2">Nuestro Catálogo</h1>
            <p class="lead text-white-50 small text-uppercase tracking-widest mb-0">Encuentra tu calce perfecto</p>
            <div class="mx-auto mt-3" style="width: 50px; height: 3px; background-color: #c9a054;"></div>
        </div>
    </div>

    <div class="container py-5" style="background-color: #fcfbf7;">

        {{-- Indicador/Alerta Visual de Modo Liquidación --}}
        @if (request('liquidacion') == 1)
            <div class="alert alert-danger d-flex align-items-center justify-content-between rounded-4 mb-4 shadow-sm border-danger-subtle"
                role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-fire fs-4 me-3 text-danger"></i>
                    <div>
                        <strong class="d-block">Sección Liquidación Activa 🔥</strong>
                        <span class="small text-muted">Estás viendo únicamente las prendas en oferta. Puedes buscar o
                            filtrar categorías dentro de esta sección.</span>
                    </div>
                </div>
                <a href="{{ route('catalogo.index', request()->except('liquidacion')) }}"
                    class="btn btn-sm btn-outline-danger rounded-3 ms-3" title="Quitar liquidación">
                    <i class="bi bi-x-lg me-1"></i> Ver Todo
                </a>
            </div>
        @endif

        <div class="row g-4">

            {{-- SIDEBAR DE FILTROS Y BÚSQUEDA (Columna Izquierda) --}}
            <div class="col-12 col-lg-3">
                <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">

                    {{-- 1. Buscador por Palabra --}}
                    <h6 class="fw-bold text-denim text-uppercase mb-3"
                        style="color: #1a3352; font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="bi bi-search me-1"></i> Buscar Jeans
                    </h6>
                    <form action="{{ route('catalogo.index') }}" method="GET" class="mb-4">
                        {{-- Preservamos otros filtros si están activos --}}
                        @if (request('categoria'))
                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                        @endif
                        @if (request('orden'))
                            <input type="hidden" name="orden" value="{{ request('orden') }}">
                        @endif
                        @if (request('liquidacion'))
                            <input type="hidden" name="liquidacion" value="{{ request('liquidacion') }}">
                        @endif

                        <div class="input-group">
                            <input type="text" name="buscar"
                                class="form-control form-control-sm rounded-start-3 border-secondary-subtle"
                                placeholder="Ej: Mom, Skinny..." value="{{ request('buscar') }}">
                            <button class="btn text-white btn-sm px-3 rounded-end-3" type="submit"
                                style="background-color: #1a3352;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        @if (request('buscar'))
                            <a href="{{ route('catalogo.index', request()->except('buscar')) }}"
                                class="badge bg-danger-subtle text-danger text-decoration-none mt-2 d-inline-block">
                                <i class="bi bi-x-circle me-1"></i> Limpiar búsqueda
                            </a>
                        @endif
                    </form>

                    <hr class="text-secondary opacity-25">

                    {{-- 2. Lista de Categorías --}}
                    <h6 class="fw-bold text-denim text-uppercase mb-3"
                        style="color: #1a3352; font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="bi bi-grid-fill me-1"></i> Categorías
                    </h6>

                    <div class="list-group list-group-flush small">
                        {{-- Opción: Todas las Categorías (mantiene liquidación y buscar si existen) --}}
                        <a href="{{ route('catalogo.index', request()->except('categoria')) }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-2 py-2 rounded-2 {{ !request('categoria') ? 'fw-bold text-white active-category' : 'text-secondary' }}">
                            <span>Todas</span>
                            <i class="bi bi-chevron-right fs-6"></i>
                        </a>

                        @foreach ($categorias as $cat)
                            @php
                                $esActiva = request('categoria') == $cat->id;
                            @endphp
                            <a href="{{ route('catalogo.index', array_merge(request()->query(), ['categoria' => $cat->id])) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-2 py-2 rounded-2 my-1 {{ $esActiva ? 'fw-bold text-white active-category' : 'text-secondary' }}">
                                <span>{{ $cat->nombre }}</span>
                                <i class="bi bi-chevron-right fs-6"></i>
                            </a>
                        @endforeach
                    </div>

                    {{-- Botón para resetear todos los filtros si hay alguno aplicado --}}
                    @if (request()->hasAny(['buscar', 'categoria', 'orden', 'liquidacion']))
                        <div class="mt-4 pt-2 border-top">
                            <a href="{{ route('catalogo.index') }}"
                                class="btn btn-outline-secondary btn-sm w-100 rounded-3">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer Filtros
                            </a>
                        </div>
                    @endif

                </div>
            </div>

            {{-- GRILLA Y ORDENAMIENTO DE PRODUCTOS (Columna Derecha) --}}
            <div class="col-12 col-lg-9">

                {{-- Barra Superior de Resultados y Selector de Orden --}}
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary-subtle">
                    <p class="text-muted small mb-3 mb-md-0">
                        Mostrando <span class="fw-bold text-denim">{{ $productos->count() }}</span> productos
                        espectaculares
                    </p>

                    {{-- Formulario de Ordenamiento Automático --}}
                    <form action="{{ route('catalogo.index') }}" method="GET" id="formOrden">
                        @if (request('buscar'))
                            <input type="hidden" name="buscar" value="{{ request('buscar') }}">
                        @endif
                        @if (request('categoria'))
                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                        @endif
                        @if (request('liquidacion'))
                            <input type="hidden" name="liquidacion" value="{{ request('liquidacion') }}">
                        @endif

                        <div class="d-flex gap-2">
                            <select name="orden" class="form-select form-select-sm border-secondary-subtle text-muted"
                                style="max-width: 220px; font-size: 0.8rem;"
                                onchange="document.getElementById('formOrden').submit()">
                                <option value="" {{ !request('orden') ? 'selected' : '' }}>Ordenar por...</option>
                                <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio:
                                    Menor a Mayor</option>
                                <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>
                                    Precio: Mayor a Menor</option>
                                <option value="novedades" {{ request('orden') == 'novedades' ? 'selected' : '' }}>Novedades
                                </option>
                            </select>
                        </div>
                    </form>
                </div>

                {{-- Grilla Dinámica de Productos --}}
                <div class="row g-4">
                    @if ($productos->isEmpty())
                        <div class="col-12 my-5 text-center">
                            <div class="alert alert-info d-inline-block px-5 rounded-4 shadow-sm">
                                <i class="bi bi-info-circle me-2"></i> No encontramos jeans con esa descripción o categoría.
                            </div>
                        </div>
                    @else
                        @foreach ($productos as $producto)
                            @php
                                $stockTotal = $producto->talles->sum('pivot.stock');
                                $isAgotado = $producto->esAgotado() || $stockTotal <= 0;
                            @endphp

                            <div class="col-6 col-md-4">
                                <div
                                    class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative bg-white custom-card-hover {{ $isAgotado ? 'opacity-75' : '' }}">

                                    {{-- Etiquetas --}}
                                    @if ($isAgotado)
                                        <span
                                            class="position-absolute top-50 start-50 translate-middle bg-dark text-white fw-bold px-4 py-2 rounded-3 shadow-lg text-uppercase tracking-wider"
                                            style="font-size: 0.8rem; z-index: 15; letter-spacing: 1px; opacity: 0.9;">
                                            Agotado
                                        </span>
                                    @endif

                                    @if ($producto->liquidacion && $producto->porc_liquidacion > 0 && !$isAgotado)
                                        <span
                                            class="position-absolute top-0 start-0 bg-danger text-white small fw-bold px-3 py-1 m-3 rounded-pill shadow-sm"
                                            style="font-size: 0.65rem; z-index: 10; letter-spacing: 0.5px;">
                                            <i class="bi bi-fire"></i> {{ $producto->porc_liquidacion }}% OFF LIQ
                                        </span>
                                    @elseif ($producto->esNuevo() && !$isAgotado)
                                        <span
                                            class="position-absolute top-0 start-0 text-dark small fw-bold px-3 py-1 m-3 rounded-pill shadow-sm"
                                            style="font-size: 0.65rem; background-color: #c9a054; z-index: 10;">
                                            NUEVO
                                        </span>
                                    @endif

                                    {{-- Imagen --}}
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

                                        {{-- Precios --}}
                                        <div class="mt-2">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                @if ($producto->liquidacion && $producto->porc_liquidacion > 0)
                                                    <span class="text-muted text-decoration-line-through small"
                                                        style="font-size: 0.75rem;">
                                                        ${{ number_format($producto->precio, 0, ',', '.') }}
                                                    </span>
                                                    <span class="fw-bold fs-5" style="color: #1a3352;">
                                                        ${{ number_format($producto->precio_lista_actual, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="fw-bold fs-5" style="color: #1a3352;">
                                                        ${{ number_format($producto->precio, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>

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
                                                <div class="mb-3" style="height: 38px;"></div>
                                            @endif
                                        </div>

                                        {{-- Botón de Ver Detalles --}}
                                        <div class="d-grid gap-2 mt-2">
                                            <a href="{{ route('productos.show', $producto->id) }}"
                                                class="btn text-white fw-semibold py-2"
                                                style="background-color: #1a3352; border-radius: 8px;">
                                                Ver Detalles
                                            </a>

                                            @if (auth()->check() && auth()->user()->isAdmin())
                                                <a href="{{ route('admin.productos.edit', $producto->id) }}"
                                                    class="btn btn-outline-secondary fw-semibold py-2"
                                                    style="border-radius: 8px;">
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
        </div>
    </div>

    <style>
        /* Estilo para la categoría seleccionada/activa */
        .active-category {
            background-color: #1a3352 !important;
            color: #ffffff !important;
        }

        .list-group-item-action:hover:not(.active-category) {
            background-color: #f1f5f9;
            color: #1a3352 !important;
        }

        .custom-card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(26, 51, 82, 0.12) !important;
        }

        .custom-card-hover:hover .transition-img {
            transform: scale(1.05);
        }

        .transition-img {
            transition: transform 0.4s ease;
        }
    </style>

@endsection
