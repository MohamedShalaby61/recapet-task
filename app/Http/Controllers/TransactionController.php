<?php
// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    public function transfer(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'unautheticated user'], 400);
        }

        $wallet = $user->wallet;
        $receiver = User::find($request->receiver_id);
        if (!$receiver) {
            return response()->json(['message' => 'receiver user is not found'], 400);
        }

        if ($user->id == $receiver->id) {
            return response()->json(['message' => 'receiver id should be different of user id'], 400);
        }

        if ($wallet->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $fee = 0;
        if ($request->amount > 25) {
            $fee = 2.5 + (0.10 * $request->amount);
        }

        $totalAmount = $request->amount + $fee;

        if ($wallet->balance < $totalAmount) {
            return response()->json(['message' => 'Insufficient balance to cover the fee'], 400);
        }

        $wallet->balance -= $totalAmount;
        $wallet->save();

        $receiver->wallet->balance += $request->amount;
        $receiver->wallet->save();

        Transaction::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'amount' => $request->amount,
            'fee' => $fee,
        ]);

        return response()->json(['message' => 'Transfer successful']);
    }

    public function history()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'unautheticated user'], 400);
        }

        $transactions = Transaction::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->get();
        $mappedTransactionsArray = [];
        foreach ($transactions as $transaction) {
            $mappedTransactionsArray[] = [
                'type' => $transaction->receiver_id == $user->id ? 'receiving' : 'sending',
                'amount' => $transaction->amount,
                'transaction_fee' => $transaction->fee
            ];
        }

        return response()->json([
            'transactions' => $mappedTransactionsArray
        ]);
    }
}
