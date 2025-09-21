<?php

namespace App\Services;

use App\Models\Account;

/**
 * @template-extends BaseService<Account>
 */
class AccountService extends BaseService
{
  public function __construct(Account $model)
  {
    parent::__construct($model, "Account");
  }
}