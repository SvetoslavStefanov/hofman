<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderPlaced;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail {
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct() {
    //
  }

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle($event) {
    //sending the emails via a queue for better performance, although on the local environment the QUEUE_CONNECTION constant is set to `sync`
    Mail::to($event->order->email)->queue(new OrderConfirmation($event->order));
  }
}
