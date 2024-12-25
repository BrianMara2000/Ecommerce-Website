<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['total_price', 'status', 'created_by', 'updated_by'];

    protected $appends = ['isPaid'];

    public function getIsPaidAttribute()
    {
        return $this->status === OrderStatus::Paid->value;
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
