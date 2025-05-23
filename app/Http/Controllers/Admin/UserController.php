<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::get();
        return view('admin.users.create',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|unique:users,phone',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'fcm_token' => 'nullable|string',
            'activate' => 'required|in:1,2',
            'city_id' => 'required|exists:cities,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->has('photo')) {
            $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
            $validated['photo'] = $the_file_path;
         }

        $user =  User::create($validated);
         Wallet::create([
            'user_id'=>$user->id,
            'total'=>0,
         ]);
         
        return redirect()->route('users.index')
            ->with('success', __('messages.user_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
          $cities = City::get();
        return view('admin.users.edit', compact('user','cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'phone' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'fcm_token' => 'nullable|string',
            'activate' => 'required|in:1,2',
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

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', __('messages.user_updated_successfully'));
    }


    /**
     * Toggle user activation status.
     */
    public function toggleActivation(User $user)
    {
        $user->update([
            'activate' => $user->activate == 1 ? 2 : 1
        ]);

        $status = $user->activate == 1 ? __('messages.activated') : __('messages.deactivated');
        
        return redirect()->back()
            ->with('success', __('messages.user_status_updated', ['status' => $status]));
    }
}
