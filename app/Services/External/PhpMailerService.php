<?php

namespace App\Services\External;

use App\Models\Email;
use App\Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;

class PhpMailerService
{
  private PHPMailer $mailer;
  public function configure(Setting $setting): self
  {
    $this->mailer = new PHPMailer(true);
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

  public function send(Email $email)
  {
    $this->mailer->setFrom(address: $email->from['address'], name: $email->from['name'] ?? null);
    $this->mailer->addAddress(address: $email->to['address'], name: $email->to['name'] ?? null);
    $this->mailer->addReplyTo(address: $email->replyTo['address'], name: $email->replyTo['name'] ?? null);
    if ($email->cc) {
      $this->mailer->addCC(address: $email->cc);
    }
    if ($email->bcc) {
      $this->mailer->addBCC(address: $email->bcc);
    }

    //Attachments
    // $this->mailer->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $this->mailer->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $this->mailer->isHTML(true);
    $this->mailer->Subject = $email->template->subject;
    $this->mailer->Body = $email->body;

    $this->mailer->send();
  }
}