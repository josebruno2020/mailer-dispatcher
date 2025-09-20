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
    $this->mailer->setFrom($email->from['address'], $email->from['name']);
    $this->mailer->addAddress($email->to);     //Add a recipient
    // $this->mailer->addReplyTo('info@example.com', 'Information');
    if ($email->cc) {
      $this->mailer->addCC($email->cc);
    }
    if ($email->bcc) {
      $this->mailer->addBCC($email->bcc);
    }

    //Attachments
    // $this->mailer->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $this->mailer->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $this->mailer->isHTML(true);                                  //Set email format to HTML
    $this->mailer->Subject = $email->template->subject;
    $this->mailer->Body = $email->body;

    $this->mailer->send();
  }
}