<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverNotified;
use App\Models\Order;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverNotifiedController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:driverNotified-table|driverNotified-add|driverNotified-edit|driverNotified-delete', ['only' => ['index']]);
        $this->middleware('permission:driverNotified-add',    ['only' => ['create', 'store']]);
        $this->middleware('permission:driverNotified-edit',   ['only' => ['edit', 'update']]);
        $this->middleware('permission:driverNotified-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of notified drivers.
     */
    public function index(Request $request)
    {
        $query = DriverNotified::with(['order', 'driver'])
            ->latest();

        // Filter by order
        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by radius
        if ($request->filled('radius_km')) {
            $query->where('radius_km', $request->radius_km);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('notified_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('notified_at', '<=', $request->date_to);
        }

        $driverNotifieds = $query->paginate(20)->withQueryString();

        // For filter dropdowns
        $orders  = Order::select('id', 'number')->latest()->get();
        $drivers = Driver::select('id', 'name')->where('activate', 1)->get();

        $statusOptions = [
            'notified' => __('messages.notified'),
            'accepted' => __('messages.accepted'),
            'rejected' => __('messages.rejected'),
            'ignored'  => __('messages.ignored'),
        ];

        // Stats cards
        $stats = [
            'total'    => DriverNotified::count(),
            'notified' => DriverNotified::where('status', 'notified')->count(),
            'accepted' => DriverNotified::where('status', 'accepted')->count(),
            'rejected' => DriverNotified::where('status', 'rejected')->count(),
            'ignored'  => DriverNotified::where('status', 'ignored')->count(),
        ];

        return view('admin.driver_notified.index', compact(
            'driverNotifieds',
            'orders',
            'drivers',
            'statusOptions',
            'stats'
        ));
    }

    /**
     * Show details for a specific record.
     */
    public function show($id)
    {
        $driverNotified = DriverNotified::with(['order.user', 'driver'])->findOrFail($id);
        return view('admin.driver_notified.show', compact('driverNotified'));
    }

   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('driverNotified-delete');

        DriverNotified::findOrFail($id)->delete();

        return redirect()
            ->route('admin.driver-notified.index')
            ->with('success', __('messages.deleted_successfully'));
    }

    /**
     * Show all drivers notified for a specific order.
     */
    public function byOrder($orderId)
    {
        $order = Order::with('user')->findOrFail($orderId);

        $driverNotifieds = DriverNotified::with('driver')
            ->where('order_id', $orderId)
            ->orderBy('distance_km')
            ->get();

        return view('admin.driver_notified.by_order', compact('order', 'driverNotifieds'));
    }
}