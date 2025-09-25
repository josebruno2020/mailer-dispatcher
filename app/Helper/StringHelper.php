<?php

namespace App\Helper;

class StringHelper
{
  public static function extractParameters(string $body): array
  {
    preg_match_all('/\{\{(\w+)\}\}/', $body, $matches);
    return array_values(array_unique($matches[1]));
  }
}