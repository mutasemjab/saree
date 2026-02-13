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
use App\Exports\UsersExport;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-table', ['only' => ['index', 'show', 'export']]);
        $this->middleware('permission:user-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update', 'toggleActivation']]);
    }

    /**
     * Export users to Excel
     */
    public function export(Request $request)
    {
        return Excel::download(
            new UsersExport($request),
            'users_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by activation status
        if ($request->filled('status')) {
            $query->where('activate', $request->status);
        }

        // Filter by city
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Get all cities for the dropdown
        $cities = \App\Models\City::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::get();
        return view('admin.users.create', compact('cities'));
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
            'user_id' => $user->id,
            'total' => 0,
        ]);

        return redirect()->route('users.index')
            ->with('success', __('messages.user_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Load relationships
        $user->load(['city', 'wallet.transactions' => function ($query) {
            $query->latest()->limit(100);
        }, 'addresses']);

        // Get user's orders with statistics
        $orders = $user->orders()->latest()->paginate(100);

        $statistics = [
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('order_status', 1)->count(),
            'completed_orders' => $user->orders()->where('order_status', 4)->count(),
            'cancelled_orders' => $user->orders()->whereIn('order_status', [5, 6])->count(),
            'total_spent' => $user->orders()->where('order_status', 4)->sum('final_price'),
            'total_addresses' => $user->addresses()->count(),
        ];

        return view('admin.users.show', compact('user', 'orders', 'statistics'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $cities = City::get();
        return view('admin.users.edit', compact('user', 'cities'));
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
