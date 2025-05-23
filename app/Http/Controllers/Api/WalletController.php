<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletController extends Controller
{


    public function index()
    {
        $user_id = auth()->user()->id;

        $data = Wallet::where('user_id',$user_id)->get();

        return response()->json(['data' => $data], 200);
    }

    public function walletTransaction($wallet_id)
    {
        $data = WalletTransaction::with('admin','user')->where('wallet_id', $wallet_id)->get();
        return response()->json(['data' => $data], 200);
    }



}
