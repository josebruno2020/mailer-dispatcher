<?php

namespace App\Listeners;

use App\Events\EmailCreatedEvent;
use App\Jobs\ProcessEmail;
use App\Services\EmailAttachmentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class EmailEventListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly EmailAttachmentService $emailAttachmentService
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmailCreatedEvent $event): void
    {
        $email = $event->email;
        $attachments = $event->email->attachments;
        Log::info('EmailEventSubscriber triggered for Email ID: ' . $email->id);
        if ($attachments->count() > 0) {
            foreach ($attachments as $attachment) {
                $this->emailAttachmentService->updateDriver($attachment->id, 's3');
            }
        }

        if (!$email->scheduled_at || $email->scheduled_at <= now()) {
            ProcessEmail::dispatch($email);
        }
    }
}
