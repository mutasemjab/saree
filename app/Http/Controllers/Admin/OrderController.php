<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\AppSetting;
use App\Models\Driver;
use Carbon\Carbon;
use App\Services\EnhancedFCMService;

class OrderController extends Controller
{
    
    public function cancel(Order $order)
{
    // Check if order can be cancelled
    if (in_array($order->order_status, [4, 5, 6])) {
        return redirect()->back()
            ->with('error', __('messages.order_cannot_be_cancelled'));
    }

    try {
        // Store the previous status before updating
        $previousStatus = $order->order_status;
        
        // Update order status to cancelled by user (5)
        $order->update([
            'order_status' => 5
        ]);

        // Send notification to user if they exist
        // if ($order->user && $order->user->fcm_token) {
        //     $userTitle = 'إلغاء الطلب';
        //     $userBody = "تم إلغاء طلبك رقم {$order->number} من قبل الإدارة";
            
        //     $userData = [
        //         'order_id' => (string)$order->id,
        //         'order_number' => $order->number ?? '',
        //         'status' => '5',
        //         'screen' => 'order_details',
        //         'action' => 'order_cancelled'
        //     ];
            
        //     EnhancedFCMService::sendMessageWithData(
        //         $userTitle,
        //         $userBody,
        //         $order->user->fcm_token,
        //         $order->user->id,
        //         $userData
        //     );
            
        //     \Log::info('Cancellation notification sent to user', [
        //         'order_id' => $order->id,
        //         'user_id' => $order->user->id
        //     ]);
        // }

        // Send notification to driver if they exist and are assigned
        // if ($order->driver && $order->driver->fcm_token) {
        //     $driverTitle = 'إلغاء الطلب';
        //     $driverBody = "تم إلغاء الطلب رقم {$order->number} من قبل الإدارة";
            
        //     $driverData = [
        //         'order_id' => (string)$order->id,
        //         'order_number' => $order->number ?? '',
        //         'status' => '5',
        //         'screen' => 'order_details',
        //         'action' => 'order_cancelled'
        //     ];
            
        //     EnhancedFCMService::sendMessageWithData(
        //         $driverTitle,
        //         $driverBody,
        //         $order->driver->fcm_token,
        //         $order->driver->id,
        //         $driverData
        //     );
            
        //     \Log::info('Cancellation notification sent to driver', [
        //         'order_id' => $order->id,
        //         'driver_id' => $order->driver->id
        //     ]);
        // }

        // Log the cancellation
        \Log::info('Order cancelled by admin', [
            'order_id' => $order->id,
            'order_number' => $order->number,
            'previous_status' => $previousStatus,
            'cancelled_by' => auth()->user()->id ?? 'system'
        ]);

        return redirect()->back()
            ->with('success', __('messages.order_cancelled_successfully'));
            
    } catch (\Exception $e) {
        \Log::error('Error cancelling order', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', __('messages.error_cancelling_order'));
    }
}
    
    
      /**
     * Display a listing of the resource.
     */
    public function ordersToday(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $query = Order::whereBetween('created_at', [$today, $tomorrow])
            ->with(['user', 'driver'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment type
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(15);
        
        return view('admin.orders.today', compact('orders'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'driver'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment type
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

  
    public function show(Order $order)
    {
        $order->load(['user', 'driver']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $users = User::active()->get();
        $drivers = Driver::active()->get();
        
        return view('admin.orders.edit', compact('order', 'users', 'drivers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'number' => 'nullable|string|unique:orders,number,' . $order->id,
            'order_status' => 'required|integer|in:1,2,3,4,5,6',
            'price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'total_distance' => 'nullable|numeric|min:0',
            'total_time' => 'nullable|string',
            'payment_type' => 'required|integer|in:1,2',
            'payment_method' => 'required|integer|in:1,2',
            'user_id' => 'required|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        // Calculate final price if not provided
        if (empty($validated['final_price']) && !empty($validated['price'])) {
            $discount = $validated['discount'] ?? 0;
            $validated['final_price'] = $validated['price'] - $discount;
        }

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', __('messages.order_updated_successfully'));
    }

 

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|integer|in:1,2,3,4,5,6',
        ]);

        $order->update($validated);

        return redirect()->back()
            ->with('success', __('messages.order_status_updated_successfully'));
    }

    /**
     * Assign driver to order.
     */
    public function assignDriver(Request $request, Order $order)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $order->update($validated);

        return redirect()->back()
            ->with('success', __('messages.driver_assigned_successfully'));
    }

    /**
     * Get orders by status.
     */
    public function byStatus($status)
    {
        $orders = Order::with(['user', 'driver'])
            ->where('order_status', $status)
            ->latest()
            ->paginate(15);
            
        return view('orders.by-status', compact('orders', 'status'));
    }


}
