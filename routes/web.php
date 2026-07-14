<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TalleController;

Route::get('/', function () {
    return view('index');
});

Route::get('/catalogo', [ProductoController::class, 'index'])->name('catalogo');

Route::get('/productosPub/{id}', [ProductoController::class, 'show'])->name('productos.show');

// RUTAS DE LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');//para ver la vista
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTAS DE REGISTRO
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');//para ver la vista
Route::post('/register', [AuthController::class, 'register']);


//--------RUTAS DE ADMINISTRADOR-----------------

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/admin/categorias', function () {
        return view('admin.categorias.index');
    })->name('admin.categorias.index');

    Route::get('/admin/usuarios', function () {
        return view('admin.usuarios.index');
    })->name('admin.usuarios.index');

    Route::get('/admin/productos', function () {
        return view('admin.productos.index');
    })->name('admin.productos.index');

    Route::get('/admin/ventas', function () {
        return view('admin.ventas.index');
    })->name('admin.ventas.index');

    Route::get('/admin/consultas', function () {
        return view('admin.consultas.index');
    })->name('admin.consultas.index');

    Route::get('/admin/productos/create', [ProductoController::class, 'create'])->name('admin.productos.create');

    Route::post('/admin/productos', [ProductoController::class, 'store'])->name('admin.productos.store');

    Route::post('/admin/talles', [TalleController::class, 'store'])->name('admin.talles.store');

    Route::get('/admin/productos/{id}/edit', [ProductoController::class, 'edit'])->name('admin.productos.edit');

    Route::put('/admin/productos/{id}', [ProductoController::class, 'update'])->name('admin.productos.update');
});

Route::get('/test-php', function () {
    phpinfo();
});

Route::get('/info.php', function () {
    return view('info');
});
