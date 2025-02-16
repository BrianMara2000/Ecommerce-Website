<?php

namespace App\Http\Controllers\User;

use App\Enums\ProductStatus;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use App\Http\Resources\ProductListResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->where('status', ProductStatus::Published->value)->orderBy('updated_at', 'desc')->paginate(10);

        return Inertia::render('User/Product/Index', [
            'products' => $products,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function show(Product $product)
    {
        return Inertia::render('User/Product/View', ['product' => $product]);
    }
}
