<?php

namespace App\Listeners;

use App\Events\EmailCreatedEvent;
use App\Jobs\ProcessEmail;
use Illuminate\Support\Facades\Log;

class EmailEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmailCreatedEvent $event): void
    {
        $email = $event->email;
        Log::info('EmailEventSubscriber triggered for Email ID: ' . $email->id);
        if (!$email->scheduled_at || $email->scheduled_at <= now()) {
            ProcessEmail::dispatch($email);
        }
    }
}
