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
   
});