<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = ['user_id', 'asunto', 'mensaje', 'estado'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function cantPendientes()
    {
        return self::where('estado', 'pendiente')->count();
    }

    public static function cantRespondidas()
    {
        return self::where('estado', 'respondida')->count();
    }

    public static function total()
    {
        return self::count();
    }
}