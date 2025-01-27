<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Mail\OrderUpdateEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderListResource;

class OrderController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Order::query()->join('users', 'orders.created_by', '=', 'users.id')
            ->select('orders.*', 'users.name');
        $query->orderBy($sortField, $sortDirection);
        if ($search) {
            $query->where('id', 'like', "%{$search}%")
                ->orWhere('customers.first_name', 'like', "%{$search}%");
        }
        return OrderListResource::collection($query->paginate($perPage));
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function getStatuses()
    {
        return response()->json(OrderStatus::getStatuses());
    }

    public function updateStatus(Order $order, $status)
    {
        $order->status = $status;
        $order->save();

        Mail::to($order->user->email)->send(new OrderUpdateEmail($order));
        return response()->json(['message' => 'Order status updated successfully']);
    }
}
