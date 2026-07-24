@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.ventas.index') }}"
                    class="btn btn-outline-secondary rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 42px; height: 42px;" title="Volver al historial">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Registrar Venta por Mostrador</h1>
                    <p class="text-muted small mb-0">Selecciona productos, ajusta cantidades y procesa la venta en caja.</p>
                </div>
            </div>
        </div>

        {{-- ALERTA DE MENSAJES --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- COLUMNA IZQUIERDA: BUSCADOR Y CATÁLOGO DE PRODUCTOS --}}
            <div class="col-12 col-lg-7 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">

                    {{-- Buscador en tiempo real --}}
                    <div class="mb-4">
                        <label for="buscarProducto" class="form-label fw-bold text-secondary small">BUSCAR PRODUCTO</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i
                                    class="bi bi-search"></i></span>
                            <input type="text" id="buscarProducto" class="form-control bg-light border-start-0 fs-6"
                                placeholder="Escribe el nombre del producto o el SKU/Código..." autofocus>
                        </div>
                    </div>

                    {{-- Lista / Grilla de Productos --}}
                    <div class="row g-3 overflow-auto pe-1" style="max-height: 520px;" id="contenedorProductos">
                        @forelse($productos as $producto)
                            @php
                                $sinStock = $producto->stock <= 0;
                            @endphp
                            <div class="col-12 col-md-6 col-xl-4 item-producto"
                                data-nombre="{{ strtolower($producto->nombre) }}"
                                data-sku="{{ strtolower($producto->sku ?? '') }}">

                                <div
                                    class="card h-100 border rounded-3 p-3 position-relative transition-all {{ $sinStock ? 'opacity-50 bg-light' : 'hover-shadow' }}">
                                    <div class="d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary font-monospace small">
                                                    SKU: {{ $producto->sku ?? 'S/N' }}
                                                </span>
                                                <span
                                                    class="badge {{ $sinStock ? 'bg-danger' : 'bg-success' }} bg-opacity-10 text-{{ $sinStock ? 'danger' : 'success' }} fw-bold">
                                                    Stock: <span
                                                        id="stock-display-{{ $producto->id }}">{{ $producto->stock }}</span>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1 text-truncate"
                                                title="{{ $producto->nombre }}">{{ $producto->nombre }}</h6>
                                            <div class="fw-bold text-success fs-5 mb-3">
                                                ${{ number_format($producto->precio, 0, ',', '.') }}</div>
                                        </div>

                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm rounded-3 w-100 fw-bold d-flex align-items-center justify-content-center gap-1 btn-agregar"
                                            data-id="{{ $producto->id }}" data-nombre="{{ $producto->nombre }}"
                                            data-precio="{{ $producto->precio }}" data-stock="{{ $producto->stock }}"
                                            {{ $sinStock ? 'disabled' : '' }}>
                                            <i class="bi bi-plus-lg"></i> {{ $sinStock ? 'Agotado' : 'Agregar' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                No hay productos registrados en el sistema.
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

            {{-- COLUMNA DERECHA: ORDEN DE VENTA (CARRITO DE CAJA) --}}
            <div class="col-12 col-lg-5 col-xl-4">
                <form action="{{ route('admin.ventas.guardarVentaMostrador') }}" method="POST" id="formVenta">
                    @csrf

                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">

                        {{-- Encabezado del ticket --}}
                        <div class="card-header bg-denim text-white p-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-cart3"></i> Orden de Venta
                            </h6>
                            <span class="badge bg-light text-dark font-monospace" id="totalItems">0 ítems</span>
                        </div>

                        {{-- Lista de Ítems en la Orden --}}
                        <div class="card-body p-3 overflow-auto" style="min-height: 250px; max-height: 350px;">
                            <table class="table align-middle mb-0" id="tablaOrden">
                                <thead class="text-secondary small font-monospace border-bottom">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center" style="width: 100px;">Cant.</th>
                                        <th class="text-end">Subtotal</th>
                                        <th style="width: 30px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="listaOrden">
                                    {{-- Se llena dinámicamente con JS --}}
                                </tbody>
                            </table>

                            <div id="ordenVacia" class="text-center py-5 text-muted">
                                <i class="bi bi-bag-plus fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                <p class="small mb-0">Selecciona productos para armar la orden</p>
                            </div>
                        </div>

                        {{-- Resumen de Pago y Totales --}}
                        <div class="card-footer bg-light p-3 border-top">

                            {{-- Selección opcional de Cliente / Nombre --}}
                            <div class="mb-3">
                                <label for="cliente_nombre" class="form-label small fw-bold text-secondary mb-1">Nombre del
                                    Cliente (Opcional)</label>
                                <input type="text" name="cliente_nombre" id="cliente_nombre"
                                    class="form-control form-control-sm rounded-2" placeholder="Cliente Ocasional / Final">
                            </div>

                            {{-- Método de Pago --}}
                            <div class="mb-3">
                                <label for="metodo_pago" class="form-label small fw-bold text-secondary mb-1">Método de
                                    Pago</label>
                                <select name="metodo_pago" id="metodo_pago" class="form-select form-select-sm rounded-2"
                                    required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta_debito">Tarjeta de Débito</option>
                                    <option value="tarjeta_credito">Tarjeta de Crédito</option>
                                    <option value="transferencia">Transferencia / QR</option>
                                </select>
                            </div>

                            {{-- Calculadora de Cambio para Efectivo --}}
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

                            {{-- Total Final --}}
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-3">
                                <span class="fw-bold fs-5 text-denim">TOTAL:</span>
                                <span class="fw-bold fs-3 text-success" id="totalMonto">$ 0</span>
                            </div>

                            {{-- Botón Procesar Venta --}}
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

    <style>
        .hover-shadow {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        }

        .bg-denim {
            background-color: #1a3352;
        }

        .text-denim {
            color: #1a3352;
        }
    </style>

    {{-- LÓGICA DE INTERACCIÓN Y CONTROL DE STOCK (JAVASCRIPT) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let carrito = [];

            const buscador = document.getElementById('buscarProducto');
            const itemsProductos = document.querySelectorAll('.item-producto');
            const listaOrden = document.getElementById('listaOrden');
            const ordenVacia = document.getElementById('ordenVacia');
            const totalMonto = document.getElementById('totalMonto');
            const totalItems = document.getElementById('totalItems');
            const btnCompletarVenta = document.getElementById('btnCompletarVenta');
            const pagaConInput = document.getElementById('pagaCon');
            const vueltoMonto = document.getElementById('vueltoMonto');
            const metodoPagoSelect = document.getElementById('metodo_pago');
            const bloqueEfectivo = document.getElementById('bloqueEfectivo');

            // 1. BUSCADOR EN TIEMPO REAL (SKU / Nombre)
            buscador.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                itemsProductos.forEach(item => {
                    const nombre = item.getAttribute('data-nombre');
                    const sku = item.getAttribute('data-sku');
                    if (nombre.includes(query) || sku.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // 2. AGREGAR PRODUCTO AL CARRITO
            document.querySelectorAll('.btn-agregar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nombre = this.getAttribute('data-nombre');
                    const precio = parseFloat(this.getAttribute('data-precio'));
                    const stockMax = parseInt(this.getAttribute('data-stock'));

                    const existe = carrito.find(p => p.id === id);

                    if (existe) {
                        if (existe.cantidad < stockMax) {
                            existe.cantidad++;
                        } else {
                            alert(
                                `No puedes agregar más unidades. El stock máximo disponible es ${stockMax}.`
                                );
                            return;
                        }
                    } else {
                        carrito.push({
                            id: id,
                            nombre: nombre,
                            precio: precio,
                            cantidad: 1,
                            stockMax: stockMax
                        });
                    }

                    renderizarOrden();
                });
            });

            // 3. RENDERIZAR TABLA DE LA ORDEN
            function renderizarOrden() {
                listaOrden.innerHTML = '';

                if (carrito.length === 0) {
                    ordenVacia.style.display = 'block';
                    btnCompletarVenta.disabled = true;
                    totalMonto.textContent = '$ 0';
                    totalItems.textContent = '0 ítems';
                    vueltoMonto.textContent = '$ 0';
                    pagaConInput.value = '';
                    return;
                }

                ordenVacia.style.display = 'none';
                btnCompletarVenta.disabled = false;

                let total = 0;
                let cantidadTotalItems = 0;

                carrito.forEach((prod, index) => {
                    const subtotal = prod.precio * prod.cantidad;
                    total += subtotal;
                    cantidadTotalItems += prod.cantidad;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td>
                    <div class="fw-bold text-dark text-truncate" style="max-width: 130px;" title="${prod.nombre}">${prod.nombre}</div>
                    <small class="text-muted">$${prod.precio.toLocaleString('es-CL')}</small>
                    <input type="hidden" name="productos[${index}][id]" value="${prod.id}">
                    <input type="hidden" name="productos[${index}][cantidad]" value="${prod.cantidad}">
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm">
                        <button type="button" class="btn btn-outline-secondary p-1 btn-restar" data-index="${index}">-</button>
                        <input type="text" class="form-control text-center px-1 fw-bold" value="${prod.cantidad}" readonly style="width: 35px;">
                        <button type="button" class="btn btn-outline-secondary p-1 btn-sumar" data-index="${index}">+</button>
                    </div>
                </td>
                <td class="text-end fw-bold text-dark">$${subtotal.toLocaleString('es-CL')}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-link text-danger p-0 ms-1 btn-eliminar" data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
                    listaOrden.appendChild(tr);
                });

                totalMonto.textContent = `$ ${total.toLocaleString('es-CL')}`;
                totalItems.textContent = `${cantidadTotalItems} ítems`;

                calcularVuelto(total);
                actualizarEventosControles();
            }

            // 4. MANTENER LÓGICA DE BOTONES (+) (-) (Eliminar) EN LA TABLA
            function actualizarEventosControles() {
                document.querySelectorAll('.btn-sumar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.getAttribute('data-index');
                        const item = carrito[index];
                        if (item.cantidad < item.stockMax) {
                            item.cantidad++;
                            renderizarOrden();
                        } else {
                            alert(`Stock máximo alcanzado (${item.stockMax} unidades).`);
                        }
                    });
                });

                document.querySelectorAll('.btn-restar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.getAttribute('data-index');
                        if (carrito[index].cantidad > 1) {
                            carrito[index].cantidad--;
                        } else {
                            carrito.splice(index, 1);
                        }
                        renderizarOrden();
                    });
                });

                document.querySelectorAll('.btn-eliminar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const index = this.getAttribute('data-index');
                        carrito.splice(index, 1);
                        renderizarOrden();
                    });
                });
            }

            // 5. CÁLCULO DE VUELTO EN CASO DE EFECTIVO
            function calcularVuelto(totalVenta) {
                const pago = parseFloat(pagaConInput.value) || 0;
                const vuelto = pago - totalVenta;
                if (pago > 0 && vuelto >= 0) {
                    vueltoMonto.textContent = `$ ${vuelto.toLocaleString('es-CL')}`;
                    vueltoMonto.className = 'text-success fs-6 fw-bold';
                } else if (pago > 0 && vuelto < 0) {
                    vueltoMonto.textContent = 'Monto insuficiente';
                    vueltoMonto.className = 'text-danger fs-6 small';
                } else {
                    vueltoMonto.textContent = '$ 0';
                    vueltoMonto.className = 'text-success fs-6';
                }
            }

            pagaConInput.addEventListener('input', function() {
                const total = carrito.reduce((sum, p) => sum + (p.precio * p.cantidad), 0);
                calcularVuelto(total);
            });

            metodoPagoSelect.addEventListener('change', function() {
                if (this.value === 'efectivo') {
                    bloqueEfectivo.style.display = 'block';
                } else {
                    bloqueEfectivo.style.display = 'none';
                }
            });
        });
    </script>
@endsection
