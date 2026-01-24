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
            // Existing settings
            [
                'key' => 'driver_must_have_more_than_to_get_orders',
                'value' => '5',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'start_price',
                'value' => '0.25',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'price_per_km',
                'value' => '0.15',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'commission_admin',
                'value' => '0.5',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // NEW: Progressive Driver Search Settings
            [
                'key' => 'driver_search_min_radius_km',
                'value' => '1', // Start searching from 1km
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'driver_search_max_radius_km',
                'value' => '5', // Stop searching at 5km
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'driver_search_wait_time_seconds',
                'value' => '40', // Wait 40 seconds at each radius for driver acceptance
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'driver_search_radius_increment',
                'value' => '1', // Increase radius by 1km each time (1→2→3→4→5)
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
