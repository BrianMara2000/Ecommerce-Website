<?php

namespace App\Enums;


enum ProductStatus: string
{
  case Published = 'published';
  case Unpublished = 'unpublished';
  case Archive = 'archive';

  public static function getStatuses(): array
  {
    return array_column(self::cases(), 'value');
  }
}
