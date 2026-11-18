<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 5 tentatives de login par minute, puis 429.
Route::post('/login', [
    AuthController::class, 'login',
])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')
    ->get('/user', fn (Request $r) => $r->user());

Route::middleware('auth:sanctum')
    ->post('/logout', [AuthController::class, 'logout']);
