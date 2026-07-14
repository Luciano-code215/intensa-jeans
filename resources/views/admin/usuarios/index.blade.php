@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ENCABEZADO --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-dark bg-opacity-10 text-dark rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px;">
                    <i class="bi bi-people-fill fs-4 text-denim"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Gestión de Usuarios</h1>
                    <p class="text-muted small mb-0 d-none d-sm-block">Administrá las cuentas del sistema, permisos de
                        administrador y seguridad.</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- BUSCADOR GLOBAL POR TECLADO (Izquierda) --}}
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-center">
                    <h6 class="fw-bold text-denim d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-search text-secondary"></i> Buscador de Cuentas
                    </h6>
                    <form action="#" method="GET">
                        <div class="input-group border rounded-3 bg-light bg-opacity-50 px-2 align-items-center">
                            <span class="text-muted"><i class="bi bi-keyboard"></i></span>
                            <input type="text" class="form-control border-0 bg-transparent py-2.5"
                                placeholder="Escribí el nombre, ID o correo electrónico para buscar..."
                                name="buscar_usuario">
                        </div>
                    </form>
                </div>
            </div>

            {{-- CAMBIAR CONTRASEÑA ADMIN LOGUEADO (Derecha) --}}
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold text-denim d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-shield-lock-fill text-oro"></i> Mi Seguridad (Tu Cuenta)
                    </h6>
                    <form action="#" method="POST" class="row g-2">
                        @csrf
                        <div class="col-8">
                            <input type="password"
                                class="form-control rounded-3 py-2 border-light-subtle bg-light bg-opacity-25"
                                placeholder="Nueva contraseña" name="password" required style="font-size: 0.9rem;">
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-dark w-100 py-2 rounded-3 fw-bold border-0 bg-denim"
                                style="font-size: 0.85rem;">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TABLA 1: CLIENTES REGISTRADOS --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-person-lines-fill text-primary"></i> Clientes Registrados
                </h5>
            </div>
            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3 ps-2">ID</th>
                            <th class="py-3">Nombre</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Estado de Cuenta</th>
                            <th class="py-3 text-end" style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        {{-- Cliente Activo --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-bold ps-2">#USR-841</td>
                            <td class="fw-bold text-dark text-capitalize">luciano</td>
                            <td class="text-secondary">luciano@gmail.com</td>
                            <td>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                    style="font-size: 0.8rem;">
                                    Activa
                                </span>
                            </td>
                            <td class="text-end">
                                <button
                                    class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                    style="font-size: 0.85rem;">
                                    <i class="bi bi-person-x-fill"></i> Suspender
                                </button>
                            </td>
                        </tr>
                        {{-- Cliente Suspendido --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-bold ps-2">#USR-512</td>
                            <td class="fw-bold text-dark text-capitalize">marta gomez</td>
                            <td class="text-secondary">marta.g@hotmail.com</td>
                            <td>
                                <span
                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                    style="font-size: 0.8rem;">
                                    Suspendida
                                </span>
                            </td>
                            <td class="text-end">
                                <button
                                    class="btn btn-success btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                    style="font-size: 0.85rem;">
                                    <i class="bi bi-person-check-fill"></i> Activar Cuenta
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABLA 2: ADMINISTRADORES DEL SITIO --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-denim d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-shield-lock text-oro"></i> Administradores del Sistema
                </h5>
            </div>
            <div class="table-responsive px-4 pb-4">
                <table class="table align-middle mb-0 text-nowrap">
                    <thead class="table-light text-secondary small text-uppercase font-monospace border-bottom">
                        <tr>
                            <th class="py-3 ps-2">ID Admin</th>
                            <th class="py-3">Nombre</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Rol Interno</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3 text-end" style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        {{-- Admin Logueado (Tú) --}}
                        <tr class="border-bottom border-light bg-light bg-opacity-40">
                            <td class="text-muted font-monospace fw-bold ps-2">#ADM-001</td>
                            <td class="fw-bold text-dark text-capitalize">
                                {{ Auth::user()->name ?? 'Admin Principal' }}
                                <span class="badge bg-primary rounded-pill px-2 py-0.5 ms-1 fw-semibold text-white"
                                    style="font-size: 0.65rem;">Vos</span>
                            </td>
                            <td class="text-secondary">{{ Auth::user()->email ?? 'admin@intensajeans.com' }}</td>
                            <td><span class="text-muted small fw-medium">Super Admin</span></td>
                            <td>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                    style="font-size: 0.8rem;">
                                    Activo
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-1.5 text-muted" disabled
                                    style="font-size: 0.85rem; cursor: not-allowed;">
                                    <i class="bi bi-slash-circle"></i> Inmune
                                </button>
                            </td>
                        </tr>
                        {{-- Otro Admin --}}
                        <tr class="border-bottom border-light">
                            <td class="text-muted font-monospace fw-bold ps-2">#ADM-004</td>
                            <td class="fw-bold text-dark text-capitalize">Soporte Intensa</td>
                            <td class="text-secondary">soporte@intensajeans.com</td>
                            <td><span class="text-muted small fw-medium">Moderador</span></td>
                            <td>
                                <span
                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                    style="font-size: 0.8rem;">
                                    Activo
                                </span>
                            </td>
                            <td class="text-end">
                                <button
                                    class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                    style="font-size: 0.85rem;">
                                    <i class="bi bi-person-x-fill"></i> Suspender
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <style>
        .form-control:focus {
            border-color: #1a3352 !important;
            box-shadow: none !important;
        }
    </style>
@endsection
