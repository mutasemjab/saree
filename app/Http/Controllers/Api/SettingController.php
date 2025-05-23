<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ApiResponseTrait;

class SettingController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all settings
     */
    public function index()
    {
        try {
            $settings = Setting::all()->pluck('value', 'key');

            return $this->successResponse('Settings retrieved successfully', [
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve settings');
        }
    }

    /**
     * Get specific setting by key
     */
    public function getSetting($key)
    {
        try {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                return $this->notFoundResponse('Setting not found');
            }

            return $this->successResponse('Setting retrieved successfully', [
                'setting' => [
                    'key' => $setting->key,
                    'value' => $setting->value
                ]
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve setting');
        }
    }
}
