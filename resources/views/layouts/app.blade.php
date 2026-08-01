<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Intensa Jeans - Jeans que realzan tu esencia')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #faf9f6;
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
        }

        .btn-denim:hover {
            background-color: #112236;
            color: white;
        }

        .text-oro {
            color: #d4af37 !important;
        }

        .bg-oro {
            background-color: #d4af37 !important;
        }

        .btn-oro {
            background-color: #d4af37;
            color: #1a3352;
            font-weight: 600;
        }

        .btn-oro:hover {
            background-color: #bda030;
            color: #1a3352;
        }
    </style>
</head>

<body>

    @include('partials.navbar')
    @include('partials.login-msg')
    @if (session('agregado_carrito'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('agregado_carrito') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <main>
        @yield('content')
    </main>

    <footer class="bg-denim text-white mt-5 border-top border-4 border-warning pt-5 pb-4"
        style="border-color: #d4af37 !important;">
        <div class="container">
            <div class="row g-4">

                <div class="col-md-4 text-center text-md-start">
                    <h5 class="fw-bold text-oro mb-3 font-titulo">Intensa Jeans</h5>
                    <p class="small text-white-50 lh-lg mb-0">
                        Jeans que realzan tu esencia. Calidad, estilo y confianza desde Corrientes, Argentina.
                    </p>
                </div>

                <div class="col-md-4 text-center">
                    <h6 class="fw-bold text-uppercase small mb-3 tracking-wider text-white-50">Enlaces</h6>
                    <ul class="list-unstyled small lh-lg">
                        <li><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Inicio</a></li>
                        <li><a href="{{ route('catalogo.index') }}" class="text-white-50 text-decoration-none">Catálogo</a></li>
                        <li><a href="{{ route('pagina.quienes-somos') }}" class="text-white-50 text-decoration-none">Quiénes Somos</a></li>
                        <li><a href="{{ route('pagina.como-comprar') }}" class="text-white-50 text-decoration-none">Cómo Comprar</a></li>
                        <li><a href="{{ route('pagina.terminos') }}" class="text-white-50 text-decoration-none">Términos y Condiciones</a></li>
                    </ul>
                </div>

                <div class="col-md-4 text-center text-md-end">
                    <h6 class="fw-bold text-uppercase small mb-3 tracking-wider text-white-50">Seguinos</h6>
                    <div class="d-flex justify-content-center justify-content-md-end gap-3 fs-4 mb-3">
                        <a href="{{ config('app.instagram_url') }}" target="_blank" class="text-white-50 text-decoration-none"><i class="bi bi-instagram"></i></a>
                        <a href="https://wa.me/{{ config('app.whatsapp_owner') }}" target="_blank" class="text-white-50 text-decoration-none"><i class="bi bi-whatsapp"></i></a>
                        <a href="{{ config('app.facebook_url') }}" target="_blank" class="text-white-50 text-decoration-none"><i class="bi bi-facebook"></i></a>
                    </div>
                    <p class="small text-white-50 mb-0">
                        <i class="bi bi-geo-alt me-1"></i> Corrientes, Argentina
                    </p>
                </div>

            </div>

            <hr class="border-secondary border-opacity-25 my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="small text-white-50 mb-0">
                    &copy; {{ date('Y') }} Intensa Jeans. Todos los derechos reservados.
                </p>
                <p class="small text-white-50 mb-0">
                    Desarrollado con ❤️ por <a href="https://wa.me/5493795101613" target="_blank" class="text-white-50 text-decoration-none" title="Contactar desarrollador">Luciano</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
