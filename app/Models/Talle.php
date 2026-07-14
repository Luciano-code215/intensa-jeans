<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talle extends Model
{
    protected $fillable = ['nombre', 'activo'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_talles')->withPivot('stock');
    }
}
