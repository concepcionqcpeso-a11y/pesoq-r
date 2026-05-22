<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/roles',     [QuestionController::class, 'roles']);
Route::get('/questions/{role_key}', [QuestionController::class, 'byRole']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
});

Route::middleware(['auth:sanctum', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('/stats',                [AdminController::class, 'stats']);
        Route::get('/users',                [AdminController::class, 'users']);
        Route::patch('/users/{id}/toggle',  [AdminController::class, 'toggleUser']);
        Route::delete('/users/{id}',        [AdminController::class, 'deleteUser']);
        Route::get('/questions',            [AdminController::class, 'questions']);
        Route::post('/questions',           [AdminController::class, 'storeQuestion']);
        Route::put('/questions/{id}',       [AdminController::class, 'updateQuestion']);
        Route::delete('/questions/{id}',    [AdminController::class, 'deleteQuestion']);
        Route::get('/logs',                 [AdminController::class, 'logs']);
    });
