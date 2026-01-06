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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->timestamps();
        });
        
         DB::table('settings')->insert([
                [
                    'key' => 'driver_must_have_more_than_to_get_orders',
                    'value' => '5', // Default value - adjust as needed
                    'city_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Add more settings here if needed
                [
                    'key' => 'start_price',
                    'value' => '0.25',
                    'city_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'key' => 'price_per_km',
                    'value' => '0.15', // Default value - adjust as needed
                    'city_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                // Add more settings here if needed
                [
                    'key' => 'commission_admin',
                    'value' => '0.5',
                    'city_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
