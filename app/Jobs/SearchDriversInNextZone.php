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

class SearchDriversInNextZone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;
    protected $currentRadius;
    protected $userLat;
    protected $userLng;
    protected $cityId;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId, $currentRadius, $userLat, $userLng, $cityId = 1)
    {
        $this->orderId = $orderId;
        $this->currentRadius = $currentRadius;
        $this->userLat = $userLat;
        $this->userLng = $userLng;
        $this->cityId = $cityId;

        // تأخير 40 ثانية (أو حسب الإعدادات)
        $waitTime = Cache::remember("driver_search_wait_time_city_{$cityId}", 3600, function() use ($cityId) {
            return \DB::table('settings')
                ->where('city_id', $cityId)
                ->where('key', 'driver_search_wait_time_seconds')
                ->value('value') ?? 40;
        });

        $this->delay(now()->addSeconds($waitTime));
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $order = Order::find($this->orderId);

            // تحقق من حالة الطلب
            if (!$order) {
                Log::info("Order {$this->orderId} not found. Stopping search.");
                return;
            }

            if ($order->order_status != 1) { // Not pending
                Log::info("Order {$this->orderId} is not pending (status: {$order->order_status}). Stopping search.");
                return;
            }

            if ($order->driver_id) {
                Log::info("Order {$this->orderId} already has driver {$order->driver_id}. Stopping search.");
                return;
            }

            // الحصول على الإعدادات
            $minRadius = Cache::remember("driver_search_min_radius_city_{$this->cityId}", 3600, function() {
                return \DB::table('settings')
                    ->where('city_id', $this->cityId)
                    ->where('key', 'driver_search_min_radius_km')
                    ->value('value') ?? 1;
            });

            $maxRadius = Cache::remember("driver_search_max_radius_city_{$this->cityId}", 3600, function() {
                return \DB::table('settings')
                    ->where('city_id', $this->cityId)
                    ->where('key', 'driver_search_max_radius_km')
                    ->value('value') ?? 5;
            });

            $radiusIncrement = Cache::remember("driver_search_radius_increment_city_{$this->cityId}", 3600, function() {
                return \DB::table('settings')
                    ->where('city_id', $this->cityId)
                    ->where('key', 'driver_search_radius_increment')
                    ->value('value') ?? 1;
            });

            // حساب النطاق التالي
            $nextRadius = $this->currentRadius + $radiusIncrement;

            if ($nextRadius > $maxRadius) {
                Log::info("Max radius {$maxRadius}km reached for order {$this->orderId}. No drivers available.");

                // تحديث حالة الطلب إلى "لا يوجد سائقين"
                $order->update([
                    'order_status' => 7 // No drivers available
                ]);

                // إرسال إشعار للمستخدم
                $this->notifyUserNoDriversAvailable($order);

                return;
            }

            Log::info("Expanding search to {$nextRadius}km for order {$this->orderId}");

            // البحث عن السائقين في النطاق الجديد
            $driverLocationService = new DriverLocationService($this->cityId);

            $result = $driverLocationService->findAndNotifyNearestDrivers(
                $this->userLat,
                $this->userLng,
                $this->orderId,
                $nextRadius
            );

            if ($result['success'] && $result['drivers_found'] > 0) {
                Log::info("Found {$result['drivers_found']} drivers in {$nextRadius}km radius for order {$this->orderId}. Sent {$result['notifications_sent']} notifications.");

                // جدولة البحث التالي (في حالة عدم القبول)
                if ($nextRadius < $maxRadius) {
                    SearchDriversInNextZone::dispatch(
                        $this->orderId,
                        $nextRadius,
                        $this->userLat,
                        $this->userLng,
                        $this->cityId
                    );
                }
            } else {
                Log::warning("No drivers found in {$nextRadius}km radius for order {$this->orderId}");

                // المحاولة في النطاق التالي مباشرة
                if ($nextRadius < $maxRadius) {
                    SearchDriversInNextZone::dispatch(
                        $this->orderId,
                        $nextRadius,
                        $this->userLat,
                        $this->userLng,
                        $this->cityId
                    )->delay(now()->addSeconds(5)); // انتظار 5 ثواني فقط إذا لم يوجد سائقين
                }
            }

        } catch (\Exception $e) {
            Log::error("Error in SearchDriversInNextZone job for order {$this->orderId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * إرسال إشعار للمستخدم بعدم توفر سائقين
     */
    private function notifyUserNoDriversAvailable($order)
    {
        try {
            $order->load('user');

            if (!$order->user || !$order->user->fcm_token) {
                Log::warning("Cannot notify user - Order or user FCM token not found for order {$this->orderId}");
                return false;
            }

            $title = 'عذراً - لا يوجد سائقين متاحين';
            $body = 'لا يوجد سائقين متاحين حالياً في منطقتك. يرجى المحاولة مرة أخرى لاحقاً.';

            $customData = [
                'order_id' => (string)$this->orderId,
                'screen' => 'no_drivers_available',
                'action' => 'order_failed'
            ];

            \App\Services\EnhancedFCMService::sendMessageWithData(
                $title,
                $body,
                $order->user->fcm_token,
                $order->user->id,
                $customData
            );

            Log::info("Sent 'no drivers available' notification to user for order {$this->orderId}");

            return true;

        } catch (\Exception $e) {
            Log::error("Error notifying user about no drivers for order {$this->orderId}: " . $e->getMessage());
            return false;
        }
    }
}
