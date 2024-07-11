@extends('layouts.mail')

@section('content')
  <h1>Thank you for your payment!</h1>
  <p>Your payment has been processed successfully. Below are the details of your order:</p>
  <p><strong>Order ID:</strong> {{ $order->ref_id }}</p>
  <p><strong>Email:</strong> {{ $order->email }}</p>
  <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
@endsection