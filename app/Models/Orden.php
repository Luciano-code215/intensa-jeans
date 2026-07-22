<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes'; // Forzamos el plural correcto en español
    protected $fillable = ['user_id', 'total', 'estado', 'origen'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ItemOrden::class, 'orden_id');
    }

    // Método para recalcular el total en base a sus ítems asociados
    public function calcularTotal()
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }

    static public function cantidadVentasPorEstado($estado)
    {
        return self::where('estado', $estado)->count();
    }
}
