<?php

namespace App\Services;

use App\Jobs\ProcessEmail;
use App\Models\Email;

/**
 * @template-extends BaseService<Email>
 */
class EmailService extends BaseService
{
  public function __construct(
    Email $model,
    private SettingService $settingService,
    private TemplateService $templateService
  ) {
    parent::__construct($model, "Email");
  }

  public function create(array $data): \Illuminate\Database\Eloquent\Model
  {
    $this->settingService->findOrThrow($data['setting_id']);
    $this->templateService->findOrThrow($data['template_id']);
    $data['body'] = $this->templateService->renderTemplate(
      $data['template_id'],
      $data['parameters'] ?? []
    );
    $email = parent::create($data);
    // envia para a fila;
    if (!$email->scheduled_at || $email->scheduled_at <= now()) {
      ProcessEmail::dispatch($email);
    }
    return $email;
  }
}