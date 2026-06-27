<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/test-db', function () {
    $result = DB::select('SELECT * FROM users');
    return response()->json($result);
});

Route::get('/', function () {
    return view('welcome');
});
