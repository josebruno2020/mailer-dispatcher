<?php

namespace App\Jobs;

use App\Models\Email;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessWebhook extends BaseJob implements ShouldQueue
{
    use Queueable;

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
    public function handle(EmailService $emailService): void
    {
        $webhookConfig = $emailService->webhookDecide($this->email);
        if (!isset($webhookConfig['webhook_url'])) {
            return;
        }

        Log::info("Dispatching webhook for Email {$this->email->id} to {$webhookConfig['webhook_url']}");

        $headers = $webhookConfig['webhook_headers'] ?? [];
        $webhookData = [
            'email_id' => $this->email->id,
            'status' => $this->email->status,
            'sent_at' => $this->email->sent_at,
            'error_message' => $this->email->error_message,
        ];
        $resp = Http::withHeaders(array_merge(
            $headers,
            ['Content-Type' => 'application/json'],
        ))->post($webhookConfig['webhook_url'], $webhookData);
        
        $emailService->updatePartial($this->email->id, [
            'webhook_status' => $resp->status(),
            'webhook_data' => $resp->body(),
        ]);
        
        if ($resp->failed()) {
            Log::error("Webhook for Email {$this->email->id} failed with status {$resp->status()} and body: {$resp->body()}");

            if ($this->attempts() >= $this->tries) {
                return;
            }

            throw new \Exception("Webhook failed with status {$resp->status()}");
        }
    }
}
