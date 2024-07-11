<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller {
  /**
   * Place a new order.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request) {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'order_items' => 'required|array',
      'order_items.*.product_id' => 'required|exists:products,id',
      'order_items.*.quantity' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->errors(),
      ], 401);
    }

    $validated = $validator->validated();

    $productIds = array_column($validated['order_items'], 'product_id');
    $products = Product::whereIn('id', $productIds)->pluck('price', 'id');

    $totalPrice = 0;
    foreach ($validated['order_items'] as $item) {
      $totalPrice += $products[$item['product_id']] * $item['quantity'];
    }

    $order = Order::create([
      'email' => $validated['email'],
      'total_price' => $totalPrice,
    ]);

    foreach ($validated['order_items'] as $item) {
      OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $item['product_id'],
        'quantity' => $item['quantity'],
        'price' => $products[$item['product_id']],
      ]);
    }

    event(new OrderPlaced($order));

    return response()->json($order->load('items.product'), 201);
  }

  /**
   * Retrieve a list of all orders.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index() {
    return response()->json(Order::with('items.product')->get());
  }
}
