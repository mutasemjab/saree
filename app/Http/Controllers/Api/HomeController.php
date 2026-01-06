<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\City;
use App\Models\Driver;
use App\Models\Notification;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WholeSale;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = auth()->user(); // works for both user and driver

        $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json([
            'status' => true,
            'message' => 'FCM token updated successfully',
        ]);
    }
}

