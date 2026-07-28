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
    .hover-opacity:hover {
        opacity: 0.8;
        transition: opacity 0.2s ease;
    }
</style>

<!-- BARRA DE ANUNCIO SUPERIOR MODIFICADA -->
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
    <div class="container position-relative">

        {{-- LOGO --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo-intensa.jpeg') }}" alt="Logo Intensa Jeans" width="60" height="60"
                class="rounded-circle shadow-sm me-2" style="object-fit: cover;">
            <span class="font-titulo fw-bold tracking-wider text-denim fs-3 d-none d-sm-inline">INTENSA jeans</span>
        </a>

        {{-- BOTÓN HAMBURGUESA (MÓVIL) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido"
            aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENÚ DE NAVEGACIÓN PRINCIPAL --}}
        <div class="collapse navbar-collapse" id="navbarContenido">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium text-center">

                {{-- Helper PHP rápido para verificar si una categoría coincide exactamente --}}
                @php
                    $buscar = request('buscar');
                    $liquidacion = request('liquidacion');
                    $esCatalogo = request()->routeIs('catalogo.index');

                    // Mapeo de búsquedas exactas para saber si estamos en una categoría del menú
                    $categoriasMenu = ['Skinny chupin', 'mom', 'Wide Leg Baggy', 'Short Pollera'];

                    // Si estamos en el catálogo pero NO es liquidación ni una categoría del menú, es Búsqueda General / Catálogo Completo
                    $esCatalogoCompleto = $esCatalogo && !$liquidacion && !in_array($buscar, $categoriasMenu);
                @endphp

                <!-- 1. Inicio -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary {{ request()->routeIs('home') || request()->is('/') ? 'active fw-bold text-denim border-bottom border-2 border-warning' : '' }}"
                        style="{{ request()->routeIs('home') || request()->is('/') ? 'border-color: #d4af37 !important;' : '' }}"
                        href="{{ url('/') }}">Inicio</a>
                </li>

                <!-- 2. Skinny Jeans -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary {{ $esCatalogo && $buscar === 'Skinny chupin' && !$liquidacion ? 'active fw-bold text-denim border-bottom border-2 border-warning' : '' }}"
                        style="{{ $esCatalogo && $buscar === 'Skinny chupin' && !$liquidacion ? 'border-color: #d4af37 !important;' : '' }}"
                        href="{{ route('catalogo.index', ['buscar' => 'Skinny chupin']) }}">Skinny Jeans</a>
                </li>

                <!-- 3. Mom Jeans -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary {{ $esCatalogo && $buscar === 'mom' && !$liquidacion ? 'active fw-bold text-denim border-bottom border-2 border-warning' : '' }}"
                        style="{{ $esCatalogo && $buscar === 'mom' && !$liquidacion ? 'border-color: #d4af37 !important;' : '' }}"
                        href="{{ route('catalogo.index', ['buscar' => 'mom']) }}">Mom Jeans</a>
                </li>

                <!-- 4. Wide Leg / Baggy -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary {{ $esCatalogo && $buscar === 'Wide Leg Baggy' && !$liquidacion ? 'active fw-bold text-denim border-bottom border-2 border-warning' : '' }}"
                        style="{{ $esCatalogo && $buscar === 'Wide Leg Baggy' && !$liquidacion ? 'border-color: #d4af37 !important;' : '' }}"
                        href="{{ route('catalogo.index', ['buscar' => 'Wide Leg Baggy']) }}">Wide Leg / Baggy</a>
                </li>

                <!-- 5. Shorts & Polleras -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary {{ $esCatalogo && $buscar === 'Short Pollera' && !$liquidacion ? 'active fw-bold text-denim border-bottom border-2 border-warning' : '' }}"
                        style="{{ $esCatalogo && $buscar === 'Short Pollera' && !$liquidacion ? 'border-color: #d4af37 !important;' : '' }}"
                        href="{{ route('catalogo.index', ['buscar' => 'Short Pollera']) }}">Shorts & Polleras</a>
                </li>

                <!-- 6. Catálogo Completo (Se activa con búsquedas personalizadas o sin filtros) -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-secondary {{ $esCatalogoCompleto ? 'active fw-bold text-denim border-bottom border-2 border-warning' : '' }}"
                        style="{{ $esCatalogoCompleto ? 'border-color: #d4af37 !important;' : '' }}"
                        href="{{ route('catalogo.index') }}">Catálogo Completo</a>
                </li>

                <!-- 7. Liquidadas -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-danger fw-bold {{ $esCatalogo && $liquidacion ? 'border-bottom border-2 border-danger pb-1' : '' }}"
                        href="{{ route('catalogo.index', ['liquidacion' => 1]) }}">
                        Liquidadas 🔥
                    </a>
                </li>

            </ul>
        </div>

        {{-- SECCIÓN DE ICONOS A LA DERECHA --}}
        <div class="d-flex justify-content-center gap-3 fs-5 text-secondary pt-2 pt-lg-0 align-items-center">

            {{-- 1. Icono de Búsqueda Desplegable --}}
            <div class="dropdown position-static">
                <a href="#" class="text-secondary hover-denim no-arrow d-inline-block" id="dropdownBuscador"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                    title="Buscar productos">
                    <i class="bi bi-search"></i>
                </a>

                {{-- Menú flotante del buscador --}}
                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3 rounded-4 mt-3"
                    aria-labelledby="dropdownBuscador" style="width: 320px; z-index: 1060;">
                    <form action="{{ route('catalogo.index') }}" method="GET">
                        @if (request('liquidacion'))
                            <input type="hidden" name="liquidacion" value="{{ request('liquidacion') }}">
                        @endif

                        <label class="form-label text-denim fw-bold small mb-2">Buscar en Intensa Jeans</label>
                        <div class="input-group">
                            <input type="text" name="buscar"
                                class="form-control form-control-sm rounded-start-pill ps-3"
                                placeholder="Ej: Mom, Skinny, Wide..." value="{{ request('buscar') }}" autofocus>
                            <button class="btn btn-sm text-white rounded-end-pill px-3" type="submit"
                                style="background-color: #1a3352;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. Menú Desplegable de Usuario --}}
            <div class="dropdown">
                <a href="#" class="text-secondary hover-denim dropdown-toggle no-arrow d-inline-block"
                    id="menuUsuario" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="bi bi-person"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2"
                    aria-labelledby="menuUsuario" style="font-size: 0.85rem; min-width: 180px; z-index: 1050;">

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

            {{-- Icono del Carrito --}}
            @php
                $cart = session()->get('cart', []);
                $cantTotal = array_sum(array_column($cart, 'cantidad'));
            @endphp

            <a href="{{ route('carrito.index') }}" class="text-secondary hover-denim position-relative"
                title="Ver Carrito">
                <i class="bi bi-bag-heart fs-4"></i>
                @if ($cantTotal > 0)
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-oro text-denim fw-bold"
                        style="font-size: 0.65rem; padding: 0.3em 0.5em;">
                        {{ $cantTotal }}
                    </span>
                @else
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-secondary text-white"
                        style="font-size: 0.6rem; padding: 0.25em 0.45em;">
                        0
                    </span>
                @endif
            </a>

        </div>

    </div>
</nav>

<style>
    .no-arrow::after {
        display: none !important;
    }
</style>
