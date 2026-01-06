<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::latest()->paginate(10);
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::get();
        return view('admin.settings.create',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'required|string|max:1000',
             'city_id' => 'required|exists:cities,id',
        ]);

        Setting::create($validated);

        return redirect()->route('settings.index')
            ->with('success', __('messages.setting_created_successfully'));
    }

 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        $cities = City::get();
        return view('admin.settings.edit', compact('setting','cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', Rule::unique('settings')->ignore($setting->id)],
            'value' => 'required|string|max:1000',
             'city_id' => 'required|exists:cities,id',
        ]);

        $setting->update($validated);

        return redirect()->route('settings.index')
            ->with('success', __('messages.setting_updated_successfully'));
    }

   
}
