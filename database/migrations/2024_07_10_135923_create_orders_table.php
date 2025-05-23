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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('number')->nullable();
            $table->tinyInteger('order_status')->default(1);  // 1 Pending //2 Accepted //3 on the way //4 Delivered //5 cancelled by user //6 canceled by driver
            $table->double('price')->nullable();
            $table->double('discount')->nullable();
            $table->double('final_price')->nullable();
            $table->double('total_distance')->nullable(); 
            $table->text('total_time')->nullable();
            $table->tinyInteger('payment_type')->default(1); // 1 paid //2 unpaid
            $table->tinyInteger('payment_method')->default(1); // 1 cash  // 2 visa
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
};
