@extends('layouts.app') {{-- O el nombre de tu plantilla principal (ej: layouts.layout) --}}

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

                {{-- REGISTRO OCULTO PARA FANCYBOX (Evita duplicados y maneja el slider) --}}
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

                    {{-- Etiquetas flotantes sobre la foto grande --}}
                    @if ($isAgotado)
                        <span
                            class="position-absolute top-50 start-50 translate-middle bg-dark text-white fw-bold px-4 py-2 rounded-3 shadow-lg text-uppercase tracking-wider"
                            style="z-index: 10;">
                            Agotado
                        </span>
                    @endif

                    <img id="fotoPrincipal" src="{{ $urlPrincipal }}" class="w-100 h-100 object-fit-cover"
                        style="{{ $isAgotado ? 'filter: grayscale(100%) opacity(50%);' : '' }}"
                        alt="{{ $producto->nombre }}">
                </div>

                {{-- MINIATURAS (Click para cambiar la foto grande) --}}
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

                    {{-- Precios --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="fw-bold fs-2" style="color: #1a3352;">
                            ${{ number_format($producto->precio_final, 0, ',', '.') }}
                        </span>
                        @if ($producto->porc_desc > 0)
                            <span class="text-muted text-decoration-line-through fs-5">
                                ${{ number_format($producto->precio, 0, ',', '.') }}
                            </span>
                            <span class="badge bg-danger px-3 py-2 rounded-pill small">
                                {{ $producto->porc_desc }}% OFF
                            </span>
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
                                    {{-- Input oculto para armar botones tipo Radio --}}
                                    <input type="radio" name="talle_id" value="{{ $talle->id }}" class="btn-check"
                                        {{ !$hasStock ? 'disabled' : '' }} required>

                                    {{-- El botón visual que ve la clienta --}}
                                    <span
                                        class="btn border fw-bold px-3 py-2 rounded-3 d-flex align-items-center justify-content-center"
                                        style="min-width: 55px; font-size: 0.85rem;">
                                        {{ $talle->nombre }}
                                        @if (!$hasStock)
                                            {{-- Pequeña línea diagonal visual si no hay stock del talle --}}
                                            <span
                                                class="position-absolute top-50 start-50 translate-middle text-muted opacity-50"
                                                style="transform: translate(-50%, -50%) rotate(-45deg) !important; font-size: 1.2rem;">/</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Botón de Acción Final --}}
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

    {{-- SCRIPT INTERACTIVO DE IMÁGENES --}}
    <script>
        // Índice de la foto actualmente seleccionada
        let fotoActivaIndex = 0;

        function cambiarFoto(nuevaRuta, index) {
            // 1. Cambia la foto principal en la pantalla
            document.getElementById('fotoPrincipal').src = nuevaRuta;

            // 2. Guarda el índice de la foto activa
            fotoActivaIndex = index;

            // 3. Remueve el borde azul de todas las miniaturas y se lo pone solo a la activa
            document.querySelectorAll('.thumb-container').forEach(el => {
                el.classList.remove('active-thumb');
            });
            document.getElementById('thumb-wrapper-' + index).classList.add('active-thumb');
        }

        function abrirLightbox() {
            // Dispara programáticamente el clic en el enlace oculto de Fancybox que corresponde a la foto activa
            document.getElementById('fancy-link-' + fotoActivaIndex).click();
        }
    </script>

    <style>
        /* Estilos de bordes y miniaturas activas */
        .thumb-container {
            border: 2px solid #dee2e6 !important;
            transition: all 0.2s ease-in-out;
        }

        .thumb-container:hover {
            border-color: #1a3352 !important;
        }

        /* Estilo para la miniatura seleccionada actualmente (borde de tu color denim #1a3352) */
        .active-thumb {
            border-color: #1a3352 !important;
            box-shadow: 0 0 0 2px rgba(26, 51, 82, 0.2);
        }

        /* Estilo para que cuando se seleccione el talle cambie de color lindo */
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
    </style>

    {{-- JS de Fancybox --}}
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        // Inicializa Fancybox con opciones optimizadas para celulares y escritorio
        Fancybox.bind("[data-fancybox='galeria-producto']", {
            transitionEffect: "slide",
            Images: {
                Panzoom: {
                    maxScale: 3, // Zoom de hasta 3x al tocar/hacer doble click
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
