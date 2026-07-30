@extends('layouts.admin')

@section('title', 'Editar Banner')

@section('content')
<div class="py-4">
    <h2 class="fw-bold text-denim mb-1">
        <i class="bi bi-images me-2"></i>Editar Banner de Inicio
    </h2>
    <p class="text-secondary small mb-4">Subí las imágenes que se muestran en el carrusel de la página principal.</p>

    @if (session('success'))
        <div class="alert alert-success rounded-3">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-denim text-white fw-semibold small py-2">Banner actual (Desktop)</div>
                <img src="{{ asset('images/banner1.jpeg') }}?v={{ time() }}" class="w-100 d-block" alt="Banner desktop"
                    style="max-height: 300px; object-fit: cover;">
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-secondary text-white fw-semibold small py-2">Banner actual (Mobile)</div>
                <img src="{{ asset('images/banner1-mobile.jpg') }}?v={{ time() }}" class="w-100 d-block" alt="Banner mobile"
                    style="max-height: 300px; object-fit: cover;">
            </div>
        </div>
    </div>

    <form action="{{ route('admin.banner.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="fw-semibold text-denim small mb-2">Banner Desktop (reemplaza banner1.jpeg)</label>
                    <input type="file" name="banner_desktop" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text small">JPG, PNG o WebP. Máx 10MB. Se redimensionará a 1920x750 aprox.</div>
                </div>
                <div class="col-md-6">
                    <label class="fw-semibold text-denim small mb-2">Banner Mobile (reemplaza banner1-mobile.jpg)</label>
                    <input type="file" name="banner_mobile" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text small">JPG, PNG o WebP. Máx 10MB. Ideal 750x750.</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-denim px-4 rounded-3">
                    <i class="bi bi-upload me-2"></i>Actualizar Banner
                </button>
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary px-4 rounded-3">
                    <i class="bi bi-eye me-2"></i>Ver sitio
                </a>
            </div>
        </div>
    </form>
</div>
@endsection