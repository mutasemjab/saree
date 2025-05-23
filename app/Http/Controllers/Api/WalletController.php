<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get User Wallet
     */
    public function userWallet(Request $request)
    {
        try {
            $user = $request->user();
            
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                // Create wallet if not exists
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'total' => 0
                ]);
            }

            $walletData = [
                'id' => $wallet->id,
                'total' => $wallet->total,
                'formatted_total' => number_format($wallet->total, 2),
                'currency' => 'USD', // You can make this dynamic from settings
                'created_at' => $wallet->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $wallet->updated_at->format('Y-m-d H:i:s'),
            ];

            return $this->successResponse('Wallet retrieved successfully', [
                'wallet' => $walletData
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve wallet');
        }
    }

    /**
     * Get Driver Wallet
     */
    public function driverWallet(Request $request)
    {
        try {
            $driver = $request->user();
            
            $wallet = Wallet::where('driver_id', $driver->id)->first();

            if (!$wallet) {
                // Create wallet if not exists
                $wallet = Wallet::create([
                    'driver_id' => $driver->id,
                    'total' => 0
                ]);
            }

            $walletData = [
                'id' => $wallet->id,
                'total' => $wallet->total,
                'formatted_total' => number_format($wallet->total, 2),
                'currency' => 'USD', // You can make this dynamic from settings
                'created_at' => $wallet->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $wallet->updated_at->format('Y-m-d H:i:s'),
            ];

            return $this->successResponse('Wallet retrieved successfully', [
                'wallet' => $walletData
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve wallet');
        }
    }

    /**
     * Get User Wallet Transactions
     */
    public function userWalletTransactions(Request $request)
    {
        try {
            $user = $request->user();
            
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return $this->notFoundResponse('Wallet not found');
            }

            $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                ->with(['user', 'driver', 'admin'])
                ->latest()
                ->paginate(20);

            $transactionsData = $transactions->getCollection()->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'deposit' => $transaction->deposit,
                    'withdrawal' => $transaction->withdrawal,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'formatted_amount' => $transaction->formatted_amount,
                    'note' => $transaction->note,
                    'performed_by' => [
                        'type' => $transaction->performer_type,
                        'name' => $transaction->performer_name,
                    ],
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->successResponse('Wallet transactions retrieved successfully', [
                'wallet' => [
                    'id' => $wallet->id,
                    'total' => $wallet->total,
                    'formatted_total' => number_format($wallet->total, 2),
                ],
                'transactions' => $transactionsData,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve wallet transactions');
        }
    }

    /**
     * Get Driver Wallet Transactions
     */
    public function driverWalletTransactions(Request $request)
    {
        try {
            $driver = $request->user();
            
            $wallet = Wallet::where('driver_id', $driver->id)->first();

            if (!$wallet) {
                return $this->notFoundResponse('Wallet not found');
            }

            $transactions = WalletTransaction::where('wallet_id', $wallet->id)
                ->with(['user', 'driver', 'admin'])
                ->latest()
                ->paginate(20);

            $transactionsData = $transactions->getCollection()->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'deposit' => $transaction->deposit,
                    'withdrawal' => $transaction->withdrawal,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'formatted_amount' => $transaction->formatted_amount,
                    'note' => $transaction->note,
                    'performed_by' => [
                        'type' => $transaction->performer_type,
                        'name' => $transaction->performer_name,
                    ],
                    'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->successResponse('Wallet transactions retrieved successfully', [
                'wallet' => [
                    'id' => $wallet->id,
                    'total' => $wallet->total,
                    'formatted_total' => number_format($wallet->total, 2),
                ],
                'transactions' => $transactionsData,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve wallet transactions');
        }
    }

  
}
