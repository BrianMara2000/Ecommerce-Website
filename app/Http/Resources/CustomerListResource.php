<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user_id,
            'name' => $this->user->name ?? null,
            'email' => $this->user->email ?? null,
            'status' => $this->status,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
        ];
    }
}
