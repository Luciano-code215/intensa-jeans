@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- 1. TARJETAS DE MÉTRICAS SUPERIORES (Colores sólidos de tu referencia) --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="text-white p-3 rounded-4 shadow-sm" style="background-color: #198754;">
                    <span class="text-uppercase fw-bold tracking-wider" style="font-size: 0.75rem; opacity: 0.85;">Mom
                        Jeans</span>
                    <h2 class="fw-bold mb-0 mt-1">16</h2>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="text-white p-3 rounded-4 shadow-sm"
                    style="background-color: #ffc107; color: #1a3352 !important;">
                    <span class="text-uppercase fw-bold tracking-wider" style="font-size: 0.75rem; opacity: 0.85;">Skinny
                        Jeans</span>
                    <h2 class="fw-bold mb-0 mt-1">12</h2>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="text-white p-3 rounded-4 shadow-sm" style="background-color: #dc3545;">
                    <span class="text-uppercase fw-bold tracking-wider" style="font-size: 0.75rem; opacity: 0.85;">Wide Leg
                        / Baggy</span>
                    <h2 class="fw-bold mb-0 mt-1">11</h2>
                </div>
            </div>
        </div>

        {{-- 2. SECCIÓN INFERIOR (Formulario Izquierda + Tabla Derecha) --}}
        <div class="row g-4">

            {{-- FORMULARIO: NUEVA CATEGORÍA (Izquierda) --}}
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-plus-circle text-success"></i> Nueva Categoría
                    </h5>

                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.9rem;">Nombre de la
                                Categoría</label>
                            <input type="text"
                                class="form-control rounded-3 py-2.5 border-light-subtle bg-light bg-opacity-25"
                                placeholder="Ej: Skinny, Mom, Wide Leg, Shorts..." required>
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

                                {{-- Fila 1 --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-3 text-muted font-monospace fw-semibold">#1</td>
                                    <td class="fw-bold text-dark">Mom Jeans</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                            style="font-size: 0.8rem;">
                                            Visible
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#"
                                                class="btn btn-outline-secondary btn-sm rounded-3 px-2 py-1.5 border-opacity-25"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            <button
                                                class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1.5 border-opacity-25"><i
                                                    class="bi bi-eye-slash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Fila 2 --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-3 text-muted font-monospace fw-semibold">#2</td>
                                    <td class="fw-bold text-dark">Skinny Jeans</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                            style="font-size: 0.8rem;">
                                            Visible
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#"
                                                class="btn btn-outline-secondary btn-sm rounded-3 px-2 py-1.5 border-opacity-25"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            <button
                                                class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1.5 border-opacity-25"><i
                                                    class="bi bi-eye-slash"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Fila 3 --}}
                                <tr class="border-bottom border-light">
                                    <td class="ps-3 text-muted font-monospace fw-semibold">#3</td>
                                    <td class="fw-bold text-dark">Wide Leg / Baggy</td>
                                    <td>
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                            style="font-size: 0.8rem;">
                                            Visible
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#"
                                                class="btn btn-outline-secondary btn-sm rounded-3 px-2 py-1.5 border-opacity-25"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            <button
                                                class="btn btn-outline-danger btn-sm rounded-3 px-2 py-1.5 border-opacity-25"><i
                                                    class="bi bi-eye-slash"></i></button>
                                        </div>
                                    </td>
                                </tr>

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
@endsection
