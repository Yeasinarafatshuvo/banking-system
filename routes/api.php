<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;





// Public routes
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);