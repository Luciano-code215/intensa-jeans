@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ENCABEZADO --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.ventas.index') }}"
                    class="btn btn-outline-secondary rounded-3 p-2 d-inline-flex align-items-center justify-content-center flex-shrink-0"
                    style="width: 42px; height: 42px;" title="Volver al historial">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h1 class="h4 fw-bold text-denim mb-0 font-titulo">Venta por Mostrador</h1>
                    <p class="text-muted small mb-0 d-none d-sm-block">Seleccioná productos con talle y procesá la venta en caja.</p>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3">

            {{-- COLUMNA IZQUIERDA: PRODUCTOS --}}
            <div class="col-12 col-lg-7 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 bg-white mb-3">

                    <div class="mb-3">
                        <label for="buscarProducto" class="form-label fw-bold text-secondary small">BUSCAR PRODUCTO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="buscarProducto" class="form-control bg-light border-start-0"
                                placeholder="Nombre o código..." autofocus>
                        </div>
                    </div>

                    <div class="row g-2 overflow-auto" style="max-height: 520px;" id="contenedorProductos">
                        @forelse($productos as $producto)
                            @php
                                $sinStock = $producto->stock <= 0;
                                $precioLista = $producto->precio_lista_actual;
                                $tieneLiq = $producto->liquidacion && $producto->porc_liquidacion > 0;
                                $tieneDescEf = $producto->porc_desc_ef > 0;
                            @endphp
                            <div class="col-6 col-sm-4 col-xl-3 item-producto"
                                data-nombre="{{ strtolower($producto->nombre) }}"
                                data-sku="{{ strtolower($producto->sku ?? '') }}">
                                <div class="card h-100 border rounded-3 p-2 p-md-3 position-relative {{ $sinStock ? 'opacity-50 bg-light' : 'hover-shadow' }}">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-1 gap-1">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary font-monospace" style="font-size: 0.6rem;">
                                                    {{ $producto->sku ?? 'S/N' }}
                                                </span>
                                                <span class="badge {{ $sinStock ? 'bg-danger' : 'bg-success' }} bg-opacity-10 text-{{ $sinStock ? 'danger' : 'success' }} flex-shrink-0" style="font-size: 0.65rem;">
                                                    <span id="stock-display-{{ $producto->id }}">{{ $producto->stock }}</span>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.8rem;" title="{{ $producto->nombre }}">{{ $producto->nombre }}</h6>
                                            <div class="fw-bold mb-1" style="font-size: 0.9rem;">
                                                @if ($tieneLiq)
                                                    <span class="text-muted text-decoration-line-through" style="font-size:0.75rem;">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                                                @endif
                                                <span class="text-success">${{ number_format($precioLista, 0, ',', '.') }}</span>
                                            </div>
                                            @if ($tieneDescEf)
                                                <span class="badge bg-warning text-dark bg-opacity-25 fw-semibold" style="font-size:0.6rem;">
                                                    <i class="bi bi-cash"></i> EF {{ $producto->porc_desc_ef }}% OFF
                                                </span>
                                            @endif
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm rounded-3 w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-agregar"
                                            data-id="{{ $producto->id }}" data-nombre="{{ $producto->nombre }}"
                                            data-precio-lista="{{ $precioLista }}"
                                            data-porc-desc-ef="{{ $producto->porc_desc_ef }}"
                                            data-stock="{{ $producto->stock }}"
                                            data-talles='@json($producto->talles->map(fn($t) => ["id" => $t->id, "nombre" => $t->nombre, "stock" => $t->pivot->stock]))'
                                            {{ $sinStock ? 'disabled' : '' }}>
                                            <i class="bi bi-plus-lg"></i>
                                            <span class="d-none d-sm-inline">{{ $sinStock ? 'Agotado' : 'Agregar' }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                No hay productos registrados.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: ORDEN --}}
            <div class="col-12 col-lg-5 col-xl-4">
                <form action="{{ route('admin.ventas.guardarVentaMostrador') }}" method="POST" id="formVenta">
                    @csrf
                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                        <div class="card-header bg-denim text-white p-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-cart3"></i> Orden
                            </h6>
                            <span class="badge bg-light text-dark font-monospace" id="totalItems">0</span>
                        </div>
                        <div class="card-body p-2 p-md-3 overflow-auto" style="min-height: 200px; max-height: 350px;">
                            <table class="table align-middle mb-0 d-none d-md-table" id="tablaOrden">
                                <thead class="text-secondary small font-monospace border-bottom">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center" style="width: 90px;">Cant.</th>
                                        <th class="text-end" style="width: 90px;">Subtotal</th>
                                        <th style="width: 30px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="listaOrden"></tbody>
                            </table>
                            {{-- Mobile list --}}
                            <div id="listaOrdenMobile" class="d-md-none"></div>
                            <div id="ordenVacia" class="text-center py-5 text-muted">
                                <i class="bi bi-bag-plus fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="small mb-0">Seleccioná productos</p>
                            </div>
                        </div>
                        <div class="card-footer bg-light p-3 border-top">
                            <div class="mb-3">
                                <label for="cliente_nombre" class="form-label small fw-bold text-secondary mb-1">Cliente</label>
                                <input type="text" name="cliente_nombre" id="cliente_nombre"
                                    class="form-control form-control-sm rounded-2" placeholder="Nombre (opcional)">
                            </div>
                            <div class="mb-3">
                                <label for="metodo_pago" class="form-label small fw-bold text-secondary mb-1">Método de Pago</label>
                                <select name="metodo_pago" id="metodo_pago" class="form-select form-select-sm rounded-2" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta_debito">Tarjeta Débito</option>
                                    <option value="tarjeta_credito">Tarjeta Crédito</option>
                                    <option value="transferencia">Transferencia / QR</option>
                                </select>
                            </div>
                            <div id="bloqueEfectivo" class="mb-3 p-2 bg-white rounded-3 border">
                                <div class="row g-2 align-items-center">
                                    <div class="col-6">
                                        <label for="pagaCon" class="form-label small text-muted mb-0">Paga con:</label>
                                        <input type="number" id="pagaCon" min="0" step="any"
                                            class="form-control form-control-sm" placeholder="$ 0">
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="small text-muted d-block">Vuelto:</span>
                                        <strong class="text-success fs-6" id="vueltoMonto">$ 0</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-3">
                                <span class="fw-bold fs-5 text-denim">TOTAL:</span>
                                <span class="fw-bold fs-3 text-success" id="totalMonto">$ 0</span>
                            </div>
                            <button type="submit" id="btnCompletarVenta"
                                class="btn btn-success btn-lg w-100 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"
                                disabled>
                                <i class="bi bi-check-circle-fill"></i> Registrar y Cobrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL SELECCIÓN DE TALLE --}}
    <div class="modal fade" id="modalTalle" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pt-3 px-3">
                    <h6 class="fw-bold mb-0" id="modalTalleTitle">Seleccionar Talle</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-3 pb-3" id="modalTalleBody">
                    {{-- Se llena con JS --}}
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            cursor: pointer;
        }
        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important;
        }
        .bg-denim { background-color: #1a3352; }
        .text-denim { color: #1a3352; }
        .item-carrito-mobile {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }
        .item-carrito-mobile:last-child { border-bottom: none; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let carrito = [];
            let modalTalleInstance = null;

            const buscador = document.getElementById('buscarProducto');
            const itemsProductos = document.querySelectorAll('.item-producto');
            const listaOrden = document.getElementById('listaOrden');
            const listaOrdenMobile = document.getElementById('listaOrdenMobile');
            const ordenVacia = document.getElementById('ordenVacia');
            const totalMonto = document.getElementById('totalMonto');
            const totalItems = document.getElementById('totalItems');
            const btnCompletarVenta = document.getElementById('btnCompletarVenta');
            const pagaConInput = document.getElementById('pagaCon');
            const vueltoMonto = document.getElementById('vueltoMonto');
            const metodoPagoSelect = document.getElementById('metodo_pago');
            const bloqueEfectivo = document.getElementById('bloqueEfectivo');
            const modalTalle = document.getElementById('modalTalle');

            if (modalTalle) {
                modalTalleInstance = new bootstrap.Modal(modalTalle);
            }

            // Búsqueda
            buscador.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                itemsProductos.forEach(item => {
                    const nombre = item.getAttribute('data-nombre');
                    const sku = item.getAttribute('data-sku');
                    item.style.display = (nombre.includes(q) || sku.includes(q)) ? '' : 'none';
                });
            });

            // Agregar producto
            document.querySelectorAll('.btn-agregar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nombre = this.getAttribute('data-nombre');
                    const precioLista = parseFloat(this.getAttribute('data-precio-lista'));
                    const porcDescEf = parseInt(this.getAttribute('data-porc-desc-ef')) || 0;
                    const stockMax = parseInt(this.getAttribute('data-stock'));
                    let talles = [];
                    try {
                        talles = JSON.parse(this.getAttribute('data-talles') || '[]');
                    } catch(e) {}

                    if (talles.length > 0) {
                        mostrarModalTalle(id, nombre, precioLista, porcDescEf, talles);
                    } else {
                        agregarAlCarrito(id, nombre, precioLista, porcDescEf, stockMax, null);
                    }
                });
            });

            function mostrarModalTalle(productoId, productoNombre, precioLista, porcDescEf, talles) {
                const title = document.getElementById('modalTalleTitle');
                const body = document.getElementById('modalTalleBody');
                title.textContent = productoNombre;

                let html = '';
                talles.forEach(t => {
                    const disponible = parseInt(t.stock) || 0;
                    const agotado = disponible <= 0;
                    html += `
                        <div class="d-flex align-items-center justify-content-between border rounded-3 p-2 mb-2 ${agotado ? 'opacity-50' : ''}">
                            <div>
                                <span class="fw-semibold">${t.nombre}</span>
                                <small class="text-muted d-block">Stock: ${disponible}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" class="form-control form-control-sm text-center"
                                    style="width: 55px;" min="1" max="${disponible}" value="1"
                                    id="talle-cant-${t.id}" ${agotado ? 'disabled' : ''}>
                                <button type="button"
                                    class="btn btn-sm ${agotado ? 'btn-outline-secondary' : 'btn-outline-primary'} rounded-3 agregar-talle-btn"
                                    data-producto-id="${productoId}"
                                    data-producto-nombre="${productoNombre}"
                                    data-precio="${precioLista}"
                                    data-porc-desc-ef="${porcDescEf}"
                                    data-talle-id="${t.id}"
                                    data-talle-nombre="${t.nombre}"
                                    data-talle-stock="${disponible}"
                                    ${agotado ? 'disabled' : ''}>
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                body.innerHTML = html;

                body.querySelectorAll('.agregar-talle-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const pid = this.getAttribute('data-producto-id');
                        const pnombre = this.getAttribute('data-producto-nombre');
                        const pprecio = parseFloat(this.getAttribute('data-precio'));
                        const porcDescEf = parseInt(this.getAttribute('data-porc-desc-ef')) || 0;
                        const talleId = this.getAttribute('data-talle-id');
                        const talleNombre = this.getAttribute('data-talle-nombre');
                        const talleStock = parseInt(this.getAttribute('data-talle-stock'));
                        const cantInput = document.getElementById(`talle-cant-${talleId}`);
                        const cant = parseInt(cantInput?.value) || 1;

                        agregarAlCarrito(pid, pnombre, pprecio, porcDescEf, talleStock, {
                            id: talleId,
                            nombre: talleNombre
                        }, cant);

                        if (modalTalleInstance) modalTalleInstance.hide();
                    });
                });

                modalTalleInstance.show();
            }

            function agregarAlCarrito(id, nombre, precioLista, porcDescEf, stockMax, talle, cantidad) {
                cantidad = cantidad || 1;

                const existe = carrito.find(p => p.id === id && p.talle?.id === (talle?.id ?? null));

                if (existe) {
                    if (existe.cantidad + cantidad <= stockMax) {
                        existe.cantidad += cantidad;
                    } else {
                        const maxPosible = stockMax - existe.cantidad;
                        if (maxPosible > 0) {
                            existe.cantidad += maxPosible;
                        } else {
                            alert('Stock máximo alcanzado.');
                        }
                    }
                } else {
                    const precioEf = porcDescEf > 0 ? precioLista * (1 - porcDescEf / 100) : precioLista;
                    carrito.push({
                        id,
                        nombre,
                        precio: precioLista,
                        precioEf,
                        porcDescEf,
                        cantidad,
                        stockMax,
                        talle: talle || null
                    });
                }

                renderizarOrden();
            }

            function renderizarOrden() {
                listaOrden.innerHTML = '';
                listaOrdenMobile.innerHTML = '';

                if (carrito.length === 0) {
                    ordenVacia.style.display = 'block';
                    btnCompletarVenta.disabled = true;
                    totalMonto.textContent = '$ 0';
                    totalItems.textContent = '0';
                    vueltoMonto.textContent = '$ 0';
                    pagaConInput.value = '';
                    return;
                }

                ordenVacia.style.display = 'none';
                btnCompletarVenta.disabled = false;

                let totalLista = 0;
                let totalEfectivo = 0;
                let cantidadTotal = 0;

                carrito.forEach((prod, index) => {
                    const subtotal = prod.precio * prod.cantidad;
                    const subtotalEf = prod.precioEf * prod.cantidad;
                    totalLista += subtotal;
                    totalEfectivo += subtotalEf;
                    cantidadTotal += prod.cantidad;
                    const talleTexto = prod.talle ? ` (${prod.talle.nombre})` : '';

                    const ahorroItem = subtotal - subtotalEf;

                    // Desktop row
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 120px;" title="${prod.nombre}${talleTexto}">
                                ${prod.nombre}${talleTexto ? `<br><small class="text-muted">${talleTexto.replace(/[()]/g,'')}</small>` : ''}
                            </div>
                            <small class="text-muted">$${prod.precio.toLocaleString('es-CL')} c/u</small>
                            ${ahorroItem > 0 ? `<br><small class="text-success fw-semibold">EF: $${prod.precioEf.toLocaleString('es-CL')}</small>` : ''}
                            <input type="hidden" name="productos[${index}][id]" value="${prod.id}">
                            <input type="hidden" name="productos[${index}][cantidad]" value="${prod.cantidad}">
                            <input type="hidden" name="productos[${index}][talle_id]" value="${prod.talle?.id ?? ''}">
                            <input type="hidden" name="productos[${index}][talle_nombre]" value="${prod.talle?.nombre ?? ''}">
                        </td>
                        <td class="text-center">
                            <div class="input-group input-group-sm flex-nowrap justify-content-center">
                                <button type="button" class="btn btn-outline-secondary p-0 px-1 btn-restar" data-index="${index}">-</button>
                                <span class="fw-bold mx-1">${prod.cantidad}</span>
                                <button type="button" class="btn btn-outline-secondary p-0 px-1 btn-sumar" data-index="${index}">+</button>
                            </div>
                        </td>
                        <td class="text-end fw-bold text-dark">$${subtotal.toLocaleString('es-CL')}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-link text-danger p-0 btn-eliminar" data-index="${index}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                    listaOrden.appendChild(tr);

                    // Mobile item
                    const mobileDiv = document.createElement('div');
                    mobileDiv.className = 'item-carrito-mobile';
                    mobileDiv.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1 me-2">
                                <span class="fw-semibold small">${prod.nombre}</span>
                                ${prod.talle ? `<span class="badge bg-secondary bg-opacity-10 text-secondary ms-1">${prod.talle.nombre}</span>` : ''}
                                <div class="text-muted small">$${prod.precio.toLocaleString('es-CL')} c/u${ahorroItem > 0 ? ` / EF: $${prod.precioEf.toLocaleString('es-CL')}` : ''}</div>
                            </div>
                            <button type="button" class="btn btn-link text-danger p-0 btn-eliminar flex-shrink-0" data-index="${index}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm p-0 px-1 btn-restar" data-index="${index}">-</button>
                                <span class="fw-bold">${prod.cantidad}</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm p-0 px-1 btn-sumar" data-index="${index}">+</button>
                            </div>
                            <span class="fw-bold">$${subtotal.toLocaleString('es-CL')}</span>
                        </div>
                        <input type="hidden" name="productos[${index}][id]" value="${prod.id}">
                        <input type="hidden" name="productos[${index}][cantidad]" value="${prod.cantidad}">
                        <input type="hidden" name="productos[${index}][talle_id]" value="${prod.talle?.id ?? ''}">
                        <input type="hidden" name="productos[${index}][talle_nombre]" value="${prod.talle?.nombre ?? ''}">
                    `;
                    listaOrdenMobile.appendChild(mobileDiv);
                });

                const ahorro = totalLista - totalEfectivo;

                // Mostrar total listado y efectivo
                let totalHtml = `$ ${totalLista.toLocaleString('es-CL')}`;
                if (ahorro > 0) {
                    totalHtml = `<span style="text-decoration:line-through;font-size:1rem;color:#999;">$ ${totalLista.toLocaleString('es-CL')}</span> <span class="text-success">$ ${totalEfectivo.toLocaleString('es-CL')}</span>`;
                }
                totalMonto.innerHTML = totalHtml;
                totalItems.textContent = cantidadTotal;
                calcularVuelto(totalEfectivo);
                actualizarEventos();
            }

            function actualizarEventos() {
                document.querySelectorAll('.btn-sumar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        const item = carrito[idx];
                        if (item.cantidad < item.stockMax) {
                            item.cantidad++;
                            renderizarOrden();
                        } else {
                            alert('Stock máximo: ' + item.stockMax);
                        }
                    });
                });
                document.querySelectorAll('.btn-restar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        if (carrito[idx].cantidad > 1) {
                            carrito[idx].cantidad--;
                        } else {
                            carrito.splice(idx, 1);
                        }
                        renderizarOrden();
                    });
                });
                document.querySelectorAll('.btn-eliminar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        carrito.splice(idx, 1);
                        renderizarOrden();
                    });
                });
            }

            function calcularVuelto(totalVenta) {
                const pago = parseFloat(pagaConInput.value) || 0;
                const vuelto = pago - totalVenta;
                if (pago > 0 && vuelto >= 0) {
                    vueltoMonto.textContent = `$ ${vuelto.toLocaleString('es-CL')}`;
                    vueltoMonto.className = 'text-success fs-6 fw-bold';
                } else if (pago > 0 && vuelto < 0) {
                    vueltoMonto.textContent = 'Insuficiente';
                    vueltoMonto.className = 'text-danger fs-6 small';
                } else {
                    vueltoMonto.textContent = '$ 0';
                    vueltoMonto.className = 'text-success fs-6';
                }
            }

            pagaConInput.addEventListener('input', function() {
                const total = carrito.reduce((s, p) => s + p.precioEf * p.cantidad, 0);
                calcularVuelto(total);
            });

            metodoPagoSelect.addEventListener('change', function() {
                bloqueEfectivo.style.display = this.value === 'efectivo' ? 'block' : 'none';
            });
        });
    </script>
@endsection