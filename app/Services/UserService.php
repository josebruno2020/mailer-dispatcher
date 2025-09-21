<?php

namespace App\Services;

class UserService extends BaseService
{
  public function __construct(\App\Models\User $model)
  {
    parent::__construct($model, "User");
  }
}