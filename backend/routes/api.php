<?php

use App\Http\Controllers\Api\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/roles', [QuestionController::class, 'roles']);
Route::get('/questions/{role_key}', [QuestionController::class, 'byRole']);