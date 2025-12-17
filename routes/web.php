<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;

// Rutas publicas

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categoria/{category}', [HomeController::class, 'category'])->name('tienda.category');
Route::get('/producto/{slug}', [HomeController::class, 'show'])->name('tienda.show');



    //rutas del carrito
Route::get('/carrito', [CartController::class,'index'])->name('cart.index');   
Route::post('/carrito/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/carrito/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');




Route::get('/checkout', function(){
    //Preparado para guardar el pedido en el BD
    return "Procesando pedido de: " . count(session('cart')) . " productos.";
})->name('checkout')->middleware('auth'); 

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Rutas logeados
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout');
    Route::post('/finalizar-pedido', [OrderController::class, 'store'])->name('order.store');

});

require __DIR__.'/auth.php';
