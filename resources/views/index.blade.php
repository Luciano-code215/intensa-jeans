@extends('layouts.app')

@section('title', 'Intensa Jeans - Jeans de Mujer')

@section('content')

    <div class="w-100 p-0 overflow-hidden shadow-sm">

        <div class="d-none d-md-block w-100">
            <a href="#">
                <img src="{{ asset('images/banner1.jpeg') }}" alt="Gran Lanzamiento - Intensa Jeans"
                    class="img-fluid w-100 h-auto" style="object-fit: cover;">
            </a>
        </div>

        <div class="d-block d-md-none w-100">
            <a href="#">
                <img src="{{ asset('images/banner1-mobile.jpg') }}" alt="Promo Lanzamiento Jeans - Intensa"
                    class="img-fluid w-100 h-auto">
            </a>
        </div>

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

    <div class="container py-5 mt-4">
        <div class="text-center mb-5">
            <h2 class="font-titulo fw-bold text-denim">Compra por Estilo</h2>
            <p class="text-muted small mb-2">¿Cuál es tu calce favorito para hoy?</p>
            <div class="mx-auto bg-oro" style="width: 60px; height: 3px;"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-dark text-white border-0 overflow-hidden shadow-sm h-100 position-relative"
                    style="min-height: 380px;">
                    <img src="https://images.unsplash.com/photo-1604176354204-9268737828e4?q=80&w=500"
                        class="card-img h-100 object-fit-cover opacity-75" alt="Mom Jeans">
                    <div class="card-img-overlay d-flex flex-column justify-content-end p-4"
                        style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                        <h3 class="card-title font-titulo h4 mb-1">Mom Jeans</h3>
                        <p class="card-text small text-white-50 mb-3">Estilo retro, tiro alto y total comodidad.</p>
                        <a href="#" class="text-oro text-decoration-none fw-medium">Ver colección &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-dark text-white border-0 overflow-hidden shadow-sm h-100 position-relative"
                    style="min-height: 380px;">
                    <img src="https://images.unsplash.com/photo-1582533561751-ef6f6ab93a2e?q=80&w=500"
                        class="card-img h-100 object-fit-cover opacity-75" alt="Wide Leg">
                    <div class="card-img-overlay d-flex flex-column justify-content-end p-4"
                        style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                        <h3 class="card-title font-titulo h4 mb-1">Wide Leg / Baggy</h3>
                        <p class="card-text small text-white-50 mb-3">Sueltos, elegantes y en tendencia absoluta.</p>
                        <a href="#" class="text-oro text-decoration-none fw-medium">Ver colección &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-dark text-white border-0 overflow-hidden shadow-sm h-100 position-relative"
                    style="min-height: 380px;">
                    <img src="https://images.unsplash.com/photo-1475184634737-a62e88213b3d?q=80&w=500"
                        class="card-img h-100 object-fit-cover opacity-75" alt="Skinny Jeans">
                    <div class="card-img-overlay d-flex flex-column justify-content-end p-4"
                        style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                        <h3 class="card-title font-titulo h4 mb-1">Chupines / Skinny</h3>
                        <p class="card-text small text-white-50 mb-3">El ajuste clásico que realza tus curvas de forma
                            perfecta.</p>
                        <a href="#" class="text-oro text-decoration-none fw-medium">Ver colección &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
