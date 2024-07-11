<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  public Order $order;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(Order $order) {
    $this->order = $order;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build() {
    return $this->view('mails.order_confirmation')
      ->with([
        'order' => $this->order,
      ])
      ->subject('Order Confirmation - ' . $this->order->ref_id);
  }
}
