<?php

namespace App\Services;

use App\Events\EmailCreated;
use App\Events\EmailCreatedEvent;
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
    event(new EmailCreatedEvent($email));
    return $email;
  }

  public function resend(string $id): Email
  {
    $email = $this->findOrThrow($id);
    $newEmail = new Email($email->toArray());
    $newEmail->status = 'pending';
    $newEmail->scheduled_at = null;
    $newEmail->sent_at = null;
    $newEmail->updated_at = null;
    $newEmail->save();
    event(new EmailCreatedEvent($newEmail));
    return $newEmail;
  }
}