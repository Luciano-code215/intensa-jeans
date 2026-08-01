<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\Consulta;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $ventasMes = Orden::where('estado', 'pagada')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $ventasMesAnterior = Orden::where('estado', 'pagada')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $pctVentas = $ventasMesAnterior > 0 ? round(($ventasMes - $ventasMesAnterior) / $ventasMesAnterior * 100, 1) : 0;

        $pedidosPendientes = Orden::where('estado', 'creada')->count();
        $pendientesEnvio = Orden::where('estado', 'pagada')->count();
        $prendasActivas = Producto::where('activo', true)->count();
        $sinStock = Producto::where('activo', true)->get()->filter(fn($p) => $p->stock <= 0)->count();
        $consultasPendientes = Consulta::where('estado', 'pendiente')->count();
        $usuariosRegistrados = User::count();

        $ultimasOrdenes = Orden::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'ventasMes', 'pctVentas', 'pedidosPendientes', 'pendientesEnvio',
            'prendasActivas', 'sinStock', 'consultasPendientes',
            'usuariosRegistrados', 'ultimasOrdenes'
        ));
    }
}
