<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * User Login
     */
    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->unauthorizedResponse('Invalid credentials');
        }

        if ($user->activate != 1) {
            return $this->forbiddenResponse('Account is deactivated');
        }

        // Update FCM token if provided
        if ($request->fcm_token) {
            $user->update(['fcm_token' => $request->fcm_token]);
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return $this->successResponse('Login successful', [
             'user' => [
            $user,
            ]
            ,'token' => $token
        ]);
    }

    /**
     * Driver Login
     */
    public function driverLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $driver = Driver::where('phone', $request->phone)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return $this->unauthorizedResponse('Invalid credentials');
        }

        if ($driver->activate != 1) {
            return $this->forbiddenResponse('Account is deactivated');
        }

        // Update FCM token if provided
        if ($request->fcm_token) {
            $driver->update(['fcm_token' => $request->fcm_token]);
        }

        $token = $driver->createToken('driver-token')->plainTextToken;

        return $this->successResponse('Login successful', [
            'driver' => [
            $driver
            ],
            'token' => $token
        ]);
    }

    /**
     * Get User Profile
     */
    public function userProfile(Request $request)
    {
        $user = $request->user();

        return $this->successResponse('Profile retrieved successfully', [
            $user
        ]);
    }

    /**
     * Get Driver Profile
     */
    public function driverProfile(Request $request)
    {
        $driver = $request->user();

        return $this->successResponse('Profile retrieved successfully', [
           $driver
        ]);
    }

    /**
     * Update User Profile
     */
    public function updateUserProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $data = $request->only(['name', 'phone', 'lat', 'lng', 'fcm_token']);

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        return $this->successResponse('Profile updated successfully', [
           $user
        ]);
    }

    /**
     * Update Driver Profile
     */
    public function updateDriverProfile(Request $request)
    {
        $driver = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|unique:drivers,phone,' . $driver->id,
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $data = $request->only(['name', 'phone', 'fcm_token']);

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($driver->photo) {
                Storage::disk('public')->delete($driver->photo);
            }
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        $driver->update($data);

        return $this->successResponse('Profile updated successfully', [
           $driver
        ]);
    }

    /**
     * Delete User Account
     */
    public function deleteUserAccount(Request $request)
    {
        $user = $request->user();
        // Dis active user
        $user->update([
            'activate'=>2,
        ]);
        // Delete all tokens
        $user->tokens()->delete();

      

        return $this->successResponse('Account deleted successfully');
    }

    /**
     * Delete Driver Account
     */
    public function deleteDriverAccount(Request $request)
    {
        $driver = $request->user();

         // Dis active user
        $driver->update([
            'activate'=>2,
        ]);

        // Delete all tokens
        $driver->tokens()->delete();

        return $this->successResponse('Account deleted successfully');
    }

    /**
     * Logout User
     */
    public function userLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Logged out successfully');
    }

    /**
     * Logout Driver
     */
    public function driverLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('Logged out successfully');
    }
}

