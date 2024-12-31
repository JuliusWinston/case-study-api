<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth;

Route::middleware('auth:sanctum')->apiResource('articles', ArticleController::class);

Route::post('register', [UserController::class, 'store']);
Route::post('login', [Auth::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::get('user', [Auth::class, 'loggedin_user']);
    Route::post('logout', [Auth::class, 'logout']);
});
