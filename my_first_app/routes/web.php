<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showAuth'])->name('login');
Route::get('/auth', [AuthController::class, 'showAuth']);

Route::post('/api/signup', [AuthController::class, 'signup'])->name('api.signup');
Route::post('/api/login', [AuthController::class, 'login'])->name('api.login');
Route::get('/landing', [AuthController::class, 'landing'])->name('landing');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/api/trial/use', [AuthController::class, 'useTrial'])->name('api.trial.use');

