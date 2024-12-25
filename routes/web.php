<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ProductController;


Route::middleware(['guestOrVerified'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('user.home');
    Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.view');

    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        Route::delete('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        Route::patch('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('updateQuantity');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('/checkout')->controller(CheckoutController::class)->group((function () {
        Route::post('/order', 'store')->name('checkout.store');
        Route::get('/success', 'success')->name('checkout.success');
        Route::get('/failure', 'failure')->name('checkout.failure');
        Route::post('/{order}', 'checkoutOrder')->name('checkout.order');
    }));

    Route::prefix('/my-order')->controller(OrderController::class)->group((function () {
        Route::get('/', 'index')->name('my-order.index');
        Route::get('/{order}', 'view')->name('my-order.view');
    }));
});

Route::post('/webhook/stripe', [CheckoutController::class, 'webhook']);

require __DIR__ . '/auth.php';
