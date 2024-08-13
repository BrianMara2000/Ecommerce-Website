<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\User\ProductController;


Route::middleware(['guestOrVerified'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('user.home');
    Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.view');

    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        Route::patch('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('updateQuantity');
    });
});

// Route::prefix('cart')->controller(CartController::class)->group(function () {
//     Route::get('view', 'view')->name('cart.view');
//     Route::post('store/{product}', 'store')->name('cart.store');
//     Route::patch('update/{product}', 'update')->name('cart.update');
//     Route::delete('delete/{product}', 'delete')->name('cart.delete');
// });

require __DIR__ . '/auth.php';
