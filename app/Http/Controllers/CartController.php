<?php

namespace App\Http\Controllers;

use App\Models\Producto; // O el modelo que utilices para tus jeans
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Ver el carrito
    public function index()
    {
        $cart = session()->get('cart', []);

        // Traemos los productos de la BD para validar stock (del paso anterior)
        $productoIds = array_column($cart, 'id');
        $productosBD = Producto::with('talles')->whereIn('id', $productoIds)->get()->keyBy('id');

        // 👇 CALCULAMOS EL TOTAL
        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['precio'] * $item['cantidad']);
        }, 0);

        // 👇 Calculamos el total efectivo real con el % de c/producto
        $totalEfectivo = array_reduce($cart, function ($carry, $item) use ($productosBD) {
            $producto = $productosBD[$item['id']] ?? null;
            $porcDesc = $producto ? (int) $producto->porc_desc_ef : 0;
            $precioEf = $porcDesc > 0
                ? $item['precio'] * (1 - $porcDesc / 100)
                : $item['precio'];
            return $carry + ($precioEf * $item['cantidad']);
        }, 0);

        $ahorro = $total - $totalEfectivo;

        // Pasamos $total a la vista
        return view('carrito.index', compact('cart', 'productosBD', 'total', 'totalEfectivo', 'ahorro'));
    }

    // Agregar producto al carrito
    public function add(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return back()->with('error', 'Los administradores no pueden agregar productos al carrito.');
        }

        $producto = Producto::findOrFail($id);
        $cart = session()->get('cart', []);

        $talle = $request->input('talle', 'Único');
        $cartKey = $id . '_' . $talle;
        $cantSolicitada = (int) $request->input('cantidad', 1);

        $stockTalle = $producto->stockPorTalle($talle);
        $cantEnCarrito = $cart[$cartKey]['cantidad'] ?? 0;
        $totalSolicitada = $cantEnCarrito + $cantSolicitada;

        if ($totalSolicitada > $stockTalle) {
            $disponible = max(0, $stockTalle - $cantEnCarrito);
            return back()->with('error', "Solo hay {$stockTalle} unidades disponibles de «{$producto->nombre}» (talle {$talle}). Podés agregar hasta {$disponible} más.");
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['cantidad'] = $totalSolicitada;
        } else {
            $cart[$cartKey] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio_lista_actual,
                'imagen' => $producto->url_imagen,
                'talle' => $talle,
                'cantidad' => $cantSolicitada,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('agregado_carrito', '¡Producto agregado al carrito!');
    }

    // Actualizar cantidad
    public function update(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['cantidad'] = max(1, (int) $request->cantidad);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('carrito_actualizado', 'Carrito actualizado');
    }

    // Eliminar producto
    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('producto_eliminado', 'Producto eliminado');
    }

    // Vaciar todo el carrito
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('carrito_vaciado', 'Carrito vaciado');
    }
}
