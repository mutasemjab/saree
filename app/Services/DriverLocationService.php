<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class DriverLocationService
{
    protected $projectId;
    protected $baseUrl;
    protected $cityId;
    
       public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }
    
    protected function getMinRadius()
    {
        return Cache::remember("driver_search_min_radius", 3600, function() {
            return Setting::where('key', 'driver_search_min_radius_km')->value('value') ?? 1;
        });
    }
    
    protected function getMaxRadius()
    {
        return Cache::remember("driver_search_max_radius", 3600, function() {
            return Setting::where('key', 'driver_search_max_radius_km')->value('value') ?? 5;
        });
    }
    
    protected function getWaitTime()
    {
        return Cache::remember("driver_search_wait_time", 3600, function() {
            return Setting::where('key', 'driver_search_wait_time_seconds')->value('value') ?? 40;
        });
    }
    
    protected function getRadiusIncrement()
    {
        return Cache::remember("driver_search_radius_increment", 3600, function() {
            return Setting::where('key', 'driver_search_radius_increment')->value('value') ?? 1;
        });
    }
    
    /**
     * Progressive radius search: Start from min_km and expand to max_km
     * Wait X seconds at each radius for driver acceptance
     */
    public function findDriversWithProgressiveRadius($userLat, $userLng, $orderId)
    {
        try {
            $minRadius = $this->getMinRadius();
            $maxRadius = $this->getMaxRadius();
            $waitTime = $this->getWaitTime();
            $radiusIncrement = $this->getRadiusIncrement();
            
            Log::info('Starting progressive radius search', [
                'orderId' => $orderId,
                'userLat' => $userLat,
                'userLng' => $userLng,
                'minRadius' => $minRadius,
                'maxRadius' => $maxRadius,
                'waitTime' => $waitTime,
                'radiusIncrement' => $radiusIncrement,
                'cityId' => $this->cityId
            ]);

            $currentRadius = $minRadius;
            
            while ($currentRadius <= $maxRadius) {
                Log::info("Searching within {$currentRadius}km radius...");
                
                // Search for drivers within current radius
                $result = $this->findAndNotifyNearestDrivers(
                    $userLat, 
                    $userLng, 
                    $orderId, 
                    $currentRadius
                );
                
                // If drivers were found and notified
                if ($result['success'] && $result['notifications_sent'] > 0) {
                    Log::info("Found {$result['drivers_found']} drivers within {$currentRadius}km, sent {$result['notifications_sent']} notifications");
                    
                    // Wait for driver acceptance
                    Log::info("Waiting {$waitTime} seconds for driver acceptance...");
                    sleep($waitTime);
                    
                    // Check if order was accepted
                    $order = Order::find($orderId);
                    
                    if ($order && $order->driver_id && in_array($order->order_status, [2, 3])) {
                        Log::info("Order {$orderId} accepted by driver {$order->driver_id} within {$currentRadius}km");
                        
                        return [
                            'success' => true,
                            'accepted' => true,
                            'driver_id' => $order->driver_id,
                            'radius_km' => $currentRadius,
                            'message' => "Order accepted by driver within {$currentRadius}km radius"
                        ];
                    }
                    
                    Log::info("No acceptance within {$currentRadius}km after {$waitTime} seconds");
                }
                
                // Expand radius if we haven't reached max
                if ($currentRadius < $maxRadius) {
                    $currentRadius += $radiusIncrement;
                    Log::info("Expanding search radius to {$currentRadius}km...");
                } else {
                    break;
                }
            }
            
            // No drivers accepted after searching all radiuses
            Log::warning("No drivers accepted order {$orderId} within {$maxRadius}km radius");
            
            // Send notification to user that no drivers are available
            $this->notifyUserNoDriversAvailable($orderId);
            
            // Update order status to indicate no drivers available
            Order::where('id', $orderId)->update([
                'order_status' => 7, // No drivers available
            ]);
            
            return [
                'success' => false,
                'accepted' => false,
                'max_radius_searched' => $maxRadius,
                'message' => 'No drivers available within ' . $maxRadius . 'km radius'
            ];
            
        } catch (\Exception $e) {
            Log::error('Error in progressive radius search', [
                'orderId' => $orderId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error during driver search'
            ];
        }
    }
    
    /**
     * Find available drivers, send notifications, and write order to Firebase
     */
    public function findAndNotifyNearestDrivers($userLat, $userLng, $orderId, $radius = 5)
    {
        try {
            Log::info('Starting findAndNotifyNearestDrivers', [
                'userLat' => $userLat,
                'userLng' => $userLng,
                'orderId' => $orderId,
                'radius' => $radius
            ]);

            // Step 1: Get available drivers from MySQL
            $availableDrivers = $this->getAvailableDriversOptimized();
            
            Log::info('Available drivers from MySQL', [
                'count' => count($availableDrivers),
                'ids' => array_column($availableDrivers, 'id')
            ]);
            
            if (empty($availableDrivers)) {
                Log::warning('No available drivers found');
                return [
                    'success' => false,
                    'message' => 'No available drivers found',
                    'drivers_found' => 0,
                    'notifications_sent' => 0
                ];
            }
            
            // Step 2: Get driver locations from Firestore
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
                    'message' => 'No drivers with active locations found',
                    'drivers_found' => 0,
                    'notifications_sent' => 0
                ];
            }
            
            // Step 3: Calculate distances and filter by radius
            $sortedDrivers = $this->sortDriversByDistance($driversWithLocations, $userLat, $userLng, $radius);
            
            Log::info('Drivers after distance sorting', [
                'count' => count($sortedDrivers),
                'radius' => $radius,
                'nearest_driver' => $sortedDrivers[0] ?? null
            ]);
            
            if (empty($sortedDrivers)) {
                Log::warning('No drivers found within radius', ['radius' => $radius]);
                return [
                    'success' => false,
                    'message' => 'No drivers found within specified radius',
                    'drivers_found' => 0,
                    'notifications_sent' => 0
                ];
            }
            
            // Step 4: Write order data to Firebase
            $firebaseResult = $this->writeOrderToFirebase($orderId, $sortedDrivers, $radius);
            
            if (!$firebaseResult['success']) {
                Log::error('Failed to write order to Firebase', [
                    'orderId' => $orderId,
                    'message' => $firebaseResult['message']
                ]);
            }
            
            // Step 5: Send notifications
            $notificationResults = $this->sendNotificationsToDrivers($sortedDrivers, $orderId);
            
            Log::info('Notification results', [
                'radius' => $radius,
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
                'nearest_driver' => $sortedDrivers[0] ?? null,
                'search_radius' => $radius
            ];
            
        } catch (\Exception $e) {
            Log::error('Error in findAndNotifyNearestDrivers', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error processing request',
                'drivers_found' => 0,
                'notifications_sent' => 0
            ];
        }
    }
    
    /**
     * Notify user that no drivers are available
     */
    private function notifyUserNoDriversAvailable($orderId)
    {
        try {
            $order = Order::with('user')->find($orderId);
            
            if (!$order || !$order->user || !$order->user->fcm_token) {
                Log::warning("Cannot notify user - Order or user FCM token not found", [
                    'orderId' => $orderId
                ]);
                return false;
            }
            
            $title = 'عذراً - لا يوجد سائقين متاحين';
            $body = 'لا يوجد سائقين متاحين حالياً في منطقتك. يرجى المحاولة مرة أخرى لاحقاً.';
            
            $customData = [
                'order_id' => (string)$orderId,
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
            
            Log::info("Sent 'no drivers available' notification to user", [
                'orderId' => $orderId,
                'userId' => $order->user->id
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error notifying user about no drivers', [
                'orderId' => $orderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * OPTIMIZED: Single query to get all available drivers with FCM tokens
     */
       private function getAvailableDriversOptimized()
    {
        try {
            $minWalletAmount = Cache::remember("min_wallet_amount", 3600, function() {
                return Setting::where('key', 'driver_must_have_more_than_to_get_orders')->value('value') ?? 0;
            });
            
            $availableDrivers = Driver::select('drivers.id', 'drivers.fcm_token')
                ->where('drivers.status', 1)
                ->where('drivers.activate', 1)
                ->whereNotNull('drivers.fcm_token')
                ->where('drivers.fcm_token', '!=', '')
                ->leftJoin('wallets', 'drivers.id', '=', 'wallets.driver_id')
                ->where(function($query) use ($minWalletAmount) {
                    $query->whereNull('wallets.id')
                          ->orWhere('wallets.total', '>', $minWalletAmount);
                })
                ->whereNotExists(function($query) {
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
            Log::error('Error in getAvailableDriversOptimized', ['message' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Get driver locations from Firestore using REST API with PAGINATION
     */
    private function getDriverLocationsFromFirestore(array $driverIds)
    {
        $driversWithLocations = [];

        try {
            $nextPageToken = null;
            $pageSize = 300;
            
            do {
                $url = "{$this->baseUrl}/drivers?pageSize={$pageSize}";
                if ($nextPageToken) {
                    $url .= "&pageToken=" . urlencode($nextPageToken);
                }
                
                $response = Http::timeout(10)->get($url);

                if (!$response->successful()) {
                    Log::error('Failed to fetch drivers from Firestore: ' . $response->body());
                    break;
                }

                $firestoreData = $response->json();

                if (isset($firestoreData['documents']) && is_array($firestoreData['documents'])) {
                    foreach ($firestoreData['documents'] as $document) {
                        $nameParts = explode('/', $document['name']);
                        $driverId = (int)end($nameParts);

                        if (!in_array($driverId, $driverIds)) {
                            continue;
                        }

                        $fields = $document['fields'] ?? [];
                        $lat = $this->getFieldValue($fields, 'lat');
                        $lng = $this->getFieldValue($fields, 'lng');

                        if (!empty($lat) && !empty($lng)) {
                            $driversWithLocations[] = [
                                'id' => $driverId,
                                'lat' => (float)$lat,
                                'lng' => (float)$lng,
                            ];
                        }
                    }
                }
                
                $nextPageToken = $firestoreData['nextPageToken'] ?? null;
                
            } while ($nextPageToken);
            
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

        if (isset($field['stringValue'])) return $field['stringValue'];
        if (isset($field['integerValue'])) return $field['integerValue'];
        if (isset($field['doubleValue'])) return $field['doubleValue'];
        if (isset($field['booleanValue'])) return $field['booleanValue'];
        if (isset($field['timestampValue'])) return $field['timestampValue'];

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
            
            if ($distance <= $maxRadius) {
                $driver['distance'] = round($distance, 2);
                $driversWithDistance[] = $driver;
            }
        }
        
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
        $earthRadius = 6371;
        
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
     */
    private function writeOrderToFirebase($orderId, array $drivers, $searchRadius = null)
    {
        try {
            $order = Order::with(['user', 'driver', 'address'])->find($orderId);

            if (!$order) {
                return [
                    'success' => false,
                    'message' => 'Order not found'
                ];
            }

            $driverIDs = array_map(function ($driver) {
                return $driver['id'];
            }, $drivers);

            $orderData = [
                'ride_id' => $orderId,
                'order_number' => $order->number,
                'status' => $order->status_text ?? 'pending',
                'order_status' => $order->order_status,
                'user_id' => $order->user_id,
                'user_info' => [
                    'id' => $order->user->id ?? null,
                    'name' => $order->user->name ?? '',
                    'email' => $order->user->email ?? '',
                    'phone' => $order->user->phone ?? '',
                ],
                'pickup_location' => [
                    'name' => $order->pick_up_name ?? '',
                    'latitude' => $order->start_lat,
                    'longitude' => $order->start_lng,
                ],
                'dropoff_location' => [
                    'name' => $order->drop_name ?? '',
                    'latitude' => $order->end_lat,
                    'longitude' => $order->end_lng,
                ],
                'pricing' => [
                    'price' => $order->price ?? 0,
                    'discount' => $order->discount ?? 0,
                    'final_price' => $order->final_price ?? 0,
                    'commission_amount' => $order->commission_amount ?? 0,
                    'driver_earnings' => $order->driver_earnings ?? 0,
                ],
                'payment_info' => [
                    'payment_method' => $order->payment_method_text ?? 'cash',
                    'payment_method_id' => $order->payment_method,
                    'payment_type' => $order->payment_status_text ?? 'unpaid',
                    'payment_type_id' => $order->payment_type,
                ],
                'driver_ids' => $driverIDs,
                'total_available_drivers' => count($driverIDs),
                'assigned_driver_id' => $order->driver_id,
                'driver_info' => $order->driver ? [
                    'id' => $order->driver->id,
                    'name' => $order->driver->name ?? '',
                    'phone' => $order->driver->phone ?? '',
                ] : null,
                'search_radius_km' => $searchRadius,
                'total_distance' => $order->total_distance,
                'total_time' => $order->total_time,
                'address_id' => $order->address_id,
                'created_at' => $order->created_at->toIso8601String(),
                'updated_at' => $order->updated_at->toIso8601String(),
                'firebase_created_at' => now()->toIso8601String(),
                'firebase_updated_at' => now()->toIso8601String(),
            ];

            $firestoreData = [
                'fields' => $this->convertToFirestoreFormat($orderData)['mapValue']['fields']
            ];

            $response = Http::timeout(10)->patch(
                "{$this->baseUrl}/ride_requests/{$orderId}",
                $firestoreData
            );

            if ($response->successful()) {
                Log::info("Order {$orderId} written to Firebase with " . count($driverIDs) . " drivers within {$searchRadius}km");
                return [
                    'success' => true,
                    'message' => 'Order data successfully written to Firebase',
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
            Log::error("Error writing order {$orderId} to Firebase: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to write order data to Firebase: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Convert PHP data to Firestore REST API format
     */
    private function convertToFirestoreFormat($data)
    {
        if (is_array($data)) {
            if (array_keys($data) === range(0, count($data) - 1)) {
                return [
                    'arrayValue' => [
                        'values' => array_map(function ($item) {
                            return $this->convertToFirestoreFormat($item);
                        }, $data)
                    ]
                ];
            } else {
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
     * Send notifications to drivers
     */
    private function sendNotificationsToDrivers(array $drivers, $orderId)
    {
        $sent = 0;
        $failed = 0;
        
        foreach ($drivers as $driver) {
            try {
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
            
            usleep(50000); // 50ms delay
        }
        
        return [
            'sent' => $sent,
            'failed' => $failed
        ];
    }
}