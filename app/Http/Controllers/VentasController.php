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

    // ---- EDICIÓN DE ORDEN ----

    public function editar($id)
    {
        $orden = Orden::with('items.producto.talles')->findOrFail($id);

        if ($orden->esEstadoFinal()) {
            return back()->with('error', "No se puede modificar una orden {$orden->estado}.");
        }

        $productos = Producto::with('talles')->get();
        return view('admin.ventas.editar', compact('orden', 'productos'));
    }

    public function actualizarItems(Request $request, $id)
    {
        $orden = Orden::with('items.producto.talles')->findOrFail($id);

        if ($orden->esEstadoFinal()) {
            return back()->with('error', "No se puede modificar una orden {$orden->estado}.");
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:item_ordenes,id',
            'items.*.cantidad' => 'required|integer|min:0',
            'nuevos' => 'nullable|array',
            'nuevos.*.producto_id' => 'required|exists:productos,id',
            'nuevos.*.cantidad' => 'required|integer|min:1',
        ]);

        // Restaurar stock actual antes de modificar
        $orden->restaurarStock();

        // Actualizar items existentes
        foreach ($request->items as $itemData) {
            $item = ItemOrden::find($itemData['id']);
            if (!$item || $item->orden_id != $orden->id) continue;

            if ((int) $itemData['cantidad'] === 0) {
                $item->delete();
                continue;
            }

            $item->update([
                'cantidad' => $itemData['cantidad'],
                'subtotal' => $item->precio_unitario * $itemData['cantidad'],
            ]);
        }

        // Agregar nuevos items
        if ($request->has('nuevos')) {
            foreach ($request->nuevos as $nuevo) {
                $producto = Producto::with('talles')->find($nuevo['producto_id']);
                $precio = $producto->precio_lista_actual;

                ItemOrden::create([
                    'orden_id' => $orden->id,
                    'producto_id' => $producto->id,
                    'talle' => null,
                    'cantidad' => $nuevo['cantidad'],
                    'precio_unitario' => $precio,
                    'subtotal' => $precio * $nuevo['cantidad'],
                ]);
            }
        }

        $orden->load('items.producto.talles');
        $orden->calcularTotal();
        $orden->deducirStock();

        return redirect()->route('admin.ventas.index')->with('success', "Orden #{$orden->id} actualizada correctamente.");
    }

    // ---- CAMBIAR ESTADO ----

    public function cambiarEstado(Request $request, $id)
    {
        $orden = Orden::with('items.producto.talles')->findOrFail($id);

        if ($orden->esEstadoFinal()) {
            return back()->with('error', "No se puede cambiar el estado de una orden {$orden->estado}.");
        }

        $request->validate([
            'estado' => 'required|in:creada,pagada,entregada,cancelada',
            'metodo_pago' => 'required_if:estado,pagada|in:efectivo,tarjeta,transferencia,tarjeta_debito,tarjeta_credito',
        ]);

        $nuevoEstado = $request->estado;
        $viejoEstado = $orden->estado;

        if ($nuevoEstado === 'cancelada' && $viejoEstado !== 'cancelada') {
            $orden->restaurarStock();
        }

        if ($viejoEstado === 'cancelada' && $nuevoEstado !== 'cancelada') {
            $orden->deducirStock();
        }

        $updateData = ['estado' => $nuevoEstado];

        if ($nuevoEstado === 'pagada' && $request->filled('metodo_pago')) {
            $updateData['metodo_pago'] = $request->metodo_pago;
        }

        $orden->update($updateData);

        $mensajes = [
            'creada' => 'Orden reactivada correctamente.',
            'pagada' => 'Orden marcada como pagada.',
            'entregada' => 'Orden marcada como entregada.',
            'cancelada' => 'Orden cancelada. El stock fue restaurado.',
        ];

        return back()->with('success', $mensajes[$nuevoEstado] ?? 'Estado actualizado.');
    }

    // ---- EXTRACTO ----

    public function extracto(Request $request)
    {
        $ordenes = Orden::whereNotIn('estado', ['entregada', 'cancelada']);

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

        $mapaPago = [
            'efectivo' => 'Efectivo',
            'tarjeta' => 'Tarjeta',
            'transferencia' => 'Transferencia',
            'tarjeta_debito' => 'Tarjeta Débito',
            'tarjeta_credito' => 'Tarjeta Crédito',
        ];

        $mapaOrigen = [
            'web' => 'Web',
            'mostrador' => 'Mostrador',
        ];

        $totalGeneral = $ordenes->sum('total');

        return view('admin.ventas.extracto', compact('ordenes', 'mapaPago', 'mapaOrigen', 'totalGeneral'));
    }

    // ---- TICKET ----

    public function ticket($id)
    {
        $orden = Orden::with('items.producto', 'user')->findOrFail($id);

        if (!in_array($orden->estado, ['creada', 'pagada', 'entregada'])) {
            return back()->with('error', 'No hay ticket disponible para esta orden.');
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
