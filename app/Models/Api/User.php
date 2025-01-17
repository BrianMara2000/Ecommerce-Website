<?php

namespace App\Models\Api;


class User extends \App\Models\User
{
  public function getRouteKeyName()
  {
    return 'id';
  }
}
