<?php

namespace App\Enums;

enum EmailAttachmentDriver: string
{
  case LOCAL = 'local';
  case S3 = 's3';
}