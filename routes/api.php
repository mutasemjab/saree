<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

// USER API ROUTES
Route::group(['prefix' => 'v1/user'], function () {

    //---------------- Auth --------------------//
    Route::post('/login', [AuthController::class, 'userLogin']);

    //Route unAuth
    // Settings
    Route::get('/settings', [SettingController::class, 'index']);

    // Auth Route
    Route::group(['middleware' => ['auth:sanctum']], function () {
        
        // Profile Routes
        Route::get('/profile', [AuthController::class, 'userProfile']);
        Route::post('/update_profile', [AuthController::class, 'updateUserProfile']);
        Route::post('/delete_account', [AuthController::class, 'deleteUserAccount']);
        Route::post('/logout', [AuthController::class, 'userLogout']);

        // Order Routes
        Route::get('/orders', [OrderController::class, 'userOrders']);
        Route::get('/order/{id}', [OrderController::class, 'orderDetails']);
        Route::post('/order', [OrderController::class, 'store']);
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
        
        // Profile Routes
        Route::get('/profile', [AuthController::class, 'driverProfile']);
        Route::post('/update_profile', [AuthController::class, 'updateDriverProfile']);
        Route::post('/delete_account', [AuthController::class, 'deleteDriverAccount']);
        Route::post('/logout', [AuthController::class, 'driverLogout']);

        // Order Routes
        Route::get('/orders', [OrderController::class, 'driverOrders']);
        Route::get('/order/{id}', [OrderController::class, 'orderDetails']);
        Route::post('/order/{id}/accept', [OrderController::class, 'acceptOrder']);
        Route::post('/order/{id}/update_status', [OrderController::class, 'updateStatus']);

        // Wallet Routes
        Route::get('/wallet', [WalletController::class, 'driverWallet']);
        Route::get('/wallet/transactions', [WalletController::class, 'driverWalletTransactions']);
    });
});