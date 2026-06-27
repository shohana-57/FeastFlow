<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\MenuController;

Route::get('/menu', [MenuController::class, 'index']);
