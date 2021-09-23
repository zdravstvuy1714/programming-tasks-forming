<?php

use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\SessionController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/registration', [RegistrationController::class, 'store']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

// Tasks
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks/{id}/complete', [TaskController::class, 'complete']);
Route::post('/tasks/{id}/skip', [TaskController::class, 'skip']);
