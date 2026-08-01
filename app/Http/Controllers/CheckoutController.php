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

        $errores = $this->validarCarrito($cart);
        if (!empty($errores)) {
            $lista = implode('<br>', $errores);
            return redirect()->route('carrito.index')->with('error_stock', "Tu carrito necesita una revisión antes de finalizar la compra:<br><br>{$lista}");
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

        $errores = $this->validarCarrito($cart);
        if (!empty($errores)) {
            $lista = implode('<br>', $errores);
            return redirect()->route('carrito.index')->with('error_stock', "Tu carrito necesita una revisión antes de finalizar la compra:<br><br>{$lista}");
        }

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
            $sku = $item->producto->sku ?? 'S/N';
            $nombre = $item->producto->nombre ?? 'Producto eliminado';
            $mensaje .= "• {$nombre}{$talle} x{$item->cantidad}";
            $mensaje .= " - *SKU:* {$sku}";
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

    private function validarCarrito(array $cart): array
    {
        $errores = [];
        $preciosActualizados = false;

        foreach ($cart as $key => $item) {
            $producto = Producto::find($item['id']);

            if (!$producto) {
                $errores[] = "Un producto de tu carrito ya no está disponible en la tienda.";
                continue;
            }

            if (!$producto->activo) {
                $errores[] = "«{$producto->nombre}» ya no está disponible. Lo retiramos de la tienda.";
                continue;
            }

            $precioActual = (float) $producto->precio_lista_actual;
            if ((float) $item['precio'] !== $precioActual) {
                $cart[$key]['precio'] = $precioActual;
                $preciosActualizados = true;
                $errores[] = "El precio de «{$producto->nombre}» cambió: era \$" . number_format($item['precio'], 0, ',', '.') . " y ahora es \$" . number_format($precioActual, 0, ',', '.') . ". Actualizamos tu carrito con el nuevo precio.";
                continue;
            }

            $talle = $item['talle'] ?? null;
            if ($talle === 'Único') $talle = null;

            $stockActual = $talle ? $producto->stockPorTalle($talle) : $producto->stockTotal();

            if ($item['cantidad'] > $stockActual) {
                $errores[] = "«{$producto->nombre}» (" . ($talle ? "talle {$talle}" : 'sin talle') . "): pediste {$item['cantidad']}, disponible {$stockActual}.";
            }
        }

        if ($preciosActualizados) {
            session()->put('cart', $cart);
        }

        return $errores;
    }
}
