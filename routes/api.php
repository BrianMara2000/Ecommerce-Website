<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;

Route::middleware('auth:sanctum', 'admin')
    ->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/products/statuses', [ProductController::class, 'getStatuses']);
        Route::get('/products/archived', [ProductController::class, 'getArchived']);
        Route::patch('/products/{product}/archive', [ProductController::class, 'archive']);
        Route::apiResource('/products', ProductController::class);

        Route::get('/orders/statuses', [OrderController::class, 'getStatuses']);
        Route::put('/orders/change-status/{order}/{status}', [OrderController::class, 'updateStatus']);
        Route::apiResource('/orders', OrderController::class);
        Route::apiResource('/users', UserController::class);

        Route::apiResource('/customers', CustomerController::class);

        Route::prefix('/dashboard')->group(function () {
            Route::get('/statistics', [DashboardController::class, 'getStatistics']);
            Route::get('/revenues', [DashboardController::class, 'getRevenues']);
            Route::get('/country-customers', [DashboardController::class, 'getCountryCustomer']);
            Route::get('/latest-customers', [DashboardController::class, 'getLatestCustomers']);
            Route::get('/latest-orders', [DashboardController::class, 'getLatestOrders']);
        });

        Route::get('/reports/report', [ReportController::class, 'getReports']);
    });
Route::post('/login', [AuthController::class, 'login']);
