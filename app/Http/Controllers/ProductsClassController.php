<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Unit;

class ProductsClassController extends Controller
{
    public function classificationRead()
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('admin.products.cassification', compact('categories', 'units'));
    }

    public function categoriesSave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
        ]);

        if ($request->id) {
            // 🔹 Update existing
            $category = Category::findOrFail($request->id);

            // ensure uniqueness except current id
            $request->validate([
                'name' => 'unique:categories,name,' . $request->id,
            ]);

            $category->update([
                'name' => $request->name,
                'icon' => $request->icon,
            ]);

            return redirect()->back()->with('success', 'Category updated successfully!');
        } else {
            // 🔹 Create new
            $request->validate([
                'name' => 'unique:categories,name',
            ]);

            Category::create([
                'name' => $request->name,
                'icon' => $request->icon,
            ]);

            return redirect()->back()->with('success', 'Category created successfully!');
        }
    }

    public function unitSave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->id) {
            // 🔹 Update
            $unit = Unit::findOrFail($request->id);

            $request->validate([
                'name' => 'unique:units,name,' . $request->id,
            ]);

            $unit->update([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Unit updated successfully!');
        } else {
            // 🔹 Create
            $request->validate([
                'name' => 'unique:units,name',
            ]);

            Unit::create([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Unit created successfully!');
        }
    }

}
