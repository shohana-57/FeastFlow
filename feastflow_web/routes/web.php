<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;

Route::get('/menu', [MenuController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/inventory', [InventoryController::class, 'index']);
Route::post('/inventory', [InventoryController::class, 'store']);
Route::put('/inventory/update/{id}', [InventoryController::class, 'update']);
Route::post('/inventory/delete/{id}', [InventoryController::class, 'destroy']);
Route::get('/admin/menu', [MenuController::class, 'adminIndex']);
Route::post('/admin/menu', [MenuController::class, 'store']);
Route::put('/admin/menu/update/{id}', [MenuController::class, 'update']);
Route::post('/admin/menu/delete/{id}', [MenuController::class, 'destroy']);

Route::get('/order/create', [OrderController::class, 'create']);
Route::post('/order', [OrderController::class, 'store']);
Route::get('/order/payment', [OrderController::class, 'payment']);
Route::post('/order/payment', [OrderController::class, 'processPayment']);
Route::get('/order/feedback', [OrderController::class, 'feedback']);
Route::post('/order/feedback', [OrderController::class, 'submitFeedback']);
Route::get('/order/thankyou', [OrderController::class, 'thankyou']);
