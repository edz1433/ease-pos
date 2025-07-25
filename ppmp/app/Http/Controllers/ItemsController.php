<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Type;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    public function itemsCreate(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string|unique:item,item_code',
            'item_spec' => 'required|string',
            'amount' => 'nullable|numeric',
            'category_id' => 'required|exists:category,id',
            'measure_id' => 'required|exists:category,id',
            'part_id' => 'required|exists:category,id',
        ]);

        Item::create([
            'item_code' => $request->item_code,
            'item_spec' => $request->item_spec,
            'amount' => $request->amount,
            'category' => $request->category_id,
            'measure' => $request->measure_id,
            'partno' => $request->part_id,
        ]);

        return redirect()->route('itemsRead')->with('success', 'Item created successfully.');
    }

    public function itemsEdit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:item,id',
        ]);

        $itemsedit = Item::findOrFail($request->id);
        $type = Type::all();
        $categories = Category::all();

        $items = DB::table('item as i')
            ->leftJoin('category as c', 'i.category', '=', 'c.id')
            ->leftJoin('category as m', 'i.measure', '=', 'm.id')
            ->leftJoin('category as p', 'i.partno', '=', 'p.id')
            ->select(
                'i.*',
                'c.title as category_name',
                'm.title as measure_name',
                'p.title as partno_name'
            )
            ->get();

        return view('items.index', compact('items', 'itemsedit', 'type', 'categories'));
    }

    public function itemsUpdate(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string|unique:item,item_code',
            'item_code' => 'required|string|max:255',
            'item_spec' => 'required|string',
            'amount' => 'nullable|numeric',
            'category_id' => 'required|exists:category,id',
            'measure_id' => 'required|exists:category,id',
            'part_id' => 'required|exists:category,id',
        ]);

        $item = Item::findOrFail($request->id);

        $item->update([
            'item_code' => $request->item_code,
            'item_spec' => $request->item_spec,
            'amount' => $request->amount ?? 0.00,
            'category' => $request->category_id,
            'measure' => $request->measure_id,
            'partno' => $request->part_id,
        ]);

        return redirect()->route('itemsRead')->with('success', 'Item updated successfully.');
    }

    public function itemsDelete($id)
    {
        $item = Item::find($id);
        
        if (!$item) {
            return response()->json([
                'status' => 404,
                'message' => "Item not found",
            ]);
        }

        $item->delete();

        return response()->json([
            'status' => 200,
            'message' => "Item deleted successfully",
        ]);
    }

}
