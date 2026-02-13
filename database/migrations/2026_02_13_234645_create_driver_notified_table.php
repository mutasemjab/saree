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
        Schema::create('driver_notified', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('driver_id');
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('radius_km', 8, 2)->nullable();
            $table->enum('status', ['notified', 'accepted', 'rejected', 'ignored'])->default('notified');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');

            $table->unique(['order_id', 'driver_id']); // prevent duplicates
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
        Schema::dropIfExists('driver_notified');
    }
};
