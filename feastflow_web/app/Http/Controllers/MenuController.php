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

     public function adminIndex()
    {
        $menu = DB::select("SELECT m.*, c.name as category_name 
                            FROM menu_items m 
                            JOIN categories c ON m.category_id = c.id
                            ORDER BY m.id");
        $categories = DB::select("SELECT * FROM categories ORDER BY name");
        return view('admin.menu', compact('menu', 'categories'));
    }

     public function store(Request $request)
    {
        DB::statement(
            "INSERT INTO menu_items (id, category_id, name, price, description, status, image)
             VALUES (menu_items_seq.NEXTVAL, ?, ?, ?, ?, ?, ?)",
            [
                $request->category_id,
                $request->name,
                $request->price,
                $request->description,
                $request->status ?? 'available',
                $request->image ?? null
            ]
        );
        return redirect('/admin/menu')->with('success', 'Food item added successfully!');
    }

     public function update(Request $request, $id)
    {
        DB::statement(
            "UPDATE menu_items 
             SET category_id = ?, name = ?, price = ?, 
                 description = ?, status = ?
             WHERE id = ?",
            [
                $request->category_id,
                $request->name,
                $request->price,
                $request->description,
                $request->status,
                $id
            ]
        );
        return redirect('/admin/menu')->with('success', 'Item updated successfully!');
    }

     public function destroy($id)
    {
        $hasOrderItems = DB::table('order_items')
            ->where('menu_item_id', $id)
            ->exists();

        if ($hasOrderItems) {
            return redirect('/admin/menu')
                ->with('error', 'Cannot delete this menu item because it has existing order records.');
        }

        DB::table('menu_items')->where('id', $id)->delete();
        return redirect('/admin/menu')->with('success', 'Item deleted successfully!');
    }
}