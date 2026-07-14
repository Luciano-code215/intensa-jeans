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
        // Iniciamos la consulta base con productos activos
        $query = Producto::where('activo', true)->with('talles');

        // Filtro por palabra clave (Buscador y Botones de Categorías)
        if ($request->has('buscar') && $request->buscar != '') {
            $palabra = $request->buscar;

            $query->where(function ($q) use ($palabra) {
                $q->where('nombre', 'LIKE', "%$palabra%")
                    ->orWhere('descripcion', 'LIKE', "%$palabra%")
                    // También busca si coincide con el nombre de la categoría
                    ->orWhereHas('categoria', function ($statusQuery) use ($palabra) {
                        $statusQuery->where('nombre', 'LIKE', "%$palabra%");
                    });
            });
        }

        // Traemos los productos filtrados
        $productos = $query->get();

        // Traemos las categorías activas para la botonera
        $categoriasMenu = Categoria::where('activo', true)->get();

        return view('catalogo', compact('productos', 'categoriasMenu'));
    }

    /**
     * VISTA PANEL ADMIN: Listado total de productos para el Administrador
     */
    public function adminIndex()
    {
        $productos = Producto::with('categoria')->get();
        return view('admin.productos.index', compact('productos'));
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
        // 1. VALIDACIÓN ADAPTADA CON NUEVOS CAMPOS
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'porc_desc_ef' => 'nullable|integer|min:0|max:100', // Descuento efectivo/transferencia
            'liquidacion' => 'nullable|boolean',                 // Checkbox de liquidación
            'porc_liquidacion' => 'nullable|integer|min:0|max:100', // % de liquidación
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'sku' => 'nullable|unique:productos,sku|string|max:100',
            'imagenes' => 'required|array|min:1',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:6144', // Max 6MB por Herd
            'talles' => 'required|array',
        ]);

        // Mapeamos los datos base para el modelo
        $data = $request->only(['nombre', 'precio', 'categoria_id', 'descripcion', 'sku']);
        $data['activo'] = true;

        // Procesamos el estado de liquidación
        $esLiquidacion = $request->has('liquidacion');
        $data['liquidacion'] = $esLiquidacion;
        $data['porc_liquidacion'] = $esLiquidacion ? ($request->porc_liquidacion ?? 0) : 0;

        // Guardamos el porcentaje de descuento efectivo
        $porcDescEf = $request->porc_desc_ef ?? 0;
        $data['porc_desc_ef'] = $porcDescEf;

        // --- MATEMÁTICA COMERCIAL PARA EL PRECIO EFECTIVO ---
        $precioOriginal = $request->precio;

        // A. Si está en liquidación, el precio de lista base baja
        if ($esLiquidacion && $data['porc_liquidacion'] > 0) {
            $precioBaseParaEfectivo = $precioOriginal - ($precioOriginal * ($data['porc_liquidacion'] / 100));
        } else {
            $precioBaseParaEfectivo = $precioOriginal;
        }

        // B. El precio final en efectivo se calcula aplicando el desc. de transferencia sobre el precio base obtenido
        $data['precio_ef'] = $precioBaseParaEfectivo - ($precioBaseParaEfectivo * ($porcDescEf / 100));

        // Generación de SKU por si vino vacío
        if (empty($data['sku'])) {
            $data['sku'] = 'INT' . rand(1000, 9000);
        }

        // 2. PROCESAR LA GALERÍA DE IMÁGENES
        if ($request->hasFile('imagenes')) {
            $imagenes = $request->file('imagenes');

            // Guardamos la primera imagen como la portada principal
            $fotoPrincipal = $imagenes[0];
            $rutaPrincipal = $fotoPrincipal->store('productos', 'public');
            $data['url_imagen'] = 'storage/' . $rutaPrincipal;
        } else {
            return redirect()->back()->withErrors([
                'imagenes' => 'Las imágenes no se pudieron procesar adecuadamente. Probá reduciendo su resolución o cargando menos fotos juntas.'
            ])->withInput();
        }

        // Creamos el producto base con todos los datos procesados
        $producto = Producto::create($data);

        // Guardamos el resto de las imágenes (a partir de la segunda)
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

        // Borramos su imagen física
        if ($producto->url_imagen && Storage::exists(str_replace('storage/', 'public/', $producto->url_imagen))) {
            Storage::delete(str_replace('storage/', 'public/', $producto->url_imagen));
        }

        // Al eliminar el producto, gracias al onDelete('cascade') de tu migración,
        // Laravel borrará automáticamente sus registros de stock en producto_talles.
        $producto->delete();

        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado por completo.');
    }

    public function show($id)
    {
        // 1. Buscamos el producto pero le ordenamos que traiga también sus talles y sus fotos secundarias
        $producto = Producto::with(['talles', 'imagenesSecundarias'])->findOrFail($id);

        // 2. Le enviamos toda esa información junta a la vista de detalles
        return view('productos.show', compact('producto'));
    }
}
