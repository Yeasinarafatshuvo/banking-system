<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    //method show all transaction and balanace
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

    //method for show all deoposite money for user 
    public function showDeposits()
    {
        $user = Auth::user();
        
        $deposits = Transaction::where('user_id', $user->id)
                                ->where('transaction_type', 'deposit')
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        return response()->json(['deposits' => $deposits]);
    }

    //method for user add money to account for deposite 
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

        return response()->json(['message' => 'Deposit successful'], 200);
    }


    //method for show all user withdrawal transaction
    public function showWithdrawals()
    {
        $user = Auth::user();

        $withdrawals = Transaction::where('user_id', $user->id)
                                    ->where('transaction_type', 'withdraw')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        return $user;
        return response()->json(['withdrawals' => $withdrawals]);
    }


    //method for withdraw user money from account 
    public function withdraw(Request $request)
    {
       
        $this->validate($request, [
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $withdrawalAmount = $request->amount;
        $withdrawalFee = 0;

        // Apply withdrawal rate based on account type
        if ($user->account_type == 'Individual') {
            $today = Carbon::now()->format('Y-m-d');
            $friday = Carbon::parse('Friday')->format('Y-m-d');

            // Check if it's a Friday
            if ($today === $friday) {
                $withdrawalFee = 0;
            } else {
                $freeWithdrawalLimit = 1000;
                $freeWithdrawalMonthLimit = 5000;
                $totalWithdrawalsThisMonth = Transaction::where('user_id', $user->id)
                    ->where('transaction_type', 'withdraw')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->sum('amount');

                // Check if the withdrawal is within free limits
                if ($withdrawalAmount <= $freeWithdrawalLimit ||
                    $totalWithdrawalsThisMonth <= $freeWithdrawalMonthLimit) {
                    $withdrawalFee = 0;
                } else {
                    // Apply the withdrawal fee
                    $withdrawalFee = $withdrawalAmount * 0.015;
                }
            }
        } elseif ($user->account_type == 'Business') {
            $totalWithdrawals = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'withdraw')
                ->sum('amount');

            // Check if the withdrawal exceeds 50K
            if ($totalWithdrawals > 50000) {
                // Apply reduced withdrawal fee for Business accounts
                $withdrawalFee = $withdrawalAmount * 0.015;
            } else {
                $withdrawalFee = $withdrawalAmount * 0.025;
            }
        }

        $totalAmount = $withdrawalAmount + $withdrawalFee;

        if ($user->balance < $totalAmount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $user->balance -= $totalAmount;
        $user->save();
        
        $transaction = new Transaction([
            'user_id' => $user->id,
            'transaction_type' => 'withdraw',
            'amount' => $withdrawalAmount,
            'fee' => $withdrawalFee,
        ]);
        
        $transaction->save();

        return response()->json(['message' => 'Withdrawal successful'], 200);
    }




}
