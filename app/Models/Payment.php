<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mollie\Laravel\Facades\Mollie;

class Payment extends Model {
  use HasFactory;

  protected $fillable = [
    'order_id',
    'paid_at',
    'payment_link'
  ];

  protected static function boot() {
    parent::boot();

    static::created(function ($payment) {
      $url = config('app.url') . '/api/orders/' . $payment->order->id . '/payment';

      $paymentMollie = Mollie::api()->payments->create([
        "amount" => [
          "currency" => "EUR",
          'value' => number_format($payment->order->total_price, 2, '.', ''),
        ],
        "description" => "Order #{$payment->order->ref_id}",
        "redirectUrl" => $url,
        "webhookUrl" => $url,
        "metadata" => [
          "order_id" => $payment->order->id,
          "order_ref_id" => $payment->order->ref_id,
          "payment_id" => $payment->id,
        ],
      ]);

      $payment->payment_link = $paymentMollie->getCheckoutUrl();
      $payment->save();
    });
  }

  public function order() {
    return $this->belongsTo(Order::class);
  }

  public function isPaid(): bool {
    return !is_null($this->paid_at);
  }
}
