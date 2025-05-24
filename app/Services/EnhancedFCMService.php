<?php

namespace App\Services;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;

class EnhancedFCMService extends AdminFCMController
{
    /**
     * Send new order notification to a specific driver
     */
    public static function sendNewOrderToDriver($driverId, $orderId, $distance, $userLocation = null)
    {
        $driver = Driver::find($driverId);
        $order = Order::with('user')->find($orderId);
        
        if (!$driver || !$order || !$driver->fcm_token) {
            \Log::error("Cannot send notification - Driver ID: $driverId, Order ID: $orderId");
            return false;
        }
        
        // Customize notification content
        $title = '🚗 طلب توصيل جديد';
        $body = "طلب جديد على بعد {$distance} كم - اضغط للقبول";
        
        // Add order details to notification data
        $orderData = [
            'order_id' => (string)$orderId,
            'driver_id' => (string)$driverId,
            'distance' => (string)$distance,
            'order_number' => $order->number ?? '',
            'user_name' => $order->user->name ?? 'مستخدم',
            'price' => (string)($order->price ?? 0),
            'payment_method' => (string)$order->payment_method,
            'screen' => 'new_order',
            'action' => 'accept_order'
        ];
        
        return self::sendMessageWithData(
            $title,
            $body,
            $driver->fcm_token,
            $driverId,
            $orderData
        );
    }
    
    /**
     * Send notification with custom data payload
     */
    public static function sendMessageWithData($title, $body, $fcmToken, $userId, $customData = [])
    {
        if (!$fcmToken) {
            \Log::error("FCM Error: No FCM token provided for user ID $userId");
            return false;
        }

        $credentialsFilePath = base_path('json/saree3-5d027-8d02870ad8f5.json');

        try {
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->useApplicationDefaultCredentials();
            $client->fetchAccessTokenWithAssertion();
            $tokenResponse = $client->getAccessToken();

            $access_token = $tokenResponse['access_token'];

            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json'
            ];

            // Build data payload
            $dataPayload = array_merge([
                'screen' => 'order',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ], $customData);

            $data = [
                "message" => [
                    "token" => $fcmToken,
                    "notification" => [
                        "title" => $title,
                        "body" => $body
                    ],
                    "data" => $dataPayload,
                    "android" => [
                        "priority" => "high",
                        "notification" => [
                            "sound" => "default",
                            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                            "channel_id" => "order_notifications"
                        ]
                    ],
                    "apns" => [
                        "payload" => [
                            "aps" => [
                                "sound" => "default",
                                "badge" => 1
                            ]
                        ]
                    ]
                ]
            ];

            $payload = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/saree3-5d027/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $result = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($result === false || $err) {
                \Log::error("FCM Error for user ID $userId: cURL Error: " . $err);
                return false;
            } else {
                $response = json_decode($result, true);
                \Log::info("FCM Response for user ID $userId: " . json_encode($response));
                
                if (isset($response['name'])) {
                    return true;
                } else {
                    \Log::error("FCM Error for user ID $userId: " . json_encode($response));
                    if (isset($response['error']['details'][0]['errorCode']) && $response['error']['details'][0]['errorCode'] === 'UNREGISTERED') {
                        \Log::info("FCM token cleanup for user ID $userId");
                        Driver::where('id', $userId)->update(['fcm_token' => null]);
                    }
                    return false;
                }
            }
        } catch (\Exception $e) {
            \Log::error("FCM Error for user ID $userId: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send order status update to user
     */
    public static function sendOrderStatusToUser($orderId, $status)
    {
        $order = Order::with('user')->find($orderId);
        
        if (!$order || !$order->user || !$order->user->fcm_token) {
            return false;
        }
        
        $statusMessages = [
            2 => 'تم قبول طلبك! السائق في الطريق إليك',
            3 => 'السائق في الطريق إليك الآن',
            4 => 'تم تسليم طلبك بنجاح',
            5 => 'تم إلغاء الطلب',
            6 => 'قام السائق بإلغاء الطلب'
        ];
        
        $title = 'تحديث الطلب';
        $body = $statusMessages[$status] ?? 'تم تحديث حالة طلبك';
        
        return self::sendMessage($title, $body, $order->user->fcm_token, $order->user->id, 'order_status');
    }
    
    /**
     * Send bulk notifications to multiple drivers
     */
    public static function sendBulkToDrivers(array $driverIds, $title, $body, $customData = [])
    {
        $sent = 0;
        $failed = 0;
        
        $drivers = Driver::whereIn('id', $driverIds)
            ->whereNotNull('fcm_token')
            ->get();
            
        foreach ($drivers as $driver) {
            $result = self::sendMessageWithData($title, $body, $driver->fcm_token, $driver->id, $customData);
            
            if ($result) {
                $sent++;
            } else {
                $failed++;
            }
            
            // Small delay to prevent rate limiting
            usleep(50000); // 50ms
        }
        
        return [
            'sent' => $sent,
            'failed' => $failed,
            'total' => count($drivers)
        ];
    }
}