<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Mail\PaymentConfirmation;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mollie\Laravel\Facades\Mollie;

class PaymentController extends Controller {
  public function confirmPayment(Request $request, Order $order) {
    $paymentId = $request->input('id');
    $payment = Mollie::api()->payments->get($paymentId);

    if ($payment->isPaid()) {
      $order->payment->paid_at = now();
      $order->payment->save();

      Mail::to($order->email)->send(new PaymentConfirmation($order));

      return response('Payment recorded', 200);
    }

    return response('Payment not processed', 400);
  }
}
