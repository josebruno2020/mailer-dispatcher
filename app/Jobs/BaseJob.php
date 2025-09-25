<?php

namespace App\Jobs;

abstract class BaseJob
{
  public $tries = 3;
  public $maxExceptions = 3;
}