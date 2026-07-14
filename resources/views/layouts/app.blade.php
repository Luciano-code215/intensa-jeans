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

    <main>
        @yield('content')
    </main>

    <footer class="bg-denim text-white text-center py-5 mt-5 border-top border-4 border-warning"
        style="border-color: #d4af37 !important;">
        <div class="container">
            <p class="font-titulo italic fs-4 text-oro mb-2">"Jeans que realzan tu esencia"</p>
            <p class="small text-white-50 mb-0">&copy; {{ date('Y') }} Intensa Jeans. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
