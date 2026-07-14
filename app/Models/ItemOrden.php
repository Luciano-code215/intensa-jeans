<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrden extends Model
{
    protected $table = 'item_ordenes';
    protected $fillable = ['orden_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal'];

    protected static function booted()
    {
        static::creating(function ($item) {
            // Buscamos el producto para extraer su precio_final vigente
            $producto = Producto::find($item->producto_id);

            if ($producto) {
                $item->precio_unitario = $producto->precio_final;
                $item->subtotal = $item->precio_unitario * $item->cantidad;
            }
        });

        // Al guardarse un ítem con éxito, recalculamos el total general de la Orden madre
        static::created(function ($item) {
            $item->orden->calcularTotal();
        });
    }

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
