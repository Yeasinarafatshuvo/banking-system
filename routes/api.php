<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;





// Public routes
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);


// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/deposit', [TransactionController::class, 'showDeposits']);
    Route::post('/deposit', [TransactionController::class, 'deposit']);
    Route::get('/withdrawal', [TransactionController::class, 'showWithdrawals']);
    Route::post('/withdrawal', [TransactionController::class, 'withdraw']);

    Route::get('/logout', [UserController::class, 'logout']);

});