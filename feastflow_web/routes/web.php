<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;

Route::get('/menu', [MenuController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

