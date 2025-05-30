<?php

namespace App\Services;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use App\Models\Driver;
use App\Models\Order;
use App\Http\Controllers\FCMController;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Firestore;

class DriverLocationService
{
    protected $firestore;
    
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }
    
    /**
     * Find available drivers and send notifications sorted by distance
     */
    public function findAndNotifyNearestDrivers($userLat, $userLng, $orderId, $radius = 10)
    {
        try {
            // Step 1: Get available drivers from MySQL
            $availableDriverIds = $this->getAvailableDrivers();
            
            if (empty($availableDriverIds)) {
                return [
                    'success' => false,
                    'message' => 'No available drivers found'
                ];
            }
            
            // Step 2: Get driver locations from Firestore
            $driversWithLocations = $this->getDriverLocationsFromFirestore($availableDriverIds);
            
            if (empty($driversWithLocations)) {
                return [
                    'success' => false,
                    'message' => 'No drivers with active locations found'
                ];
            }
            
            // Step 3: Calculate distances and sort
            $sortedDrivers = $this->sortDriversByDistance($driversWithLocations, $userLat, $userLng, $radius);
            
            if (empty($sortedDrivers)) {
                return [
                    'success' => false,
                    'message' => 'No drivers found within specified radius'
                ];
            }
            
            // Step 4: Send notifications to sorted drivers
            $notificationResults = $this->sendNotificationsToDrivers($sortedDrivers, $orderId);
            
            return [
                'success' => true,
                'drivers_found' => count($sortedDrivers),
                'notifications_sent' => $notificationResults['sent'],
                'notifications_failed' => $notificationResults['failed'],
                'drivers' => $sortedDrivers
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error in findAndNotifyNearestDrivers: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get available drivers from MySQL
     */
    private function getAvailableDrivers()
    {
        return Driver::where('status', 1) // status = 1 (online)
            ->where('activate', 1) // activate = 1 (active)
            ->whereNotIn('id', function($query) {
                $query->select('driver_id')
                    ->from('orders')
                    ->whereIn('order_status', [1, 2, 3]) // Pending, Accepted, On the way
                    ->whereNotNull('driver_id');
            })
            ->pluck('id')
            ->toArray();
    }
    
    /**
     * Get driver locations from Firestore
     */
    private function getDriverLocationsFromFirestore(array $driverIds)
    {
        $driversWithLocations = [];
        
        try {
            $collection = $this->firestore->database()->collection('drivers');
            
            foreach ($driverIds as $driverId) {
                $document = $collection->document((string)$driverId)->snapshot();
                
                if ($document->exists()) {
                    $data = $document->data();
                    
                    // Check if location data exists
                    if (isset($data['lat']) && isset($data['lng']) && 
                        !empty($data['lat']) && !empty($data['lng'])) {
                        
                        $driversWithLocations[] = [
                            'id' => $driverId,
                            'lat' => (float)$data['lat'],
                            'lng' => (float)$data['lng'],
                        ];
                    }
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Error fetching from Firestore: ' . $e->getMessage());
        }
        
        return $driversWithLocations;
    }
    
    /**
     * Sort drivers by distance from user location
     */
    private function sortDriversByDistance(array $drivers, $userLat, $userLng, $maxRadius)
    {
        $driversWithDistance = [];
        
        foreach ($drivers as $driver) {
            $distance = $this->calculateDistance(
                $userLat, 
                $userLng, 
                $driver['lat'], 
                $driver['lng']
            );
            
            // Only include drivers within the specified radius
            if ($distance <= $maxRadius) {
                $driver['distance'] = round($distance, 2);
                $driversWithDistance[] = $driver;
            }
        }
        
        // Sort by distance (nearest first)
        usort($driversWithDistance, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
        
        return $driversWithDistance;
    }
    
    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLng/2) * sin($dLng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        
        return $distance;
    }
    
   private function sendNotificationsToDrivers(array $drivers, $orderId)
    {
        $sent = 0;
        $failed = 0;
        
        foreach ($drivers as $driver) {
            try {
                $success = EnhancedFCMService::sendNewOrderToDriver(
                    $driver['id'],
                    $orderId,
                    $driver['distance']
                );
                
                if ($success) {
                    $sent++;
                    \Log::info("Notification sent to driver {$driver['id']} at distance {$driver['distance']}km");
                } else {
                    $failed++;
                    \Log::error("Failed to send notification to driver {$driver['id']}");
                }
                
            } catch (\Exception $e) {
                $failed++;
                \Log::error("Exception sending notification to driver {$driver['id']}: " . $e->getMessage());
            }
            
            // Add small delay between notifications to avoid rate limiting
            usleep(100000); // 100ms delay
        }
        
        return [
            'sent' => $sent,
            'failed' => $failed
        ];
    }
    
}