@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ENCABEZADO PRINCIPAL --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-warning bg-opacity-20 text-warning rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <i class="bi bi-box-seam-fill fs-4 text-oro"></i>
                </div>
                <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Catálogo de Productos</h1>
            </div>
            <div>
                <a href="{{ route('admin.productos.create') }}"
                    class="btn btn-success d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 fw-semibold shadow-sm border-0"
                    style="background-color: #198754;">
                    <i class="bi bi-plus-circle-fill"></i> Agregar Producto
                </a>
            </div>
        </div>

        {{-- BARRA DE FILTROS Y BÚSQUEDA --}}
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
            <form action="#" method="GET" class="row g-3 align-items-center">
                <!-- Buscar -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="input-group border rounded-3 bg-light bg-opacity-50 px-2 align-items-center">
                        <span class="text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-0 bg-transparent py-2" placeholder="Buscar..."
                            name="buscar">
                    </div>
                </div>

                <!-- Filtrar por Categoría -->
                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <select class="form-select border rounded-3 py-2 text-secondary bg-light bg-opacity-20"
                        name="categoria">
                        <option value="">Todas las categorías</option>
                        <option value="skinny">Skinny Jeans</option>
                        <option value="mom">Mom Jeans</option>
                        <option value="wide-leg">Wide Leg / Baggy</option>
                        <option value="shorts">Shorts & Polleras</option>
                    </select>
                </div>

                <!-- Filtrar por Estado -->
                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <select class="form-select rounded-3 py-2 text-secondary bg-white" name="estado"
                        style="border-color: #ffc107 !important;">
                        <option value="activos">Ver: Productos Activos</option>
                        <option value="pausados">Ver: Productos Pausados</option>
                        <option value="todos">Ver: Todos</option>
                    </select>
                </div>

                <!-- Ordenar por -->
                <div class="col-12 col-sm-6 col-md-2 col-lg-3 ms-auto">
                    <select class="form-select rounded-3 py-2 text-primary fw-medium bg-white" name="ordenar"
                        style="border-color: #0d6efd !important;">
                        <option value="defecto">Ordenar por: Por Defecto</option>
                        <option value="precio-menor">Precio: Menor a Mayor</option>
                        <option value="precio-mayor">Precio: Mayor a Menor</option>
                        <option value="mas-vendidos">Más vendidos</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- CONTENEDOR DE LA TABLA --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-list-ul text-secondary"></i> Artículos en Inventario
                </h5>
            </div>

            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3">Código</th>
                            <th class="py-3">Miniatura</th>
                            <th class="py-3">Nombre del Producto</th>
                            <th class="py-3">Categoría</th>
                            <th class="py-3">Precio Unitario</th>
                            <th class="py-3">Stock Disponible</th>
                            <th class="py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">

                        {{-- EJEMPLO 1: Jean Mom --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-medium">JNS-MOM012</td>
                            <td>
                                <div class="rounded-3 border overflow-hidden shadow-sm d-flex justify-content-center align-items-center"
                                    style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                    <i class="bi bi-image text-muted fs-4"></i> {{-- Reemplazar por <img src="..." style="object-fit: cover; width:100%; height:100%;"> --}}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-0">Jean Mom Rígido Celeste Celeste</div>
                                <span class="text-success small d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.78rem;">
                                    <i class="bi bi-graph-up-arrow"></i> Vendidos: 14 u.
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-semibold"
                                    style="font-size: 0.75rem;">Mom Jeans</span>
                            </td>
                            <td class="fw-bold text-dark">$38.500</td>
                            <td>
                                <span
                                    class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                    style="font-size: 0.8rem; color: #bda030 !important;">5 últimas</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-3 px-2.5 py-1.5"><i
                                            class="bi bi-pencil"></i></a>
                                    <button class="btn btn-outline-danger btn-sm rounded-3 px-2.5 py-1.5"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        {{-- EJEMPLO 2: Jean Skinny --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-medium">JNS-SKN045</td>
                            <td>
                                <div class="rounded-3 border overflow-hidden shadow-sm d-flex justify-content-center align-items-center"
                                    style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                    <i class="bi bi-image text-muted fs-4"></i>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-0">Jean Skinny Elastizado Negro</div>
                                <span class="text-success small d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.78rem;">
                                    <i class="bi bi-graph-up-arrow"></i> Vendidos: 32 u.
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-semibold"
                                    style="font-size: 0.75rem;">Skinny Jeans</span>
                            </td>
                            <td class="fw-bold text-dark">$35.000</td>
                            <td>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                    style="font-size: 0.8rem;">24 unidades</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-3 px-2.5 py-1.5"><i
                                            class="bi bi-pencil"></i></a>
                                    <button class="btn btn-outline-danger btn-sm rounded-3 px-2.5 py-1.5"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        {{-- EJEMPLO 3: Sin Stock --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-medium">JNS-WDL089</td>
                            <td>
                                <div class="rounded-3 border overflow-hidden shadow-sm d-flex justify-content-center align-items-center"
                                    style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                    <i class="bi bi-image text-muted fs-4"></i>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-0">Wide Leg Nevado Localizado</div>
                                <span class="text-success small d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.78rem;">
                                    <i class="bi bi-graph-up-arrow"></i> Vendidos: 0 u.
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-semibold"
                                    style="font-size: 0.75rem;">Wide Leg</span>
                            </td>
                            <td class="fw-bold text-dark">$42.900</td>
                            <td>
                                <span
                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                    style="font-size: 0.8rem;">Sin Stock</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-3 px-2.5 py-1.5"><i
                                            class="bi bi-pencil"></i></a>
                                    <button class="btn btn-outline-danger btn-sm rounded-3 px-2.5 py-1.5"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <style>
        /* Quitar el borde azul por defecto al hacer foco en las cajas de texto y combos */
        .form-control:focus,
        .form-select:focus {
            box-shadow: none !important;
        }
    </style>
@endsection
