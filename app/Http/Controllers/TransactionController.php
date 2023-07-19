<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $balance = $user->balance;

        return response()->json([
            'transactions' => $transactions,
            'balance' => $balance,
        ]);
    }

    public function showDeposits()
    {
        $user = Auth::user();

        $deposits = Transaction::where('user_id', $user->id)
            ->where('transaction_type', 'deposit')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['deposits' => $deposits]);
    }

    public function deposit(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
        ]);
        
        $user = User::find($request->user_id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->balance += $request->amount;
        $user->save();

        $transaction = new Transaction([
            'user_id' => $user->id,
            'transaction_type' => 'deposit',
            'amount' => $request->amount,
            'fee' => 0,
        ]);

        $transaction->save();

        return response()->json(['message' => 'Deposit successful'], 201);
    }
}
