<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmation extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public Order $order;

  public function __construct(Order $order) {
    $this->order = $order;
  }

  public function build() {
    return $this->view('mails.payment_confirmation')
      ->with([
        'order' => $this->order,
      ])
      ->subject('Payment Confirmation - ' . $this->order->ref_id);
  }
}
