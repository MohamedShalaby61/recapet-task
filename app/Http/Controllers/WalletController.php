<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function getBalance()
    {
        $user = Auth::user();
        if(!$user) {
            return response()->json(['message' => 'unautheticated user'], 400);
        }

        $wallet = $user->wallet;
        return response()->json(['balance' => $wallet->balance]);
    }

    public function topUp(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0.01']);

        $user = Auth::user();
        if(!$user) {
            return response()->json(['message' => 'unautheticated user'], 400);
        }

        $wallet = $user->wallet;
        $wallet->balance += $request->amount;
        $wallet->save();

        return response()->json(['message' => 'Top-up successful']);
    }
}
