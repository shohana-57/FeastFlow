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
        $validated = $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|in:kg,g,liter,pcs,dozen',
            'min_stock' => 'required|numeric|min:0',
        ]);

        $ingredientName = trim($validated['ingredient_name']);

        if (DB::table('inventory')->where('ingredient_name', $ingredientName)->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['ingredient_name' => 'This ingredient already exists in inventory. Use edit to update it.']);
        }

        DB::statement(
            "INSERT INTO inventory (id, ingredient_name, quantity, unit, min_stock)
             VALUES (inventory_seq.NEXTVAL, ?, ?, ?, ?)",
            [
                $ingredientName,
                $validated['quantity'],
                $validated['unit'],
                $validated['min_stock']
            ]
        );
        return redirect('/inventory')->with('success', 'Item added successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ingredient_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|in:kg,g,liter,pcs,dozen',
            'min_stock' => 'required|numeric|min:0',
        ]);

        $ingredientName = trim($validated['ingredient_name']);

        if (DB::table('inventory')
            ->where('ingredient_name', $ingredientName)
            ->where('id', '<>', $id)
            ->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['ingredient_name' => 'Another item already uses this ingredient name.']);
        }

        DB::statement(
            "UPDATE inventory 
             SET ingredient_name = ?, quantity = ?, unit = ?, 
                 min_stock = ?, updated_at = SYSDATE
             WHERE id = ?",
            [
                $ingredientName,
                $validated['quantity'],
                $validated['unit'],
                $validated['min_stock'],
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
