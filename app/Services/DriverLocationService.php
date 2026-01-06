<?php

namespace App\Services;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use App\Models\Driver;
use App\Models\Order;
use App\Http\Controllers\FCMController;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Firestore;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DriverLocationService
{
    protected $projectId;
    protected $baseUrl;
    
    public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }
    
    /**
     * Find available drivers, send notifications, and write order to Firebase
     */
    public function findAndNotifyNearestDrivers($userLat, $userLng, $orderId, $radius = 20)
    {
        try {
            Log::info('Starting findAndNotifyNearestDrivers', [
                'userLat' => $userLat,
                'userLng' => $userLng,
                'orderId' => $orderId,
                'radius' => $radius
            ]);

            // Step 1: Get available drivers from MySQL (optimized single query)
            $availableDrivers = $this->getAvailableDriversOptimized();
            
            Log::info('Available drivers from MySQL', [
                'count' => count($availableDrivers),
                'ids' => array_column($availableDrivers, 'id')
            ]);
            
            if (empty($availableDrivers)) {
                Log::warning('No available drivers found');
                return [
                    'success' => false,
                    'message' => 'No available drivers found'
                ];
            }
            
            // Step 2: Get driver locations from Firestore using REST API with PAGINATION
            $driversWithLocations = $this->getDriverLocationsFromFirestore(
                array_column($availableDrivers, 'id')
            );
            
            // Add FCM tokens to drivers with locations
            $driverTokensMap = [];
            foreach ($availableDrivers as $driver) {
                $driverTokensMap[$driver['id']] = $driver['fcm_token'];
            }
            
            foreach ($driversWithLocations as &$driver) {
                $driver['fcm_token'] = $driverTokensMap[$driver['id']] ?? null;
            }
            
            Log::info('Drivers with locations from Firestore', [
                'count' => count($driversWithLocations)
            ]);
            
            if (empty($driversWithLocations)) {
                Log::warning('No drivers with active locations found');
                return [
                    'success' => false,
                    'message' => 'No drivers with active locations found'
                ];
            }
            
            // Step 3: Calculate distances and sort
            $sortedDrivers = $this->sortDriversByDistance($driversWithLocations, $userLat, $userLng, $radius);
            
            Log::info('Drivers after distance sorting', [
                'count' => count($sortedDrivers),
                'nearest_driver' => $sortedDrivers[0] ?? null
            ]);
            
            if (empty($sortedDrivers)) {
                Log::warning('No drivers found within radius', ['radius' => $radius]);
                return [
                    'success' => false,
                    'message' => 'No drivers found within specified radius'
                ];
            }
            
            // Step 4: Write order data to Firebase BEFORE sending notifications
            $firebaseResult = $this->writeOrderToFirebase($orderId, $sortedDrivers, $radius);
            
            if (!$firebaseResult['success']) {
                Log::error('Failed to write order to Firebase', [
                    'orderId' => $orderId,
                    'message' => $firebaseResult['message']
                ]);
            }
            
            // Step 5: Send notifications (synchronous - fast enough for small number)
            $notificationResults = $this->sendNotificationsToDrivers($sortedDrivers, $orderId);
            
            Log::info('Notification results', [
                'sent' => $notificationResults['sent'],
                'failed' => $notificationResults['failed']
            ]);
            
            return [
                'success' => true,
                'drivers_found' => count($sortedDrivers),
                'notifications_sent' => $notificationResults['sent'],
                'notifications_failed' => $notificationResults['failed'],
                'firebase_write' => $firebaseResult['success'] ? 'success' : 'failed',
                'firebase_message' => $firebaseResult['message'] ?? null,
                'nearest_driver' => $sortedDrivers[0] ?? null
            ];
            
        } catch (\Exception $e) {
            Log::error('Error in findAndNotifyNearestDrivers', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error processing request'
            ];
        }
    }
    
    /**
     * OPTIMIZED: Single query to get all available drivers with FCM tokens
     * This is THE KEY optimization - from 7 queries to 1!
     */
    private function getAvailableDriversOptimized()
    {
        try {
            // Cache the minimum wallet setting for 1 hour
            $minWalletAmount = Cache::remember('min_wallet_amount', 3600, function() {
                return \App\Models\Setting::where('key', 'driver_must_have_more_than_to_get_orders')
                    ->value('value') ?? 0;
            });
            
            // ONE powerful query that does everything:
            // ✓ Check status = 1 (online)
            // ✓ Check activate = 1 (activated)
            // ✓ Check has FCM token (IMPORTANT!)
            // ✓ Check wallet balance
            // ✓ Check not busy with orders
            $availableDrivers = Driver::select('drivers.id', 'drivers.fcm_token')
                ->where('drivers.status', 1)
                ->where('drivers.activate', 1)
                ->whereNotNull('drivers.fcm_token')
                ->where('drivers.fcm_token', '!=', '')
                ->leftJoin('wallets', 'drivers.id', '=', 'wallets.driver_id')
                ->where(function($query) use ($minWalletAmount) {
                    // Allow drivers without wallet OR drivers with sufficient balance
                    $query->whereNull('wallets.id')
                          ->orWhere('wallets.total', '>', $minWalletAmount);
                })
                ->whereNotExists(function($query) {
                    // Exclude drivers currently assigned to active orders
                    $query->select(DB::raw(1))
                        ->from('orders')
                        ->whereColumn('orders.driver_id', 'drivers.id')
                        ->whereIn('orders.order_status', [1, 2, 3])
                        ->whereNotNull('orders.driver_id');
                })
                ->get()
                ->toArray();
            
            return $availableDrivers;
            
        } catch (\Exception $e) {
            Log::error('Error in getAvailableDriversOptimized', [
                'message' => $e->getMessage()
            ]);
            return [];
        }
    }
    
    /**
     * Get driver locations from Firestore using REST API with PAGINATION
     * This handles large numbers of drivers efficiently (300 per page)
     */
    private function getDriverLocationsFromFirestore(array $driverIds)
    {
        $driversWithLocations = [];

        try {
            $nextPageToken = null;
            $pageSize = 300; // Maximum allowed by Firebase
            
            do {
                // Build URL with pagination
                $url = "{$this->baseUrl}/drivers?pageSize={$pageSize}";
                if ($nextPageToken) {
                    $url .= "&pageToken=" . urlencode($nextPageToken);
                }
                
                $response = Http::timeout(10)->get($url);

                if (!$response->successful()) {
                    Log::error('Failed to fetch drivers from Firestore: ' . $response->body());
                    break; // Stop pagination on error
                }

                $firestoreData = $response->json();

                // Process documents if they exist
                if (isset($firestoreData['documents']) && is_array($firestoreData['documents'])) {
                    foreach ($firestoreData['documents'] as $document) {
                        // Extract driver ID from document name
                        $nameParts = explode('/', $document['name']);
                        $driverId = (int)end($nameParts);

                        // Only process drivers that are in our available list
                        if (!in_array($driverId, $driverIds)) {
                            continue;
                        }

                        $fields = $document['fields'] ?? [];

                        // Get lat and lng from Firestore
                        $lat = $this->getFieldValue($fields, 'lat');
                        $lng = $this->getFieldValue($fields, 'lng');

                        // Check if location data exists and is valid
                        if (!empty($lat) && !empty($lng)) {
                            $driversWithLocations[] = [
                                'id' => $driverId,
                                'lat' => (float)$lat,
                                'lng' => (float)$lng,
                            ];
                        } else {
                            Log::debug("Driver {$driverId} has no valid location data in Firestore");
                        }
                    }
                }
                
                // Check if there are more pages
                $nextPageToken = $firestoreData['nextPageToken'] ?? null;
                
            } while ($nextPageToken); // Continue if there's a next page
            
            Log::info("Fetched " . count($driversWithLocations) . " drivers with valid locations from Firestore");
            
        } catch (\Exception $e) {
            Log::error('Error fetching from Firestore: ' . $e->getMessage());
        }

        return $driversWithLocations;
    }

    /**
     * Helper method to extract value from Firestore field structure
     */
    private function getFieldValue($fields, $fieldName)
    {
        if (!isset($fields[$fieldName])) {
            return null;
        }

        $field = $fields[$fieldName];

        // Check for different value types
        if (isset($field['stringValue'])) {
            return $field['stringValue'];
        }
        if (isset($field['integerValue'])) {
            return $field['integerValue'];
        }
        if (isset($field['doubleValue'])) {
            return $field['doubleValue'];
        }
        if (isset($field['booleanValue'])) {
            return $field['booleanValue'];
        }
        if (isset($field['timestampValue'])) {
            return $field['timestampValue'];
        }

        return null;
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
     * Calculate distance using Haversine formula
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
             sin($dLng/2) * sin($dLng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Write complete order data to Firebase using REST API
     * Maintains structure from second code for mobile app compatibility
     */
    private function writeOrderToFirebase($orderId, array $drivers, $searchRadius = null)
    {
        try {
            // Get the complete order with user and service relationships
            $order = Order::with(['user', 'service'])->find($orderId);

            if (!$order) {
                return [
                    'success' => false,
                    'message' => 'Order not found'
                ];
            }

            // Extract only driver IDs from the sorted drivers array
            $driverIDs = array_map(function ($driver) {
                return $driver['id'];
            }, $drivers);

            // Prepare complete order data with user information
            $orderData = [
                // Order basic information
                'ride_id' => $orderId,
                'order_number' => $order->number,
                'status' => 'pending',
                'service_id' => $order->service_id,

                // User information
                'user_id' => $order->user_id,
                'user_info' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name ?? '',
                    'email' => $order->user->email ?? '',
                    'phone' => $order->user->phone ?? '',
                ],

                // Service information
                'service_info' => [
                    'id' => $order->service->id,
                    'name' => $order->service->name ?? '',
                    'type' => $order->service->type ?? '',
                    'waiting_time' => $order->service->waiting_time ?? '',
                ],

                // Location information
                'pickup_location' => [
                    'name' => $order->pick_name,
                    'latitude' => $order->pick_lat,
                    'longitude' => $order->pick_lng,
                ],
                'dropoff_location' => [
                    'name' => $order->drop_name,
                    'latitude' => $order->drop_lat,
                    'longitude' => $order->drop_lng,
                ],

                // Pricing information
                'pricing' => [
                    'total_price_before_discount' => $order->total_price_before_discount,
                    'discount_value' => $order->discount_value ?? 0,
                    'total_price_after_discount' => $order->total_price_after_discount,
                    'net_price_for_driver' => $order->net_price_for_driver,
                    'commission_of_admin' => $order->commision_of_admin,
                ],

                // Payment information
                'payment_info' => [
                    'payment_method' => $order->payment_method->value ?? 'cash',
                    'payment_status' => $order->status_payment->value ?? 'pending',
                ],

                // Driver information
                'driver_ids' => $driverIDs,
                'total_available_drivers' => count($driverIDs),
                'assigned_driver_id' => $order->driver_id,
                'search_radius_km' => $searchRadius,

                // Additional information
                'reason_for_cancel' => $order->reason_for_cancel,
                'distance' => $order->getDistance(),

                // Timestamps
                'created_at' => $order->created_at->toIso8601String(),
                'updated_at' => $order->updated_at->toIso8601String(),
                'firebase_created_at' => now()->toIso8601String(),
                'firebase_updated_at' => now()->toIso8601String(),
            ];

            // Convert to Firestore format
            $firestoreData = [
                'fields' => $this->convertToFirestoreFormat($orderData)['mapValue']['fields']
            ];

            // Write to Firestore using PATCH to create or update
            $response = Http::timeout(10)->patch(
                "{$this->baseUrl}/ride_requests/{$orderId}",
                $firestoreData
            );

            if ($response->successful()) {
                Log::info("Complete order data for order {$orderId} written to Firebase with " . count($driverIDs) . " available drivers within {$searchRadius}km");

                return [
                    'success' => true,
                    'message' => 'Complete order data successfully written to Firebase',
                    'drivers_count' => count($driverIDs),
                    'search_radius' => $searchRadius,
                ];
            } else {
                Log::error("Failed to write order {$orderId} to Firebase: " . $response->body());
                return [
                    'success' => false,
                    'message' => 'Failed to write order data to Firebase: ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error("Error writing complete order {$orderId} to Firebase: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to write complete order data to Firebase: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Convert PHP data to Firestore REST API format while maintaining original structure
     */
    private function convertToFirestoreFormat($data)
    {
        if (is_array($data)) {
            // Check if it's an associative array (map) or indexed array (list)
            if (array_keys($data) === range(0, count($data) - 1)) {
                // Indexed array - convert to Firestore array
                return [
                    'arrayValue' => [
                        'values' => array_map(function ($item) {
                            return $this->convertToFirestoreFormat($item);
                        }, $data)
                    ]
                ];
            } else {
                // Associative array - convert to Firestore map
                $fields = [];
                foreach ($data as $key => $value) {
                    $fields[$key] = $this->convertToFirestoreFormat($value);
                }
                return [
                    'mapValue' => [
                        'fields' => $fields
                    ]
                ];
            }
        } elseif (is_string($data)) {
            return ['stringValue' => $data];
        } elseif (is_int($data)) {
            return ['integerValue' => (string)$data];
        } elseif (is_float($data) || is_double($data)) {
            return ['doubleValue' => $data];
        } elseif (is_bool($data)) {
            return ['booleanValue' => $data];
        } elseif ($data instanceof \DateTime) {
            return ['timestampValue' => $data->format('c')];
        } elseif ($data === null) {
            return ['nullValue' => null];
        } else {
            return ['stringValue' => (string)$data];
        }
    }
    
    /**
     * Send notifications to drivers (synchronous - fine for small numbers)
     * With 16 drivers, this takes ~2 seconds which is acceptable
     */
    private function sendNotificationsToDrivers(array $drivers, $orderId)
    {
        $sent = 0;
        $failed = 0;
        
        foreach ($drivers as $driver) {
            try {
                // Validate FCM token exists
                if (empty($driver['fcm_token'])) {
                    Log::warning("Driver {$driver['id']} has no FCM token");
                    $failed++;
                    continue;
                }
                
                $success = EnhancedFCMService::sendNewOrderToDriver(
                    $driver['id'],
                    $orderId,
                    $driver['distance']
                );
                
                if ($success) {
                    $sent++;
                    Log::info("✓ Notification sent to driver {$driver['id']} ({$driver['distance']}km)");
                } else {
                    $failed++;
                    Log::error("✗ Failed to send to driver {$driver['id']}");
                }
                
            } catch (\Exception $e) {
                $failed++;
                Log::error("✗ Exception for driver {$driver['id']}: " . $e->getMessage());
            }
            
            // Small delay to avoid rate limiting (50ms)
            usleep(50000);
        }
        
        return [
            'sent' => $sent,
            'failed' => $failed
        ];
    }
}