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

class OrderController extends Controller
{
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
        
        return view('orders.show', compact('order'));
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

    /**
     * Get order statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 1)->count(),
            'accepted_orders' => Order::where('order_status', 2)->count(),
            'on_way_orders' => Order::where('order_status', 3)->count(),
            'delivered_orders' => Order::where('order_status', 4)->count(),
            'cancelled_by_user_orders' => Order::where('order_status', 5)->count(),
            'cancelled_by_driver_orders' => Order::where('order_status', 6)->count(),
            'total_revenue' => Order::whereIn('order_status', [4])->sum('final_price'),
            'average_order_value' => Order::whereIn('order_status', [4])->avg('final_price'),
            'cash_orders' => Order::where('payment_method', 1)->count(),
            'visa_orders' => Order::where('payment_method', 2)->count(),
            'paid_orders' => Order::where('payment_type', 1)->count(),
            'unpaid_orders' => Order::where('payment_type', 2)->count(),
        ];

        return view('orders.statistics', compact('stats'));
    }

}
