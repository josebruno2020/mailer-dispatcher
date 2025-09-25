<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class TemplateForm extends Form
{
  #[Validate('required|string|max:255')]
  public string $name = '';
  #[Validate('required|string|max:255')]
  public string $subject = '';
  #[Validate('nullable|string|max:255')]
  public ?string $description = '';
  #[Validate('required|string')]
  public string $body = '';
  public ?string $webhook_url = '';
  public ?array $webhook_headers = null;

  public static function from(self $form, array $data): self
  {
    $form->fill($data);
    return $form;
  }
}