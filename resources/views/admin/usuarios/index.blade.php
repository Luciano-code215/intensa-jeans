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
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-center">
                    <h6 class="fw-bold text-denim d-flex align-items-center gap-2 mb-3" style="color: #1a3352;">
                        <i class="bi bi-search text-secondary"></i> Buscador de Cuentas
                    </h6>

                    {{-- Formulario nativo por método GET --}}
                    <form action="{{ route('admin.usuarios.index') }}" method="GET">
                        <div class="input-group border rounded-3 bg-light bg-opacity-50 px-2 align-items-center">
                            <span class="text-muted"><i class="bi bi-keyboard"></i></span>

                            {{-- Al presionar ENTER dentro de este input, el navegador enviará el formulario de forma automática --}}
                            <input type="text" class="form-control border-0 bg-transparent py-2.5 shadow-none"
                                placeholder="Escribí el nombre, ID, email o rol y presioná Enter..." name="buscar"
                                value="{{ request('buscar') }}" id="input-buscar-cuentas">
                        </div>
                    </form>
                </div>
            </div>

            {{-- REGISTRAR NUEVO ADMINISTRADOR (Derecha) --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold text-denim d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-person-plus-fill text-oro"></i> Registrar Nuevo Administrador
                    </h6>
                    <form action="{{ route('admin.usuarios.store-admin') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-12 col-sm-4">
                            <input type="text"
                                class="form-control rounded-3 py-2 border-light-subtle bg-light bg-opacity-25"
                                placeholder="Nombre completo" name="name" required style="font-size: 0.9rem;">
                        </div>
                        <div class="col-12 col-sm-4">
                            <input type="email"
                                class="form-control rounded-3 py-2 border-light-subtle bg-light bg-opacity-25"
                                placeholder="Correo electrónico" name="email" required style="font-size: 0.9rem;">
                        </div>
                        <div class="col-12 col-sm-4">
                            <input type="password"
                                class="form-control rounded-3 py-2 border-light-subtle bg-light bg-opacity-25"
                                placeholder="Contraseña" name="password" required style="font-size: 0.9rem;">
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-dark py-2 px-4 rounded-3 fw-bold border-0 bg-denim"
                                style="font-size: 0.85rem;">
                                <i class="bi bi-shield-check me-1"></i> Crear Administrador
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
                        @foreach ($clientes as $cliente)
                            <tr class="border-bottom border-light">
                                <td class="text-muted font-monospace fw-bold ps-2">{{ $cliente->id }}</td>
                                <td class="fw-bold text-dark text-capitalize">{{ $cliente->name }}</td>
                                <td class="text-secondary">{{ $cliente->email }}</td>
                                <td>
                                    @if ($cliente->activo)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                            style="font-size: 0.8rem;">
                                            Activa
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                            style="font-size: 0.8rem;">
                                            Suspendida
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($cliente->activo)
                                        <a href="{{ route('admin.usuarios.cambiar-estado', ['id' => $cliente->id]) }}"
                                            class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium btn-cambiar-estado-usuario"
                                            data-nombre="{{ $cliente->name }}" data-accion="suspender al usuario"
                                            style="font-size: 0.85rem;">
                                            <i class="bi bi-person-x-fill"></i> Suspender
                                        </a>
                                    @else
                                        <a href="{{ route('admin.usuarios.cambiar-estado', ['id' => $cliente->id]) }}"
                                            class="btn btn-success btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium btn-cambiar-estado-usuario"
                                            data-nombre="{{ $cliente->name }}" data-accion="activar la cuenta de"
                                            style="font-size: 0.85rem;">
                                            <i class="bi bi-person-check-fill"></i> Activar Cuenta
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
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
                            <th class="py-3">Estado</th>
                            <th class="py-3 text-end" style="width: 180px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.95rem;">
                        @foreach ($admins as $admin)
                            <tr class="border-bottom border-light">
                                <td class="text-muted font-monospace fw-bold ps-2">
                                    {{ str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-bold text-dark text-capitalize">{{ $admin->name }}</td>
                                <td class="text-secondary">{{ $admin->email }}</td>
                                <td>
                                    @if ($admin->activo)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                            style="font-size: 0.8rem;">
                                            Activo
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-2 fw-medium"
                                            style="font-size: 0.8rem;">
                                            Suspendido
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($admin->id === Auth::user()->id)
                                        {{-- El Admin logueado puede cambiar su contraseña mediante un Modal --}}
                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium"
                                            data-bs-toggle="modal" data-bs-target="#modalCambiarPassword"
                                            style="font-size: 0.85rem;">
                                            <i class="bi bi-key-fill text-oro"></i> Contraseña
                                        </button>
                                    @else
                                        @if ($admin->activo)
                                            <a href="{{ route('admin.usuarios.cambiar-estado', ['id' => $admin->id]) }}"
                                                class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium btn-cambiar-estado-usuario"
                                                data-nombre="{{ $admin->name }}"
                                                data-accion="suspender al administrador" style="font-size: 0.85rem;">
                                                <i class="bi bi-person-x-fill"></i> Suspender
                                            </a>
                                        @else
                                            <a href="{{ route('admin.usuarios.cambiar-estado', ['id' => $admin->id]) }}"
                                                class="btn btn-success btn-sm rounded-3 px-3 py-1.5 d-inline-flex align-items-center gap-1 fw-medium btn-cambiar-estado-usuario"
                                                data-nombre="{{ $admin->name }}" data-accion="activar la cuenta de"
                                                style="font-size: 0.85rem;">
                                                <i class="bi bi-person-check-fill"></i> Activar
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- MODAL BOOTSTRAP: CAMBIAR CONTRASEÑA PROPIA --}}
    <div class="modal fade" id="modalCambiarPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalCambiarPasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <h5 class="modal-title fw-bold text-denim d-flex align-items-center gap-2"
                        id="modalCambiarPasswordLabel">
                        <i class="bi bi-shield-lock-fill text-oro"></i> Actualizar Mi Contraseña
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.usuarios.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4 py-3">
                        <p class="text-muted small mb-3">Por seguridad, ingresá una combinación fuerte de caracteres para
                            resguardar el acceso a tu cuenta.</p>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold text-secondary">Nueva Contraseña</label>
                            <input type="password"
                                class="form-control rounded-3 py-2 border-light-subtle bg-light bg-opacity-25"
                                id="password" name="password" required placeholder="Escribí tu nueva clave...">
                        </div>

                        <div class="mb-1">
                            <label for="password_confirmation" class="form-label small fw-bold text-secondary">Confirmar
                                Contraseña</label>
                            <input type="password"
                                class="form-control rounded-3 py-2 border-light-subtle bg-light bg-opacity-25"
                                id="password_confirmation" name="password_confirmation" required
                                placeholder="Repetí tu nueva clave...">
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-2 d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-light rounded-3 fw-semibold px-4 border"
                            data-bs-dismiss="modal" style="font-size: 0.85rem;">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-dark rounded-3 fw-bold border-0 bg-denim px-4"
                            style="font-size: 0.85rem;">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            border-color: #1a3352 !important;
            box-shadow: none !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Captura todos los botones/enlaces de cambio de estado (Clientes y Admins)
            const botonesEstadoUsuario = document.querySelectorAll('.btn-cambiar-estado-usuario');

            botonesEstadoUsuario.forEach(boton => {
                boton.addEventListener('click', function(e) {
                    e.preventDefault(); // Frenamos el viaje inmediato a la ruta

                    const nombre = this.getAttribute('data-nombre');
                    const accion = this.getAttribute(
                    'data-accion'); // 'suspender al usuario', 'activar...', etc.
                    const urlDestino = this.getAttribute('href');

                    // Mensaje de confirmación interactivo
                    const mensaje = `¿Estás seguro de que deseas ${accion} "${nombre}"?`;

                    if (confirm(mensaje)) {
                        window.location.href = urlDestino;
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputBuscar = document.getElementById('input-buscar-cuentas');
            if (inputBuscar && inputBuscar.value !== '') {
                // Si ya hay un término buscado, ponemos el foco y el cursor al final del texto
                const valor = inputBuscar.value;
                inputBuscar.value = '';
                inputBuscar.focus();
                inputBuscar.value = valor;
            }
        });
    </script>
@endsection
