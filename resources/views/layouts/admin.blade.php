<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Intensa Jeans</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            padding-top: 56px;
        }
        @media (min-width: 768px) {
            body { padding-top: 0; }
        }
        .font-titulo { font-family: 'Playfair Display', serif; }
        .bg-denim { background-color: #1a3352 !important; }
        .text-denim { color: #1a3352 !important; }
        .btn-denim { background-color: #1a3352; color: white; font-weight: 500; }
        .btn-denim:hover { background-color: #112236; color: white; }
        .text-oro { color: #d4af37 !important; }
        .hover-sidebar:hover { color: white !important; background-color: rgba(255, 255, 255, 0.05); transition: all 0.2s ease; }
        .sidebar-offcanvas { width: 260px !important; }
        @media (min-width: 768px) {
            .sidebar-offcanvas { width: auto !important; }
        }
    </style>
</head>

<body>

    {{-- MOBILE TOPBAR --}}
    <nav class="navbar navbar-dark bg-denim d-md-none fixed-top px-3 shadow-sm">
        <span class="fw-bold text-uppercase small text-oro">Intensa Admin</span>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar"
            aria-controls="offcanvasSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    {{-- OFFCANVAS SIDEBAR (mobile) --}}
    <div class="offcanvas offcanvas-start sidebar-offcanvas bg-denim text-white d-md-none" tabindex="-1"
        id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
            <span class="fw-bold text-uppercase small text-oro" id="offcanvasSidebarLabel">Intensa Admin</span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-3 d-flex flex-column">
            <ul class="nav flex-column gap-2 fw-medium flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 {{ Request::is('dashboard') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('dashboard') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-speedometer2"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/productos*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('admin.productos.index') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-tags"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/categorias*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('admin.categorias.index') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-folder"></i> Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/ventas') || Request::is('admin/ventas?*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('admin.ventas.index') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-cart3"></i> Órdenes / Ventas
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-1.5 px-3 d-flex align-items-center gap-2 small {{ Request::is('admin/ventas/mostrador') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('admin.ventas.mostrador') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-plus-circle"></i> Venta Mostrador
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/usuarios*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('admin.usuarios.index') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-people-fill"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/consultas*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('admin.consultas.index') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-chat-left-text"></i> Consultas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/banner') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                        href="{{ route('admin.banner') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-images"></i> Banner
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-white-50 hover-sidebar rounded-3 py-1.5 px-3 d-flex align-items-center gap-2 small"
                        href="{{ url('/') }}" onclick="cerrarOffcanvas()">
                        <i class="bi bi-arrow-left-short"></i> Ver Tienda
                    </a>
                </li>
            </ul>
            <div class="border-top border-secondary border-opacity-25 pt-3 mt-2">
                <div class="d-flex align-items-center gap-2 text-white text-truncate">
                    <i class="bi bi-person-circle fs-4 text-oro"></i>
                    <div class="overflow-hidden">
                        <p class="small fw-bold mb-0 text-truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <span class="text-white-50" style="font-size: 0.65rem;">Administrador</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DESKTOP SIDEBAR --}}
    <div class="d-none d-md-block">
        <div class="col-md-3 col-lg-2 px-0 bg-denim min-vh-100 shadow-sm d-flex flex-column justify-content-between position-fixed top-0 start-0"
            style="z-index: 1000;">
            <div class="p-3">
                <div class="text-white border-bottom border-secondary border-opacity-25 pb-3 mb-4 text-center text-md-start">
                    <span class="fw-bold text-uppercase small tracking-wider text-oro">Intensa Admin</span>
                </div>
                <ul class="nav flex-column gap-2 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('dashboard') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/productos*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('admin.productos.index') }}">
                            <i class="bi bi-tags"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/categorias*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('admin.categorias.index') }}">
                            <i class="bi bi-folder"></i> Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/ventas') || Request::is('admin/ventas?*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('admin.ventas.index') }}">
                            <i class="bi bi-cart3"></i> Órdenes / Ventas
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 small {{ Request::is('admin/ventas/mostrador') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('admin.ventas.mostrador') }}">
                            <i class="bi bi-plus-circle"></i> Venta Mostrador
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/usuarios*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('admin.usuarios.index') }}">
                            <i class="bi bi-people-fill"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/consultas*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('admin.consultas.index') }}">
                            <i class="bi bi-chat-left-text"></i> Consultas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/banner') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                            href="{{ route('admin.banner') }}">
                            <i class="bi bi-images"></i> Banner
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-white-50 hover-sidebar rounded-3 py-2 px-3 d-flex align-items-center gap-2 small"
                            href="{{ url('/') }}">
                            <i class="bi bi-arrow-left-short"></i> Ver Tienda Pública
                        </a>
                    </li>
                </ul>
            </div>
            <div class="p-3 border-top border-secondary border-opacity-25 bg-black bg-opacity-20">
                <div class="d-flex align-items-center gap-2 text-white text-truncate">
                    <i class="bi bi-person-circle fs-4 text-oro"></i>
                    <div class="overflow-hidden">
                        <p class="small fw-bold mb-0 text-truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <span class="text-white-50" style="font-size: 0.65rem;">Administrador</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENIDO --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-3 p-md-5">
                @yield('admin_content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cerrarOffcanvas() {
            const el = document.getElementById('offcanvasSidebar');
            const offcanvas = bootstrap.Offcanvas.getInstance(el);
            if (offcanvas) offcanvas.hide();
        }
    </script>
</body>
</html>