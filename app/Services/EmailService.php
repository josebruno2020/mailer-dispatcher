<?php

namespace App\Services;

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
    private TemplateService $templateService,
    private EmailAttachmentService $emailAttachmentService,
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
    if (count($data['attachments'] ?? []) > 0) {
      foreach ($data['attachments'] as $attachment) {
        $this->emailAttachmentService->create([
          'email' => $email,
          'file' => $attachment,
          'driver' => 'local',
        ]);
      }
    }
    event(new EmailCreatedEvent(
      email: $email
    ));
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
    $newEmail->error_message = null;
    $newEmail->save();
    event(new EmailCreatedEvent(
      email: $newEmail
    ));
    return $newEmail;
  }
}