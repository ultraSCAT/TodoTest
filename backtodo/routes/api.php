<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;

Route::middleware('auth:sanctum','cors')->prefix('/v1')->group(function() {
   Route::resource('/tasks', TasksController::class);
});

Route::post('/login', [AuthController::class, 'login']);

