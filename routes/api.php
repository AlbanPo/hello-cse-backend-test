<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('profiles', ProfileController::class)
        ->except(['index']);

    Route::post('/profiles/{profile}/comments', [CommentController::class, 'store']);
});

Route::get('/profiles', [ProfileController::class, 'indexActive']);
