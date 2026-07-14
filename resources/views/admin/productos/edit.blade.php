@extends('layouts.app')

@section('title', 'Editar Producto - ' . $producto->nombre)

@section('content')
    <div class="container my-5">
        {{-- Encabezado y botón Volver --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-muted text-uppercase fw-bold small">Panel de Administración</span>
                <h1 class="fw-bold font-titulo" style="color: #1a3352;">Editar Producto</h1>
            </div>
            <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-4">

                {{-- Formulario de Edición --}}
                <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        {{-- COLUMNA IZQUIERDA: Datos principales --}}
                        <div class="col-lg-8">

                            {{-- Campo: Nombre --}}
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold" style="color: #1a3352;">Nombre del
                                    Producto</label>
                                <input type="text" name="nombre" id="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre', $producto->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Campo: Descripción --}}
                            <div class="mb-3">
                                <label for="descripcion" class="form-label fw-bold"
                                    style="color: #1a3352;">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="4"
                                    class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SECCIÓN: PRECIOS Y DESCUENTOS --}}
                            <div class="row g-3 mb-4">
                                {{-- Campo: Precio de Lista --}}
                                <div class="col-md-6">
                                    <label for="precio" class="form-label fw-bold" style="color: #1a3352;">Precio de Lista
                                        ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="precio" id="precio"
                                            class="form-control @error('precio') is-invalid @enderror"
                                            value="{{ old('precio', $producto->precio) }}" step="0.01" min="0"
                                            required>
                                        @error('precio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted d-block mt-1">El precio de venta normal del producto.</small>
                                </div>

                                {{-- Campo: % Descuento Efectivo --}}
                                <div class="col-md-6">
                                    <label for="porc_desc_ef" class="form-label fw-bold" style="color: #1a3352;">% Descuento
                                        Efectivo / Transferencia</label>
                                    <div class="input-group">
                                        <input type="number" name="porc_desc_ef" id="porc_desc_ef"
                                            class="form-control @error('porc_desc_ef') is-invalid @enderror"
                                            value="{{ old('porc_desc_ef', $producto->porc_desc_ef ?? 0) }}" min="0"
                                            max="100">
                                        <span class="input-group-text">%</span>
                                        @error('porc_desc_ef')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted d-block mt-1">Se aplica sobre el precio de lista o el
                                        liquidado.</small>

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
                                    <h5
                                        class="card-title fw-bold text-warning-emphasis d-flex align-items-center gap-2 mb-3">
                                        <i class="bi bi-fire text-danger"></i> Configuración de Liquidación
                                    </h5>

                                    <div class="row align-items-center g-3">
                                        {{-- Switch: ¿Está en liquidación? --}}
                                        <div class="col-md-6">
                                            <div class="form-check form-switch fs-5">
                                                <input class="form-check-input cursor-pointer" type="checkbox"
                                                    role="switch" id="liquidacion" name="liquidacion" value="1"
                                                    {{ old('liquidacion', $producto->liquidacion) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold cursor-pointer" for="liquidacion"
                                                    style="color: #1a3352; font-size: 1rem;">
                                                    Activar Liquidación para este producto
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-1">Coloca el cartel de liquidación y baja su
                                                precio de lista base.</small>
                                        </div>

                                        {{-- Campo: % Descuento Liquidación --}}
                                        <div class="col-md-6" id="wrapper-porc-liquidacion">
                                            <label for="porc_liquidacion" class="form-label fw-bold"
                                                style="color: #1a3352;">% Descuento de Liquidación</label>
                                            <div class="input-group">
                                                <input type="number" name="porc_liquidacion" id="porc_liquidacion"
                                                    class="form-control @error('porc_liquidacion') is-invalid @enderror"
                                                    value="{{ old('porc_liquidacion', $producto->porc_liquidacion ?? 0) }}"
                                                    min="0" max="100">
                                                <span class="input-group-text">%</span>
                                                @error('porc_liquidacion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="text-muted d-block mt-1">Este porcentaje reduce el precio de
                                                lista original.</small>

                                            {{-- SIMULACIÓN PRECIO LIQUIDACIÓN --}}
                                            <div class="mt-2" id="preview-liquidacion-box" style="display: none;">
                                                <span
                                                    class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1.5 rounded w-100 text-start">
                                                    <i class="bi bi-tag-fill me-1"></i> Simulación Lista Liquidación:
                                                    <strong id="simulacion-liquidacion">$0</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECCIÓN: CONTROL DE STOCK POR TALLE --}}
                            <div class="card border-secondary-subtle bg-white mb-4">
                                <div class="card-header bg-light fw-bold text-denim" style="color: #1a3352;">
                                    <i class="bi bi-layers-half me-1"></i> Control de Stock por Talle
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 100px;">¿Habilitado?</th>
                                                    <th>Talle</th>
                                                    <th style="width: 200px;">Cantidad en Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($talles as $talle)
                                                    @php
                                                        $tieneAsociado = array_key_exists($talle->id, $stocksActuales);
                                                        $stockActualVal = $tieneAsociado
                                                            ? $stocksActuales[$talle->id]
                                                            : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="form-check fs-5">
                                                                <input class="form-check-input check-talle"
                                                                    type="checkbox" name="talles[]"
                                                                    value="{{ $talle->id }}"
                                                                    id="talle-{{ $talle->id }}"
                                                                    {{ $tieneAsociado ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <label class="form-check-label fw-semibold"
                                                                for="talle-{{ $talle->id }}">
                                                                Talle {{ $talle->nombre }}
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="stock[{{ $talle->id }}]"
                                                                id="stock-{{ $talle->id }}"
                                                                class="form-control form-control-sm input-stock"
                                                                value="{{ old('stock.' . $talle->id, $stockActualVal) }}"
                                                                min="0" placeholder="0"
                                                                {{ !$tieneAsociado ? 'disabled' : '' }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: Categorías, Imagen Principal e Imagenes adicionales --}}
                        <div class="col-lg-4">

                            {{-- Estado Activo --}}
                            <div class="card border-0 bg-light mb-4 p-3 rounded-3">
                                <div class="form-check form-switch fs-5 m-0">
                                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch"
                                        id="activo" name="activo" value="1"
                                        {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold cursor-pointer text-dark fs-6" for="activo">
                                        Producto Visible en la Tienda
                                    </label>
                                </div>
                            </div>

                            {{-- Categoría --}}
                            <div class="mb-4">
                                <label for="categoria_id" class="form-label fw-bold"
                                    style="color: #1a3352;">Categoría</label>
                                <select name="categoria_id" id="categoria_id"
                                    class="form-select @error('categoria_id') is-invalid @enderror" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Imagen Principal --}}
                            <div class="card border-secondary-subtle mb-4">
                                <div class="card-header bg-light fw-bold text-dark" style="font-size: 0.9rem;">
                                    Imagen Principal
                                </div>
                                <div class="card-body text-center">
                                    @if ($producto->url_imagen)
                                        <div class="mb-3 position-relative d-inline-block rounded overflow-hidden border"
                                            style="height: 180px;">
                                            <img src="{{ str_starts_with($producto->url_imagen, 'http') ? $producto->url_imagen : asset($producto->url_imagen) }}"
                                                class="w-100 h-100 object-fit-cover" id="preview-imagen-principal">
                                        </div>
                                    @endif
                                    <input type="file" name="imagen" id="imagen"
                                        class="form-control form-control-sm @error('imagen') is-invalid @enderror">
                                    <small class="text-muted d-block mt-1">Formatos permitidos: JPG, PNG, WEBP. Subir una
                                        nueva reemplazará la anterior.</small>
                                    @error('imagen')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Imágenes Adicionales (Galería) --}}
                            <div class="card border-secondary-subtle mb-4">
                                <div class="card-header bg-light fw-bold text-dark" style="font-size: 0.9rem;">
                                    Añadir a la Galería Secundaria
                                </div>
                                <div class="card-body">
                                    <input type="file" name="imagenes_galeria[]" id="imagenes_galeria"
                                        class="form-control form-control-sm" multiple>
                                    <small class="text-muted d-block mt-2">Puedes seleccionar varias imágenes al mismo
                                        tiempo para sumarlas a la galería del producto.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción final --}}
                    <div class="border-top pt-4 mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.productos.index') }}" class="btn btn-light px-4">Cancelar</a>
                        <button type="submit" class="btn text-white px-5 fw-semibold"
                            style="background-color: #1a3352;">
                            Guardar Cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPT INTERACTIVO --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Manejo del Stock por Talle (Habilitar/Deshabilitar inputs)
            const checkTalles = document.querySelectorAll('.check-talle');
            checkTalles.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const talleId = this.value;
                    const inputStock = document.getElementById('stock-' + talleId);
                    if (this.checked) {
                        inputStock.removeAttribute('disabled');
                        inputStock.value = inputStock.value == 0 ? '' : inputStock.value;
                        inputStock.focus();
                    } else {
                        inputStock.setAttribute('disabled', 'true');
                        inputStock.value = 0;
                    }
                });
            });

            // 2. Simulador de Precios Interactivos
            const inputPrecio = document.getElementById('precio');
            const inputPorcDescEf = document.getElementById('porc_desc_ef');
            const switchLiquidacion = document.getElementById('liquidacion');
            const inputPorcLiquidacion = document.getElementById('porc_liquidacion');

            // Elementos de simulación
            const previewEfectivoBox = document.getElementById('preview-efectivo-box');
            const textSimulacionEfectivo = document.getElementById('simulacion-efectivo');
            const previewLiquidacionBox = document.getElementById('preview-liquidacion-box');
            const textSimulacionLiquidacion = document.getElementById('simulacion-liquidacion');

            // Formateador de moneda de Argentina
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

                // Si no hay precio ingresado, ocultamos las cajas
                if (precioOriginal <= 0) {
                    previewEfectivoBox.style.display = 'none';
                    previewLiquidacionBox.style.display = 'none';
                    return;
                }

                let precioListaActual = precioOriginal;

                // A. Simulación de Liquidación
                if (esLiquidacion && porcLiquidacion > 0) {
                    precioListaActual = precioOriginal - (precioOriginal * (porcLiquidacion / 100));
                    textSimulacionLiquidacion.textContent = formatter.format(precioListaActual);
                    previewLiquidacionBox.style.display = 'block';
                } else {
                    previewLiquidacionBox.style.display = 'none';
                }

                // B. Simulación de Efectivo (Calculado sobre el precio_lista actual)
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
                } else {
                    inputPorcLiquidacion.setAttribute('disabled', 'true');
                    inputPorcLiquidacion.value = 0;
                }
                calcularSimulaciones();
            }

            // Escuchar eventos
            inputPrecio.addEventListener('input', calcularSimulaciones);
            inputPorcDescEf.addEventListener('input', calcularSimulaciones);
            inputPorcLiquidacion.addEventListener('input', calcularSimulaciones);
            switchLiquidacion.addEventListener('change', toggleLiquidacionInput);

            // Correr simulador en carga inicial para pintar los valores guardados del producto
            toggleLiquidacionInput();
        });
    </script>

    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection
