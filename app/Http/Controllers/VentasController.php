<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;
use App\Models\ItemOrden;
use App\Models\Producto;

class VentasController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Orden::query();

        if ($request->filled('estado')) {
            $ventas->where('estado', $request->estado);
        }

        if ($request->filled('fecha_inicio')) {
            $ventas->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $ventas->whereDate('created_at', '<=', $request->fecha_fin);
        }

        if ($request->filled('buscar')) {
            $palabra = $request->buscar;
            $ventas->where(function ($query) use ($palabra) {
                $query->where('id', 'like', "%{$palabra}%")
                    ->orWhere('nombre_contacto', 'like', "%{$palabra}%")
                    ->orWhereHas('user', function ($q) use ($palabra) {
                        $q->where('name', 'like', "%{$palabra}%");
                    });
            });
        }

        $ventas = $ventas->latest()->get();

        return view('admin.ventas.index', compact('ventas'));
    }

    // ---- VENTA DE MOSTRADOR ----

    public function mostrador()
    {
        $productos = Producto::with('talles')->get();
        return view('admin.ventas.regVentaMostrador', compact('productos'));
    }

    public function guardarVentaMostrador(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.talle_id' => 'nullable|exists:talles,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta_debito,tarjeta_credito,transferencia',
            'cliente_nombre' => 'nullable|string|max:255',
        ]);

        $orden = Orden::create([
            'nombre_contacto' => $request->cliente_nombre ?? 'Venta mostrador',
            'total' => 0,
            'estado' => 'pagada',
            'origen' => 'mostrador',
            'metodo_pago' => $request->metodo_pago,
        ]);

        $total = 0;

        foreach ($request->productos as $p) {
            $producto = Producto::with('talles')->find($p['id']);
            $precio = $producto->precio_lista_actual;

            ItemOrden::create([
                'orden_id' => $orden->id,
                'producto_id' => $producto->id,
                'talle' => $p['talle_nombre'] ?? null,
                'cantidad' => $p['cantidad'],
                'precio_unitario' => $precio,
                'subtotal' => $precio * $p['cantidad'],
            ]);

            if (!empty($p['talle_id'])) {
                $this->deducirStockTalle($producto, $p['talle_id'], $p['cantidad']);
            } else {
                $this->deducirStockProducto($producto, $p['cantidad']);
            }

            $total += $precio * $p['cantidad'];
        }

        $orden->update(['total' => $total]);

        return redirect()->route('admin.ventas.index')->with('success', "Venta de mostrador #{$orden->id} registrada correctamente.");
    }

    // ---- DETALLE DE ORDEN ----

    public function detalle($id)
    {
        $orden = Orden::with('items.producto', 'user')->findOrFail($id);
        return view('admin.ventas.detalle', compact('orden'));
    }

    // ---- CAMBIAR ESTADO (MÁQUINA DE ESTADOS) ----

    public function cambiarEstado(Request $request, $id)
    {
        $orden = Orden::with('items.producto.talles')->findOrFail($id);

        if ($orden->esEstadoFinal()) {
            return back()->with('error', "No se puede cambiar una orden {$orden->estado}.");
        }

        $request->validate([
            'estado' => 'required|in:creada,pagada,entregada,cancelada',
            'metodo_pago' => 'required_if:estado,pagada|nullable|in:efectivo,tarjeta,tarjeta_debito,tarjeta_credito,transferencia',
        ]);

        $nuevo = $request->estado;
        $viejo = $orden->estado;

        $transiciones = [
            'creada'    => ['pagada', 'entregada', 'cancelada'],
            'pagada'    => ['creada', 'entregada', 'cancelada'],
            'entregada' => [],
            'cancelada' => [],
        ];

        if (!in_array($nuevo, $transiciones[$viejo] ?? [])) {
            return back()->with('error', "No se puede pasar de «{$viejo}» a «{$nuevo}».");
        }

        if ($nuevo === 'cancelada') {
            $orden->restaurarStock();
        }

        if ($viejo === 'cancelada' && $nuevo !== 'cancelada') {
            $orden->deducirStock();
        }

        $updateData = ['estado' => $nuevo];

        if ($nuevo === 'pagada' && $request->filled('metodo_pago')) {
            $updateData['metodo_pago'] = $request->metodo_pago;
        }

        $orden->update($updateData);

        $mensajes = [
            'creada'    => 'Orden reactivada correctamente.',
            'pagada'    => 'Orden marcada como pagada.',
            'entregada' => 'Orden marcada como entregada.',
            'cancelada' => 'Orden cancelada y stock restaurado.',
        ];

        return back()->with('success', $mensajes[$nuevo] ?? 'Estado actualizado.');
    }

    // ---- EXTRACTO ----

    public function extracto(Request $request)
    {
        $ordenes = Orden::with('items.producto', 'user')->whereNotIn('estado', ['entregada', 'cancelada']);

        if ($request->filled('estado')) {
            $ordenes->where('estado', $request->estado);
        }

        if ($request->filled('fecha_inicio')) {
            $ordenes->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $ordenes->whereDate('created_at', '<=', $request->fecha_fin);
        }

        if ($request->filled('buscar')) {
            $palabra = $request->buscar;
            $ordenes->where(function ($query) use ($palabra) {
                $query->where('id', 'like', "%{$palabra}%")
                    ->orWhere('nombre_contacto', 'like', "%{$palabra}%")
                    ->orWhereHas('user', function ($q) use ($palabra) {
                        $q->where('name', 'like', "%{$palabra}%");
                    });
            });
        }

        $ordenes = $ordenes->latest()->get();

        return view('admin.ventas.extracto', compact('ordenes'));
    }

    // ---- TICKET ----

    public function ticket($id)
    {
        $orden = Orden::with('items.producto', 'user')->findOrFail($id);

        if ($orden->estado !== 'pagada') {
            return back()->with('error', 'Solo se puede emitir ticket para órdenes pagadas.');
        }

        return view('admin.ventas.ticket', compact('orden'));
    }

    // ---- STOCK HELPERS ----

    private function deducirStockTalle($producto, $talleId, $cantidad)
    {
        $talle = $producto->talles()->where('talles.id', $talleId)->first();
        if (!$talle) return;

        $stockActual = (int) $talle->pivot->stock;
        $nuevoStock = max(0, $stockActual - $cantidad);
        $producto->talles()->updateExistingPivot($talleId, ['stock' => $nuevoStock]);
    }

    private function deducirStockProducto($producto, $cantidad)
    {
        $talles = $producto->talles->sortBy('pivot.stock');
        $restante = $cantidad;

        foreach ($talles as $talle) {
            if ($restante <= 0) break;
            $disponible = (int) $talle->pivot->stock;
            if ($disponible <= 0) continue;

            $aDeducir = min($restante, $disponible);
            $nuevoStock = $disponible - $aDeducir;
            $producto->talles()->updateExistingPivot($talle->id, ['stock' => $nuevoStock]);
            $restante -= $aDeducir;
        }
    }
}
