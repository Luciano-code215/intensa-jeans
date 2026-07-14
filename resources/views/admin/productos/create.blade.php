@extends('layouts.admin')

@section('admin_content')
    <div class="container-fluid px-0">

        {{-- ALERTA DE PRODUCTO CREADO CON ÉXITO --}}
        @if (session('producto_creado'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between animate__animated animate__fadeIn"
                role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success bg-opacity-25 text-success rounded-3 p-1.5 d-inline-flex align-items-center justify-content-center"
                        style="width: 32px; height: 32px;">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                    </div>
                    <div>
                        <span class="fw-bold d-block" style="font-size: 0.95rem;">¡Operación Exitosa!</span>
                        <span class="small opacity-90">{{ session('producto_creado') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close small opacity-75" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        {{-- BLOQUE TEMPORAL PARA VER QUÉ SE ESTÁ ROMPIENDO --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-4 shadow-sm p-3 mb-4">
                <h6 class="fw-bold m-0 mb-2">¡Corregí los siguientes campos para poder guardar!</h6>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ENCABEZADO --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.productos.index') }}"
                class="btn btn-outline-secondary btn-sm rounded-3 px-2 py-1.5 border-opacity-25 me-1">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 d-inline-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px;">
                <i class="bi bi-box-seam-fill text-success fs-5"></i>
            </div>
            <h1 class="h3 fw-bold text-denim mb-0 font-titulo">Nuevo Producto</h1>
        </div>

        {{-- FORMULARIO PRINCIPAL --}}
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data"
            id="formNuevoProducto">
            @csrf

            <div class="row g-4">

                {{-- COLUMNA IZQUIERDA: DATOS GENERALES Y TALLES --}}
                <div class="col-12 col-lg-7">

                    {{-- Bloque 1: Detalles Básicos --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <h5 class="fw-bold text-denim mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle text-muted"></i> Detalles del Artículo
                        </h5>

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-1">Nombre del Producto</label>
                            <input type="text" class="form-control rounded-3 py-2"
                                placeholder="Ej: Jean Mom Rígido Celeste" name="nombre" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Categoría -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-bold text-dark mb-1">Categoría</label>
                                <select class="form-select rounded-3 py-2 text-secondary" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- SKU -->
                            <div class="col-12 col-sm-6">
                                <label
                                    class="form-label fw-bold text-dark mb-1 d-flex justify-content-between align-items-center">
                                    SKU / Código
                                    <span class="text-muted font-monospace fw-normal" style="font-size: 0.75rem;">(Auto si
                                        queda vacío)</span>
                                </label>
                                <input type="text" class="form-control rounded-3 py-2 font-monospace text-uppercase"
                                    placeholder="Ej: JNS-MOM012" name="sku" id="skuInput">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            {{-- Campo: Precio de Lista --}}
                            <div class="col-md-4">
                                <label for="precio" class="form-label fw-bold" style="color: #1a3352;">Precio de Lista
                                    ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio" id="precio"
                                        class="form-control @error('precio') is-invalid @enderror"
                                        value="{{ old('precio') }}" step="0.01" min="0" required
                                        placeholder="Ej: 15000">
                                    @error('precio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted d-block">El precio de venta normal del producto.</small>
                            </div>

                            {{-- Campo: % Descuento Efectivo --}}
                            <div class="col-md-4">
                                <label for="porc_desc_ef" class="form-label fw-bold" style="color: #1a3352;">% Descuento
                                    Efectivo / Transferencia</label>
                                <div class="input-group">
                                    <input type="number" name="porc_desc_ef" id="porc_desc_ef"
                                        class="form-control @error('porc_desc_ef') is-invalid @enderror"
                                        value="{{ old('porc_desc_ef', 0) }}" min="0" max="100"
                                        placeholder="Ej: 10">
                                    <span class="input-group-text">%</span>
                                    @error('porc_desc_ef')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted d-block">Se aplica sobre el precio (o sobre el precio
                                    liquidado).</small>

                                {{-- SIMULACIÓN PRECIO EFECTIVO --}}
                                <div class="mt-2" id="preview-efectivo-box" style="display: none;">
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1.5 rounded w-100 text-start">
                                        <i class="bi bi-wallet2 me-1"></i> Simulación Efectivo: <strong
                                            id="simulacion-efectivo">$0</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted my-4">

                        {{-- SECCIÓN: LIQUIDACIÓN --}}
                        <div class="card border-warning bg-light-subtle mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-warning-emphasis d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-fire text-danger"></i> Configuración de Liquidación
                                </h5>

                                <div class="row align-items-center g-3">
                                    {{-- Switch: ¿Está en liquidación? --}}
                                    <div class="col-md-6">
                                        <div class="form-check form-switch fs-5">
                                            <input class="form-check-input cursor-pointer" type="checkbox" role="switch"
                                                id="liquidacion" name="liquidacion" value="1"
                                                {{ old('liquidacion') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold cursor-pointer" for="liquidacion"
                                                style="color: #1a3352; font-size: 1rem;">
                                                Activar Liquidación para este producto
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-1">Al activarse, se mostrará un cartel de
                                            "LIQUIDACIÓN" en la tienda y el precio de lista bajará.</small>
                                    </div>

                                    {{-- Campo: % Descuento Liquidación (Habilitado solo si el switch está ON) --}}
                                    <div class="col-md-6" id="wrapper-porc-liquidacion">
                                        <label for="porc_liquidacion" class="form-label fw-bold"
                                            style="color: #1a3352;">% Descuento de Liquidación</label>
                                        <div class="input-group">
                                            <input type="number" name="porc_liquidacion" id="porc_liquidacion"
                                                class="form-control @error('porc_liquidacion') is-invalid @enderror"
                                                value="{{ old('porc_liquidacion', 0) }}" min="0" max="100"
                                                placeholder="Ej: 20" disabled>
                                            <span class="input-group-text">%</span>
                                            @error('porc_liquidacion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted d-block">Este porcentaje se descuenta directamente del
                                            Precio de Lista original.</small>

                                        {{-- SIMULACIÓN PRECIO LIQUIDACIÓN --}}
                                        <div class="mt-2" id="preview-liquidacion-box" style="display: none;">
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1.5 rounded w-100 text-start">
                                                <i class="bi bi-tag-fill me-1"></i> Simulación Lista Liquidación: <strong
                                                    id="simulacion-liquidacion">$0</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>>
                    </div>

                    {{-- Bloque 2: SELECCIÓN DE TALLES Y STOCK INDIVIDUAL CON MODAL --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h5 class="fw-bold text-denim mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-rulers text-muted"></i> Control de Talles y Stock
                                </h5>
                                <p class="text-muted small mb-0">Ingresá el stock disponible para cada talle. Los talles
                                    vacíos quedarán deshabilitados.</p>
                            </div>

                        </div>

                        {{-- Contenedor donde se renderizan los talles --}}
                        <div class="row g-2 mt-3" id="contenedorTalles">

                            @foreach ($talles as $talle)
                                <div class="col-6 col-sm-3 talle-item">
                                    <div class="p-2 border rounded-3 text-center bg-light bg-opacity-25">
                                        <span class="fw-bold text-denim d-block mb-1"
                                            style="font-size: 1.1rem;">{{ $talle->nombre }}</span>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0 text-muted"
                                                style="font-size: 0.75rem;">Cant.</span>
                                            <input type="number"
                                                class="form-control border-start-0 text-center fw-bold text-success"
                                                placeholder="0" name="talles[{{ $talle->id }}]" min="0"
                                                style="font-size: 0.85rem;">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Botón para abrir el Modal --}}
                            <button type="button"
                                class="btn btn-sm btn-outline-primary rounded-3 px-2.5 py-1.5 fw-medium d-flex align-items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#modalNuevoTalle">
                                <i class="bi bi-plus-lg"></i> Agregar Talle
                            </button>
                        </div>
                    </div>

                    {{-- Bloque 3: Descripción --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <label class="form-label fw-bold text-dark mb-1">Descripción corta o Detalles de la prenda</label>
                        <textarea class="form-control rounded-3" rows="3" placeholder="Detallá la moldería, si cede la tela, etc..."
                            name="descripcion"></textarea>
                    </div>

                </div>

                {{-- COLUMNA DERECHA: SUBIDA DE IMÁGENES MULTIMEDIA --}}
                <div class="col-12 col-lg-5">
                    <div
                        class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold text-denim mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-images text-muted"></i> Galería de Fotos
                            </h5>
                            <p class="text-muted small mb-4">
                                Seleccioná una o más imágenes. <span class="text-success fw-bold">La primera imagen se
                                    guardará como la foto de portada principal automáticamente</span>.
                            </p>

                            <!-- Input File -->
                            <div class="border border-dashed rounded-4 p-4 text-center bg-light bg-opacity-25"
                                style="border-style: dashed !important; border-color: #6c757d !important;">
                                <i class="bi bi-cloud-arrow-up text-secondary display-5 mb-2"></i>
                                <h6 class="fw-bold text-dark">Subir archivos multimedia</h6>
                                <p class="text-secondary small mb-3">Formatos: JPG, PNG, WEBP</p>

                                <label class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-3 fw-medium bg-white">
                                    <i class="bi bi-plus-lg"></i> Seleccionar Imágenes
                                    <input type="file" name="imagenes[]" id="imagenesInput" multiple accept="image/*"
                                        class="d-none">
                                </label>
                            </div>

                            <!-- Previsualización Dinámica -->
                            <div class="mt-4">
                                <div id="previewContainer" class="row g-2"></div>
                            </div>
                        </div>

                        {{-- ACCIÓN DE GUARDADO --}}
                        <div class="mt-4 pt-3 border-top">
                            <button type="submit"
                                class="btn btn-success w-100 py-2.5 rounded-3 fw-bold border-0 shadow-sm shadow-success-sm"
                                style="background-color: #198754;">
                                <i class="bi bi-save2-fill me-1"></i> Guardar Producto Completo
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        // 1. AUTOGENERADOR DE SKU
        document.getElementById('formNuevoProducto').addEventListener('submit', function(e) {
            const skuInput = document.getElementById('skuInput');
            if (skuInput.value.trim() === '') {
                const randomNum = Math.floor(1000 + Math.random() * 9000);
                skuInput.value = 'INT' + randomNum;
            }
        });

        // 2. PREVISUALIZADOR
        document.getElementById('imagenesInput').addEventListener('change', function(event) {
            const container = document.getElementById('previewContainer');
            container.innerHTML = '';
            const archivos = event.target.files;

            if (archivos.length === 0) return;

            Array.from(archivos).forEach((archivo, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-4 position-relative';
                    const esPrincipal = index === 0;
                    const badgePrincipal = esPrincipal ?
                        `<span class="position-absolute top-0 start-50 translate-middle badge bg-success shadow-sm" style="font-size:0.65rem; z-index:10;">PRINCIPAL</span>` :
                        '';
                    const bordeEstilo = esPrincipal ? 'border-success border-2' : 'border-light-subtle';

                    col.innerHTML = `
                    <div class="position-relative rounded-3 overflow-hidden border ${bordeEstilo} shadow-sm" style="height: 100px; background-color: #f8f9fa;">
                        ${badgePrincipal}
                        <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">
                        <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-70 text-center text-white small font-monospace" style="font-size: 0.65rem;">
                            Foto ${index + 1}
                        </span>
                    </div>
                `;
                    container.appendChild(col);
                };
                reader.readAsDataURL(archivo);
            });
        });

        //simulacion de precios y descuentos, bloqueador de liquidacion

        document.addEventListener("DOMContentLoaded", function() {
            const inputPrecio = document.getElementById('precio');
            const inputPorcDescEf = document.getElementById('porc_desc_ef');
            const switchLiquidacion = document.getElementById('liquidacion');
            const inputPorcLiquidacion = document.getElementById('porc_liquidacion');

            // Elementos de simulación
            const previewEfectivoBox = document.getElementById('preview-efectivo-box');
            const textSimulacionEfectivo = document.getElementById('simulacion-efectivo');
            const previewLiquidacionBox = document.getElementById('preview-liquidacion-box');
            const textSimulacionLiquidacion = document.getElementById('simulacion-liquidacion');

            // Formateador de moneda de Argentina (ARS)
            const formatter = new Intl.NumberFormat('es-AR', {
                style: 'currency',
                currency: 'ARS',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });

            function calcularSimulaciones() {
                const precioOriginal = parseFloat(inputPrecio.value) || 0;
                const porcDescEf = parseFloat(inputPorcDescEf.value) || 0;
                const esLiquidacion = switchLiquidacion.checked;
                const porcLiquidacion = parseFloat(inputPorcLiquidacion.value) || 0;

                // Si no hay precio ingresado, ocultamos las cajas de simulación
                if (precioOriginal <= 0) {
                    previewEfectivoBox.style.display = 'none';
                    previewLiquidacionBox.style.display = 'none';
                    return;
                }

                let precioListaActual = precioOriginal;

                // 1. Simulación de Liquidación
                if (esLiquidacion && porcLiquidacion > 0) {
                    precioListaActual = precioOriginal - (precioOriginal * (porcLiquidacion / 100));
                    textSimulacionLiquidacion.textContent = formatter.format(precioListaActual);
                    previewLiquidacionBox.style.display = 'block';
                } else {
                    previewLiquidacionBox.style.display = 'none';
                }

                // 2. Simulación de Efectivo (Siempre sobre el precio base obtenido del paso anterior)
                if (porcDescEf > 0) {
                    const precioEfectivo = precioListaActual - (precioListaActual * (porcDescEf / 100));
                    textSimulacionEfectivo.textContent = formatter.format(precioEfectivo);
                    previewEfectivoBox.style.display = 'block';
                } else {
                    previewEfectivoBox.style.display = 'none';
                }
            }

            function toggleLiquidacionInput() {
                if (switchLiquidacion.checked) {
                    inputPorcLiquidacion.removeAttribute('disabled');
                    if (inputPorcLiquidacion.value === '0' || inputPorcLiquidacion.value === '') {
                        inputPorcLiquidacion.value = '';
                        inputPorcLiquidacion.focus();
                    }
                } else {
                    inputPorcLiquidacion.setAttribute('disabled', 'true');
                    inputPorcLiquidacion.value = 0;
                }
                calcularSimulaciones();
            }

            // Escuchar eventos para recalcular al instante
            inputPrecio.addEventListener('input', calcularSimulaciones);
            inputPorcDescEf.addEventListener('input', calcularSimulaciones);
            inputPorcLiquidacion.addEventListener('input', calcularSimulaciones);
            switchLiquidacion.addEventListener('change', toggleLiquidacionInput);

            // Inicializar al cargar por primera vez
            toggleLiquidacionInput();
        });
    </script>

    <style>
        .form-control:focus,
        .form-select:focus {
            box-shadow: none !important;
            border-color: #198754 !important;
        }
    </style>

    {{-- MODAL CON FORMULARIO TRADICIONAL CORREGIDO --}}
    <div class="modal fade" id="modalNuevoTalle" tabindex="-1" aria-labelledby="modalNuevoTalleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow rounded-4">

                <form action="{{ route('admin.talles.store') }}" method="POST">
                    @csrf

                    <div class="modal-header border-0 pt-4 px-4 pb-2">
                        <h6 class="modal-title fw-bold text-denim d-flex align-items-center gap-1"
                            id="modalNuevoTalleLabel">
                            <i class="bi bi-tag-fill text-primary"></i> Crear Nuevo Talle
                        </h6>
                        <button type="button" class="btn-close small" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4 pb-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary mb-1">Nombre o Número del Talle</label>
                            <input type="text"
                                class="form-control rounded-3 py-2 text-center fw-bold @error('nombre') is-invalid @enderror"
                                name="nombre" placeholder="Ej: 50, XL, S" maxlength="10" required
                                value="{{ old('nombre') }}">

                            {{-- 🔥 CORRECCIÓN AQUÍ: Cambiado @error por @enderror --}}
                            @error('nombre')
                                <div class="invalid-feedback text-start" style="font-size: 0.75rem;">{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold border-0 bg-denim">
                            Guardar Talle
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    @if ($errors->has('nombre'))
        <script>
            // Si Laravel devuelve un error de validación, reabrimos el modal automáticamente
            var myModal = new bootstrap.Modal(document.getElementById('modalNuevoTalle'));
            myModal.show();
        </script>
    @endif
@endsection
