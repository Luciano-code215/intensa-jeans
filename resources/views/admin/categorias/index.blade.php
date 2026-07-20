@extends('layouts.admin')

@section('admin_content')
    {{-- ALERTA DE CATEGORÍA CREADA --}}
    @if (session('categoria_creada'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 p-3 bg-white"
            role="alert" style="border-left: 4px solid #198754 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center"
                        style="width: 35px; height: 35px;">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">¡Operación Exitosa!</h6>
                        <span class="text-secondary small">{{ session('categoria_creada') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none position-static p-2" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('estado_alterado'))
        <div class="alert alert-info alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 p-3 bg-white"
            role="alert" style="border-left: 4px solid #0dcaf0 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center"
                        style="width: 35px; height: 35px;">
                        <i class="bi bi-arrow-left-right fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">Estado Actualizado</h6>
                        <span class="text-secondary small">{{ session('estado_alterado') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none position-static p-2" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container-fluid px-0">

        {{-- 1. TARJETAS DE MÉTRICAS SUPERIORES (Estilo Minimalista y Dinámico) --}}
        <div class="row g-3 mb-4">
            @foreach ($categorias as $cat)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border border-light-subtle rounded-4 bg-white shadow-sm h-100 transition-hover">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                {{-- Nombre de la categoría --}}
                                <span class="text-uppercase fw-bold text-secondary tracking-wider d-block font-monospace"
                                    style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                    {{ $cat->nombre }}
                                </span>
                                {{-- Cantidad de productos (Dinámico) --}}
                                <h3 class="fw-bold text-dark mb-0 mt-1 font-titulo" style="color: #1a3352 !important;">
                                    {{ $cat->cantidadProductosActivos() }}
                                </h3>
                            </div>
                            {{-- Mini icono sutil indicador --}}
                            <div class="text-muted bg-light rounded-3 d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px; opacity: 0.7;">
                                <i class="bi bi-tag-fill fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            /* Pequeño efecto visual sutil para que se sienta más interactivo al pasar el mouse */
            .transition-hover {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .transition-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
            }
        </style>

        {{-- 2. SECCIÓN INFERIOR (Formulario Izquierda + Tabla Derecha) --}}
        <div class="row g-4">

            {{-- FORMULARIO: NUEVA CATEGORÍA (Izquierda) --}}
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-plus-circle text-success"></i> Nueva Categoría
                    </h5>

                    <form action="{{ route('admin.categorias.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.9rem;">Nombre de la
                                Categoría</label>
                            <input type="text"
                                class="form-control rounded-3 py-2.5 border-light-subtle bg-light bg-opacity-25"
                                placeholder="Nueva categoría" name="nombre" required>
                        </div>

                        <button type="submit"
                            class="btn btn-info text-white w-100 py-2.5 rounded-3 fw-bold border-0 shadow-sm"
                            style="background-color: #0dcaf0;">
                            Guardar Categoría
                        </button>
                    </form>
                </div>
            </div>

            {{-- TABLA: CATEGORÍAS REGISTRADAS (Derecha) --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-tags-fill text-secondary"></i> Categorías Registradas
                    </h5>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0 text-nowrap">
                            <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                                <tr>
                                    <th class="py-3 ps-3">ID</th>
                                    <th class="py-3">Nombre</th>
                                    <th class="py-3">Estado</th>
                                    <th class="py-3 text-center" style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.95rem;">
                                @foreach ($categorias as $categoria)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-3 text-muted font-monospace fw-semibold">#{{ $categoria->id }}</td>
                                        <td class="fw-bold text-dark">{{ $categoria->nombre }}</td>
                                        <td>
                                            @if ($categoria->activo)
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                                    style="font-size: 0.8rem;">
                                                    Visible
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                                    style="font-size: 0.8rem;">
                                                    Oculta
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="#"
                                                    class="btn btn-outline-secondary btn-sm rounded-3 px-2 py-1.5 border-opacity-25"><i
                                                        class="bi bi-pencil-square"></i></a>
                                                @if ($categoria->activo)
                                                    <a href="{{ route('admin.categoria.alterar-estado', $categoria->id) }}"
                                                        class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1.5 border-opacity-25 btn-alterar-estado"
                                                        data-nombre="{{ $categoria->nombre }}" data-accion="ocultar"
                                                        title="Ocultar categoría">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.categoria.alterar-estado', $categoria->id) }}"
                                                        class="btn btn-outline-success btn-sm rounded-3 px-2 py-1.5 border-opacity-25 btn-alterar-estado"
                                                        data-nombre="{{ $categoria->nombre }}" data-accion="mostrar"
                                                        title="Mostrar categoría">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <style>
        /* Efectos de foco limpios */
        .form-control:focus {
            border-color: #0dcaf0 !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.15) !important;
            background-color: #fff !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Buscamos todos los botones que tengan la clase que agregamos
            const botonesEstado = document.querySelectorAll('.btn-alterar-estado');

            botonesEstado.forEach(boton => {
                boton.addEventListener('click', function(e) {
                    // Frenamos temporalmente la redirección automática del enlace
                    e.preventDefault();

                    const nombre = this.getAttribute('data-nombre');
                    const accion = this.getAttribute('data-accion'); // 'ocultar' o 'mostrar'
                    const url = this.getAttribute('href');

                    // Personalizamos la pregunta según el estado actual
                    const mensaje =
                        `¿Estás seguro de que deseas ${accion} la categoría "${nombre}"?`;

                    // Si el administrador presiona "Aceptar", avanzamos a la ruta
                    if (confirm(mensaje)) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>
@endsection
