@extends('layouts.app')

@section('title', 'Quiénes Somos - Intensa Jeans')

@section('content')
    <div class="container my-5">
        <div class="text-center mb-5">
            <h1 class="font-titulo fw-bold text-denim">Quiénes Somos</h1>
            <div class="mx-auto" style="width: 60px; height: 3px; background-color: #d4af37;"></div>
        </div>

        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <img src="{{ asset('images/local-intensa.jpg') }}" alt="Local Intensa Jeans"
                    class="img-fluid rounded-4 shadow-sm" onerror="this.style.display='none'">
            </div>
            <div class="col-lg-6">
                <p class="lead fw-semibold text-denim">Intensa Jeans nació del amor por la moda y la dedicación a la mujer actual.</p>
                <p class="text-secondary lh-lg">
                    Lo que alguna vez fue un pequeño local en el centro de Corrientes, donde cada prenda se elegía con cariño
                    y se vendía con una sonrisa, hoy renace con la misma esencia pero adaptada a los nuevos tiempos.
                </p>
                <p class="text-secondary lh-lg">
                    Somos un emprendimiento familiar que conoce el rubro desde adentro. Sabemos lo que es buscar el jean
                    perfecto, ese que realza, que acompaña, que se siente cómodo desde el primer momento.
                </p>
                <p class="text-secondary lh-lg mb-0">
                    Hoy volvemos con más fuerza, combinando la atención personalizada de siempre con la comodidad
                    de la tienda online. Para que encuentres tu estilo donde sea que estés.
                </p>
            </div>
        </div>

        <div class="row g-4 mt-5 text-center">
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 h-100">
                    <i class="bi bi-heart-fill text-oro fs-2 mb-3 d-block"></i>
                    <h5 class="fw-bold text-denim">Calidad</h5>
                    <p class="small text-secondary mb-0">Trabajamos con marcas de confianza que garantizan durabilidad y confort.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 h-100">
                    <i class="bi bi-person-check-fill text-oro fs-2 mb-3 d-block"></i>
                    <h5 class="fw-bold text-denim">Atención Personalizada</h5>
                    <p class="small text-secondary mb-0">Cada consulta es respondida por alguien que entiende de moda y de talles reales.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 h-100">
                    <i class="bi bi-truck-fill text-oro fs-2 mb-3 d-block"></i>
                    <h5 class="fw-bold text-denim">Envíos a Todo el País</h5>
                    <p class="small text-secondary mb-0">Llegamos a cada rincón de Argentina con la misma dedicación de siempre.</p>
                </div>
            </div>
        </div>
    </div>
@endsection