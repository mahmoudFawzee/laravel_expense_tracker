<?php

use App\Http\Controllers\Api\V1\ExpenseController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('login', LoginController::class);
    Route::apiResource('register', RegisterController::class);
    Route::apiResource('expense', ExpenseController::class);
});
