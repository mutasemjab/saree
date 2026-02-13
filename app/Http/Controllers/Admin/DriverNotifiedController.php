<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverNotified;
use App\Models\Order;
use Illuminate\Http\Request;

class DriverNotifiedController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:driverNotified-table|driverNotified-add|driverNotified-edit|driverNotified-delete', ['only' => ['index']]);
        $this->middleware('permission:driverNotified-delete', ['only' => ['destroy']]);
    }

    /**
     * Display orders that have driver notifications (one row per order)
     */
    public function index(Request $request)
    {
        $query = Order::with(['user'])
            ->whereHas('driverNotified')
            ->withCount([
                'driverNotified',
                'driverNotified as notified_count'  => fn($q) => $q->where('status', 'notified'),
                'driverNotified as accepted_count'  => fn($q) => $q->where('status', 'accepted'),
                'driverNotified as rejected_count'  => fn($q) => $q->where('status', 'rejected'),
                'driverNotified as ignored_count'   => fn($q) => $q->where('status', 'ignored'),
            ])
            ->latest();

        // Filter by order number
        if ($request->filled('search')) {
            $query->where('number', 'like', '%' . $request->search . '%');
        }

        // Filter by order status
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();

        // Global stats
        $stats = [
            'total_orders'  => Order::whereHas('driverNotified')->count(),
            'total_notified'=> DriverNotified::count(),
            'accepted'      => DriverNotified::where('status', 'accepted')->count(),
            'rejected'      => DriverNotified::where('status', 'rejected')->count(),
            'ignored'       => DriverNotified::where('status', 'ignored')->count(),
        ];

        return view('admin.driver_notified.index', compact('orders', 'stats'));
    }

    /**
     * Show all drivers notified for a specific order
     */
    public function show($orderId)
    {
        $order = Order::with('user')->findOrFail($orderId);

        $driverNotifieds = DriverNotified::with('driver')
            ->where('order_id', $orderId)
            ->orderBy('distance_km')
            ->get();

        $stats = [
            'total'    => $driverNotifieds->count(),
            'notified' => $driverNotifieds->where('status', 'notified')->count(),
            'accepted' => $driverNotifieds->where('status', 'accepted')->count(),
            'rejected' => $driverNotifieds->where('status', 'rejected')->count(),
            'ignored'  => $driverNotifieds->where('status', 'ignored')->count(),
        ];

        return view('admin.driver_notified.show', compact('order', 'driverNotifieds', 'stats'));
    }

    /**
     * Delete a single driver notification record
     */
    public function destroy($id)
    {
        DriverNotified::findOrFail($id)->delete();

        return back()->with('success', __('messages.deleted_successfully'));
    }
}