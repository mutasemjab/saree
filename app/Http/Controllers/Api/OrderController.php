<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\User;
use App\Services\DriverLocationService;
use App\Services\EnhancedFCMService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ApiResponseTrait;

    protected $driverLocationService;

    public function __construct(DriverLocationService $driverLocationService)
    {
        $this->driverLocationService = $driverLocationService;
    }

    /**
     * Create a new order and START progressive driver search via cron
     */
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_lat' => 'required|numeric|between:-90,90',
            'start_lng' => 'required|numeric|between:-180,180',
            'address_id' => 'nullable',
            'pick_up_name' => 'required',
            'price' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|integer|in:1,2', // 1 cash, 2 visa
            'city_id' => 'nullable|integer|exists:cities,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cityId = $request->city_id ?? 1;
            
            // Get initial search radius from settings
            $minRadius = Setting::where('city_id', $cityId)
                ->where('key', 'driver_search_min_radius_km')
                ->value('value') ?? 1;

            // Create the order with search tracking
            $order = Order::create([
                'start_lat' => $request->start_lat,
                'address_id' => $request->address_id,
                'start_lng' => $request->start_lng,
                'pick_up_name' => $request->pick_up_name,
                'order_status' => 1, // Pending
                'price' => $request->price ?? null,
                'payment_method' => $request->payment_method ?? 1,
                'payment_type' => 2, // Unpaid by default
                'user_id' => auth()->user()->id,
                'city_id' => $cityId,
                // Initialize search tracking for cron
                'search_started_at' => now(),
                'current_search_radius' => $minRadius,
                'last_search_at' => null,
                'search_iteration' => 0,
            ]);

            Log::info('Order created - Cron will handle progressive search', [
                'order_id' => $order->id,
                'user_lat' => $request->start_lat,
                'user_lng' => $request->start_lng,
                'initial_radius' => $minRadius,
                'city_id' => $cityId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully. Searching for nearby drivers...',
                'data' => [
                    'order' => $order,
                    'search_info' => [
                        'status' => 'searching',
                        'message' => 'We are searching for available drivers in your area. You will be notified once a driver accepts.',
                        'started_at' => $order->search_started_at->format('Y-m-d H:i:s'),
                        'current_radius_km' => $minRadius,
                    ],
                    'user_location' => [
                        'lat' => $request->start_lat,
                        'lng' => $request->start_lng
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get User Orders
     */
    public function userOrders(Request $request)
    {
        try {
            $user = $request->user();

            $query = Order::where('user_id', $user->id)->with(['driver']);

            // Filter by status if provided
            if ($request->has('status') && $request->status != '') {
                $query->where('order_status', $request->status);
            }

            // Filter by payment type if provided
            if ($request->has('payment_type') && $request->payment_type != '') {
                $query->where('payment_type', $request->payment_type);
            }

            $orders = $query->latest()->paginate(15);

            $ordersData = $orders->getCollection()->map(function ($order) {
                $orderData = [
                    'id' => $order->id,
                    'number' => $order->number,
                    'order_status' => $order->order_status,
                    'status_text' => $order->status_text,
                    'status_color' => $order->status_color,
                    'price' => $order->price,
                    'discount' => $order->discount,
                    'final_price' => $order->final_price,
                    'formatted_final_price' => $order->formatted_final_price,
                    'total_distance' => $order->total_distance,
                    'total_time' => $order->total_time,
                    'payment_type' => $order->payment_type,
                    'payment_status_text' => $order->payment_status_text,
                    'payment_method' => $order->payment_method,
                    'payment_method_text' => $order->payment_method_text,
                    'start_lat' => $order->start_lat,
                    'start_lng' => $order->start_lng,
                    'pick_up_name' => $order->pick_up_name,
                    'driver' => $order->driver ? [
                        'id' => $order->driver->id,
                        'name' => $order->driver->name,
                        'phone' => $order->driver->phone,
                        'photo' => $order->driver->photo ? asset('storage/' . $order->driver->photo) : null,
                        'activate' => $order->driver->activate,
                    ] : null,
                    'can_cancel' => $order->canBeCancelled(),
                    'is_active' => $order->isActive(),
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
                ];

                // Add search progress for pending orders
                if ($order->isPending() && $order->search_started_at) {
                    $orderData['search_progress'] = [
                        'is_searching' => true,
                        'current_radius_km' => $order->current_search_radius,
                        'iteration' => $order->search_iteration,
                        'elapsed_seconds' => $order->search_started_at->diffInSeconds(now()),
                    ];
                }

                return $orderData;
            });

            return $this->successResponse('Orders retrieved successfully', [
                'orders' => $ordersData,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'has_more_pages' => $orders->hasMorePages(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve orders');
        }
    }

    /**
     * Get Driver Orders
     */
    public function driverOrders(Request $request)
    {
        try {
            $driver = $request->user();

            $query = Order::with(['user']);
            $query->where('driver_id', $driver->id);

            // Filter by status if provided
            if ($request->has('status') && $request->status != '') {
                $query->where('order_status', $request->status);
            }

            $orders = $query->latest()->paginate(15);

            $ordersData = $orders->getCollection()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'number' => $order->number,
                    'order_status' => $order->order_status,
                    'status_text' => $order->status_text,
                    'status_color' => $order->status_color,
                    'price' => $order->price,
                    'discount' => $order->discount,
                    'final_price' => $order->final_price,
                    'formatted_final_price' => $order->formatted_final_price,
                    'total_distance' => $order->total_distance,
                    'total_time' => $order->total_time,
                    'payment_type' => $order->payment_type,
                    'payment_status_text' => $order->payment_status_text,
                    'payment_method' => $order->payment_method,
                    'payment_method_text' => $order->payment_method_text,
                    'start_lat' => $order->start_lat,
                    'start_lng' => $order->start_lng,
                    'pick_up_name' => $order->pick_up_name,
                    'address' => $order->address,
                    'user' => $order->user ? [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'phone' => $order->user->phone,
                        'photo' => $order->user->photo ? asset('assets/admin/uploads/' . $order->user->photo) : null,
                        'lat' => $order->user->lat,
                        'lng' => $order->user->lng,
                        'activate' => $order->user->activate,
                    ] : null,
                    'can_accept' => $order->canBeAssignedDriver() && !$order->hasDriver(),
                    'can_cancel' => $order->canBeCancelled(),
                    'is_active' => $order->isActive(),
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->successResponse('Orders retrieved successfully', [
                'orders' => $ordersData,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'has_more_pages' => $orders->hasMorePages(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve orders');
        }
    }

    public function ordersAcceptedAndOnTheWay(Request $request)
    {
        try {
            $driver = $request->user();

            $query = Order::with(['user']);
            $query->where('driver_id', $driver->id);
            $query->whereIn('order_status', [2, 3]);

            $orders = $query->latest()->paginate(15);
            
            $ordersData = $orders->getCollection()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'number' => $order->number,
                    'order_status' => $order->order_status,
                    'status_text' => $order->status_text,
                    'status_color' => $order->status_color,
                    'price' => $order->price,
                    'discount' => $order->discount,
                    'final_price' => $order->final_price,
                    'formatted_final_price' => $order->formatted_final_price,
                    'total_distance' => $order->total_distance,
                    'total_time' => $order->total_time,
                    'payment_type' => $order->payment_type,
                    'payment_status_text' => $order->payment_status_text,
                    'payment_method' => $order->payment_method,
                    'payment_method_text' => $order->payment_method_text,
                    'start_lat' => $order->start_lat,
                    'start_lng' => $order->start_lng,
                    'pick_up_name' => $order->pick_up_name,
                    'user' => $order->user ? [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'phone' => $order->user->phone,
                        'photo' => $order->user->photo ? asset('assets/admin/uploads/' . $order->user->photo) : null,
                        'lat' => $order->user->lat,
                        'lng' => $order->user->lng,
                        'activate' => $order->user->activate,
                    ] : null,
                    'can_accept' => $order->canBeAssignedDriver() && !$order->hasDriver(),
                    'can_cancel' => $order->canBeCancelled(),
                    'is_active' => $order->isActive(),
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
                ];
            });
            
            return $this->successResponse('Orders retrieved successfully', [
                'orders' => $ordersData,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'has_more_pages' => $orders->hasMorePages(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve orders');
        }
    }

    /**
     * Get Order Details
     */
    public function orderDetails(Request $request, $id)
    {
        try {
            $user = $request->user();
            $userType = $user instanceof User ? 'user' : 'driver';

            $query = Order::with(['user', 'driver']);

            if ($userType === 'user') {
                $query->where('user_id', $user->id);
            } else {
                $query->where(function ($q) use ($user) {
                    $q->where('driver_id', $user->id)
                        ->orWhere(function ($subQ) {
                            $subQ->where('order_status', 1)->whereNull('driver_id');
                        });
                });
            }

            $order = $query->find($id);

            if (!$order) {
                return $this->notFoundResponse('Order not found or access denied');
            }

            $orderData = [
                'id' => $order->id,
                'number' => $order->number,
                'order_status' => $order->order_status,
                'status_text' => $order->status_text,
                'status_color' => $order->status_color,
                'price' => $order->price,
                'pick_up_name' => $order->pick_up_name,
                'discount' => $order->discount,
                'final_price' => $order->final_price,
                'formatted_price' => $order->formatted_price,
                'formatted_discount' => $order->formatted_discount,
                'formatted_final_price' => $order->formatted_final_price,
                'formatted_distance' => $order->formatted_distance,
                'total_distance' => $order->total_distance,
                'total_time' => $order->total_time,
                'payment_type' => $order->payment_type,
                'payment_status_text' => $order->payment_status_text,
                'payment_method' => $order->payment_method,
                'payment_method_text' => $order->payment_method_text,
                'start_lat' => $order->start_lat,
                'start_lng' => $order->start_lng,
                'user' => $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'phone' => $order->user->phone,
                    'photo' => $order->user->photo ? asset('assets/admin/uploads/' . $order->user->photo) : null,
                    'lat' => $order->user->lat,
                    'lng' => $order->user->lng,
                    'activate' => $order->user->activate,
                ] : null,
                'driver' => $order->driver ? [
                    'id' => $order->driver->id,
                    'name' => $order->driver->name,
                    'phone' => $order->driver->phone,
                    'photo' => $order->driver->photo ? asset('assets/admin/uploads/' . $order->driver->photo) : null,
                    'activate' => $order->driver->activate,
                ] : null,
                'is_pending' => $order->isPending(),
                'is_accepted' => $order->isAccepted(),
                'is_on_the_way' => $order->isOnTheWay(),
                'is_delivered' => $order->isDelivered(),
                'is_cancelled' => $order->isCancelled(),
                'is_active' => $order->isActive(),
                'has_driver' => $order->hasDriver(),
                'can_accept' => $order->canBeAssignedDriver() && !$order->hasDriver(),
                'can_cancel' => $order->canBeCancelled(),
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
            ];

            // Add search progress if still searching
            if ($order->isPending() && $order->search_started_at) {
                $orderData['search_progress'] = [
                    'is_searching' => true,
                    'started_at' => $order->search_started_at->format('Y-m-d H:i:s'),
                    'current_radius_km' => $order->current_search_radius,
                    'iteration' => $order->search_iteration,
                    'last_search_at' => $order->last_search_at ? $order->last_search_at->format('Y-m-d H:i:s') : null,
                    'elapsed_seconds' => $order->search_started_at->diffInSeconds(now()),
                ];
            }

            return $this->successResponse('Order details retrieved successfully', [
                'order' => $orderData
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve order details');
        }
    }

    public function acceptOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $order = Order::find($request->order_id);
            $driver = Driver::find($request->driver_id);

            if ($order->order_status !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is no longer available'
                ], 409);
            }

            if ($driver->status !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Driver is not available'
                ], 409);
            }

            $activeOrder = Order::where('driver_id', $request->driver_id)
                ->whereIn('order_status', [2, 3])
                ->first();

            if ($activeOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Driver already has an active order'
                ], 409);
            }

            // Accept the order and clean up search tracking
            $order->update([
                'driver_id' => $request->driver_id,
                'order_status' => 2, // Accepted
                // Clear search tracking
                'search_started_at' => null,
                'current_search_radius' => null,
                'last_search_at' => null,
                'search_iteration' => null,
            ]);

            // Notify the user
            EnhancedFCMService::sendOrderStatusToUser($order->id, 2);

            DB::commit();

            Log::info("Order #{$order->id} accepted by driver #{$driver->id}");

            return response()->json([
                'success' => true,
                'message' => 'Order accepted successfully',
                'data' => [
                    'order' => $order->load(['user', 'driver']),
                    'driver' => $driver
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting order: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error accepting order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        $userType = $user instanceof User ? 'user' : 'driver';
        
        $validator = Validator::make($request->all(), [
            'order_status' => 'required|integer|in:1,2,3,4,5,6,7',
            'end_lat' => 'required_if:order_status,4|numeric',
            'end_lng' => 'required_if:order_status,4|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $query = Order::query();

        if ($userType === 'user') {
            $query->where('user_id', $user->id);
            if ($request->order_status != 5) {
                return $this->forbiddenResponse('Users can only cancel orders (status 5)');
            }
        } else {
            $query->where('driver_id', $user->id);
            if ($request->order_status == 5) {
                return $this->forbiddenResponse('Drivers cannot set order as cancelled by user');
            }
            if (!in_array($request->order_status, [2, 3, 4, 6])) {
                return $this->forbiddenResponse('Invalid status for driver');
            }
        }

        $order = $query->find($id);
        if (!$order) {
            return $this->notFoundResponse('Order not found or access denied');
        }

        if ($order->isCancelled() || $order->isDelivered()) {
            return $this->errorResponse('Cannot update status of completed/cancelled order');
        }

        $currentStatus = $order->order_status;
        $newStatus = $request->order_status;

        $validTransitions = [
            1 => [2, 5, 6, 7],
            2 => [3, 4, 6],
            3 => [4, 6],
            7 => [1], // Allow retry from no drivers available
        ];

        if (
            isset($validTransitions[$currentStatus]) &&
            !in_array($newStatus, $validTransitions[$currentStatus])
        ) {
            return $this->errorResponse('Invalid status transition');
        }

        $updateData = ['order_status' => $newStatus];

        if ($newStatus == 4) {
            $updateData['end_lat'] = $request->end_lat;
            $updateData['end_lng'] = $request->end_lng;
            $updateData['payment_type'] = 1;

            $cityId = $order->city_id ?? 1;
            $startPrice = Setting::where('city_id', $cityId)->where('key', 'start_price')->value('value') ?? 0;
            $pricePerKm = Setting::where('city_id', $cityId)->where('key', 'price_per_km')->value('value') ?? 0;
            $commissionAdmin = Setting::where('city_id', $cityId)->where('key', 'commission_admin')->value('value') ?? 0;

            $distance = $this->calculateDistance(
                $order->start_lat,
                $order->start_lng,
                $request->end_lat,
                $request->end_lng
            );

            $totalPrice = $startPrice + ($distance * $pricePerKm);
            if ($totalPrice < 1) {
                $totalPrice = 1;
            }
            
            $commissionAmount = ($totalPrice * $commissionAdmin) / 100;

            $updateData['total_distance'] = $distance;
            $updateData['final_price'] = $totalPrice;
            $updateData['commission_amount'] = $commissionAmount;

            $this->processWalletTransactions($order, $commissionAmount);
        }

        $order->update($updateData);
        $order->load(['user', 'driver']);

        EnhancedFCMService::sendOrderStatusToUser($order->id, $newStatus);

        $statusText = $this->getOrderStatusText($newStatus);

        $responseData = [
            'order' => [
                'id' => $order->id,
                'number' => $order->number,
                'order_status' => $order->order_status,
                'status_text' => $statusText,
                'status_color' => $order->status_color,
                'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
            ]
        ];

        if ($newStatus == 4) {
            $responseData['order']['distance'] = $order->total_distance;
            $responseData['order']['total_price'] = $order->final_price;
            $responseData['order']['commission_amount'] = $order->commission_amount;
            $responseData['order']['payment_type'] = $order->payment_type;
            $responseData['order']['payment_status'] = $order->payment_type == 1 ? 'Paid' : 'Unpaid';
            $responseData['order']['end_lat'] = $order->end_lat;
            $responseData['order']['end_lng'] = $order->end_lng;
        }

        return $this->successResponse('Order status updated successfully', $responseData);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    private function processWalletTransactions($order, $commissionAmount)
    {
        DB::transaction(function () use ($order, $commissionAmount) {
            $driverWallet = Wallet::firstOrCreate(
                ['driver_id' => $order->driver_id],
                ['total' => 0]
            );

            $adminWallet = Wallet::firstOrCreate(
                ['admin_id' => 1],
                ['total' => 0]
            );

            $driverWallet->decrement('total', $commissionAmount);
            $adminWallet->increment('total', $commissionAmount);

            WalletTransaction::create([
                'deposit' => 0,
                'withdrawal' => $commissionAmount,
                'note' => "Deduct from order #{$order->number} - the admin commission",
                'wallet_id' => $driverWallet->id,
                'driver_id' => $order->driver_id,
            ]);

            WalletTransaction::create([
                'deposit' => $commissionAmount,
                'withdrawal' => 0,
                'note' => "Commission from order #{$order->number} - Admin commission ({$this->getCommissionPercentage()}%)",
                'wallet_id' => $adminWallet->id,
                'admin_id' => 1,
            ]);
        });
    }

    private function getCommissionPercentage()
    {
        return Setting::where('key', 'commission_admin')->value('value') ?? 0;
    }

    private function getOrderStatusText($status)
    {
        $statuses = [
            1 => 'Pending',
            2 => 'Accepted',
            3 => 'On the way',
            4 => 'Delivered',
            5 => 'Cancelled by user',
            6 => 'Cancelled by driver',
            7 => 'No drivers available',
        ];

        return $statuses[$status] ?? 'Unknown';
    }
}