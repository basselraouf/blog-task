<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'user'], function(){
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/verify', [UserController::class, 'confirmVerificationCode']);
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::put('/tags/{id}', [TagController::class, 'update']);
    Route::delete('/tags/{id}', [TagController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->prefix('posts')->group(function () {
    Route::get('/deleted', [PostController::class, 'viewDeletedPosts']);
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']);
    Route::get('/{id}', [PostController::class, 'show']);
    Route::post('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'destroy']);
    Route::post('/{id}/restore', [PostController::class, 'restore']);
});

Route::middleware('auth:sanctum')->get('/stats', [StatsController::class, 'stats']);
