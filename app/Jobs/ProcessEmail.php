<?php

namespace App\Jobs;

use App\Enums\EmailStatusEnum;
use App\Models\Email;
use App\Services\EmailService;
use App\Services\External\PhpMailerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessEmail implements ShouldQueue
{
    use Queueable;
    public $tries = 3;
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Email $email)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(EmailService $emailService, PhpMailerService $phpMailerService): void
    {
        try {
            $phpMailerService->configure($this->email->setting)
                ->send($this->email);

            $emailService->updatePartial($this->email->id, [
                'status' => EmailStatusEnum::SENT->value,
                'sent_at' => now(),
            ]);

            Log::info("Email {$this->email->id} sent successfully.");

        } catch (\Exception $e) {
            Log::error("Email {$this->email->id} failed. Attempt: {$this->attempts()}/{$this->tries}. Error: {$e->getMessage()}");

            if ($this->attempts() >= $this->tries) {
                $emailService->updatePartial($this->email->id, [
                    'status' => EmailStatusEnum::FAILED->value,
                    'error_message' => $e->getMessage(),
                ]);
                Log::error("Email {$this->email->id} failed permanently after {$this->tries} attempts.");
            } else {
                $emailService->updatePartial($this->email->id, [
                    'status' => EmailStatusEnum::RETRYING->value,
                    'error_message' => $e->getMessage(),
                ]);
            }
            throw $e;
        }
    }

    /**
     * Handle a job failure (chamado automaticamente após esgotar todas as tentativas)
     */
    public function failed(\Throwable $exception): void
    {
        $emailService = app(EmailService::class);

        $emailService->updatePartial($this->email->id, [
            'status' => EmailStatusEnum::FAILED->value,
            'error_message' => $exception->getMessage(),
        ]);

        Log::error("Email {$this->email->id} failed permanently: {$exception->getMessage()}");
    }
}
