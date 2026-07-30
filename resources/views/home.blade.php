@extends('layouts.app')

@section('title', 'Intensa Jeans - Jeans de Mujer')

@section('content')

    <div class="position-relative w-100 p-0 overflow-hidden shadow-sm">

        <div class="d-none d-md-block w-100">
            <a href="#">
                <img src="{{ asset('images/banner1.jpeg') }}?v={{ time() }}" alt="Gran Lanzamiento - Intensa Jeans"
                    class="img-fluid w-100 h-auto" style="object-fit: cover;">
            </a>
        </div>

        <div class="d-block d-md-none w-100">
            <a href="#">
                <img src="{{ asset('images/banner1-mobile.jpg') }}?v={{ time() }}" alt="Promo Lanzamiento Jeans - Intensa"
                    class="img-fluid w-100 h-auto">
            </a>
        </div>

        @auth
            @if (Auth::user()->admin ?? false)
                <a href="{{ route('admin.banner') }}"
                    class="position-absolute top-0 end-0 m-2 btn btn-sm btn-dark bg-opacity-75 rounded-3 px-3">
                    <i class="bi bi-pencil-square me-1"></i> Editar banner
                </a>
            @endif
        @endauth
    </div>

    <div class="bg-denim text-white py-4 border-top border-secondary-subtle">
        <div class="container">
            <div class="row g-4 text-center text-sm-start">
                <div class="col-6 col-md-3 d-flex flex-column flex-sm-row align-items-center gap-2 gap-sm-3">
                    <div class="fs-3 text-oro"><i class="bi bi-heart-fill"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.75rem;">Calidad</h4>
                        <p class="small text-white-50 mb-0" style="font-size: 0.7rem;">Que ya conocés</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 d-flex flex-column flex-sm-row align-items-center gap-2 gap-sm-3">
                    <div class="fs-3 text-oro"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.75rem;">Marcas</h4>
                        <p class="small text-white-50 mb-0" style="font-size: 0.7rem;">De confianza</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 d-flex flex-column flex-sm-row align-items-center gap-2 gap-sm-3">
                    <div class="fs-3 text-oro"><i class="bi bi-person-bounding-box"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.75rem;">Talles</h4>
                        <p class="small text-white-50 mb-0" style="font-size: 0.7rem;">Reales y cómodos</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 d-flex flex-column flex-sm-row align-items-center gap-2 gap-sm-3">
                    <div class="fs-3 text-oro"><i class="bi bi-star-fill"></i></div>
                    <div>
                        <h4 class="fw-bold mb-0 text-uppercase tracking-wider" style="font-size: 0.75rem;">Atención</h4>
                        <p class="small text-white-50 mb-0" style="font-size: 0.7rem;">Cercana y personalizada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-3 border-bottom shadow-sm text-uppercase fw-medium tracking-widest text-denim"
        style="background-color: #fcfbf7; font-size: 0.7rem;">
        <div class="container text-center">
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 gap-md-4">
                <span>✨ Mismas Marcas</span>
                <span class="text-oro">•</span>
                <span>Mejor Precio</span>
                <span class="text-oro">•</span>
                <span>Misma Esencia</span>
                <span class="text-oro">•</span>
                <span>Nueva Etapa ❤️</span>
            </div>
        </div>
    </div>

    <section class="py-5 bg-light">
        <div class="container">

            {{-- ENCABEZADO --}}
            <div class="text-center mb-4">
                <h2 class="fw-bold font-titulo text-denim">Últimas Novedades</h2>
                <p class="text-muted">Descubre lo más reciente que ingresó a nuestra tienda</p>
                <div class="mx-auto" style="width: 50px; height: 3px; background-color: #ffc107;"></div>
            </div>

            @if ($novedades->count() > 0)
                {{-- CARRUSEL DE BOOTSTRAP CON AUTOPLAY --}}
                <div id="carruselNovedades" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
                    <div class="carousel-inner">

                        {{-- Agrupamos los productos de a 3 por diapositiva (o de a 4 en pantallas extra grandes) --}}
                        @foreach ($novedades->chunk(3) as $key => $grupoProductos)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <div class="row g-4 justify-content-center">

                                    @foreach ($grupoProductos as $producto)
                                        <div class="col-12 col-md-4">
                                            <div
                                                class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-producto hover-elevate">

                                                {{-- Badge de Novedad --}}
                                                <span
                                                    class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 fw-bold rounded-2 px-2.5 py-1.5 z-2"
                                                    style="font-size: 0.75rem;">
                                                    <i class="bi bi-stars"></i> ¡Nuevo!
                                                </span>

                                                {{-- Imagen del Producto --}}
                                                <div class="position-relative overflow-hidden"
                                                    style="height: 280px; background-color: #f8f9fa;">
                                                    @if ($producto->url_imagen)
                                                        <img src="{{ asset($producto->url_imagen) }}"
                                                            class="card-img-top w-100 h-100 object-fit-cover"
                                                            alt="{{ $producto->nombre }}">
                                                    @else
                                                        <div
                                                            class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                            <i class="bi bi-image fs-1 opacity-50"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Cuerpo del Producto --}}
                                                <div
                                                    class="card-body d-flex flex-column justify-content-between p-3 bg-white">
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1 text-truncate"
                                                            title="{{ $producto->nombre }}">
                                                            {{ $producto->nombre }}
                                                        </h6>
                                                        <p class="text-muted small mb-2 text-truncate">
                                                            {{ $producto->categoria->nombre ?? 'Indumentaria' }}
                                                        </p>
                                                    </div>

                                                    <div
                                                        class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                                        <div>
                                                            @if ($producto->liquidacion && $producto->porc_liquidacion > 0)
                                                                <span class="text-muted text-decoration-line-through small me-1">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                                                            @endif
                                                            <span class="fw-bold text-success fs-5">
                                                                ${{ number_format($producto->precio_lista_actual, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                        <a href="{{ route('productos.show', $producto->id) }}"
                                                            class="btn btn-outline-dark btn-sm rounded-3 px-3">
                                                            Ver detalle
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endforeach

                    </div>

                    {{-- CONTROLES DEL CARRUSEL (FLECHAS) --}}
                    @if ($novedades->count() > 3)
                        <button class="carousel-control-prev custom-control" type="button"
                            data-bs-target="#carruselNovedades" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next custom-control" type="button"
                            data-bs-target="#carruselNovedades" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif

                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-box-seam fs-2 opacity-50 d-block mb-2"></i>
                    No hay productos recientes para mostrar.
                </div>
            @endif

        </div>
    </section>

    {{-- ESTILOS ADICIONALES --}}
    <style>
        .hover-elevate {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-elevate:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .carousel-control-prev.custom-control,
        .carousel-control-next.custom-control {
            width: 5%;
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>

@endsection
