<?php

namespace App\Enums;


enum OrderStatus: string
{
  case Unpaid = 'unpaid';
  case Paid = 'paid';
  case Shipped = 'shipped';
  case Cancelled = 'cancelled';
  case Completed = 'completed';

  public static function getStatuses(): array
  {
    return array_column(self::cases(), 'value');
  }
}
