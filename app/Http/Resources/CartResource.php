<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Helpers\Cart;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        [$products, $cartItems] = $this->resource;

        $total = $products->reduce(fn(?float $carry, Product $product) => $carry + $product->price * $cartItems[$product->id]['quantity'], 0.0);
        $total = round($total, 2);

        return [
            'count' => Cart::getCartItemsCount(),
            'total' => $total,
            'items' => $cartItems,
            'products' => ProductResource::collection($products)
        ];
    }
}
