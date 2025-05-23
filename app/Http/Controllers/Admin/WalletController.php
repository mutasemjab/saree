<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Driver;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Wallet::where('id','!=',1)->with(['user', 'driver', 'admin'])
            ->latest()
            ->paginate(10);
        
        return view('admin.wallets.index', compact('wallets'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wallets = Wallet::where('id','!=',1)->with(['user', 'driver', 'admin'])->get();
        $users = User::active()->get();
        $drivers = Driver::active()->get();
        $admins = Admin::get();
        
        return view('admin.wallets.create', compact('wallets', 'users', 'drivers', 'admins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'deposit' => 'required_without:withdrawal|numeric|min:0',
            'withdrawal' => 'required_without:deposit|numeric|min:0',
            'note' => 'nullable|string|max:1000',
            'wallet_id' => 'required|exists:wallets,id',
            'admin_id' => 'nullable|exists:admins,id',
            'user_id' => 'nullable|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        // Ensure deposit and withdrawal are not both filled or both empty
        if (($validated['deposit'] ?? 0) > 0 && ($validated['withdrawal'] ?? 0) > 0) {
            return back()->withErrors([
                'transaction' => __('messages.cannot_have_both_deposit_withdrawal')
            ])->withInput();
        }

        if (($validated['deposit'] ?? 0) == 0 && ($validated['withdrawal'] ?? 0) == 0) {
            return back()->withErrors([
                'transaction' => __('messages.must_have_deposit_or_withdrawal')
            ])->withInput();
        }

        DB::transaction(function () use ($validated) {
            $wallet = Wallet::findOrFail($validated['wallet_id']);
            
            // Create transaction
            $transaction = WalletTransaction::create($validated);
            
            // Update wallet balance
            $amount = ($validated['deposit'] ?? 0) - ($validated['withdrawal'] ?? 0);
            $wallet->increment('total', $amount);
        });

        return redirect()->route('wallets.index')
            ->with('success', __('messages.transaction_created_successfully'));
    }




    public function show(Wallet $wallet)
    {
        // Load wallet with its transactions and related user/driver/admin data
        $wallet->load([
            'transactions' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'transactions.user',
            'transactions.driver', 
            'transactions.admin',
            'user',
            'driver',
            'admin'
        ]);

        // Calculate statistics
        $totalDeposits = $wallet->transactions->sum('deposit');
        $totalWithdrawals = $wallet->transactions->sum('withdrawal');
        $transactionCount = $wallet->transactions->count();

        return view('admin.wallets.show', compact(
            'wallet', 
            'totalDeposits', 
            'totalWithdrawals', 
            'transactionCount'
        ));
    }
 


    public function edit(WalletTransaction $transaction)
    {
        // Load the transaction with its wallet and related data
        $transaction->load(['wallet', 'user', 'driver', 'admin']);
        
        // Get all wallets for the select dropdown
        $wallets = Wallet::with(['user', 'driver', 'admin'])->get();
        
        return view('admin.wallets.edit', compact('transaction', 'wallets'));
    }

    public function update(Request $request, WalletTransaction $transaction)
    {
        $validated = $request->validate([
            'deposit' => 'required_without:withdrawal|numeric|min:0',
            'withdrawal' => 'required_without:deposit|numeric|min:0',
            'note' => 'nullable|string|max:1000',
            'wallet_id' => 'required|exists:wallets,id',
            'admin_id' => 'nullable|exists:admins,id',
            'user_id' => 'nullable|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        // Ensure deposit and withdrawal are not both filled or both empty
        if (($validated['deposit'] ?? 0) > 0 && ($validated['withdrawal'] ?? 0) > 0) {
            return back()->withErrors([
                'transaction' => __('messages.cannot_have_both_deposit_withdrawal')
            ])->withInput();
        }

        if (($validated['deposit'] ?? 0) == 0 && ($validated['withdrawal'] ?? 0) == 0) {
            return back()->withErrors([
                'transaction' => __('messages.must_have_deposit_or_withdrawal')
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $transaction) {
            $oldWallet = $transaction->wallet;
            $newWallet = Wallet::findOrFail($validated['wallet_id']);
            
            // Calculate old transaction amount (reverse it)
            $oldAmount = ($transaction->deposit ?? 0) - ($transaction->withdrawal ?? 0);
            // Calculate new transaction amount
            $newAmount = ($validated['deposit'] ?? 0) - ($validated['withdrawal'] ?? 0);
            
            // If wallet changed, adjust both wallets
            if ($oldWallet->id !== $newWallet->id) {
                // Reverse the old transaction from old wallet
                $oldWallet->decrement('total', $oldAmount);
                // Apply new transaction to new wallet
                $newWallet->increment('total', $newAmount);
            } else {
                // Same wallet, adjust by the difference
                $difference = $newAmount - $oldAmount;
                $oldWallet->increment('total', $difference);
            }
            
            // Update the transaction
            $transaction->update($validated);
        });

        return redirect()->route('wallets.show', $transaction->wallet)
            ->with('success', __('messages.transaction_updated_successfully'));
    }

    /**
     * Get wallet statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_wallets' => Wallet::count(),
            'user_wallets' => Wallet::whereNotNull('user_id')->count(),
            'driver_wallets' => Wallet::whereNotNull('driver_id')->count(),
            'admin_wallets' => Wallet::whereNotNull('admin_id')->count(),
            'total_balance' => Wallet::sum('total'),
            'average_balance' => Wallet::avg('total'),
        ];

        return view('wallets.statistics', compact('stats'));
    }

    /**
     * Get wallets by owner type.
     */
    public function byOwnerType($type)
    {
        $query = Wallet::query();

        switch ($type) {
            case 'users':
                $query->whereNotNull('user_id')->with('user');
                break;
            case 'drivers':
                $query->whereNotNull('driver_id')->with('driver');
                break;
            case 'admins':
                $query->whereNotNull('admin_id')->with('admin');
                break;
            default:
                abort(404);
        }

        $wallets = $query->latest()->paginate(10);
        
        return view('wallets.by-owner-type', compact('wallets', 'type'));
    }

    public function destroy(WalletTransaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $wallet = $transaction->wallet;
            
            // Calculate the amount to revert
            $amount = $transaction->deposit - $transaction->withdrawal;
            
            // Revert the transaction from wallet balance
            $wallet->decrement('total', $amount);
            
            // Delete the transaction
            $transaction->delete();
        });

        return redirect()->route('wallets.show', $transaction->wallet_id)
            ->with('success', __('messages.transaction_deleted_successfully'));
    }

}
