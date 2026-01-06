<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\City;
use App\Models\Wallet;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $query = Driver::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('id', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('activate', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('activate', 2);
            }
            // If 'all' is selected, don't add any filter
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort parameters
        $allowedSorts = ['id', 'name', 'phone', 'activate', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        $drivers = $query->paginate(10)->appends($request->query());
        
        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::get();
        return view('admin.drivers.create',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|unique:drivers,phone',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fcm_token' => 'nullable|string',
            'activate' => 'required|in:1,2',
            'identity_number' => 'required|unique:drivers,identity_number',
            'car_type' => 'required|in:1,2',
            'plate_number' => 'nullable',
            'city_id' => 'required|exists:cities,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

       if ($request->has('photo')) {
            $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
            $validated['photo'] = $the_file_path;
         }

        $driver= Driver::create($validated);

          Wallet::create([
            'driver_id'=>$driver->id,
            'total'=>0,
         ]);

        return redirect()->route('drivers.index')
            ->with('success', __('messages.driver_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return view('admin.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        $cities = City::get();
        return view('admin.drivers.edit', compact('driver','cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'phone' => ['required', 'string', Rule::unique('drivers')->ignore($driver->id)],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fcm_token' => 'nullable|string',
            'activate' => 'required|in:1,2',
            'identity_number' => ['required', 'string', Rule::unique('drivers')->ignore($driver->id)],
            'car_type' => 'required|in:1,2',
            'plate_number' => 'nullable',
            'city_id' => 'required|exists:cities,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

     if ($request->has('photo')) {
            $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
            $validated['photo'] = $the_file_path;
         }

        $driver->update($validated);

        return redirect()->route('drivers.index')
            ->with('success', __('messages.driver_updated_successfully'));
    }

 

    /**
     * Toggle driver activation status.
     */
    public function toggleActivation(Driver $driver)
    {
        $driver->update([
            'activate' => $driver->activate == 1 ? 2 : 1
        ]);

        $status = $driver->activate == 1 ? __('messages.activated') : __('messages.deactivated');
        
        return redirect()->back()
            ->with('success', __('messages.driver_status_updated', ['status' => $status]));
    }
}
