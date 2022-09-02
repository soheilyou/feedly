<?php

use App\Http\Controllers\App\V10\AuthController;
use App\Http\Controllers\App\V10\FeedController;
use App\Http\Controllers\Service\FeedController as ServiceFeedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('v1.0')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('feed')->group(function () {
            Route::post('/add', [FeedController::class, 'addFeed']);
        });
    });
});

Route::middleware('internalServiceAuth')->prefix('services')->group(function () {
    Route::post('/crawler/add-new-items', [ServiceFeedController::class, 'addNewItems']);
});
