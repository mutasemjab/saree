<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Services\DriverLocationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchDriversInNextZone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;
    protected $currentRadius;
    protected $userLat;
    protected $userLng;

    public function __construct($orderId, $currentRadius, $userLat, $userLng)
    {
        $this->orderId       = $orderId;
        $this->currentRadius = $currentRadius;
        $this->userLat       = $userLat;
        $this->userLng       = $userLng;

        $waitTime = Cache::remember("driver_search_wait_time", 3600, function () {
            return DB::table('settings')
                ->where('key', 'driver_search_wait_time_seconds')
                ->value('value') ?? 40;
        });

        $this->delay(now()->addSeconds($waitTime));
    }

    public function handle()
    {
        try {
            $order = Order::find($this->orderId);

            if (!$order) {
                Log::info("Order {$this->orderId} not found. Stopping search.");
                return;
            }

            if ($order->order_status != 1) {
                Log::info("Order {$this->orderId} is not pending (status: {$order->order_status}). Stopping search.");
                return;
            }

            if ($order->driver_id) {
                Log::info("Order {$this->orderId} already has driver {$order->driver_id}. Stopping search.");
                return;
            }

            $maxRadius       = Cache::remember("driver_search_max_radius", 3600, fn() =>
                DB::table('settings')->where('key', 'driver_search_max_radius_km')->value('value') ?? 5
            );

            $radiusIncrement = Cache::remember("driver_search_radius_increment", 3600, fn() =>
                DB::table('settings')->where('key', 'driver_search_radius_increment')->value('value') ?? 1
            );

            $nextRadius = $this->currentRadius + $radiusIncrement;

            if ($nextRadius > $maxRadius) {
                Log::info("Max radius {$maxRadius}km reached for order {$this->orderId}. No drivers available.");

                $order->update(['order_status' => 7]);
                $this->notifyUserNoDriversAvailable($order);

                return;
            }

            Log::info("Expanding search to {$nextRadius}km for order {$this->orderId}");

            $driverLocationService = new DriverLocationService();

            $result = $driverLocationService->findAndNotifyNearestDrivers(
                $this->userLat,
                $this->userLng,
                $this->orderId,
                $nextRadius
            );

            if ($result['success'] && $result['drivers_found'] > 0) {
                Log::info("Found {$result['drivers_found']} drivers in {$nextRadius}km for order {$this->orderId}.");

                if ($nextRadius < $maxRadius) {
                    SearchDriversInNextZone::dispatch(
                        $this->orderId,
                        $nextRadius,
                        $this->userLat,
                        $this->userLng
                    );
                }
            } else {
                Log::warning("No drivers found in {$nextRadius}km for order {$this->orderId}");

                if ($nextRadius < $maxRadius) {
                    SearchDriversInNextZone::dispatch(
                        $this->orderId,
                        $nextRadius,
                        $this->userLat,
                        $this->userLng
                    )->delay(now()->addSeconds(5));
                }
            }

        } catch (\Exception $e) {
            Log::error("Error in SearchDriversInNextZone for order {$this->orderId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function notifyUserNoDriversAvailable($order)
    {
        try {
            $order->load('user');

            if (!$order->user || !$order->user->fcm_token) {
                Log::warning("Cannot notify user - FCM token not found for order {$this->orderId}");
                return false;
            }

            \App\Services\EnhancedFCMService::sendMessageWithData(
                'عذراً - لا يوجد سائقين متاحين',
                'لا يوجد سائقين متاحين حالياً في منطقتك. يرجى المحاولة مرة أخرى لاحقاً.',
                $order->user->fcm_token,
                $order->user->id,
                [
                    'order_id' => (string)$this->orderId,
                    'screen'   => 'no_drivers_available',
                    'action'   => 'order_failed'
                ]
            );

            Log::info("Sent 'no drivers available' notification to user for order {$this->orderId}");
            return true;

        } catch (\Exception $e) {
            Log::error("Error notifying user for order {$this->orderId}: " . $e->getMessage());
            return false;
        }
    }
}