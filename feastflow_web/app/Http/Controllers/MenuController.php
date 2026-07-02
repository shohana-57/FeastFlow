<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->search;
        $category = $request->category;
        $sort     = $request->sort;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;

        $query = "SELECT * FROM available_menu WHERE 1=1";
        $params = [];

        if ($search) {
            $query .= " AND LOWER(name) LIKE LOWER('%' || ? || '%')";
            $params[] = $search;
        }

        if ($category) {
            $query .= " AND category = ?";
            $params[] = $category;
        }

        if ($minPrice) {
            $query .= " AND price >= ?";
            $params[] = $minPrice;
        }

        if ($maxPrice) {
            $query .= " AND price <= ?";
            $params[] = $maxPrice;
        }

        if ($sort == 'price_asc') {
            $query .= " ORDER BY price ASC";
        } elseif ($sort == 'price_desc') {
            $query .= " ORDER BY price DESC";
        } elseif ($sort == 'name') {
            $query .= " ORDER BY name ASC";
        }

        $menu = DB::select($query, $params);

        return view('menu', ['menu' => $menu]);
    }
}