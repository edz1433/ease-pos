<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Type;

class ItemSettingsController extends Controller
{
    public function itemsettingsCreate(Request $request)
    {
        $request->validate([
            'type_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'partno' => 'nullable|string|max:255',
        ]);

        $data = [
            'type_id' => $request->type_id,
            'title' => $request->title,
            'partno' => $request->partno,
        ];

        Category::create($data);

        return redirect()->route('itemsettingsRead', $request->type_id)
            ->with('success', 'Item setting created successfully.');
    }

    public function itemsettingsEdit(Request $request)
    {
        $type = Type::all();
        $itemsedit = Category::findOrFail($request->id);
        $itemid = $itemsedit->type_id;
        $items = Category::where('type_id', $itemsedit->type_id)->get();

        return view('items.settings', compact('type', 'items', 'itemid', 'itemsedit'));
    }

    public function itemsettingsUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:category,id',
            'title' => 'required|string|max:255',
            'partno' => 'nullable|string|max:255',
        ]);

        $item = Category::findOrFail($request->id);

        $updateData = [
            'type_id' => $request->type_id,
            'title' => $request->title,
            'partno' => $request->partno,
        ];

        $item->update($updateData);

        $tname = ($request->type_id == 1) ? 'category' : (($request->type_id == 2) ? 'measure' : 'part');

        return redirect()->route('itemsettingsRead', $tname)
            ->with('success', 'Item setting updated successfully.');
    }

    public function itemsettingsDelete($id)
    {
        $item = Category::findOrFail($id);
        $type_id = $item->type_id;
        $item->delete();

        return redirect()->route('itemsettingsRead', $type_id)
            ->with('success', 'Item setting deleted successfully.');
    }


}
