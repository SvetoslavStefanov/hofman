<?php

namespace App\Listeners;

use App\Models\Payment;

/**
 * This event is called once an Order has been placed.
 * The purpose of this listener is to create a new Payment record in the database.
 * & to generate a payment_link afterward.
 */
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
    Payment::create([
      'order_id' => $event->order->id,
      'paid_at' => null
    ]);
  }
}
