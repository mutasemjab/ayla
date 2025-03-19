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
        Schema::create('transfer_banks', function (Blueprint $table) {
            // هاي تحويل محافظ في التلفون زي زين كاش وكليك هيك
            $table->id();
            $table->text('name_of_wallet');
            $table->text('number_of_wallet');
            $table->double('amount');
            $table->tinyInteger('status')->default(2); // 1 approve // 2 not approve // 3 reject
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('transfer_banks');
    }
};
