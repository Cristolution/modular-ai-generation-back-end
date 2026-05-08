<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/auth/me', [AuthController::class, 'user'])->middleware('auth:sanctum');

    // Types (public)
    Route::get('/types', [TypeController::class, 'index']);

    // Users (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user_id}', [UserController::class, 'show']);
        Route::put('/users/{user_id}', [UserController::class, 'update']);
        Route::delete('/users/{user_id}', [UserController::class, 'destroy']);
    });
});
