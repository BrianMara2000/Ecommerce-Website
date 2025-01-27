<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
            'subtotal' => $this->total_price,
            'status' => $this->status,
            'customer' => [
                'id' => $this->user->id,
                'first_name' => $this->user->customer->first_name,
                'last_name' => $this->user->customer->last_name,
            ],
            'number_of_items' => $this->items->filter(function ($item) {
                return $item->product !== null;
            })->count(),
            'isPaid' => $this->isPaid,
            'items' => $this->items,
        ];
    }
}
