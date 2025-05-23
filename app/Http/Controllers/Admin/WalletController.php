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
        $wallets = Wallet::with(['user', 'driver', 'admin'])
            ->latest()
            ->paginate(10);
        
        return view('admin.wallets.index', compact('wallets'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wallets = Wallet::with(['user', 'driver', 'admin'])->get();
        $users = User::active()->get();
        $drivers = Driver::active()->get();
        $admins = Admin::get();
        
        return view('admin.wallet-transactions.create', compact('wallets', 'users', 'drivers', 'admins'));
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

        return redirect()->route('wallet-transactions.index')
            ->with('success', __('messages.transaction_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(WalletTransaction $walletTransaction)
    {
        $walletTransaction->load(['wallet', 'user', 'driver', 'admin']);
        
        return view('wallet-transactions.show', compact('walletTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WalletTransaction $walletTransaction)
    {
        $wallets = Wallet::with(['user', 'driver', 'admin'])->get();
        $users = User::active()->get();
        $drivers = Driver::active()->get();
        $admins = Admin::get();
        
        return view('wallet-transactions.edit', compact('walletTransaction', 'wallets', 'users', 'drivers', 'admins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WalletTransaction $walletTransaction)
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

        DB::transaction(function () use ($validated, $walletTransaction) {
            $oldWallet = $walletTransaction->wallet;
            $newWallet = Wallet::findOrFail($validated['wallet_id']);
            
            // Reverse old transaction
            $oldAmount = $walletTransaction->deposit - $walletTransaction->withdrawal;
            $oldWallet->decrement('total', $oldAmount);
            
            // Update transaction
            $walletTransaction->update($validated);
            
            // Apply new transaction
            $newAmount = ($validated['deposit'] ?? 0) - ($validated['withdrawal'] ?? 0);
            $newWallet->increment('total', $newAmount);
        });

        return redirect()->route('wallet-transactions.index')
            ->with('success', __('messages.transaction_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletTransaction $walletTransaction)
    {
        DB::transaction(function () use ($walletTransaction) {
            $wallet = $walletTransaction->wallet;
            
            // Reverse transaction
            $amount = $walletTransaction->deposit - $walletTransaction->withdrawal;
            $wallet->decrement('total', $amount);
            
            // Delete transaction
            $walletTransaction->delete();
        });

        return redirect()->route('wallet-transactions.index')
            ->with('success', __('messages.transaction_deleted_successfully'));
    }

    /**
     * Get transactions for specific wallet.
     */
    public function byWallet(Wallet $wallet)
    {
        $transactions = $wallet->transactions()
            ->with(['user', 'driver', 'admin'])
            ->latest()
            ->paginate(15);
        
        return view('wallet-transactions.by-wallet', compact('transactions', 'wallet'));
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
}
