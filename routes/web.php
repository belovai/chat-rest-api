<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');
