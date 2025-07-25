<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function productCreate(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'packaging' => 'required|string|max:255',
            'retail_capital' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'retail_unit' => 'required|string|max:255',
            'whole_capital' => 'required|numeric|min:0',
            'whole_price' => 'required|numeric|min:0',
            'wholesale_unit' => 'required|string|max:255',
        ]);

        Product::create($request->all());

        return redirect()->route('productRead')->with('success', 'Product added successfully.');
    }
}
