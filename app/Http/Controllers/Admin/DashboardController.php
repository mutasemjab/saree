<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

   public function index(Request $request)
    {
        $totalCustomers = User::count();
        $totalDrivers = Driver::count();
        $customersWithOrdersThisMonth = Order::whereMonth('created_at', now()->month)
            ->distinct('user_id')
            ->count('user_id');
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $totalOrders = Order::count();

        // Driver status filter
        $status = $request->get('status');

        $drivers = Driver::with('city')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalDrivers',
            'customersWithOrdersThisMonth',
            'newUsersThisMonth',
            'totalOrders',
            'drivers'
        ));
    }



}
