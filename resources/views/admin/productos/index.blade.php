@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ALERTAS DE ÉXITO --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ENCABEZADO PRINCIPAL --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-warning bg-opacity-20 text-warning rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <i class="bi bi-box-seam-fill fs-4 text-oro" style="color: #ffc107;"></i>
                </div>
                <h1 class="h3 fw-bold text-denim mb-0 font-titulo" style="color: #1a3352;">Catálogo de Productos</h1>
            </div>
            <div>
                <a href="{{ route('admin.productos.create') }}"
                    class="btn btn-success d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 fw-semibold shadow-sm border-0"
                    style="background-color: #198754;">
                    <i class="bi bi-plus-circle-fill"></i> Agregar Producto
                </a>
            </div>
        </div>

        {{-- BARRA DE FILTROS Y BÚSQUEDA DINÁMICA --}}
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
            <form action="{{ route('admin.productos.index') }}" method="GET" id="filter-form"
                class="row g-3 align-items-center">

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="input-group border rounded-3 bg-light bg-opacity-50 px-2 align-items-center">
                        <span class="text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-0 bg-transparent py-2"
                            placeholder="Buscar por nombre o SKU..." name="buscar" value="{{ request('buscar') }}">
                        @if (request('buscar'))
                            <a href="{{ route('admin.productos.index') }}" class="text-muted text-decoration-none px-1"><i
                                    class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <select class="form-select border rounded-3 py-2 text-secondary bg-light bg-opacity-20" name="categoria"
                        onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <select class="form-select rounded-3 py-2 text-secondary bg-white" name="estado"
                        style="border-color: #ffc107 !important;" onchange="this.form.submit()">
                        <option value="activos" {{ request('estado', 'activos') == 'activos' ? 'selected' : '' }}>Ver:
                            Productos Activos</option>
                        <option value="pausados" {{ request('estado') == 'pausados' ? 'selected' : '' }}>Ver: Productos
                            Pausados</option>
                        <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Ver: Todos</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-2 col-lg-3 ms-auto">
                    <select class="form-select rounded-3 py-2 text-primary fw-medium bg-white" name="ordenar"
                        style="border-color: #0d6efd !important;" onchange="this.form.submit()">
                        <option value="defecto" {{ request('ordenar', 'defecto') == 'defecto' ? 'selected' : '' }}>Ordenar
                            por: Más Nuevos</option>
                        <option value="precio-menor" {{ request('ordenar') == 'precio-menor' ? 'selected' : '' }}>Precio:
                            Menor a Mayor</option>
                        <option value="precio-mayor" {{ request('ordenar') == 'precio-mayor' ? 'selected' : '' }}>Precio:
                            Mayor a Menor</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- CONTENEDOR DE LA TABLA --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-0" style="color: #1a3352;">
                    <i class="bi bi-list-ul text-secondary"></i> Artículos en Inventario ({{ $productos->count() }})
                </h5>
            </div>

            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3">Código/SKU</th>
                            <th class="py-3">Miniatura</th>
                            <th class="py-3">Nombre del Producto</th>
                            <th class="py-3">Categoría</th>
                            <th class="py-3">Precio Unitario</th>
                            <th class="py-3">Stock Disponible</th>
                            <th class="py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">

                        @forelse($productos as $prod)
                            @php
                                // Cálculo del stock acumulado total del producto
                                $stockTotal = $prod->talles->sum('pivot.stock');
                            @endphp
                            <tr class="border-bottom border-light">
                                {{-- SKU --}}
                                <td class="text-muted font-monospace fw-medium">
                                    {{ $prod->sku ?? 'S/N' }}
                                </td>

                                {{-- Miniatura --}}
                                <td>
                                    <div class="rounded-3 border overflow-hidden shadow-sm d-flex justify-content-center align-items-center"
                                        style="width: 50px; height: 50px; background-color: #f8f9fa;">
                                        @if ($prod->url_imagen)
                                            <img src="{{ asset($prod->url_imagen) }}" alt="{{ $prod->nombre }}"
                                                style="object-fit: cover; width:100%; height:100%;">
                                        @else
                                            <i class="bi bi-image text-muted fs-4"></i>
                                        @endif
                                    </div>
                                </td>

                                {{-- Nombre e Indicador de Liquidación --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="fw-bold text-dark mb-0">{{ $prod->nombre }}</div>
                                        @if ($prod->liquidacion)
                                            <span class="badge bg-danger text-white rounded-pill px-2 py-0.5"
                                                style="font-size: 0.7rem;">LIQ {{ $prod->porc_liquidacion }}%</span>
                                        @endif
                                    </div>
                                    <span class="text-muted small d-inline-flex align-items-center gap-1"
                                        style="font-size: 0.78rem;">
                                        <i class="bi bi-circle-fill {{ $prod->activo ? 'text-success' : 'text-danger' }}"
                                            style="font-size: 0.5rem;"></i>
                                        {{ $prod->activo ? 'Visible' : 'Pausado' }}
                                    </span>
                                </td>

                                {{-- Categoría --}}
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-semibold"
                                        style="font-size: 0.75rem;">{{ $prod->categoria->nombre ?? 'Sin categoría' }}</span>
                                </td>

                                {{-- Precio --}}
                                <td class="fw-bold text-dark">
                                    ${{ number_format($prod->precio, 0, ',', '.') }}
                                </td>

                                {{-- Stock Total + Desglose dropdown interactivo por Talle --}}
                                <td>
                                    @if ($stockTotal == 0)
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-2 fw-semibold"
                                            style="font-size: 0.8rem;">
                                            Sin Stock
                                        </span>
                                    @else
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-light border dropdown-toggle fw-medium d-inline-flex align-items-center gap-1.5 px-2.5 py-1.5 rounded-2"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                style="font-size: 0.8rem;">
                                                <span
                                                    class="badge {{ $stockTotal <= 5 ? 'bg-warning' : 'bg-success' }} text-white rounded-pill">{{ $stockTotal }}</span>
                                                Talles disp.
                                            </button>
                                            <ul class="dropdown-menu shadow border-0 p-2"
                                                style="min-width: 150px; font-size: 0.85rem;">
                                                <li class="dropdown-header fw-bold text-dark border-bottom pb-1 mb-1">
                                                    Detalle de Stock</li>
                                                @foreach ($prod->talles as $talle)
                                                    <li
                                                        class="d-flex justify-content-between align-items-center py-1 px-2">
                                                        <span class="fw-semibold">Talle {{ $talle->nombre }}:</span>
                                                        <span
                                                            class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">{{ $talle->pivot->stock }}
                                                            u.</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Editar --}}
                                        <a href="{{ route('admin.productos.edit', $prod->id) }}"
                                            class="btn btn-outline-primary btn-sm rounded-3 px-2.5 py-1.5"
                                            title="Editar producto">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Eliminar (Desactivación) --}}
                                        @if ($prod->activo)
                                            <form action="{{ route('admin.productos.destroy', $prod->id) }}"
                                                method="POST" class="form-desactivar d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-3 px-2.5 py-1.5"
                                                    title="Pausar / Desactivar producto">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.productos.reactivar', $prod->id) }}"
                                                method="POST" class="form-reactivar d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-success btn-sm rounded-3 px-2.5 py-1.5"
                                                    title="Reactivar producto">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-search fs-1 d-block mb-3"></i>
                                    No se encontraron productos que coincidan con la búsqueda o filtros aplicados.
                                </td>
                            </tr>
                        @endforelse

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

        .dropdown-toggle::after {
            vertical-align: middle;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formularios = document.querySelectorAll('.form-desactivar');
            formularios.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm(
                            '¿Estás seguro de que deseas desactivar este producto? Dejará de mostrarse públicamente en la tienda.'
                        )) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
