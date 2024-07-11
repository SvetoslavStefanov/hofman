<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Listeners\CreatePaymentListener;
use App\Listeners\SendOrderConfirmationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {
  /**
   * The event to listener mappings for the application.
   *
   * @var array<class-string, array<int, class-string>>
   */
  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],
    OrderPlaced::class => [
      SendOrderConfirmationEmail::class,
      CreatePaymentListener::class,
    ],
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot() {
    parent::boot();
  }

  /**
   * Determine if events and listeners should be automatically discovered.
   *
   * @return bool
   */
  public function shouldDiscoverEvents() {
    return false;
  }
}
