<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\ItemOrden;
use App\Models\Producto;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('carrito.index');
        }

        $productoIds = array_column($cart, 'id');
        $productosBD = Producto::whereIn('id', $productoIds)->get()->keyBy('id');

        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['precio'] * $item['cantidad']);
        }, 0);

        $totalEfectivo = array_reduce($cart, function ($carry, $item) use ($productosBD) {
            $producto = $productosBD[$item['id']] ?? null;
            $porcDesc = $producto ? (int) $producto->porc_desc_ef : 0;
            $precioEf = $porcDesc > 0
                ? $item['precio'] * (1 - $porcDesc / 100)
                : $item['precio'];
            return $carry + ($precioEf * $item['cantidad']);
        }, 0);

        $ahorro = $total - $totalEfectivo;

        return view('checkout.index', compact('cart', 'total', 'totalEfectivo', 'ahorro'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('carrito.index');
        }

        $rules = [
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ];

        if (auth()->check()) {
            $rules = [];
        }

        $data = $request->validate($rules);

        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['precio'] * $item['cantidad']);
        }, 0);

        $orden = Orden::create([
            'user_id' => auth()->id(),
            'nombre_contacto' => auth()->check() ? auth()->user()->name : $data['nombre'],
            'telefono_contacto' => auth()->check() ? auth()->user()->telefono : $data['telefono'],
            'total' => $total,
            'estado' => 'creada',
            'origen' => 'web',
        ]);

        foreach ($cart as $key => $item) {
            $talle = $item['talle'] ?? null;
            if ($talle === 'Único') $talle = null;

            $itemOrden = ItemOrden::create([
                'orden_id' => $orden->id,
                'producto_id' => $item['id'],
                'talle' => $talle,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'subtotal' => $item['precio'] * $item['cantidad'],
            ]);
        }

        $orden->calcularTotal();

        $orden->load('items.producto.talles');
        $orden->deducirStock();

        session()->forget('cart');

        return redirect()->route('checkout.confirmacion', $orden->id);
    }

    public function confirmacion($id)
    {
        $orden = Orden::with('items.producto')->findOrFail($id);

        $totalEfectivo = $orden->items->sum(function ($item) {
            return $item->subtotal_efectivo;
        });

        $ahorro = $orden->total - $totalEfectivo;

        $mensaje = "🎉 *Nuevo Pedido - Intensa Jeans* 🎉\n\n";
        $mensaje .= "*Pedido #{$orden->id}*\n";
        $mensaje .= "*Cliente:* {$orden->nombre_contacto}\n";
        $mensaje .= "*Teléfono:* {$orden->telefono_contacto}\n\n";
        $mensaje .= "*Productos:*\n";

        foreach ($orden->items as $item) {
            $talle = $item->talle ? " (Talle {$item->talle})" : '';
            $nombre = $item->producto->nombre ?? 'Producto eliminado';
            $mensaje .= "• {$nombre}{$talle} x{$item->cantidad}";
            $mensaje .= " - \$" . number_format($item->subtotal, 0, ',', '.');
            if ($item->precio_efectivo < $item->precio_unitario) {
                $mensaje .= " (ef: \$" . number_format($item->subtotal_efectivo, 0, ',', '.') . ")";
            }
            $mensaje .= "\n";
        }

        $mensaje .= "\n*Total Lista:* \$" . number_format($orden->total, 0, ',', '.');
        $mensaje .= "\n*Total Efectivo:* \$" . number_format($totalEfectivo, 0, ',', '.');
        if ($ahorro > 0) {
            $mensaje .= "\n*Ahorro:* \$" . number_format($ahorro, 0, ',', '.');
        }

        $whatsappOwner = config('app.whatsapp_owner', '543795016705');
        $urlWhatsapp = 'https://wa.me/' . $whatsappOwner . '?text=' . urlencode($mensaje);

        return view('checkout.confirmacion', compact('orden', 'totalEfectivo', 'ahorro', 'urlWhatsapp'));
    }
}
