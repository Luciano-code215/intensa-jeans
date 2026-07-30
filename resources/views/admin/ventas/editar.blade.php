@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.ventas.index') }}" class="btn btn-outline-secondary rounded-3 p-2"
                    style="width: 42px; height: 42px;" title="Volver">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Editar Orden #{{ $orden->id }}</h1>
                    <p class="text-muted small mb-0">Agregá o quitá productos de la orden.</p>
                </div>
            </div>
            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">
                {{ ucfirst($orden->estado) }} - ${{ number_format($orden->total, 0, ',', '.') }}
            </span>
        </div>

        <form action="{{ route('admin.ventas.actualizar-items', $orden->id) }}" method="POST">
            @csrf

            <div class="row g-4">
                {{-- COLUMNA IZQUIERDA: ITEMS ACTUALES --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3 pb-2 border-bottom" style="color: #1a3352;">
                            <i class="bi bi-box-seam me-2"></i> Productos en la Orden
                        </h5>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="text-muted small">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center" style="width:100px;">Cantidad</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center" style="width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orden->items as $item)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                                <span class="fw-semibold">{{ $item->producto->nombre ?? 'Eliminado' }}</span>
                                                <small class="text-muted d-block">${{ number_format($item->precio_unitario, 0, ',', '.') }} c/u</small>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="items[{{ $loop->index }}][cantidad]"
                                                    value="{{ $item->cantidad }}" min="0"
                                                    class="form-control form-control-sm text-center fw-bold"
                                                    style="width: 70px;">
                                                <small class="text-muted">0 = eliminar</small>
                                            </td>
                                            <td class="text-end fw-bold">
                                                ${{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-link text-danger p-0 btn-eliminar-item"
                                                    data-id="{{ $item->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-4">

                        {{-- AGREGAR NUEVOS PRODUCTOS --}}
                        <h5 class="fw-bold mb-3" style="color: #1a3352;">
                            <i class="bi bi-plus-circle me-2"></i> Agregar Productos
                        </h5>

                        <div id="nuevos-productos">
                            <div class="row g-2 align-items-end mb-2 producto-nuevo-template">
                                <div class="col-6">
                                    <select name="nuevos[0][producto_id]" class="form-select form-select-sm">
                                        <option value="">Seleccionar producto...</option>
                                        @foreach ($productos as $p)
                                            <option value="{{ $p->id }}">{{ $p->nombre }} - ${{ number_format($p->precio_lista_actual, 0, ',', '.') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="number" name="nuevos[0][cantidad]" value="1" min="1"
                                        class="form-control form-control-sm text-center" placeholder="Cant.">
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 btn-quitar-nuevo">Quitar</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btn-agregar-fila">
                            <i class="bi bi-plus"></i> Agregar otro producto
                        </button>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: ACCIONES --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                        <h5 class="fw-bold mb-3 pb-2 border-bottom" style="color: #1a3352;">Acciones</h5>

                        <p class="text-muted small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Al guardar los cambios se re-calculará el total y se ajustará el stock automáticamente.
                        </p>

                        <button type="submit" class="btn btn-lg w-100 py-3 fw-bold rounded-3 text-white shadow-sm mb-2"
                            style="background-color: #1a3352;">
                            <i class="bi bi-check-lg me-2"></i> Guardar Cambios
                        </button>

                        <a href="{{ route('admin.ventas.index') }}"
                            class="btn btn-outline-secondary w-100 rounded-3 py-2">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('btn-agregar-fila').addEventListener('click', function() {
            const contenedor = document.getElementById('nuevos-productos');
            const index = contenedor.children.length;
            const template = contenedor.querySelector('.producto-nuevo-template');
            const clone = template.cloneNode(true);

            clone.querySelector('[name^="nuevos"]').name = `nuevos[${index}][producto_id]`;
            clone.querySelectorAll('[name^="nuevos"]')[1].name = `nuevos[${index}][cantidad]`;
            clone.querySelector('select').value = '';
            clone.querySelector('input[type="number"]').value = 1;

            clone.querySelector('.btn-quitar-nuevo').addEventListener('click', function() {
                clone.remove();
            });

            contenedor.appendChild(clone);
        });

        document.querySelectorAll('.btn-quitar-nuevo').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.producto-nuevo-template').remove();
            });
        });

        document.querySelectorAll('.btn-eliminar-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const input = row.querySelector('input[type="number"]');
                if (input) input.value = 0;
                row.style.opacity = '0.4';
            });
        });
    </script>

    <style>
        .bg-denim { background-color: #1a3352; }
        .text-denim { color: #1a3352; }
    </style>
@endsection
