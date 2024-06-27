<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Cart/Index');
    }

    public function store(Request $request, Product $product)
    {
    }
    public function update(Request $request)
    {
    }
    public function delete()
    {
    }
}
