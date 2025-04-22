<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AproppedController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\AdminController;


Route::get('/',[AproppedController::class,'ruta'])->name('ruta');



    Route::get('/usuarios', [AdminController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/usuarios/create', [AdminController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/usuarios', [AdminController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/usuarios/{user}/edit', [AdminController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{user}', [AdminController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{user}', [AdminController::class, 'destroy'])->name('admin.usuarios.destroy');

    Route::get('/reporte',[AdminController::class, 'boletas'])->name('reporte');
    Route::get('/dashboard', [AdminController::class, 'graph'])->name('dashboard');

// Usar rutas en español products.index
Route::get('/products', [ProductController::class, 'index'])->name('admin.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}/delete-image/{image}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::delete('products/{id}/deleteImage/{imagenId}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
Route::patch('/products/{product}/set-principal/{image}', [ProductController::class, 'setPrincipal'])->name('products.setPrincipal');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::post('/products/{id}/upload-image', [ProductController::class, 'uploadImage'])->name('products.uploadImage');



// Mostrar todos los productos
Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda.index');

// Mostrar un solo producto (detalles)
Route::get('/tienda/{id}', [TiendaController::class, 'show'])->name('tienda.show');

// Ordenar el producto
Route::post('/tienda/{producto}/ordenar', [TiendaController::class, 'ordenar'])->name('tienda.ordenar');


Route::get('/cliente', [ClienteController::class, 'index'])->name('home');
Route::get('/producto/{id}', [ClienteController::class, 'show'])->name('producto.detalle');
Route::get('/producto/ordenar/{id}', [ClienteController::class, 'ordenar'])->name('producto.ordenar');
Route::post('/ordenar/{id}', [ClienteController::class, 'guardarOrden'])->name('producto.ordenar.guardar');
// Ruta para registrar al cliente
Route::post('/registrar', [ClienteController::class, 'registrar'])->name('cliente.registrar');
// Para editar y eliminar perfil
Route::get('/usuario/editar', [ClienteController::class, 'editar'])->name('usuario.editar');
Route::post('/usuario/actualizar', [ClienteController::class, 'actualizar'])->name('usuario.actualizar');
Route::delete('/usuario/eliminar', [ClienteController::class, 'eliminar'])->name('usuario.eliminar');

Route::middleware('auth')->get('/orders', [OrderController::class, 'index'])->name('orders.index');


// Rutas del carrito
Route::post('/carrito/agregar/{id}', [ClienteController::class, 'agregarAlCarrito'])->name('carrito.agregar');
Route::get('/carrito', [ClienteController::class, 'verCarrito'])->name('carrito.ver');
Route::delete('/carrito/eliminar/{id}', [ClienteController::class, 'eliminarDelCarrito'])->name('carrito.eliminar');
Route::delete('/carrito/vaciar', [ClienteController::class, 'vaciarCarrito'])->name('carrito.vaciar');
Route::get('/carrito/ordenar', [ClienteController::class, 'vistaOrdenarCarrito'])->name('carrito.ordenar');
Route::put('/carrito/actualizar/{id}', [ClienteController::class, 'actualizarCantidad'])->name('carrito.actualizar');

//Rutas del vendedor
Route::get('/vender',[VendedorController::class,'vender'])->name('vendedor.index');
Route::get('/vender-prod', [VendedorController::class, 'mostrarProductos'])->name('vendedor.mostrar');


// API temporal para imágenes
Route::get('/api/producto/{id}/imagenes', function ($id) {
    return \App\Models\Image::where('producto_id', $id)->get();
});

Route::get('/api/producto/{id}/detalles', [VendedorController::class, 'apiDetalles']);

Route::match(['get', 'post'], '/boleta/ver', [VendedorController::class, 'verBoleta'])->name('boleta.ver');
Route::post('/boleta', [VendedorController::class, 'generarBoleta'])->name('boleta.generar');
