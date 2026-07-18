<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = DB::select(
            "SELECT COUNT(*) as total FROM orders 
             WHERE TRUNC(created_at) = TRUNC(SYSDATE)"
        );
        $total_orders = $orders[0]->total;

        $rev = DB::select(
            "SELECT NVL(SUM(total), 0) as revenue FROM payments 
             WHERE TRUNC(paid_at) = TRUNC(SYSDATE)"
        );
        $revenue = $rev[0]->revenue;

        $tables = DB::select(
            "SELECT COUNT(*) as free FROM restaurant_tables 
             WHERE status = 'free'"
        );
        $free_tables = $tables[0]->free;

        $stock = DB::select(
            "SELECT COUNT(*) as low FROM inventory 
             WHERE quantity < min_stock"
        );
        $low_stock = $stock[0]->low;

        $popular = DB::select('SELECT * FROM popular_items');

        return view('dashboard', compact(
            'total_orders', 'revenue', 'free_tables',
            'low_stock', 'popular'
        ));
    }
}