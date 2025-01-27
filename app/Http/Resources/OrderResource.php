<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public static $wrap = false;

    public function toArray(Request $request): array
    {
        $user = $this->user;
        $customer = $user ? $user->customer : null;

        return [
            'id' => $this->id,
            'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
            'subtotal' => $this->total_price,
            'status' => $this->status,
            'customer' => $customer ? [
                'id' => $user->id,
                'email' => $user->email,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'phone' => $customer->phone,
                'billing_address' => new CustomerAddressResource($customer->billingAddress),
                'shipping_address' => new CustomerAddressResource($customer->shippingAddress),
            ] : null,
            'isPaid' => $this->isPaid,
            'number_of_items' => $this->items->filter(function ($item) {
                return $item->product !== null;
            })->count(),
            'items' => $this->items->filter(function ($item) {
                return $item->product !== null;
            })->map(function ($item) {
                $product = $item->product;
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'product' => [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'title' => $product->title,
                        'image' => $product->image,
                    ],
                ];
            }),
        ];
    }
}
