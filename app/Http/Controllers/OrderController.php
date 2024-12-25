<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $order = Order::query()
            ->with('payment', 'items')
            ->where([
                'created_by' => $user->id,
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(5);


        return Inertia::render('User/Order/Index', ['orders' => $order]);
    }

    public function view(Request $request, Order $order)
    {
        $user = request()->user();

        $order = Order::query()
            ->with('items')
            ->where([
                'id' => $order->id,
            ])
            ->first();


        if ($order->created_by !== $user->id) {
            abort(403, "You're not authorized to view this order.");
        }

        $order = Order::with(['items.product'])->where('id', $order->id)->first();

        return Inertia::render('User/Order/View', ['order' => $order]);
    }
}
