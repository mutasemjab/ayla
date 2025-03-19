<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_voucher_id');
            $table->foreign('note_voucher_id')->references('id')->on('note_vouchers')->onDelete('cascade');
            $table->unsignedBigInteger('voucher_product_id');
            $table->foreign('voucher_product_id')->references('id')->on('voucher_products')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->text('bin_number')->nullable();
            $table->text('serial_number')->nullable();
            $table->text('expiry_date')->nullable();
            $table->tinyInteger('status')->default(2); // 1 sell // 2 not sell yet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_product_details');
    }
};
