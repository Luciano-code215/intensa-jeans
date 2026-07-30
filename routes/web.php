<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TalleController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\CartController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/catalogo', [ProductoController::class, 'index'])->name('catalogo.index');

Route::get('/productosPub/{id}', [ProductoController::class, 'show'])->name('productos.show');

// RUTAS DE LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');//para ver la vista
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTAS DE REGISTRO
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');//para ver la vista
Route::post('/register', [AuthController::class, 'register']);


//--------RUTAS DE USUARIO-----------------
Route::middleware('auth')->group(function () {

    Route::prefix('carrito')->name('carrito.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/agregar/{id}', [CartController::class, 'add'])->name('add');
        Route::post('/actualizar/{id}', [CartController::class, 'update'])->name('update');
        Route::post('/eliminar/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('/vaciar', [CartController::class, 'clear'])->name('clear');
    });

    Route::get('/consultas', [App\Http\Controllers\ConsultasController::class, 'create'])->name('consultas.create');
    Route::post('/consultas', [App\Http\Controllers\ConsultasController::class, 'store'])->name('consultas.store');

    Route::get('/mis-pedidos', [App\Http\Controllers\PedidosController::class, 'index'])->name('pedidos.index');
});

Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/checkout/confirmacion/{id}', [App\Http\Controllers\CheckoutController::class, 'confirmacion'])->name('checkout.confirmacion');

//--------RUTAS DE ADMINISTRADOR-----------------

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias.index');

    Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('admin.categorias.store');

    Route::get('/admin/categorias/{id}/alterar-estado', [CategoriaController::class, 'alterarEstado'])->name('admin.categoria.alterar-estado');

    Route::get('/admin/usuarios', [UsuariosController::class, 'index'])->name('admin.usuarios.index');

    Route::post('/admin/usuarios/store-admin', [UsuariosController::class, 'storeAdmin'])->name('admin.usuarios.store-admin');

    Route::put('/admin/usuarios/update-password', [UsuariosController::class, 'updatePassword'])->name('admin.usuarios.update-password');

    Route::get('/admin/usuarios/{id}/cambiar-estado', [UsuariosController::class, 'cambiarEstado'])->name('admin.usuarios.cambiar-estado');

    Route::get('/admin/productos', [ProductoController::class, 'indexAdmin'])->name('admin.productos.index');

    Route::get('/admin/ventas', [App\Http\Controllers\VentasController::class, 'index'])->name('admin.ventas.index');
    Route::get('/admin/ventas/extracto', [App\Http\Controllers\VentasController::class, 'extracto'])->name('admin.ventas.extracto');
    Route::get('/admin/ventas/mostrador', [App\Http\Controllers\VentasController::class, 'mostrador'])->name('admin.ventas.mostrador');
    Route::post('/admin/ventas/mostrador/guardar', [App\Http\Controllers\VentasController::class, 'guardarVentaMostrador'])->name('admin.ventas.guardarVentaMostrador');
    Route::post('/admin/ventas/{id}/cambiar-estado', [App\Http\Controllers\VentasController::class, 'cambiarEstado'])->name('admin.ventas.cambiar-estado');
    Route::get('/admin/ventas/{id}/ticket', [App\Http\Controllers\VentasController::class, 'ticket'])->name('admin.ventas.ticket');
    Route::get('/admin/ventas/{id}/detalle', [App\Http\Controllers\VentasController::class, 'detalle'])->name('admin.ventas.detalle');

    Route::get('/admin/consultas', [App\Http\Controllers\ConsultasController::class, 'index'])->name('admin.consultas.index');
    Route::post('/admin/consultas/{id}/leido', [App\Http\Controllers\ConsultasController::class, 'marcarLeido'])->name('admin.consultas.leido');

    Route::get('/admin/banner', [App\Http\Controllers\BannerController::class, 'edit'])->name('admin.banner');
    Route::post('/admin/banner', [App\Http\Controllers\BannerController::class, 'update'])->name('admin.banner.update');

    Route::get('/admin/productos/create', [ProductoController::class, 'create'])->name('admin.productos.create');

    Route::post('/admin/productos', [ProductoController::class, 'store'])->name('admin.productos.store');

    Route::post('/admin/talles', [TalleController::class, 'store'])->name('admin.talles.store');

    Route::get('/admin/productos/{id}/edit', [ProductoController::class, 'edit'])->name('admin.productos.edit');

    Route::put('/admin/productos/{id}', [ProductoController::class, 'update'])->name('admin.productos.update');

    Route::delete('/admin/productos/{id}', [ProductoController::class, 'destroy'])->name('admin.productos.destroy');

    Route::post('/admin/productos/{id}/reactivar', [ProductoController::class, 'reactivar'])->name('admin.productos.reactivar');
});

// PÁGINAS ESTÁTICAS
Route::view('/quienes-somos', 'paginas.quienes-somos')->name('pagina.quienes-somos');
Route::view('/como-comprar', 'paginas.como-comprar')->name('pagina.como-comprar');
Route::view('/terminos', 'paginas.terminos')->name('pagina.terminos');

Route::get('/test-php', function () {
    phpinfo();
});

Route::get('/info.php', function () {
    return view('info');
});
