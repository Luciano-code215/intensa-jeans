<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductoTalle extends Pivot
{
    protected $table = 'producto_talles';
    protected $fillable = ['producto_id', 'talle_id', 'stock'];
}
