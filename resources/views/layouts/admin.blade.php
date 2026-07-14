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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .font-titulo {
            font-family: 'Playfair Display', serif;
        }

        .bg-denim {
            background-color: #1a3352 !important;
        }

        .text-denim {
            color: #1a3352 !important;
        }

        .btn-denim {
            background-color: #1a3352;
            color: white;
            font-weight: 500;
        }

        .btn-denim:hover {
            background-color: #112236;
            color: white;
        }

        .text-oro {
            color: #d4af37 !important;
        }

        .hover-sidebar:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            {{-- BARRA LATERAL FIJA (SIDEBAR) --}}
            <div class="col-md-3 col-lg-2 px-0 bg-denim min-vh-100 shadow-sm d-flex flex-column justify-content-between position-fixed top-0 start-0"
                style="z-index: 1000;">
                <div class="p-3">
                    <div
                        class="text-white border-bottom border-secondary border-opacity-25 pb-3 mb-4 text-center text-md-start">
                        <span class="fw-bold text-uppercase small tracking-wider text-oro">Intensa Admin</span>
                    </div>

                    {{-- Enlaces Globales del Menú --}}
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
                        {{-- NUEVO: Categorías --}}
                        <li class="nav-item">
                            <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/categorias*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                                href="{{ route('admin.categorias.index') }}">
                                <i class="bi bi-folder"></i> Categorías
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/ordenes*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                                href="{{ route('admin.ventas.index') }}">
                                <i class="bi bi-cart3"></i> Órdenes / Ventas
                            </a>
                        </li>
                        {{-- NUEVO: Gestión de Usuarios --}}
                        <li class="nav-item">
                            <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/usuarios*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                                href="{{ route('admin.usuarios.index') }}">
                                <i class="bi bi-people-fill"></i> Gestión de Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white hover-sidebar rounded-3 py-2.5 px-3 d-flex align-items-center gap-2 {{ Request::is('admin/consultas*') ? 'bg-white bg-opacity-10 text-white' : 'text-white-50' }}"
                                href="{{ route('admin.consultas.index') }}">
                                <i class="bi bi-chat-left-text"></i> Consultas
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

                {{-- Datos de Admin --}}
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

            {{-- ESPACIO DE CONTENIDO DINÁMICO (Derecha) --}}
            {{-- Agregamos un offset-md-3 para que el contenido empiece justo donde termina el sidebar fijo --}}
            <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4 p-md-5">
                @yield('admin_content')
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
