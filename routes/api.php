<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Support\Facades\Route;

// USER API ROUTES
Route::group(['prefix' => 'v1/user'], function () {

    //---------------- Auth --------------------//
    Route::post('/register', [AuthController::class, 'userRegister']);
    Route::get('/cities', [AuthController::class, 'getCitites']);
    Route::post('/login', [AuthController::class, 'userLogin']);

    //Route unAuth
    // Settings
    Route::get('/settings', [SettingController::class, 'index']);

    // Auth Route
    Route::group(['middleware' => ['auth:sanctum']], function () {
       
        Route::get('/test_notification_for_user/{orderId}', [OrderController::class, 'test_notification_for_user']);

        Route::post('/update-fcm-token', [HomeController::class, 'updateFcmToken']);
        
        
        
        Route::get('/addresses', [UserAddressController::class,'index']); // Done
        Route::post('/addresses', [UserAddressController::class,'store']); // Done
        Route::post('/addresses/{address_id}', [UserAddressController::class,'update']); // Done
        Route::delete('/addresses/{id}', [UserAddressController::class,'destroy']); // Done

        // Profile Routes
        Route::get('/profile', [AuthController::class, 'userProfile']);
        Route::post('/update_profile', [AuthController::class, 'updateUserProfile']);
        Route::post('/delete_account', [AuthController::class, 'deleteUserAccount']);
        Route::post('/logout', [AuthController::class, 'userLogout']);

        // Order Routes
        Route::post('/create_order', [OrderController::class, 'createOrder']);
        Route::get('/orders', [OrderController::class, 'userOrders']);
        Route::get('/order/{id}', [OrderController::class, 'orderDetails']);
        Route::post('/order/{id}/update_status', [OrderController::class, 'updateStatus']);

        // Wallet Routes
        Route::get('/wallet', [WalletController::class, 'userWallet']);
        Route::get('/wallet/transactions', [WalletController::class, 'userWalletTransactions']);
    });
});

// DRIVER API ROUTES
Route::group(['prefix' => 'v1/driver'], function () {

    //---------------- Auth --------------------//
    Route::post('/login', [AuthController::class, 'driverLogin']);

    //Route unAuth
    // Settings
    Route::get('/settings', [SettingController::class, 'index']);

    // Auth Route
    Route::group(['middleware' => ['auth:sanctum']], function () {
                
        Route::post('/update-fcm-token', [HomeController::class, 'updateFcmToken']);

        Route::get('/test_notification/{orderId}', [OrderController::class, 'test_notification']);
         Route::post('/updateStatusOnOff', [AuthController::class, 'updateStatusOnOff']);
        // Profile Routes
        Route::get('/profile', [AuthController::class, 'driverProfile']);
        Route::post('/update_profile', [AuthController::class, 'updateDriverProfile']);
        Route::post('/delete_account', [AuthController::class, 'deleteDriverAccount']);
        Route::post('/logout', [AuthController::class, 'driverLogout']);

        // Order Routes
        Route::get('/ordersAcceptedAndOnTheWay', [OrderController::class, 'ordersAcceptedAndOnTheWay']);
        Route::get('/orders', [OrderController::class, 'driverOrders']);
        Route::get('/order/{id}', [OrderController::class, 'orderDetails']);
        Route::post('/order/accept', [OrderController::class, 'acceptOrder']);
        Route::post('/order/{id}/update_status', [OrderController::class, 'updateStatus']);

        // Wallet Routes
        Route::get('/wallet', [WalletController::class, 'driverWallet']);
        Route::get('/wallet/transactions', [WalletController::class, 'driverWalletTransactions']);
    });
});