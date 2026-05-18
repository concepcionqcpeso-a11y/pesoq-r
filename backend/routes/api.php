<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Support\Facades\Route;

// Public routes - no auth needed
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/roles',     [QuestionController::class, 'roles']);
Route::get('/questions/{role_key}', [QuestionController::class, 'byRole']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
});