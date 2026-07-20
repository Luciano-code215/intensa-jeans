<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'activo'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function cantidadProductosActivos()
    {
        return $this->productos()->where('activo', true)->count();
    }


}