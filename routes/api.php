<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Models\ExternalUserController;
use App\Http\Controllers\Models\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User Register Route
Route::post('user/register', [AuthController::class, 'register']);

// User Login Route
Route::post('user/login', [AuthController::class, 'login']);

// External User Register 
Route::post('user/external', [AuthController::class, 'external']);

// Protected
Route::middleware('jwt.verify')->group(function () {
    Route::get('user/info', [UserController::class, 'index']);
    Route::get('user/block', [UserController::class, 'blockUser']);
});
