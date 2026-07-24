<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TalleController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuariosController;

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

    Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias.index');

    Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('admin.categorias.store');

    Route::get('/admin/categorias/{id}/alterar-estado', [CategoriaController::class, 'alterarEstado'])->name('admin.categoria.alterar-estado');

    Route::get('/admin/usuarios', [UsuariosController::class, 'index'])->name('admin.usuarios.index');

    Route::post('/admin/usuarios/store-admin', [UsuariosController::class, 'storeAdmin'])->name('admin.usuarios.store-admin');

    Route::put('/admin/usuarios/update-password', [UsuariosController::class, 'updatePassword'])->name('admin.usuarios.update-password');

    Route::get('/admin/usuarios/{id}/cambiar-estado', [UsuariosController::class, 'cambiarEstado'])->name('admin.usuarios.cambiar-estado');

    Route::get('/admin/productos', [ProductoController::class, 'indexAdmin'])->name('admin.productos.index');

    Route::get('/admin/ventas', [App\Http\Controllers\VentasController::class, 'index'])->name('admin.ventas.index');

    Route::get('/admin/consultas', [App\Http\Controllers\ConsultasController::class, 'index'])->name('admin.consultas.index');

    Route::get('/admin/productos/create', [ProductoController::class, 'create'])->name('admin.productos.create');

    Route::post('/admin/productos', [ProductoController::class, 'store'])->name('admin.productos.store');

    Route::post('/admin/talles', [TalleController::class, 'store'])->name('admin.talles.store');

    Route::get('/admin/productos/{id}/edit', [ProductoController::class, 'edit'])->name('admin.productos.edit');

    Route::put('/admin/productos/{id}', [ProductoController::class, 'update'])->name('admin.productos.update');

    Route::delete('/admin/productos/{id}', [ProductoController::class, 'destroy'])->name('admin.productos.destroy');

    Route::post('/admin/productos/{id}/reactivar', [ProductoController::class, 'reactivar'])->name('admin.productos.reactivar');
});

Route::get('/test-php', function () {
    phpinfo();
});

Route::get('/info.php', function () {
    return view('info');
});
