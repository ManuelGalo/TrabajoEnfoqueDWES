<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;



// RUTAS PÚBLICAS


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categoria/{category}', [HomeController::class, 'category'])->name('tienda.category');
Route::get('/producto/{slug}', [HomeController::class, 'show'])->name('tienda.show');

// Carrito (público - sin auth para ver)
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/carrito/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');


// RUTAS AUTENTICADAS


Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout y Pedidos
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('cart.checkout');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{order}/payment', [OrderController::class, 'payment'])->name('order.payment');
    Route::post('/order/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('order.confirm-payment');
    Route::get('/order/{order}/success', [OrderController::class, 'success'])->name('order.success');
});

require __DIR__.'/auth.php';
