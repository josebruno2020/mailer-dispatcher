<?php

namespace App\Providers;

use App\Events\EmailCreatedEvent;
use App\Listeners\EmailEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   */
  protected $listen = [
    EmailCreatedEvent::class => [
      EmailEventListener::class,
    ],
  ];

  /**
   * Register any events for your application.
   */
  public function boot(): void
  {
    //
  }
}