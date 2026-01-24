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
            $table->double('start_lat')->nullable();
            $table->double('start_lng')->nullable();
            $table->double('end_lat')->nullable();
            $table->double('end_lng')->nullable();
            $table->string('pick_up_name')->nullable();
            $table->string('drop_name')->nullable();
            $table->double('commission_amount')->nullable();
            $table->double('driver_earnings')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('cascade');
        
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

            $table->timestamp('search_started_at')->nullable();
            $table->unsignedTinyInteger('current_search_radius')->nullable();
            $table->timestamp('last_search_at')->nullable();
            $table->unsignedTinyInteger('search_iteration')->default(0);
            
            // Add index for faster cron queries
            $table->index(['order_status', 'search_started_at', 'current_search_radius'], 'idx_order_search');
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
