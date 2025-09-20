<?php

namespace App\Services;

use App\Models\Setting;

/**
 * @template-extends BaseService<Setting>
 */
class SettingService extends BaseService
{
  public function __construct(Setting $model)
  {
    parent::__construct($model, "Setting");
  }

  public function create(array $data): \Illuminate\Database\Eloquent\Model
  {
    $existing = $this->model->where('account_id', $data['account_id'])
      ->where('name', $data['name'])
      ->first();
    if ($existing) {
      throw new \InvalidArgumentException("Setting with name '{$data['name']}' already exists for this account.");
    }
    return parent::create($data);
  }
}