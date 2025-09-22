<?php

namespace App\Services\External;

use App\Models\Email;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class S3UploadService
{
  public function upload(Email $email, UploadedFile $file): string
    {
        $t = time();
        $path = "email_attachments/{$email->id}";
        
        // putFileAs já retorna o path completo
        $fullPath = Storage::disk('s3')->putFileAs(
            $path, 
            $file, 
            $t . '_' . $file->getClientOriginalName()
        );
        
        return $fullPath;
    }
}