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
    public function __construct()
    {
        $this->middleware('permission:order-table', ['only' => ['index', 'ordersToday', 'show', 'byStatus']]);
        $this->middleware('permission:order-edit', ['only' => ['edit', 'update', 'updateStatus', 'assignDriver', 'cancel']]);
    }

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


public function index(Request $request)
{
    $query = Order::with(['user', 'driver']);

    // Search by order number
    if ($request->filled('search')) {
        $query->where('number', 'like', '%' . $request->search . '%');
    }

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

    // Filter by city - through user relationship
    if ($request->filled('city_id')) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('city_id', $request->city_id);
        });
    }

    $orders = $query->latest()->paginate(15)->withQueryString();

    // Get all cities for the dropdown
    $cities = \App\Models\City::orderBy('name')->get();

    return view('admin.orders.index', compact('orders', 'cities'));
}

public function ordersToday(Request $request)
{
    $query = Order::with(['user', 'driver'])
        ->whereDate('created_at', today());

    // Search by order number
    if ($request->filled('search')) {
        $query->where('number', 'like', '%' . $request->search . '%');
    }

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

    // Filter by city - through user relationship
    if ($request->filled('city_id')) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('city_id', $request->city_id);
        });
    }

    $orders = $query->latest()->paginate(15)->withQueryString();

    // Get all cities for the dropdown
    $cities = \App\Models\City::orderBy('name')->get();

    return view('admin.orders.today', compact('orders', 'cities'));
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
