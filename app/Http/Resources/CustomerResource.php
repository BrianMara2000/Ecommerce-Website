<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public static $wrap = false;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->user->email ?? null,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'billing_address' => new CustomerAddressResource($this->billingAddress) ?? null,
            'shipping_address' => new CustomerAddressResource($this->shippingAddress) ?? null,
        ];
    }
}
