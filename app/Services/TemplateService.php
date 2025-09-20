<?php

namespace App\Services;

use App\Models\Template;

/**
 * @template-extends BaseService<Template>
 */
class TemplateService extends BaseService
{
  public function __construct(Template $model)
  {
    parent::__construct($model, "Template");
  }

  public function create(array $data): \Illuminate\Database\Eloquent\Model
  {
    $existing = $this->model->where('account_id', $data['account_id'])
      ->where('name', $data['name'])
      ->first();
    if ($existing) {
      throw new \InvalidArgumentException("Template with name '{$data['name']}' already exists for this account.");
    }
    return parent::create($data);
  }

  public function renderTemplate(string $templateId, array $parameters = []): string
  {
    $template = $this->findOrThrow($templateId);
    $body = $template->body;

    foreach ($parameters as $key => $value) {
      $body = str_replace("{{{$key}}}", $value, $body);
    }

    // Verifica se ainda há parâmetros não substituídos
    if (preg_match('/\{\{(.*?)\}\}/', $body)) {
      throw new \InvalidArgumentException("Not all parameters were provided for the template.");
    }

    return $body;
  }
}