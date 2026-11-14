<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 30)->unique();
            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->string('customer_email', 150)->nullable();
            $table->string('delivery_method', 10);              // ambil | antar
            $table->text('shipping_address')->nullable();
            $table->text('customer_note')->nullable();
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping_cost')->default(0);
            $table->unsignedInteger('total');
            $table->string('status', 30)->default('menunggu_pembayaran')->index();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
