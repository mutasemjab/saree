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
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{


    public function index(Request $request)
    {
        $userId = auth()->user()->id;

        // Get the filters from the request
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $orderStatus = $request->query('order_status');
        $statusOfPrint = $request->query('status_of_print');

        // Initialize the query for fetching orders
        $query = Order::with('user', 'product')->where('user_id', $userId);

        // Apply date filtering
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif (!empty($fromDate)) {
            $query->whereDate('created_at', '>=', $fromDate);
        } elseif (!empty($toDate)) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        // Apply the order_status filter
        if (!empty($orderStatus)) {
            $query->where('order_status', $orderStatus);
        }

        // Apply the status_of_print filter
        if (!empty($statusOfPrint)) {
            $query->where('status_of_print', $statusOfPrint);
        }

        // Fetch the filtered orders
        $orders = $query->get();

        // Loop through each order and append the associated voucher product details
        foreach ($orders as $order) {
            // Fetch voucher product details for the current order
            $voucherProductDetails = VoucherProductDetail::where('order_id', $order->id)->get();

            // Add the voucher product details as a new property on the order
            $order->voucher_product_details = $voucherProductDetails;
        }

        // Return the orders with the embedded voucher product details
        return response()->json([
            'orders' => $orders
        ]);
    }







     public function store(Request $request)
    {
        $user = auth()->user();

        // Validate the incoming request data
        $validatedData = $request->validate([
            'number' => 'nullable|string',
            'order_status' => 'required|integer|in:1,2',
            'price' => 'required|numeric',
            'number_of_game' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        // Check if user_id is provided and if the user has a wallet
        if (!empty($validatedData['user_id'])) {
            $wallet = Wallet::where('user_id', $validatedData['user_id'])->first();

            // Check if the wallet exists and has enough total
            if ($wallet && $wallet->total >= $validatedData['price']) {

                // Check if there is a matching VoucherProductDetail
                $voucherProductDetail = VoucherProductDetail::whereHas('voucherProduct', function($query) use ($validatedData) {
                    $query->where('product_id', $validatedData['product_id']);
                })->where('status', 2) // Only check records with status 2
                  ->first();

                // If no voucherProductDetail exists, return a response
                if (!$voucherProductDetail) {
                    return response()->json(['error' => 'No card available'], 400);
                }

                // Deduct the price from the wallet's total
                $wallet->total -= $validatedData['price'];
                $wallet->save();

                // Save the transaction in the wallet_transactions table
                WalletTransaction::create([
                    'withdrawal' => $validatedData['price'],
                    'wallet_id' => $wallet->id,
                    'note' => 'Order payment deduction',
                ]);

                // Create a new order with the validated data
                $order = Order::create($validatedData);

                // Update the `number` field with the same value as `id`
                $order->update(['number' => $order->id]);

                   // Update the first voucherProductDetail with the new status and order_id
                    $voucherProductDetail->status = 1;
                    $voucherProductDetail->order_id = $order->id;
                    $voucherProductDetail->save(); // Save the changes

                // Optional game-related logic
                if ($order->number_of_game) {
                    $order->update(['order_status' => 3]);
                    // Notification logic
                    if ($user) { // Check if authenticated user exists
                        $title = 'You have a new Game Order';
                        $body = $order->number_of_game . ' from ' . $user->name . ' for ' . $order->product->name_en;
                        $type = 'Game Order';
                        $order_id = 1;

                        // Fetch admin to send notification (assuming you're notifying a specific admin)
                        $admin = Admin::first(); // Fetch the first admin as an example, or adjust to your logic

                        if ($admin && $admin->fcm_token) {
                            // Send push notification
                            AppSetting::push_notification($admin->fcm_token, $title, $body, $type, $order_id);

                            // Save the notification
                            $notification = new Notification([
                                'title' => $title,
                                'body' => $body,
                                'admin_id' => $admin->id, // Associate notification with the admin
                            ]);
                            $notification->save();
                        }
                    }
                }

                // Return a JSON response with the created order
                return response()->json($order, 200);
            } else {
                // If wallet does not exist or has insufficient funds, return an error response
                return response()->json(['error' => 'Insufficient funds in wallet'], 400);
            }
        } else {
            // If user_id is not provided, handle as needed (e.g., guest order or return error)
            return response()->json(['error' => 'User ID is required to check wallet balance'], 400);
        }
    }



    public function notPrint(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',  // Ensure order_id exists in the orders table
        ]);

        // Try to find and update the order
        $order = Order::find($validatedData['order_id']);

        if ($order) {
            // Update the status_of_print to 2
            $order->status_of_print = 2;
            $order->save();

            // Return a success response
            return response()->json([
                'message' => 'Order status updated successfully.',
                'order' => $order
            ], 200);
        } else {
            // Return an error response if the order is not found
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }
    }



}
