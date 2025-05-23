<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\VoucherProductDetail;
use Illuminate\Http\Request;
use App\Helpers\AppSetting;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\Notification;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ApiResponseTrait;

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
            
            // Check if user/driver has access to this order
            if ($userType === 'user') {
                $query->where('user_id', $user->id);
            } else {
                // Driver can see orders assigned to them OR available pending orders
                $query->where(function($q) use ($user) {
                    $q->where('driver_id', $user->id)
                      ->orWhere(function($subQ) {
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
                // Status flags
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

            return $this->successResponse('Order details retrieved successfully', [
                'order' => $orderData
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve order details');
        }
    }

    /**
     * Store New Order (User only)
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            // Ensure only users can create orders
            if (!($user instanceof User)) {
                return $this->forbiddenResponse('Only users can create orders');
            }

            $validator = Validator::make($request->all(), [
                'price' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'final_price' => 'nullable|numeric|min:0',
                'total_distance' => 'nullable|numeric|min:0',
                'total_time' => 'nullable|string|max:255',
                'payment_type' => 'required|integer|in:1,2',
                'payment_method' => 'required|integer|in:1,2',           
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $data = $request->only([
                'price', 'discount', 'final_price', 'total_distance', 
                'total_time', 'payment_type', 'payment_method'
            ]);

            // Set user and default status
            $data['user_id'] = $user->id;
            $data['order_status'] = 1; // Pending
            $data['number'] = Order::generateOrderNumber();

            // Calculate final price if not provided
            if (empty($data['final_price']) && !empty($data['price'])) {
                $discount = $data['discount'] ?? 0;
                $data['final_price'] = max(0, $data['price'] - $discount);
            }

            $order = Order::create($data);
            $order->load(['user', 'driver']);

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
                'payment_method' => $order->payment_method,
                'is_pending' => $order->isPending(),
                'can_cancel' => $order->canBeCancelled(),
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            ];

            return $this->successResponse('Order created successfully', [
                'order' => $orderData
            ], 201);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to create order');
        }
    }

    /**
     * Accept Order (Driver only)
     */
    public function acceptOrder(Request $request, $id)
    {
        try {
            $driver = $request->user();

            // Ensure only drivers can accept orders
            if (!($driver instanceof Driver)) {
                return $this->forbiddenResponse('Only drivers can accept orders');
            }

            $order = Order::where('order_status', 1) // Only pending orders
                ->whereNull('driver_id') // Not assigned to any driver
                ->find($id);

            if (!$order) {
                return $this->notFoundResponse('Order not found or already assigned');
            }

            $order->update([
                'driver_id' => $driver->id,
                'order_status' => 2 // Accepted
            ]);

            $order->load(['user', 'driver']);

            $orderData = [
                'id' => $order->id,
                'number' => $order->number,
                'order_status' => $order->order_status,
                'status_text' => $order->status_text,
                'driver' => [
                    'id' => $order->driver->id,
                    'name' => $order->driver->name,
                    'phone' => $order->driver->phone,
                ]
            ];

            return $this->successResponse('Order accepted successfully', [
                'order' => $orderData
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to accept order');
        }
    }

    /**
     * Update Order Status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = $request->user();
            $userType = $user instanceof User ? 'user' : 'driver';

            $validator = Validator::make($request->all(), [
                'order_status' => 'required|integer|in:1,2,3,4,5,6',
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $query = Order::query();
            
            // Check ownership and permissions
            if ($userType === 'user') {
                $query->where('user_id', $user->id);
                // Users can only cancel their orders (status 5)
                if ($request->order_status != 5) {
                    return $this->forbiddenResponse('Users can only cancel orders (status 5)');
                }
            } else {
                $query->where('driver_id', $user->id);
                // Drivers cannot set status to 5 (cancelled by user)
                if ($request->order_status == 5) {
                    return $this->forbiddenResponse('Drivers cannot set order as cancelled by user');
                }
                // Drivers can set status 2,3,4,6
                if (!in_array($request->order_status, [2, 3, 4, 6])) {
                    return $this->forbiddenResponse('Invalid status for driver');
                }
            }

            $order = $query->find($id);

            if (!$order) {
                return $this->notFoundResponse('Order not found or access denied');
            }

            // Check if order can be updated
            if ($order->isCancelled() || $order->isDelivered()) {
                return $this->errorResponse('Cannot update status of completed/cancelled order');
            }

            // Business logic for status transitions
            $currentStatus = $order->order_status;
            $newStatus = $request->order_status;

            // Validate status transitions
            $validTransitions = [
                1 => [2, 5, 6], // Pending -> Accepted, Cancelled by User, Cancelled by Driver
                2 => [3, 4, 6], // Accepted -> On the way, Delivered, Cancelled by Driver
                3 => [4, 6],    // On the way -> Delivered, Cancelled by Driver
            ];

            if (isset($validTransitions[$currentStatus]) && 
                !in_array($newStatus, $validTransitions[$currentStatus])) {
                return $this->errorResponse('Invalid status transition');
            }

            $order->update(['order_status' => $newStatus]);
            $order->load(['user', 'driver']);

            return $this->successResponse('Order status updated successfully', [
                'order' => [
                    'id' => $order->id,
                    'number' => $order->number,
                    'order_status' => $order->order_status,
                    'status_text' => $order->status_text,
                    'status_color' => $order->status_color,
                    'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to update order status');
        }
    }

   

}
