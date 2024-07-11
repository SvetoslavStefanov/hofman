<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
  use HasFactory;

  protected $fillable = ['email', 'total_price', 'ref_id'];

  protected static function boot() {
    parent::boot();

    static::created(function ($order) {
      $today = now();
      $year = $today->format('Y');
      $month = $today->format('m');
      $day = $today->format('d');
      $offset = 500;
      $id = $order->id + $offset;
      $order->ref_id = 'F' . $year . $month . $day . str_pad($id, 5, '0', STR_PAD_LEFT);
      $order->save();
    });
  }

  /**
   * Get the items for the order.
   */
  public function items() {
    return $this->hasMany(OrderItem::class);
  }

  public function payment() {
    return $this->hasOne(Payment::class);
  }
}
