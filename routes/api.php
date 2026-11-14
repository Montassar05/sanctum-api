<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')
    ->get('/user', fn (Request $r) => $r->user());

Route::middleware('auth:sanctum')
    ->post('/logout', [AuthController::class, 'logout']);
