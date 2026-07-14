@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <i class="bi bi-receipt-cutoff fs-4 text-success"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Historial de Ventas y Pedidos</h1>
                    <p class="text-muted small mb-0 d-none d-sm-block">Monitoreo de ventas de la tienda en tiempo real.</p>
                </div>
            </div>
        </div>

        {{-- 1. TARJETAS DE MÉTRICAS SUPERIORES (Estilo tu referencia con iconos de fondo) --}}
        <div class="row g-3 mb-4">
            <!-- Pendientes de Envío -->
            <div class="col-12 col-md-5 col-lg-4">
                <div class="p-4 rounded-4 position-relative overflow-hidden shadow-sm d-flex flex-column justify-content-between"
                    style="background-color: #ffc107; color: #1a3352; min-height: 160px;">
                    <div>
                        <span class="text-uppercase fw-bold tracking-wider lh-1"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">Pendientes de Envío</span>
                        <h1 class="fw-bold my-1 display-5 lh-1">2</h1>
                    </div>
                    <p class="small mb-0 opacity-75 fw-medium" style="font-size: 0.85rem; max-width: 80%;">Paquetes por
                        armar o despachar</p>
                    <!-- Icono sutil posicionado a la derecha -->
                    <i class="bi bi-truck position-absolute end-0 bottom-0 mb-3 me-3 opacity-25"
                        style="font-size: 3.8rem; line-height: 1; pointer-events: none; color: #1a3352;"></i>
                </div>
            </div>

            <!-- Pedidos Completados -->
            <div class="col-12 col-md-5 col-lg-4">
                <div class="p-4 rounded-4 position-relative overflow-hidden shadow-sm text-white d-flex flex-column justify-content-between"
                    style="background-color: #6c757d; min-height: 160px;">
                    <div>
                        <span class="text-uppercase fw-bold tracking-wider lh-1"
                            style="font-size: 0.8rem; letter-spacing: 0.5px; opacity: 0.9;">Pedidos Completados</span>
                        <h1 class="fw-bold my-1 display-5 lh-1">5</h1>
                    </div>
                    <p class="small mb-0 opacity-75 fw-medium" style="font-size: 0.85rem; max-width: 80%;">Transacciones
                        finalizadas con éxito</p>
                    <!-- Icono de check circular sutil -->
                    <i class="bi bi-check-circle position-absolute end-0 bottom-0 mb-3 me-3 opacity-25"
                        style="font-size: 3.8rem; line-height: 1; pointer-events: none; color: #ffffff;"></i>
                </div>
            </div>
        </div>

        {{-- 2. BUSCADOR Y FILTROS AVANZADOS (Aquí sumamos tu buscador por teclado) --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <h6 class="fw-bold text-denim d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-funnel text-secondary"></i> Buscador y Filtros Avanzados
            </h6>

            <form action="#" method="GET" class="row g-3">
                <!-- BUSCADOR POR TECLADO -->
                <div class="col-12 col-md-6">
                    <label class="form-label text-secondary small fw-bold mb-1">Buscar por palabra clave</label>
                    <div class="input-group border rounded-3 bg-light bg-opacity-50 px-2 align-items-center">
                        <span class="text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-0 bg-transparent py-2"
                            placeholder="Escribí el N° de pedido, nombre del cliente..." name="buscar_teclado">
                    </div>
                </div>

                <!-- FILTRAR POR ESTADO -->
                <div class="col-12 col-md-6">
                    <label class="form-label text-secondary small fw-bold mb-1">Filtrar por Estado</label>
                    <select class="form-select border rounded-3 py-2 text-secondary bg-light bg-opacity-20" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="entregada">Entregada</option>
                        <option value="pendiente">Pendiente de Envío</option>
                        <option value="esperando-pago">Esperando Pago</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- 3. TABLA: ÚLTIMAS TRANSACCIONES REGISTRADAS --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-list-task text-secondary"></i> Últimas Transacciones Registradas
                </h5>
            </div>

            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3">N° Pedido</th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Total</th>
                            <th class="py-3" style="width: 220px;">Estado</th>
                            <th class="py-3 text-end" style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">

                        {{-- Fila 1 --}}
                        <tr class="border-bottom border-light">
                            <td class="fw-bold text-dark">#0018</td>
                            <td class="text-capitalize text-secondary">luciano</td>
                            <td class="text-muted">21/06/2026</td>
                            <td class="fw-bold text-dark">$55.800</td>
                            <td>
                                <select
                                    class="form-select form-select-sm rounded-3 fw-medium bg-light bg-opacity-70 text-dark border-0 py-1.5 px-3">
                                    <option value="entregada" selected>Entregada</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="esperando">Esperando Pago</option>
                                </select>
                            </td>
                            <td class="text-end">
                                <a href="#"
                                    class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.85rem;">
                                    <i class="bi bi-eye"></i> Detalle
                                </a>
                            </td>
                        </tr>

                        {{-- Fila 2 --}}
                        <tr class="border-bottom border-light">
                            <td class="fw-bold text-dark">#0017</td>
                            <td class="text-capitalize text-secondary">luciano</td>
                            <td class="text-muted">17/06/2026</td>
                            <td class="fw-bold text-dark">$18.900</td>
                            <td>
                                <select
                                    class="form-select form-select-sm rounded-3 fw-medium bg-light bg-opacity-70 text-dark border-0 py-1.5 px-3">
                                    <option value="entregada" selected>Entregada</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="esperando">Esperando Pago</option>
                                </select>
                            </td>
                            <td class="text-end">
                                <a href="#"
                                    class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.85rem;">
                                    <i class="bi bi-eye"></i> Detalle
                                </a>
                            </td>
                        </tr>

                        {{-- Fila 3 --}}
                        <tr class="border-bottom border-light">
                            <td class="fw-bold text-dark">#0015</td>
                            <td class="text-capitalize text-secondary">luciano</td>
                            <td class="text-muted">17/06/2026</td>
                            <td class="fw-bold text-dark">$130.000</td>
                            <td>
                                {{-- Borde azul resaltado cuando está en espera de pago como tu mockup --}}
                                <select
                                    class="form-select form-select-sm rounded-3 fw-medium bg-white text-primary py-1.5 px-3"
                                    style="border-color: #86b7fe !important;">
                                    <option value="entregada">Entregada</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="esperando" selected>Esperando Pago</option>
                                </select>
                            </td>
                            <td class="text-end">
                                <a href="#"
                                    class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1"
                                    style="font-size: 0.85rem;">
                                    <i class="bi bi-eye"></i> Detalle
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <style>
        .form-control:focus,
        .form-select:focus {
            box-shadow: none !important;
        }
    </style>
@endsection
