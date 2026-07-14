<!-- BARRA DE INFORMACIÓN DE CONTACTO SUPERIOR (TOP BAR) -->
<div class="bg-black text-white py-2 shadow-sm border-bottom border-dark"
    style="font-size: 0.72rem; font-family: sans-serif;">
    <div class="container d-flex justify-content-center justify-content-md-end align-items-center">
        <div
            class="d-flex flex-wrap justify-content-center align-items-center gap-3 gap-md-4 tracking-wider fw-bold text-uppercase">

            <!-- Ubicación -->
            <span class="d-flex align-items-center gap-1">
                <i class="bi bi-geo-alt-fill text-white"></i> CORRIENTES, ARGENTINA
            </span>

            <!-- WhatsApp -->
            <a href="https://wa.me/541153862451" target="_blank"
                class="text-white text-decoration-none d-flex align-items-center gap-1 hover-opacity">
                <i class="bi bi-whatsapp"></i> 1153862451
            </a>

            <!-- Atención al Cliente -->
            <a href="#" class="text-white text-decoration-none d-flex align-items-center gap-1 hover-opacity">
                <i class="bi bi-headset"></i> CONTACTANOS
            </a>

        </div>
    </div>
</div>

<style>
    /* Estilo para un leve efecto de respuesta al pasar el mouse por los enlaces */
    .hover-opacity:hover {
        opacity: 0.8;
        transition: opacity 0.2s ease;
    }
</style>

<!-- BARRA DE ANUNCIO SUPERIOR MODIFICADA (FONDO ORO - LETRAS AZULES) -->
<div class="bg-oro text-denim py-2 fw-bold small text-center tracking-wider shadow-sm">
    <div class="container d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 gap-sm-5"
        style="font-size: 0.75rem;">
        <span><i class="bi bi-heart me-1"></i> VUELVE INTENSA JEANS ✨</span>
        <span class="d-none d-sm-inline">✦ MISMAS MARCAS QUE AMÁS</span>
        <span class="d-none d-md-inline">✦ CALIDAD QUE YA CONOCÉS</span>
        <span>✦ PRECIOS QUE TE VAN A ENCANTAR</span>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-intensa.jpeg') }}" alt="Logo Intensa Jeans" width="60" height="60"
                class="rounded-circle shadow-sm me-2" style="object-fit: cover;">
            <span class="font-titulo fw-bold tracking-wider text-denim fs-3 d-none d-sm-inline">INTENSA jeans</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido"
            aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContenido">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium text-center">
                <li class="nav-item mx-2">
                    <a class="nav-link active text-denim border-bottom border-2 border-warning"
                        style="border-color: #d4af37 !important;" href="{{ url('/') }}">Inicio</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary" href="/catalogo?estilo=skinny">Skinny Jeans</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary" href="/catalogo?estilo=mom">Mom Jeans</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary" href="#">Wide Leg / Baggy</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary" href="#">Shorts & Polleras</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary" href="/catalogo">Catálogo Completo</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-danger fw-bold" href="#">Liquidadas 🔥</a>
                </li>
            </ul>
        </div> {{-- CIERRE CORRECTO DEL COLLAPSE DEL MENU CENTRO --}}

        <div class="d-flex justify-content-center gap-3 fs-5 text-secondary pt-2 pt-lg-0 align-items-center">

            {{-- 1. Icono de Búsqueda --}}
            <a href="#" class="text-secondary hover-denim"><i class="bi bi-search"></i></a>

            {{-- 2. Menú Desplegable de la Personita --}}
            <div class="dropdown">
                {{-- Se añade el rol button por accesibilidad y compatibilidad estricta --}}
                <a href="#" class="text-secondary hover-denim dropdown-toggle no-arrow d-inline-block"
                    id="menuUsuario" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="bi bi-person"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2"
                    aria-labelledby="menuUsuario" style="font-size: 0.85rem; min-width: 180px; z-index: 1050;">

                    {{-- CASO 1: SI NO HAY NADIE LOGUEADO (Invitado) --}}
                    @guest
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-2"></i> Registrarse
                            </a>
                        </li>
                    @endguest

                    {{-- CASO 2: SI HAY ALGUIEN LOGUEADO --}}
                    @auth
                        <li class="dropdown-header text-dark fw-bold border-bottom pb-2 mb-1">
                            Hola, {{ Auth::user()->name }}
                        </li>

                        @if (Auth::user()->role !== 'admin')
                            <li>
                                <a class="dropdown-item py-2" href="#">
                                    <i class="bi bi-chat-left-text me-2"></i> Mis Consultas
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item py-2 fw-bold text-primary" href="{{ route('dashboard') }}">
                                    <i class="bi bi-sliders me-2"></i> Panel de Administración
                                </a>
                            </li>
                        @endif

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left me-2"></i> Cerrar Sesión
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>

            {{-- 3. Icono del Carrito/Favoritos --}}
            <a href="#" class="text-secondary hover-denim position-relative">
                <i class="bi bi-bag-heart"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-oro text-denim"
                    style="font-size: 0.6rem; padding: 0.25em 0.45em;">0</span>
            </a>

        </div>

    </div>
</nav>

<style>
    .no-arrow::after {
        display: none !important;
    }
</style>
