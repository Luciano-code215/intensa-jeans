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

                        <div class="row g-3 mb-0">
                            <!-- Precio -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-bold text-dark mb-1">Precio Unitario ($)</label>
                                <input type="number" class="form-control rounded-3 py-2" placeholder="38500" name="precio"
                                    min="0" required>
                            </div>

                            <!-- Porcentaje de Descuento -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label fw-bold text-dark mb-1">Porcentaje Descuento (%)</label>
                                <input type="number" class="form-control rounded-3 py-2" placeholder="0" value="0"
                                    name="porcentaje_descuento" min="0" max="100">
                            </div>
                        </div>
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
