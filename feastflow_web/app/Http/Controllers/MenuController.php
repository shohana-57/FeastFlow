<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menu = DB::select('SELECT * FROM available_menu');
        return view('menu', ['menu' => $menu]);
    }
}
