<?php

namespace App\Services;

use App\Enums\EmailAttachmentDriver;
use App\Models\EmailAttachment;
use App\Services\External\S3UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @template-extends BaseService<EmailAttachment>
 */
class EmailAttachmentService extends BaseService
{
  public function __construct(
    EmailAttachment $model,
    private readonly S3UploadService $s3UploadService
  ) {
    parent::__construct($model, "Email Attachment");
  }

  public function create(array $data): \Illuminate\Database\Eloquent\Model
  {
    if ($data['driver'] === EmailAttachmentDriver::LOCAL->value) {
      $path = $data['file']->store('email_attachments');
    } else if ($data['driver'] === EmailAttachmentDriver::S3->value) {
      $path = $this->s3UploadService->upload($data['email'], $data['file']);
    }
    $data['file_name'] = $data['file']->getClientOriginalName();
    $data['file_path'] = $path;
    $data['email_id'] = $data['email']->id;

    return parent::create($data);
  }

  public function updateDriver(string $id, string $driver): \Illuminate\Database\Eloquent\Model
  {
    $attachment = $this->findOrThrow($id);

    if ($driver === EmailAttachmentDriver::S3->value && $attachment->driver === EmailAttachmentDriver::LOCAL->value) {
      // Pegar o arquivo do storage local
      $localPath = $attachment->file_path;

      if (Storage::disk('local')->exists($localPath)) {
        // Criar um arquivo temporário para simular UploadedFile
        $tempPath = sys_get_temp_dir() . '/' . basename($localPath);
        $fileContent = Storage::disk('local')->get($localPath);
        file_put_contents($tempPath, $fileContent);

        $mimeType = $attachment->mime_type ?? mime_content_type($tempPath) ?? 'application/octet-stream';

        // Criar um UploadedFile a partir do arquivo temporário
        $uploadedFile = new UploadedFile(
          $tempPath,
          $attachment->original_name ?? basename($localPath),
          $mimeType,
          null,
          true // test mode para não validar se é upload real
        );

        // Upload para S3
        $s3Path = $this->s3UploadService->upload($attachment->email, $uploadedFile);

        // Atualizar attachment
        $attachment->driver = $driver;
        $attachment->file_path = $s3Path;
        $attachment->save();

        // Remover arquivo local
        Storage::disk('local')->delete($localPath);

        // Limpar arquivo temporário
        unlink($tempPath);

      } else {
        throw new \Exception("Arquivo local não encontrado: {$localPath}");
      }
    }

    return $attachment;
  }
}