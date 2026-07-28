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

        // Pasamos $total a la vista
        return view('carrito.index', compact('cart', 'productosBD', 'total'));
    }

    // Agregar producto al carrito
    public function add(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $cart = session()->get('cart', []);

        // Generar una clave única (por si luego manejas Talles/Variantes)
        $talle = $request->input('talle', 'Único');
        $cartKey = $id . '_' . $talle;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['cantidad'] += $request->input('cantidad', 1);
        } else {
            $cart[$cartKey] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio_oferta ?? $producto->precio, // si está en liquidación
                'imagen' => $producto->imagen,
                'talle' => $talle,
                'cantidad' => (int) $request->input('cantidad', 1),
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
