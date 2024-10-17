<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/misProductos', [ProductController::class, 'index'])->name('misProductos'); // Muestra solo los productos del usuario logueado
Route::get('/misProductos/store', 'formProduct')->name('misProductos.store'); // Muestra solo los productos del usuario logueado

// Ruta para gestionar los productos (CRUD completo)
Route::resource('products', ProductController::class)->except(['index', 'destroy']); // Elimina 'index' para evitar conflicto con la ruta '/misProductos'

// Ruta para mostrar el catálogo de productos
Route::get('/catalogo', [ProductController::class, 'catalogo'])->name('catalogo');

// FIN RUTA DE PRODUCTOS


// RUTAS CARRITO

// Ruta agregar productos carrito
Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregarAlCarrito'])->name('carrito.agregar');

// Ruta ver carrito
Route::get('/carrito', [CarritoController::class, 'verCarrito'])->name('carrito.ver');

// Ruta eliminar producto carrito
Route::post('/carrito/eliminar/{id}', [CarritoController::class, 'eliminarDelCarrito'])->name('carrito.eliminar');

// Ruta actualizar carrito
Route::patch('/carrito/{id}/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
