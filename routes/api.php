<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ReadingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')
    ->prefix('v1')
    ->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('{id?}', [UserController::class, 'index']);
            Route::post('', [UserController::class, 'store']);
            Route::patch('', [UserController::class, 'update']);
            Route::delete('{id}', [UserController::class, 'destroy']);
        });

        Route::prefix('readings')->group(function () {
            Route::get('', [ReadingController::class, 'index']);
            Route::get('{id}', [ReadingController::class, 'show']);
            Route::post('', [ReadingController::class, 'store']);
            Route::patch('{id}', [ReadingController::class, 'update']);
            Route::delete('{id}', [ReadingController::class, 'destroy']);
        });
    });

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});
