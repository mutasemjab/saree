<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\DriverLocationService;
use App\Services\EnhancedFCMService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:process-pending';

    /**
     * The console command description.
     */
    protected $description = 'Process pending orders and search for drivers with progressive radius';

    protected $driverLocationService;

    public function __construct(DriverLocationService $driverLocationService)
    {
        parent::__construct();
        $this->driverLocationService = $driverLocationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        
        // Get all pending orders that need processing
        $pendingOrders = Order::where('order_status', Order::STATUS_PENDING)
            ->whereNotNull('search_started_at')
            ->where('current_search_radius', '<=', config('driver_search.max_radius_km', 5))
            ->get();

        if ($pendingOrders->isEmpty()) {
            $this->info('No pending orders to process.');
            return 0;
        }

        $this->info("Processing {$pendingOrders->count()} pending orders...");

        $processed = 0;
        $accepted = 0;
        $failed = 0;

        foreach ($pendingOrders as $order) {
            try {
                $result = $this->processOrder($order);
                
                if ($result['accepted']) {
                    $accepted++;
                    $this->info("✓ Order #{$order->id} accepted by driver #{$result['driver_id']}");
                } elseif ($result['completed']) {
                    $failed++;
                    $this->warn("✗ Order #{$order->id} - No drivers available");
                } else {
                    $processed++;
                    $this->info("→ Order #{$order->id} - Searching at {$result['current_radius']}km");
                }
                
            } catch (\Exception $e) {
                Log::error("Error processing order #{$order->id}: " . $e->getMessage());
                $this->error("✗ Error processing order #{$order->id}");
            }
        }

        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        $this->info("\n=== Summary ===");
        $this->info("Processed: {$processed}");
        $this->info("Accepted: {$accepted}");
        $this->info("Failed: {$failed}");
        $this->info("Duration: {$duration}ms");

        return 0;
    }

    /**
     * Process a single order
     */
    private function processOrder(Order $order)
    {
        $currentTime = now();
        $searchStarted = $order->search_started_at;
        $lastSearch = $order->last_search_at;
        $currentRadius = $order->current_search_radius;
        $iteration = $order->search_iteration;
        
        // Get settings from database with caching
        $cityId = $order->city_id ?? 1;
        $waitTime = Cache::remember("driver_search_wait_time_city_{$cityId}", 3600, function() use ($cityId) {
            return \App\Models\Setting::where('city_id', $cityId)
                ->where('key', 'driver_search_wait_time_seconds')
                ->value('value') ?? 40;
        });
        
        $maxRadius = Cache::remember("driver_search_max_radius_city_{$cityId}", 3600, function() use ($cityId) {
            return \App\Models\Setting::where('city_id', $cityId)
                ->where('key', 'driver_search_max_radius_km')
                ->value('value') ?? 5;
        });
        
        $radiusIncrement = Cache::remember("driver_search_radius_increment_city_{$cityId}", 3600, function() use ($cityId) {
            return \App\Models\Setting::where('city_id', $cityId)
                ->where('key', 'driver_search_radius_increment')
                ->value('value') ?? 1;
        });

        // Check if order was accepted (driver_id is set)
        if ($order->driver_id && in_array($order->order_status, [Order::STATUS_ACCEPTED, Order::STATUS_ON_THE_WAY])) {
            Log::info("Order #{$order->id} was accepted by driver #{$order->driver_id}");
            
            // Clean up search tracking
            $order->update([
                'search_started_at' => null,
                'current_search_radius' => null,
                'last_search_at' => null,
                'search_iteration' => null
            ]);
            
            return [
                'accepted' => true,
                'driver_id' => $order->driver_id,
                'completed' => true
            ];
        }

        // First iteration - just started searching
        if ($iteration === 0 || $lastSearch === null) {
            return $this->searchAtRadius($order, $currentRadius);
        }

        // Calculate time elapsed since last search
        $secondsElapsed = $currentTime->diffInSeconds($lastSearch);

        // Not enough time has passed - wait for driver acceptance
        if ($secondsElapsed < $waitTime) {
            Log::debug("Order #{$order->id} - Waiting... ({$secondsElapsed}/{$waitTime}s at {$currentRadius}km)");
            
            return [
                'accepted' => false,
                'completed' => false,
                'waiting' => true,
                'current_radius' => $currentRadius,
                'seconds_elapsed' => $secondsElapsed
            ];
        }

        // Wait time expired - expand radius
        if ($currentRadius < $maxRadius) {
            $newRadius = min($currentRadius + $radiusIncrement, $maxRadius);
            
            Log::info("Order #{$order->id} - No acceptance at {$currentRadius}km after {$waitTime}s. Expanding to {$newRadius}km");
            
            return $this->searchAtRadius($order, $newRadius);
        }

        // Reached max radius and no acceptance - mark as failed
        Log::warning("Order #{$order->id} - Reached max radius ({$maxRadius}km) with no acceptance");
        
        return $this->markOrderAsNoDriversAvailable($order);
    }

    /**
     * Search for drivers at specific radius
     */
    private function searchAtRadius(Order $order, $radius)
    {
        Log::info("Order #{$order->id} - Searching at {$radius}km radius");

        // Search for drivers
        $result = $this->driverLocationService->findAndNotifyNearestDrivers(
            $order->start_lat,
            $order->start_lng,
            $order->id,
            $radius
        );

        // Update order tracking
        $order->update([
            'current_search_radius' => $radius,
            'last_search_at' => now(),
            'search_iteration' => $order->search_iteration + 1
        ]);

        Log::info("Order #{$order->id} - Search result at {$radius}km", [
            'drivers_found' => $result['drivers_found'] ?? 0,
            'notifications_sent' => $result['notifications_sent'] ?? 0
        ]);

        return [
            'accepted' => false,
            'completed' => false,
            'current_radius' => $radius,
            'drivers_found' => $result['drivers_found'] ?? 0,
            'notifications_sent' => $result['notifications_sent'] ?? 0
        ];
    }

    /**
     * Mark order as no drivers available
     */
    private function markOrderAsNoDriversAvailable(Order $order)
    {
        $order->update([
            'order_status' => Order::STATUS_NO_DRIVERS_AVAILABLE,
            'search_started_at' => null,
            'current_search_radius' => null,
            'last_search_at' => null
        ]);

        // Notify user
        $this->notifyUserNoDriversAvailable($order);

        Log::warning("Order #{$order->id} marked as 'No drivers available'");

        return [
            'accepted' => false,
            'completed' => true,
            'reason' => 'no_drivers_available'
        ];
    }

    /**
     * Notify user that no drivers are available
     */
    private function notifyUserNoDriversAvailable(Order $order)
    {
        try {
            $order->load('user');
            
            if (!$order->user || !$order->user->fcm_token) {
                return false;
            }

            $title = 'عذراً - لا يوجد سائقين متاحين';
            $body = 'لا يوجد سائقين متاحين حالياً في منطقتك. يرجى المحاولة مرة أخرى لاحقاً.';

            $customData = [
                'order_id' => (string)$order->id,
                'screen' => 'no_drivers_available',
                'action' => 'order_failed'
            ];

            EnhancedFCMService::sendMessageWithData(
                $title,
                $body,
                $order->user->fcm_token,
                $order->user->id,
                $customData
            );

            Log::info("Sent 'no drivers available' notification to user #{$order->user->id} for order #{$order->id}");

            return true;

        } catch (\Exception $e) {
            Log::error("Error notifying user about no drivers for order #{$order->id}: " . $e->getMessage());
            return false;
        }
    }
}