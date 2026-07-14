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
        // 1. VALIDACIÓN ADAPTADA A LA VISTA
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'porcentaje_descuento' => 'nullable|integer|min:0|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'sku' => 'nullable|unique:productos,sku|string|max:100',
            'imagenes' => 'required|array|min:1',

            // 🔥 CAMBIADO AQUÍ: Ahora acepta hasta 6144 KB (6 Megas)
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:6144',

            'talles' => 'required|array',
        ]);

        // Mapeamos porcentaje_descuento al campo correcto de la base de datos (porc_desc)
        $data = $request->only(['nombre', 'precio', 'categoria_id', 'descripcion', 'sku']);
        $data['porc_desc'] = $request->porcentaje_descuento ?? 0;
        $data['activo'] = true; // O customizalo según necesites

        // Si el SKU vino vacío (porque falló JS o se borró), lo generamos por backend como respaldo
        if (empty($data['sku'])) {
            $data['sku'] = 'INT' . rand(1000, 9000);
        }

        // 2. PROCESAR LA GALERÍA DE IMÁGENES
        if ($request->hasFile('imagenes')) { // 👈 Aseguramos que el servidor realmente recibió los archivos
            $imagenes = $request->file('imagenes');

            // Guardamos la primera imagen como la portada principal
            $fotoPrincipal = $imagenes[0];
            $rutaPrincipal = $fotoPrincipal->store('productos', 'public');
            $data['url_imagen'] = 'storage/' . $rutaPrincipal;
        } else {
            // Respaldo por si falla la subida física del archivo pesado
            return redirect()->back()->withErrors(['imagenes' => 'Las imágenes no se pudieron procesar adecuadamente. Probá reduciendo su resolución o cargando menos fotos juntas.'])->withInput();
        }

        // Creamos el producto base
        $producto = Producto::create($data);

        // Guardamos el resto de las imágenes (a partir de la segunda)
        if (isset($imagenes) && count($imagenes) > 1) {
            for ($i = 1; $i < count($imagenes); $i++) {
                if ($imagenes[$i]->isValid()) { // 👈 Chequeamos que la foto no esté corrupta
                    $rutaGaleria = $imagenes[$i]->store('productos_galeria', 'public');
                    $producto->imagenesSecundarias()->create([
                        'url_imagen' => 'storage/' . $rutaGaleria
                    ]);
                }
            }
        }

        // 3. PROCESAR LOS TALLES Y STOCK (Estructura asociativa)
        foreach ($request->talles as $talleId => $stock) {
            // Aseguramos que solo guarde si asignaron stock o si el talleID es numérico (filtrando los creados por JS con texto)
            $cantidadStock = $stock ?? 0;

            if (is_numeric($talleId)) {
                // Talles preexistentes en la base de datos
                $producto->talles()->attach($talleId, ['stock' => $cantidadStock]);
            } else {
                /* 
                  💡 TIP PARA LOS TALLES NUEVOS CREADOS POR MODAL:
                  Si el usuario usó el botón "Agregar talle" con texto (ej: "XL" o "52"), 
                  primero debés buscarlo o crearlo en tu tabla de Talles antes de asociarlo.
                */
                $talleNuevo = \App\Models\Talle::firstOrCreate(['nombre' => $talleId]);
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

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'porc_desc' => 'nullable|integer|min:0|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'talles' => 'required|array',
            'stock' => 'required|array',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Borramos la imagen vieja si existía para no acumular basura en el servidor
            if ($producto->url_imagen && Storage::exists(str_replace('storage/', 'public/', $producto->url_imagen))) {
                Storage::delete(str_replace('storage/', 'public/', $producto->url_imagen));
            }

            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            $data['url_imagen'] = 'storage/' . $rutaImagen;
        }

        $data['activo'] = $request->has('activo');
        $data['porc_desc'] = $request->porc_desc ?? 0;

        $producto->update($data);

        // PROCESAR LAS IMÁGENES ADICIONALES PARA LA GALERÍA
        if ($request->hasFile('imagenes_galeria')) {
            foreach ($request->file('imagenes_galeria') as $foto) {
                // Guardamos cada foto en la carpeta 'productos_galeria'
                $rutaGaleria = $foto->store('productos_galeria', 'public');

                // Creamos el registro en la nueva tabla usando la relación
                $producto->imagenesSecundarias()->create([
                    'url_imagen' => 'storage/' . $rutaGaleria
                ]);
            }
        }

        // Sincronizar la tabla pivote (agrega, edita o elimina lo que cambió en los talles)
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
