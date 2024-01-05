<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;

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

Route::post('login', [LoginController::class, 'login']);
Route::post('register', RegisterController::class);

Route::group(['middleware' => 'api.auth'], function () {
    Route::get('logout', [LoginController::class, 'logout']);

    Route::apiResource('user', UserController::class);
    Route::apiResource('portfolios', PortfolioController::class);
    Route::apiResource('assets', AssetController::class);
    Route::apiResource('transactions', TransactionController::class);
});
