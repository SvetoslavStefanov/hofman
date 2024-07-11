<?php

namespace App\Models;

use App\Services\HubSpotService;
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
      self::generatePaymentLink($payment);
    });

    static::updated(function ($payment) {
      self::generateHubspotContactAndDeal($payment);
    });
  }

  public function order() {
    return $this->belongsTo(Order::class);
  }

  public function isPaid(): bool {
    return !is_null($this->paid_at);
  }

  /**
   * After the payment has been confirmed, generate a HubSpot contact and deal.
   *
   * @param Payment $payment
   */
  private static function generateHubspotContactAndDeal(Payment $payment) {
    if ($payment->wasChanged('paid_at') && $payment->paid_at && $payment->paid_at !== $payment->getOriginal('paid_at') && $payment->isPaid()) {
      $hubSpotService = app()->make(HubSpotService::class);

      $contactData = [
        'email' => $payment->order->email,
      ];

      $contactId = $hubSpotService->createContact($contactData);

      $dealData = [
        'dealname' => 'Order ' . $payment->order->ref_id,
        'amount' => $payment->order->total_amount,
        'closedate' => now()->toDateString(),
        'dealstage' => 'closedwon',
        'pipeline' => 'default'
      ];
      $dealId = $hubSpotService->createDeal($dealData);
    }
  }

  private static function generatePaymentLink(Payment $payment): void {
    //A workaround to `route('orders.confirmPayment', $payment->order)` because the generated url is 127.0.0.1, which is not accepted by Mollie
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
  }
}
