<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Talle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * VISTA PÚBLICA: Catálogo de productos con buscador unificado
     */
    public function index(Request $request)
    {
        $query = Producto::with(['talles', 'categoria'])->where('activo', true);

        // 1. Buscador por palabra clave (nombre o descripción)
        if ($request->filled('buscar')) {
            $terminos = array_filter(explode(' ', trim($request->buscar)));

            $query->where(function ($q) use ($terminos) {
                foreach ($terminos as $termino) {
                    $q->orWhere('nombre', 'LIKE', "%{$termino}%")
                        ->orWhere('descripcion', 'LIKE', "%{$termino}%");
                }
            });
        }

        // 2. Filtro por Categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        // 3. Filtro por Liquidación (Mantiene el filtro activo)
        $esLiquidacion = $request->filled('liquidacion') && $request->liquidacion == 1;
        if ($esLiquidacion) {
            $query->where('liquidacion', true);
        }

        // 4. Ordenamiento
        if ($request->filled('orden')) {
            switch ($request->orden) {
                case 'precio_asc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'precio_desc':
                    $query->orderBy('precio', 'desc');
                    break;
                case 'novedades':
                    $query->where('created_at', '>=', now()->subDays(7))
                        ->latest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $productos = $query->get();

        // Obtenemos las categorías que tengan al menos 1 producto activo
        $categorias = Categoria::whereHas('productos', function ($q) {
            $q->where('activo', true);
        })->get();

        return view('catalogo', compact('productos', 'categorias', 'esLiquidacion'));
    }

    /**
     * VISTA PANEL ADMIN: Listado total de productos para el Administrador
     */
    public function indexAdmin(Request $request)
    {
        // Cargamos todas las categorías para llenar el selector dinámico de la vista
        $categorias = Categoria::all();

        // Query base cargando relaciones necesarias
        $query = Producto::with(['categoria', 'talles']);

        // Filtro: Búsqueda por texto (Nombre o SKU)
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                    ->orWhere('sku', 'LIKE', "%{$buscar}%");
            });
        }

        // Filtro: Categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->input('categoria'));
        }

        // Filtro: Estado de Activación (Activos, Pausados/Desactivados o Todos)
        $estado = $request->input('estado', 'activos'); // por defecto muestra los activos
        if ($estado === 'activos') {
            $query->where('activo', true);
        } elseif ($estado === 'pausados') {
            $query->where('activo', false);
        }

        // Filtro: Sin Stock
        if ($request->boolean('sin_stock')) {
            $query->whereDoesntHave('talles', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Ordenación
        $ordenar = $request->input('ordenar', 'defecto');
        switch ($ordenar) {
            case 'precio-menor':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio-mayor':
                $query->orderBy('precio', 'desc');
                break;
            case 'mas-vendidos':
                $query->withCount(['items as total_vendido' => function ($q) {
                    $q->whereHas('orden', function ($q2) {
                        $q2->where('estado', 'pagada');
                    });
                }])->orderBy('total_vendido', 'desc');
                break;
            default:
                $query->latest(); // Más nuevos primero
                break;
        }

        $productos = $query->get();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    /**
     * FORMULARIO ADMIN: Crear nuevo producto
     */
    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $talles = Talle::where('activo', true)->get();
        return view('admin.productos.create', compact('categorias', 'talles'));
    }

    /**
     * LOGICA ADMIN: Guardar el producto y sus talles con stock
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'porc_desc_ef' => 'nullable|integer|min:0|max:100',
            'liquidacion' => 'nullable|boolean',
            'porc_liquidacion' => 'nullable|integer|min:0|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'sku' => 'nullable|string|max:100',
            'imagenes' => 'required|array|min:1',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:6144',
            'talles' => 'required|array',
        ]);

        // Si el SKU ya existe, sumamos stock en vez de rechazar
        if ($request->filled('sku')) {
            $existente = Producto::with('talles')->where('sku', $request->sku)->first();
            if ($existente) {
                foreach ($request->talles as $talleId => $stock) {
                    $stockActual = (int) ($existente->talles()->where('talles.id', $talleId)->first()?->pivot->stock ?? 0);
                    $nuevoStock = $stockActual + (int) ($stock['stock'] ?? 0);
                    $existente->talles()->syncWithoutDetaching([$talleId => ['stock' => $nuevoStock]]);
                }
                return redirect()->route('admin.productos.index')->with('success', "El SKU \"{$request->sku}\" ya existe. Se agregó el stock al producto existente \"{$existente->nombre}\".");
            }
        }

        $data = $request->only(['nombre', 'precio', 'categoria_id', 'descripcion', 'sku']);
        $data['activo'] = true;

        $esLiquidacion = $request->has('liquidacion');
        $data['liquidacion'] = $esLiquidacion;
        $data['porc_liquidacion'] = $esLiquidacion ? ($request->porc_liquidacion ?? 0) : 0;

        $porcDescEf = $request->porc_desc_ef ?? 0;
        $data['porc_desc_ef'] = $porcDescEf;

        $precioOriginal = $request->precio;

        if ($esLiquidacion && $data['porc_liquidacion'] > 0) {
            $precioBaseParaEfectivo = $precioOriginal - ($precioOriginal * ($data['porc_liquidacion'] / 100));
        } else {
            $precioBaseParaEfectivo = $precioOriginal;
        }

        $data['precio_ef'] = $precioBaseParaEfectivo - ($precioBaseParaEfectivo * ($porcDescEf / 100));

        if (empty($data['sku'])) {
            do {
                $data['sku'] = 'INT' . rand(1000, 99999);
            } while (Producto::where('sku', $data['sku'])->exists());
        }

        if ($request->hasFile('imagenes')) {
            $imagenes = $request->file('imagenes');
            $fotoPrincipal = $imagenes[0];
            $rutaPrincipal = $fotoPrincipal->store('productos', 'public');
            $data['url_imagen'] = 'storage/' . $rutaPrincipal;
        } else {
            return redirect()->back()->withErrors([
                'imagenes' => 'Las imágenes no se pudieron procesar adecuadamente. Probá reduciendo su resolución o cargando menos fotos juntas.'
            ])->withInput();
        }

        $producto = Producto::create($data);

        if (isset($imagenes) && count($imagenes) > 1) {
            for ($i = 1; $i < count($imagenes); $i++) {
                if ($imagenes[$i]->isValid()) {
                    $rutaGaleria = $imagenes[$i]->store('productos_galeria', 'public');
                    $producto->imagenesSecundarias()->create([
                        'url_imagen' => 'storage/' . $rutaGaleria
                    ]);
                }
            }
        }

        // 3. PROCESAR LOS TALLES Y STOCK
        foreach ($request->talles as $talleId => $stock) {
            $cantidadStock = $stock ?? 0;

            if (is_numeric($talleId)) {
                $producto->talles()->attach($talleId, ['stock' => $cantidadStock]);
            } else {
                $talleNuevo = Talle::firstOrCreate(['nombre' => $talleId]);
                $producto->talles()->attach($talleNuevo->id, ['stock' => $cantidadStock]);
            }
        }

        return redirect()->route('admin.productos.create')
            ->with('producto_creado', '¡El producto se registró correctamente!');
    }

    /**
     * FORMULARIO ADMIN: Editar producto existente
     */
    public function edit($id)
    {
        $producto = Producto::with('talles')->findOrFail($id);
        $categorias = Categoria::where('activo', true)->get();
        $talles = Talle::where('activo', true)->get();

        // Mapeamos los stocks actuales del producto para pintarlos fácil en la vista
        $stocksActuales = $producto->talles->pluck('pivot.stock', 'id')->toArray();

        return view('admin.productos.edit', compact('producto', 'categorias', 'talles', 'stocksActuales'));
    }

    /**
     * LOGICA ADMIN: Actualizar cambios y sincronizar stock
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        // 1. VALIDACIÓN CON NUEVA LÓGICA COMERCIAL
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'porc_desc_ef' => 'nullable|integer|min:0|max:100', // Descuento efectivo/transferencia
            'liquidacion' => 'nullable|boolean',                 // Checkbox de liquidación
            'porc_liquidacion' => 'nullable|integer|min:0|max:100', // % de liquidación
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:6144', // Imagen principal
            'talles' => 'required|array',
            'stock' => 'required|array',
        ]);

        // Mapeamos los campos base para actualizar
        $data = $request->only(['nombre', 'precio', 'categoria_id', 'descripcion', 'sku']);

        $data['activo'] = $request->has('activo');

        // Procesamos el estado de liquidación
        $esLiquidacion = $request->has('liquidacion');
        $data['liquidacion'] = $esLiquidacion;
        $data['porc_liquidacion'] = $esLiquidacion ? ($request->porc_liquidacion ?? 0) : 0;

        // Guardamos el porcentaje de descuento en efectivo
        $porcDescEf = $request->porc_desc_ef ?? 0;
        $data['porc_desc_ef'] = $porcDescEf;

        // --- MATEMÁTICA COMERCIAL PARA EL PRECIO EFECTIVO ---
        $precioOriginal = $request->precio;

        // A. Si está en liquidación, calculamos la base sobre el precio rebajado
        if ($esLiquidacion && $data['porc_liquidacion'] > 0) {
            $precioBaseParaEfectivo = $precioOriginal - ($precioOriginal * ($data['porc_liquidacion'] / 100));
        } else {
            $precioBaseParaEfectivo = $precioOriginal;
        }

        // B. El precio_ef final se calcula aplicando el descuento de efectivo sobre esa base
        $data['precio_ef'] = $precioBaseParaEfectivo - ($precioBaseParaEfectivo * ($porcDescEf / 100));

        // 2. PROCESAR IMAGEN PRINCIPAL (Reemplazo físico)
        if ($request->hasFile('imagen')) {
            // Borramos la imagen vieja para no acumular basura en el servidor
            if ($producto->url_imagen && Storage::exists(str_replace('storage/', 'public/', $producto->url_imagen))) {
                Storage::delete(str_replace('storage/', 'public/', $producto->url_imagen));
            }

            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            $data['url_imagen'] = 'storage/' . $rutaImagen;
        }

        // Guardamos todos los datos del producto en la DB
        $producto->update($data);

        // 3. PROCESAR IMÁGENES SECUNDARIAS (GALERÍA)
        if ($request->hasFile('imagenes_galeria')) {
            foreach ($request->file('imagenes_galeria') as $foto) {
                $rutaGaleria = $foto->store('productos_galeria', 'public');
                $producto->imagenesSecundarias()->create([
                    'url_imagen' => 'storage/' . $rutaGaleria
                ]);
            }
        }

        // 4. SINCRONIZAR TABLA PIVOTE DE TALLES Y STOCK
        $arraySync = [];
        foreach ($request->talles as $talleId) {
            $arraySync[$talleId] = ['stock' => $request->stock[$talleId] ?? 0];
        }
        $producto->talles()->sync($arraySync);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    /**
     * LOGICA ADMIN: Eliminar producto
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        // Desactivamos el producto en lugar de eliminarlo físicamente
        $producto->update(['activo' => false]);

        return redirect()->route('admin.productos.index')
            ->with('producto_descativado', 'El producto ha sido desactivado y pausado de la tienda con éxito.');
    }

    public function show($id)
    {
        // 1. Buscamos el producto pero le ordenamos que traiga también sus talles y sus fotos secundarias
        $producto = Producto::with(['talles', 'imagenesSecundarias'])->findOrFail($id);

        // 2. Productos desactivados no se muestran públicamente
        if (!$producto->activo) {
            abort(404);
        }

        // 3. Le enviamos toda esa información junta a la vista de detalles
        return view('productos.show', compact('producto'));
    }

    public function reactivar($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update(['activo' => true]);

        return redirect()->route('admin.productos.index')
            ->with('producto_reactivado', 'El producto ha sido reactivado y publicado en la tienda con éxito.');
    }

}
