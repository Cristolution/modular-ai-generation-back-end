<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TemplateController;
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

    // Templates (public browse, auth write)
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::get('/templates/{template_id}', [TemplateController::class, 'show']);
    Route::get('/templates/{template_id}/files', [TemplateController::class, 'files']);
    Route::get('/templates/{template_id}/files/{file_id}', [TemplateController::class, 'showFile']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/templates', [TemplateController::class, 'store']);
        Route::put('/templates/{template_id}', [TemplateController::class, 'update']);
        Route::delete('/templates/{template_id}', [TemplateController::class, 'destroy']);
        Route::post('/templates/{template_id}/fork', [TemplateController::class, 'fork']);
        Route::post('/templates/{template_id}/files', [TemplateController::class, 'storeFile']);
        Route::put('/templates/{template_id}/files/{file_id}', [TemplateController::class, 'updateFile']);
        Route::delete('/templates/{template_id}/files/{file_id}', [TemplateController::class, 'destroyFile']);
        Route::post('/templates/{template_id}/upvote', [TemplateController::class, 'upvote']);
        Route::post('/templates/{template_id}/bookmark', [TemplateController::class, 'bookmark']);
    });

    // Projects (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index']);
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::get('/projects/{project_id}', [ProjectController::class, 'show']);
        Route::put('/projects/{project_id}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project_id}', [ProjectController::class, 'destroy']);
        Route::get('/projects/{project_id}/files', [ProjectController::class, 'files']);
        Route::post('/projects/{project_id}/files', [ProjectController::class, 'storeFile']);
        Route::get('/projects/{project_id}/files/{file_id}', [ProjectController::class, 'showFile']);
        Route::put('/projects/{project_id}/files/{file_id}', [ProjectController::class, 'updateFile']);
        Route::delete('/projects/{project_id}/files/{file_id}', [ProjectController::class, 'destroyFile']);
        Route::patch('/projects/{project_id}/files/reorder', [ProjectController::class, 'reorderFiles']);
    });

    // Users (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user_id}', [UserController::class, 'show']);
        Route::put('/users/{user_id}', [UserController::class, 'update']);
        Route::delete('/users/{user_id}', [UserController::class, 'destroy']);
    });
});
