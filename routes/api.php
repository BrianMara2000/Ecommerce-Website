<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

Route::middleware('auth:sanctum', 'admin')
    ->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::apiResource('/products', ProductController::class);
        Route::get('/orders/statuses', [OrderController::class, 'getStatuses']);
        Route::put('/orders/change-status/{order}/{status}', [OrderController::class, 'updateStatus']);
        Route::apiResource('/orders', OrderController::class);
    });

Route::post('/login', [AuthController::class, 'login']);
