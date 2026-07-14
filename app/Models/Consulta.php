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
}