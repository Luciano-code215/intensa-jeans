@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        @php
            $mapaPago = [
                'efectivo' => 'Efectivo',
                'tarjeta' => 'Tarjeta',
                'tarjeta_debito' => 'Tarjeta Débito',
                'tarjeta_credito' => 'Tarjeta Crédito',
                'transferencia' => 'Transferencia',
            ];
        @endphp

        {{-- ENCABEZADO --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.ventas.detalle', $orden->id) }}"
                    class="btn btn-outline-secondary rounded-3 p-2 d-inline-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 42px; height: 42px;">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h1 class="h4 fw-bold text-denim mb-0 font-titulo">Devolución #{{ $orden->id }}</h1>
                    <p class="text-muted small mb-0">Volvé a armar la venta: quitá productos, agregá otros y cerrá el cobro.</p>
                </div>
            </div>
            <span class="badge rounded-pill px-3 py-2 fs-6 bg-dark text-white">Devuelta</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i> {!! $errors->first() !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.ventas.reabrir', $orden->id) }}" id="formReabrir">
            @csrf
            <div class="row g-4">

                {{-- COLUMNA IZQUIERDA: ITEMS EDITABLES --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-denim mb-0"><i class="bi bi-box-seam me-2"></i>Productos de la venta</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0" id="tablaItems">
                                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                                        <tr>
                                            <th class="py-2">Producto</th>
                                            <th class="py-2" style="min-width: 170px;">Talle</th>
                                            <th class="py-2 text-center" style="width: 90px;">Cantidad</th>
                                            <th class="py-2 text-end" style="width: 110px;">Subtotal</th>
                                            <th class="py-2 text-center" style="width: 40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoItems">
                                        @foreach ($orden->items as $index => $item)
                                            <tr class="item-row">
                                                <td>
                                                    <select name="items[{{ $index }}][producto_id]"
                                                        class="form-select form-select-sm rounded-3 select-producto" required>
                                                        @foreach ($productos as $prod)
                                                            <option value="{{ $prod->id }}" data-precio="{{ $prod->precio_lista_actual }}"
                                                                {{ $prod->id == $item->producto_id ? 'selected' : '' }}>
                                                                {{ $prod->nombre }}{{ !$prod->activo ? ' (pausado)' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="items[{{ $index }}][talle]"
                                                        class="form-select form-select-sm rounded-3 select-talle" required>
                                                        @foreach ($item->producto->talles as $talle)
                                                            <option value="{{ $talle->nombre }}" {{ $talle->nombre == $item->talle ? 'selected' : '' }}>
                                                                Talle {{ $talle->nombre }} (stock {{ $talle->pivot->stock }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="items[{{ $index }}][cantidad]"
                                                        value="{{ $item->cantidad }}" min="1"
                                                        class="form-control form-control-sm text-center fw-bold input-cantidad" required>
                                                </td>
                                                <td class="text-end fw-bold cell-subtotal" style="color:#1a3352;"></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger p-0 quitar-item" title="Quitar">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-3 mt-3" id="agregarItem">
                                <i class="bi bi-plus-lg me-1"></i>Agregar producto
                            </button>
                        </div>
                    </div>
                </div>

                {{-- COLUMNA DERECHA: RESUMEN Y CIERRE --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                            <h5 class="fw-bold text-denim mb-0"><i class="bi bi-person me-2"></i>Cliente</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <p class="mb-1 fw-semibold">{{ $orden->nombre_contacto ?? optional($orden->user)->name ?? 'N/A' }}</p>
                            @if ($orden->telefono_contacto)
                                <p class="mb-0 text-muted small"><i class="bi bi-whatsapp me-1"></i>{{ $orden->telefono_contacto }}</p>
                            @endif
                            <hr>
                            <label class="form-label small fw-bold text-secondary mb-1">Método de pago</label>
                            <select name="metodo_pago" class="form-select rounded-3" required>
                                @foreach ($mapaPago as $valor => $etiqueta)
                                    <option value="{{ $valor }}" {{ $orden->metodo_pago === $valor ? 'selected' : '' }}>
                                        {{ $etiqueta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 bg-white mt-3 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold fs-5 text-denim">Total:</span>
                            <span class="fw-bold fs-3 text-success" id="totalMonto">$ 0</span>
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-info-circle me-1"></i>Al cerrar la venta, la orden vuelve a estado <strong>Pagada</strong> y el stock se descuenta automáticamente.
                        </p>
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" id="btnCerrarVenta">
                            <i class="bi bi-check-circle-fill"></i> Volver a cerrar venta
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productosData = @json($productosData);

            let nextIndex = {{ count($orden->items) }};

            const cuerpoItems = document.getElementById('cuerpoItems');
            const totalMonto = document.getElementById('totalMonto');

            function opcionesTalles(productoId) {
                const prod = productosData[productoId];
                if (!prod) return '';
                return prod.talles.map(t =>
                    `<option value="${t.nombre}" ${t.stock > 0 ? '' : 'disabled'}>Talle ${t.nombre} (stock ${t.stock})</option>`
                ).join('');
            }

            function opcionesProductos() {
                return Object.values(productosData).map(p =>
                    `<option value="${p.id}" data-precio="${p.precio}">${p.nombre}</option>`
                ).join('');
            }

            function stockDeLaFila(row) {
                const selProducto = row.querySelector('.select-producto');
                const selTalle = row.querySelector('.select-talle');
                const prod = productosData[selProducto.value];
                if (!prod || !selTalle.value) return 0;
                const talle = prod.talles.find(t => t.nombre === selTalle.value);
                return talle ? talle.stock : 0;
            }

            function aplicarLimiteStock(row) {
                const input = row.querySelector('.input-cantidad');
                const stock = stockDeLaFila(row);
                input.max = stock;
                if (stock > 0 && parseInt(input.value) > stock) {
                    input.value = stock;
                }
                calcularSubtotales();
            }

            function rellenarTalles(row) {
                const selProducto = row.querySelector('.select-producto');
                const selTalle = row.querySelector('.select-talle');
                const tallePrev = selTalle.value;
                selTalle.innerHTML = opcionesTalles(selProducto.value);
                const habilitado = [...selTalle.options].find(o => !o.disabled);
                if (tallePrev && [...selTalle.options].some(o => o.value === tallePrev && !o.disabled)) {
                    selTalle.value = tallePrev;
                } else if (habilitado) {
                    selTalle.value = habilitado.value;
                }
                aplicarLimiteStock(row);
            }

            function calcularSubtotales() {
                let total = 0;
                cuerpoItems.querySelectorAll('.item-row').forEach(row => {
                    const selProducto = row.querySelector('.select-producto');
                    const cantidad = parseInt(row.querySelector('.input-cantidad').value) || 0;
                    const precio = parseFloat(selProducto.selectedOptions[0]?.dataset.precio) || 0;
                    const subtotal = precio * cantidad;
                    row.querySelector('.cell-subtotal').textContent = '$ ' + subtotal.toLocaleString('es-CL');
                    total += subtotal;
                });
                totalMonto.textContent = '$ ' + total.toLocaleString('es-CL');
            }

            function agregarFila(productoId = null, talleNombre = null, cantidad = 1) {
                const tr = document.createElement('tr');
                tr.className = 'item-row';
                const idx = nextIndex++;
                tr.innerHTML = `
                    <td>
                        <select name="items[${idx}][producto_id]" class="form-select form-select-sm rounded-3 select-producto" required>
                            ${opcionesProductos()}
                        </select>
                    </td>
                    <td>
                        <select name="items[${idx}][talle]" class="form-select form-select-sm rounded-3 select-talle" required>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="number" name="items[${idx}][cantidad]" value="${cantidad}" min="1"
                            class="form-control form-control-sm text-center fw-bold input-cantidad" required>
                    </td>
                    <td class="text-end fw-bold cell-subtotal" style="color:#1a3352;"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 quitar-item" title="Quitar">
                            <i class="bi bi-trash fs-5"></i>
                        </button>
                    </td>
                `;
                cuerpoItems.appendChild(tr);

                if (productoId) {
                    tr.querySelector('.select-producto').value = String(productoId);
                }
                rellenarTalles(tr);
                if (talleNombre) {
                    const existe = [...tr.querySelector('.select-talle').options].some(o => o.value === talleNombre);
                    if (existe) tr.querySelector('.select-talle').value = talleNombre;
                }
            }

            cuerpoItems.addEventListener('change', function(e) {
                if (e.target.classList.contains('select-producto')) {
                    rellenarTalles(e.target.closest('.item-row'));
                } else if (e.target.classList.contains('select-talle')) {
                    aplicarLimiteStock(e.target.closest('.item-row'));
                }
            });

            cuerpoItems.addEventListener('input', function(e) {
                if (e.target.classList.contains('input-cantidad')) {
                    aplicarLimiteStock(e.target.closest('.item-row'));
                }
            });

            cuerpoItems.addEventListener('click', function(e) {
                const btn = e.target.closest('.quitar-item');
                if (btn) {
                    const filas = cuerpoItems.querySelectorAll('.item-row');
                    if (filas.length <= 1) {
                        alert('La venta debe tener al menos un producto.');
                        return;
                    }
                    btn.closest('.item-row').remove();
                    calcularSubtotales();
                }
            });

            document.getElementById('agregarItem').addEventListener('click', function() {
                agregarFila();
            });

            // Inicializar talles y subtotales en filas existentes
            cuerpoItems.querySelectorAll('.item-row').forEach(row => {
                rellenarTalles(row);
            });
            calcularSubtotales();
        });
    </script>
@endsection
