<?php

namespace App\Livewire\Forms;

use App\Livewire\SettingCreate;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SettingForm extends Form
{
  #[Validate('required|string|max:255')]
  public string $name = '';

  #[Validate('required|string|max:255')]
  public string $host = '';

  #[Validate('required|integer|min:1|max:65535')]
  public string $port = '';

  #[Validate('required|string|max:255')]
  public string $username = '';

  #[Validate('required|string|max:255')]
  public string $password = '';

  public static function from(self $form, array $data): self
  {
    $form->fill($data);
    return $form;
  }
}