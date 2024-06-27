<?php

use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ProductController::class, 'index'])->name('user.home');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.view');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::prefix('cart')->controller(CartController::class)->group(function () {
//     Route::get('view', 'view')->name('cart.view');
//     Route::post('store/{product}', 'store')->name('cart.store');
//     Route::patch('update/{product}', 'update')->name('cart.update');
//     Route::delete('delete/{product}', 'delete')->name('cart.delete');
// });

require __DIR__ . '/auth.php';
