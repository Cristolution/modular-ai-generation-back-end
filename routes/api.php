<?php

use App\Http\Controllers\Admin\AdminResourceController;
use App\Http\Controllers\Admin\AdminTemplateController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AiJobController;
use App\Http\Controllers\AiProviderController;
use App\Http\Controllers\Api\V1\AiChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
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
    Route::put('/me/profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');

    // AI Providers (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me/ai-providers', [AiProviderController::class, 'index']);
        Route::post('/me/ai-providers', [AiProviderController::class, 'store']);
        Route::get('/me/ai-providers/{provider_id}', [AiProviderController::class, 'show']);
        Route::put('/me/ai-providers/{provider_id}', [AiProviderController::class, 'update']);
        Route::delete('/me/ai-providers/{provider_id}', [AiProviderController::class, 'destroy']);
        Route::post('/me/ai-providers/{provider_id}/test', [AiProviderController::class, 'test']);
    });

    // AI Chat (protected) — server-held Anthropic key, SSE passthrough
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/ai/chat', [AiChatController::class, 'chat']);
    });

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
        Route::post('/templates/{template_id}/comments', [TemplateController::class, 'storeComment']);
    });

    // Template comments (public read)
    Route::get('/templates/{template_id}/comments', [TemplateController::class, 'comments']);

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
        Route::get('/projects/{project_id}/jobs', [AiJobController::class, 'index']);
        Route::post('/projects/{project_id}/generate', [AiJobController::class, 'generateFull']);
        Route::post('/projects/{project_id}/files/{file_id}/generate', [AiJobController::class, 'generateLayer']);
    });

    // Jobs poll (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/jobs/{job_id}', [AiJobController::class, 'show']);
    });

    // Export (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/projects/{project_id}/export', [ExportController::class, 'store']);
    });

    // Export jobs (public poll)
    Route::get('/export-jobs/{job_id}', [ExportController::class, 'show']);

    // Users (public read - order matters, more specific routes first)
    Route::get('/users/{user_id}', [UserController::class, 'show']);
    Route::get('/users/{user_id}/templates', [UserController::class, 'templates']);
    Route::get('/users/{user_id}/resources', [UserController::class, 'resources']);
    Route::get('/users/{user_id}/projects', [UserController::class, 'projects']);

    // Users (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user_id}', [UserController::class, 'update']);
        Route::delete('/users/{user_id}', [UserController::class, 'destroy']);
    });

    // Resources (public browse, auth write)
    Route::get('/resources', [ResourceController::class, 'index']);
    Route::get('/resources/{resource_id}', [ResourceController::class, 'show']);
    Route::get('/resources/{resource_id}/forks', [ResourceController::class, 'forks']);
    Route::get('/resources/{resource_id}/comments', [ResourceController::class, 'comments']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/resources', [ResourceController::class, 'store']);
        Route::put('/resources/{resource_id}', [ResourceController::class, 'update']);
        Route::delete('/resources/{resource_id}', [ResourceController::class, 'destroy']);
        Route::post('/resources/{resource_id}/fork', [ResourceController::class, 'fork']);
        Route::post('/resources/{resource_id}/upvote', [ResourceController::class, 'upvote']);
        Route::post('/resources/{resource_id}/bookmark', [ResourceController::class, 'bookmark']);
        Route::post('/resources/{resource_id}/comments', [ResourceController::class, 'storeComment']);
    });

    // Comments (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/comments/{comment_id}', [CommentController::class, 'update']);
        Route::delete('/comments/{comment_id}', [CommentController::class, 'destroy']);
    });

    // Admin (admin role required) — middleware order matters: auth:sanctum
    // first so $request->user() is populated, then the admin guard.
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        // List all users + keyword search, paginated.
        Route::get('/admin/users', [AdminUserController::class, 'index']);
        // Update a single user's role.
        Route::put('/admin/users/{user_id}', [AdminUserController::class, 'updateRole']);
        // List every template (incl. private/unlisted) for moderation.
        Route::get('/admin/templates', [AdminTemplateController::class, 'index']);
        // List every community resource (incl. private/unlisted).
        Route::get('/admin/resources', [AdminResourceController::class, 'index']);
    });
});
