<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained()->onDelete('cascade');
      $table->timestamp('paid_at')->nullable();
      $table->string('payment_link')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('payments');
  }
};
