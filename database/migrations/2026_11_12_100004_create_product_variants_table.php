<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku', 50)->nullable()->unique();
            $table->string('size_name', 50);                    // A4, F4, A3+
            $table->string('dimension', 50)->nullable();        // 210 x 297 mm
            $table->unsignedSmallInteger('gsm')->nullable();    // gramatur
            $table->string('unit', 20)->default('rim');         // rim / pack / lembar
            $table->string('content', 50)->nullable();          // isi per satuan: 500 lembar
            $table->unsignedInteger('price');                   // harga per satuan
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('min_qty')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
