<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Jobs\ExportPosts;
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

Route::post('/register', RegisterController::class);

Route::get('/posts', [PostController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Le token doit avoir la permission posts:publish.
    Route::post('/posts/{post}/publish', [
        PostController::class, 'publish',
    ])->middleware('abilities:posts:publish');

    Route::put('/me', [ProfileController::class, 'update']);

    // L'export part en queue : réponse 202 immédiate.
    Route::post('/exports', function (Request $r) {
        ExportPosts::dispatch($r->user());

        return response()->json(['status' => 'en cours'], 202);
    });
});

Route::get('/github/{username}', [GithubController::class, 'repos']);

// Calculé une fois, puis servi depuis le cache pendant 10 minutes.
Route::get('/stats', fn () => [
    'users' => Cache::remember(
        'stats.users', 600,
        fn () => User::count()
    ),
]);
