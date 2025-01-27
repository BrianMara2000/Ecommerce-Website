<?php

namespace App\Models\Api;


class Customer extends \App\Models\Customer
{
  public function getRouteKeyName()
  {
    return 'user_id';
  }
}
