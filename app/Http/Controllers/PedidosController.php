<?php

namespace App\Http\Controllers;

use App\Models\Orden;

class PedidosController extends Controller
{
    public function index()
    {
        $pedidos = Orden::with('items.producto')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }
}
