<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Get total customers (users)
    $totalCustomers = User::count();
    $totalDrivers = Driver::count();

    // Get customers who made an order this month
    $customersWithOrdersThisMonth = Order::whereMonth('created_at', now()->month)
        ->distinct('user_id')
        ->count('user_id');

    // Get new users registered this month
    $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

    // Get total orders
    $totalOrders = Order::count();


    return view('admin.dashboard', compact(
        'totalCustomers',
        'totalDrivers',
        'customersWithOrdersThisMonth',
        'newUsersThisMonth',
        'totalOrders',
    ));
}

}
