<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SavingPlanController;
use App\Http\Controllers\Api\ChartController;

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
    Route::get('portfolios/select', [PortfolioController::class, 'getPortfolioSelect']);
    Route::apiResource('portfolios', PortfolioController::class);
    Route::get('assets/select', [AssetController::class, 'getAssetSelect']);
    Route::apiResource('assets', AssetController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::get('saving-plans/select', [SavingPlanController::class, 'getSavingPlanSelect']);
    Route::apiResource('saving-plans', SavingPlanController::class);

    Route::get('charts/total-month', [ChartController::class, 'getTotalAssetsGroupedByType']);
});
