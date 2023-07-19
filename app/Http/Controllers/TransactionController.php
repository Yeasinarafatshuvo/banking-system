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
}
