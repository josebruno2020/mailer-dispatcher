<?php

namespace App\Enums;

enum EmailStatusEnum: string
{
  case PENDING = 'pending';
  case SENT = 'sent';
  case RETRYING = 'retrying';
  case FAILED = 'failed';
}