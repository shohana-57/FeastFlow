<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = DB::select(
            "SELECT * FROM inventory ORDER BY ingredient_name"
        );
        return view('inventory', ['inventory' => $inventory]);
    }

   
    public function store(Request $request)
    {
        DB::statement(
            "INSERT INTO inventory (id, ingredient_name, quantity, unit, min_stock)
             VALUES (inventory_seq.NEXTVAL, ?, ?, ?, ?)",
            [
                $request->ingredient_name,
                $request->quantity,
                $request->unit,
                $request->min_stock
            ]
        );
        return redirect('/inventory')->with('success', 'Item added successfully!');
    }

    public function update(Request $request, $id)
    {
        DB::statement(
            "UPDATE inventory 
             SET ingredient_name = ?, quantity = ?, unit = ?, 
                 min_stock = ?, updated_at = SYSDATE
             WHERE id = ?",
            [
                $request->ingredient_name,
                $request->quantity,
                $request->unit,
                $request->min_stock,
                $id
            ]
        );
        return redirect('/inventory')->with('success', 'Item updated successfully!');
    }

    
    public function destroy($id)
    {
        DB::table('inventory')->where('id', $id)->delete();
        return redirect('/inventory')->with('success', 'Item deleted successfully!');
    }
}
