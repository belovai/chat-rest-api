<?php

use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register');

Route::post('/login', LoginController::class)
    ->middleware('throttle:5,1');
Route::get('/logout', LogoutController::class)
    ->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth:sanctum');

Route::prefix('/friendships')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [FriendshipController::class, 'index'])
            ->name('friendship.index');
        Route::post('{user}', [FriendshipController::class, 'store'])
            ->name('friendship.store');
        Route::patch('{friendship}/accept', [FriendshipController::class, 'accept'])
            ->name('friendship.accept');
        Route::delete('{friendship}', [FriendshipController::class, 'destroy'])
            ->name('friendship.destroy');
        Route::patch('{friendship}/block', [FriendshipController::class, 'block'])
            ->name('friendship.block');
    });

Route::get('/messages/{friendship}', [MessageController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('message.index');
Route::post('/messages/{friendship}', [MessageController::class, 'store'])
    ->middleware('auth:sanctum')
    ->name('message.store');
