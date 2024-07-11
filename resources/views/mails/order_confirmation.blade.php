@extends('layouts.mail')

@section('content')
  <h1>Thank you for your order!</h1>
  <p>Your order has been placed successfully. Below are the details of your order:</p>
  <p><strong>Order ID:</strong> {{ $order->ref_id }}</p>
  <p><strong>Email:</strong> {{ $order->email }}</p>
  <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>

  <h2>Order Items</h2>
  <ul>
    @foreach ($order->items as $item)
      <li>
        {{ $item->product->name }} - {{ $item->quantity }} x ${{ number_format($item->price, 2) }}
      </li>
    @endforeach
  </ul>
@endsection