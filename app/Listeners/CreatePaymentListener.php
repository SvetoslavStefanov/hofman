<?php

namespace App\Listeners;

use App\Mail\PaymentConfirmation;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

class CreatePaymentListener {
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
    Mail::to($event->order->email)->queue(new PaymentConfirmation($event->order));
    Payment::create([
      'order_id' => $event->order->id,
      'paid_at' => null
    ]);
  }
}
