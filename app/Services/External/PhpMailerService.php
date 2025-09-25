<?php

namespace App\Services\External;

use App\Enums\EmailAttachmentDriver;
use App\Models\Email;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;

class PhpMailerService
{
  private PHPMailer $mailer;
  public function configure(Setting $setting): self
  {
    $this->mailer = new PHPMailer(true);
    $this->mailer->SMTPDebug = config('app.env') === 'local' ? 4 : 0;
    $this->mailer->isSMTP();
    $this->mailer->Host = $setting->host;
    $this->mailer->SMTPAuth = true;
    $this->mailer->Username = $setting->username;
    $this->mailer->Password = $setting->password;

    if ($setting->port == 465) {
      $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
      $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $this->mailer->Port = $setting->port;
    $this->mailer->SMTPOptions = array(
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );

    $this->mailer->CharSet = 'UTF-8';
    $this->mailer->Encoding = 'base64';
    return $this;
  }

  public function send(Email $email): void
  {
    $this->mailer->setFrom(address: $email->from['address'], name: $email->from['name'] ?? null);
    $this->mailer->addAddress(address: $email->to['address'], name: $email->to['name'] ?? null);
    if ($email->replyTo) {
      $this->mailer->addReplyTo(address: $email->replyTo['address'], name: $email->replyTo['name'] ?? null);
    }
    if ($email->cc) {
      $this->mailer->addCC(address: $email->cc);
    }
    if ($email->bcc) {
      $this->mailer->addBCC(address: $email->bcc);
    }

    // Attachments
    $tempFiles = $this->setupAttachments($email);

    //Content
    $this->mailer->isHTML(true);
    $this->mailer->Subject = $email->template->subject;
    $this->mailer->Body = str_replace("\n", "\r\n", $email->body);

    try {
      $this->mailer->send();
      $this->cleanupTempFiles($tempFiles);
    } catch (Exception $e) {
      $this->cleanupTempFiles($tempFiles);
      throw $e;
    }
  }

  private function setupAttachments(Email $email): array
  {
    $tempFiles = [];
    if ($email->attachments->count() > 0) {
      foreach ($email->attachments as $attachment) {
        try {
          if ($attachment->driver === EmailAttachmentDriver::S3->value) {
            // Baixar arquivo do S3 para um arquivo temporário
            $fileContent = Storage::disk('s3')->get($attachment->file_path);
            $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_' . $attachment->file_name;
            file_put_contents($tempPath, $fileContent);

            $this->mailer->addAttachment($tempPath, $attachment->file_name);
            $tempFiles[] = $tempPath; // Para limpar depois

          } elseif ($attachment->driver === EmailAttachmentDriver::LOCAL->value) {
            // Para arquivos locais
            $localPath = Storage::disk('local')->path($attachment->file_path);
            if (file_exists($localPath)) {
              $this->mailer->addAttachment($localPath, $attachment->file_name);
            }
          }
        } catch (\Exception $e) {
          // Log do erro, mas continue enviando o email
          Log::error("Erro ao anexar arquivo: " . $e->getMessage());
        }
      }
    }
    return $tempFiles;
  }

  private function cleanupTempFiles(array $tempFiles): void
  {
    foreach ($tempFiles as $tempFile) {
      if (file_exists($tempFile)) {
        unlink($tempFile);
      }
    }
  }
}