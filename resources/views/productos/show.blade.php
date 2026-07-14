@extends('layouts.app') {{-- O el nombre de tu plantilla principal --}}

@section('content')
    {{-- CSS de Fancybox --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <div class="container my-5">
        {{-- Botón para Volver al Catálogo --}}
        <div class="mb-4">
            <a href="/catalogo" class="btn btn-link text-decoration-none text-muted p-0">
                <i class="bi bi-arrow-left me-2"></i> Volver al catálogo
            </a>
        </div>

        @php
            // Calculamos el stock total sumando el de todos sus talles
            $stockTotal = $producto->talles->sum('pivot.stock');
            $isAgotado = $stockTotal <= 0;

            // Definimos la ruta de la imagen principal de forma limpia
            $urlPrincipal = str_starts_with($producto->url_imagen, 'http')
                ? $producto->url_imagen
                : asset($producto->url_imagen);
        @endphp

        <div class="row g-5">
            {{-- COLUMNA IZQUIERDA: GALERÍA DE IMÁGENES --}}
            <div class="col-md-6 col-lg-5">

                {{-- REGISTRO OCULTO PARA FANCYBOX --}}
                <div class="d-none">
                    <a href="{{ $urlPrincipal }}" data-fancybox="galeria-producto" id="fancy-link-0"></a>
                    @foreach ($producto->imagenesSecundarias as $index => $imgAdicional)
                        <a href="{{ asset($imgAdicional->url_imagen) }}" data-fancybox="galeria-producto"
                            id="fancy-link-{{ $index + 1 }}"></a>
                    @endforeach
                </div>

                {{-- CONTENEDOR FOTO GRANDE --}}
                <div class="w-100 rounded-4 overflow-hidden mb-3 position-relative bg-light shadow-sm"
                    style="height: 500px; cursor: zoom-in;" onclick="abrirLightbox()">

                    {{-- Etiqueta: Agotado --}}
                    @if ($isAgotado)
                        <span
                            class="position-absolute top-50 start-50 translate-middle bg-dark text-white fw-bold px-4 py-2 rounded-3 shadow-lg text-uppercase tracking-wider"
                            style="z-index: 10;">
                            Agotado
                        </span>
                    @endif

                    {{-- Etiqueta: Liquidación --}}
                    @if ($producto->liquidacion && $producto->porc_liquidacion > 0 && !$isAgotado)
                        <span
                            class="position-absolute top-3 start-3 bg-danger text-white fw-bold px-3 py-2 rounded-3 shadow-lg text-uppercase tracking-wider"
                            style="z-index: 10; font-size: 0.85rem; letter-spacing: 0.05em;">
                            <i class="bi bi-fire"></i> {{ $producto->porc_liquidacion }}% OFF LIQ
                        </span>
                    @endif

                    <img id="fotoPrincipal" src="{{ $urlPrincipal }}" class="w-100 h-100 object-fit-cover"
                        style="{{ $isAgotado ? 'filter: grayscale(100%) opacity(50%);' : '' }}"
                        alt="{{ $producto->nombre }}">
                </div>

                {{-- MINIATURAS (Click cambia la foto grande) --}}
                <div class="d-flex gap-2 overflow-x-auto pb-2">
                    {{-- Miniatura Principal --}}
                    <div id="thumb-wrapper-0"
                        class="rounded-3 overflow-hidden border cursor-pointer bg-white shadow-sm thumb-container active-thumb"
                        style="width: 75px; height: 75px; flex-shrink: 0;" onclick="cambiarFoto('{{ $urlPrincipal }}', 0)">
                        <img src="{{ $urlPrincipal }}" class="w-100 h-100 object-fit-cover">
                    </div>

                    {{-- Miniaturas Secundarias --}}
                    @foreach ($producto->imagenesSecundarias as $index => $imgAdicional)
                        @php $rutaSecundaria = asset($imgAdicional->url_imagen); @endphp
                        <div id="thumb-wrapper-{{ $index + 1 }}"
                            class="rounded-3 overflow-hidden border cursor-pointer bg-white shadow-sm thumb-container"
                            style="width: 75px; height: 75px; flex-shrink: 0;"
                            onclick="cambiarFoto('{{ $rutaSecundaria }}', {{ $index + 1 }})">
                            <img src="{{ $rutaSecundaria }}" class="w-100 h-100 object-fit-cover">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- COLUMNA DERECHA: INFORMACIÓN Y DETALLES DE COMPRA --}}
            <div class="col-md-6 col-lg-7 d-flex flex-column justify-content-between">
                <div>
                    <span class="text-muted text-uppercase fw-bold tracking-wider small d-block mb-2">
                        {{ $producto->categoria->nombre ?? 'Denim' }}
                    </span>

                    <h1 class="font-titulo fw-bold mb-3" style="color: #1a3352;">{{ $producto->nombre }}</h1>

                    {{-- NUEVA SECCIÓN DE PRECIOS ADAPTADA Y CORREGIDA --}}
                    <div class="mb-4">
                        @if ($producto->liquidacion && $producto->porc_liquidacion > 0)
                            {{-- Caso: Producto en liquidación --}}
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="text-muted text-decoration-line-through fs-5">
                                    ${{ number_format($producto->precio, 0, ',', '.') }}
                                </span>
                                <span class="fw-bold fs-2" style="color: #1a3352;">
                                    ${{ number_format($producto->precio_lista_actual, 0, ',', '.') }}
                                </span>
                                <span class="badge bg-danger px-3 py-2 rounded-pill small">
                                    {{ $producto->porc_liquidacion }}% OFF LIQ
                                </span>
                            </div>
                        @else
                            {{-- Caso: Producto normal --}}
                            <div class="mb-2">
                                <span class="fw-bold fs-2" style="color: #1a3352;">
                                    ${{ number_format($producto->precio, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        {{-- Promoción en efectivo destacada (Siempre calculada sobre el precio real de arriba) --}}
                        @if ($producto->porc_desc_ef > 0)
                            <div class="card border-success bg-success-subtle p-3 rounded-4 mt-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        {{-- AQUÍ SE MUESTRA EL PRECIO EN EFECTIVO CORRECTO ($6.750 en tu ejemplo) --}}
                                        <div class="fw-bold fs-3 text-success">
                                            ${{ number_format($producto->precio_ef_actual, 0, ',', '.') }}
                                        </div>
                                        <div class="text-success-emphasis fw-semibold small">
                                            Pagando en Efectivo / Transferencia Bancaria
                                        </div>
                                    </div>
                                    <span class="badge bg-success px-3 py-2 rounded-pill fs-6 text-white">
                                        {{ $producto->porc_desc_ef }}% OFF
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr class="text-muted my-4">

                    <p class="text-secondary lh-lg mb-4">
                        {{ $producto->descripcion ?? 'Sin descripción disponible para este artículo por el momento.' }}
                    </p>

                    {{-- FORMULARIO DE SELECCIÓN DE TALLE --}}
                    <form action="#" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">

                        <h6 class="fw-bold mb-3 text-uppercase small tracking-wider" style="color: #1a3352;">
                            Seleccioná tu Talle:
                        </h6>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach ($producto->talles as $talle)
                                @php
                                    $hasStock = $talle->pivot->stock > 0;
                                @endphp

                                <label class="position-relative">
                                    <input type="radio" name="talle_id" value="{{ $talle->id }}" class="btn-check"
                                        {{ !$hasStock ? 'disabled' : '' }} required>

                                    <span
                                        class="btn border fw-bold px-3 py-2 rounded-3 d-flex align-items-center justify-content-center"
                                        style="min-width: 55px; font-size: 0.85rem;">
                                        {{ $talle->nombre }}
                                        @if (!$hasStock)
                                            <span
                                                class="position-absolute top-50 start-50 translate-middle text-muted opacity-50"
                                                style="transform: translate(-50%, -50%) rotate(-45deg) !important; font-size: 1.2rem;">/</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="mt-5">
                            @if ($isAgotado)
                                <button type="button"
                                    class="btn btn-lg btn-secondary w-100 py-3 fw-bold text-uppercase disabled" disabled>
                                    <i class="bi bi-x-circle me-2"></i> Artículo sin Stock
                                </button>
                            @else
                                <button type="submit"
                                    class="btn btn-lg w-100 py-3 fw-bold text-uppercase text-white shadow-sm"
                                    style="background-color: #1a3352;">
                                    <i class="bi bi-bag-plus me-2"></i> Añadir al carrito
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS DE INTERACCIÓN --}}
    <script>
        let fotoActivaIndex = 0;

        function cambiarFoto(nuevaRuta, index) {
            document.getElementById('fotoPrincipal').src = nuevaRuta;
            fotoActivaIndex = index;

            document.querySelectorAll('.thumb-container').forEach(el => {
                el.classList.remove('active-thumb');
            });
            document.getElementById('thumb-wrapper-' + index).classList.add('active-thumb');
        }

        function abrirLightbox() {
            document.getElementById('fancy-link-' + fotoActivaIndex).click();
        }
    </script>

    <style>
        .thumb-container {
            border: 2px solid #dee2e6 !important;
            transition: all 0.2s ease-in-out;
        }

        .thumb-container:hover {
            border-color: #1a3352 !important;
        }

        .active-thumb {
            border-color: #1a3352 !important;
            box-shadow: 0 0 0 2px rgba(26, 51, 82, 0.2);
        }

        .btn-check:checked+.btn {
            background-color: #1a3352 !important;
            color: white !important;
            border-color: #1a3352 !important;
        }

        .btn-check:disabled+.btn {
            background-color: #f8f9fa !important;
            color: #dee2e6 !important;
            border-color: #dee2e6 !important;
            cursor: not-allowed;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .product-card img {
            transition: transform 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }
    </style>

    {{-- JS de Fancybox --}}
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox='galeria-producto']", {
            transitionEffect: "slide",
            Images: {
                Panzoom: {
                    maxScale: 3,
                },
            },
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [],
                    right: ["zoom", "slideshow", "fullscreen", "close"],
                },
            },
        });
    </script>
@endsection
