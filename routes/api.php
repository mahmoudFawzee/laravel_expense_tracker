<?php

use App\Http\Controllers\Api\V1\ExpenseController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

const AUTH_SANCTUM = 'auth:sanctum';
const API_VERSION = 'v1';

Route::prefix(API_VERSION)->middleware(AUTH_SANCTUM)->group(function () {
    Route::delete('/logout', [LoginController::class, 'destroy']);
});

Route::prefix(API_VERSION)->middleware(AUTH_SANCTUM)->group(function(){
    Route::post('/expense',[ExpenseController::class,'store']);
    Route::get('/expenses',[ExpenseController::class,'index']);
});


Route::prefix(API_VERSION)->middleware(AUTH_SANCTUM)->group(function(){
    Route::post('/category',[CategoryController::class,'store']);
    Route::get('/categories',[CategoryController::class,'index']);
});

Route::prefix(API_VERSION)->group(function(){
    Route::post('/register',[RegisterController::class,'store']);
});

Route::prefix(API_VERSION)->group(function(){
    //?why i don't put it just with logout
    //*because logout need sanctum middleware but login not.
    Route::post('/login',[LoginController::class,'store']);
});


