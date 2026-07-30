<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrden extends Model
{
    protected $table = 'item_ordenes';
    protected $fillable = ['orden_id', 'producto_id', 'talle', 'cantidad', 'precio_unitario', 'subtotal'];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getPrecioEfectivoAttribute()
    {
        $porcDesc = $this->producto ? $this->producto->porc_desc_ef : 0;
        return $porcDesc > 0
            ? $this->precio_unitario * (1 - $porcDesc / 100)
            : $this->precio_unitario;
    }

    public function getSubtotalEfectivoAttribute()
    {
        return $this->precio_efectivo * $this->cantidad;
    }
}
