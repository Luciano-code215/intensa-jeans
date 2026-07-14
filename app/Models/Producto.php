<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'categoria_id',
        'descripcion',
        'sku',
        'porc_desc_ef',
        'activo',
        'url_imagen',
        'liquidacion',
        'porc_liquidacion',
        'precio_ef',
    ];

    protected $casts = [
        'liquidacion' => 'boolean',
    ];

    public function getPrecioListaActualAttribute()
    {
        if ($this->liquidacion && $this->porc_liquidacion > 0) {
            return $this->precio - ($this->precio * ($this->porc_liquidacion / 100));
        }
        return $this->precio;
    }

    public function getPrecioEfActualAttribute()
    {
        $precioBase = $this->precio_lista_actual; // Usa el método de arriba automáticamente

        if ($this->porc_desc_ef > 0) {
            return $precioBase - ($precioBase * ($this->porc_desc_ef / 100));
        }
        return $precioBase;
    }

    protected static function booted()
    {
        static::saving(function ($producto) {
            // 1. Nos aseguramos de tener los valores numéricos limpios (si no hay descuento, es 0)
            $precioOriginal = (float) $producto->precio;
            $porcentajeDescuento = (int) ($producto->porc_desc_ef ?? 0);

            // 2. Si hay un porcentaje de descuento válido, aplicamos la rebaja
            if ($porcentajeDescuento > 0 && $porcentajeDescuento <= 100) {
                $descuento = $precioOriginal * ($porcentajeDescuento / 100);
                $producto->precio_ef = $precioOriginal - $descuento;
            } else {
                // Si el descuento es 0, el precio final es igual al precio original
                $producto->precio_ef = $precioOriginal;
            }
        });
    }



    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function talles()
    {
        return $this->belongsToMany(Talle::class, 'producto_talles')->withPivot('stock');
    }

    public function esNuevo()
    {
        // Consideramos "nuevo" si el producto fue creado en los últimos 7 días
        return $this->created_at >= now()->subDays(7);
    }

    public function esAgotado()
    {
        return $this->stock === 0; // Consideramos "agotado" si el stock es igual a 0
    }

    public function tieneDescuento()
    {
        return $this->porc_desc > 0; // Consideramos que tiene descuento si el porcentaje de descuento es mayor a 0
    }

    // Un producto tiene muchas imágenes secundarias
    public function imagenesSecundarias()
    {
        return $this->hasMany(ImagenProducto::class, 'producto_id');
    }

    public function stockTotal()
    {
        return $this->talles()->sum('stock');
    }
}
