<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index']);         // Listar todos los productos
Route::get('/products/{product}', [ProductController::class, 'show']);  // Ver un producto específico
Route::post('/products', [ProductController::class, 'store']);         // Crear un nuevo producto
Route::put('/products/{product}', [ProductController::class, 'update']); // Actualizar un producto existente
Route::delete('/products/{product}', [ProductController::class, 'destroy']); // Eliminar un producto
