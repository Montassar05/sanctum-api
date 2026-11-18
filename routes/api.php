<?php

use App\Http\Controllers\AuthController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

// 5 tentatives de login par minute, puis 429.
Route::post('/login', [
    AuthController::class, 'login',
])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')
    ->get('/user', fn (Request $r) => $r->user());

// Uniquement les champs choisis : id, name, email.
Route::middleware('auth:sanctum')
    ->get('/me', fn (Request $r)
        => new UserResource($r->user()));

Route::middleware('auth:sanctum')
    ->post('/logout', [AuthController::class, 'logout']);

// Calculé une fois, puis servi depuis le cache pendant 10 minutes.
Route::get('/stats', fn () => [
    'users' => Cache::remember(
        'stats.users', 600,
        fn () => User::count()
    ),
]);
