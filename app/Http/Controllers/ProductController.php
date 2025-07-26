<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPreset;

class ProductController extends Controller
{
    public function productCreate(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|in:1,2',
            'category' => 'required|string|max:255',
            'packaging' => 'required|numeric|min:1',
            'w_capital' => 'nullable|numeric|min:0',
            'w_price' => 'nullable|numeric|min:0',
            'w_unit' => 'nullable',
            'r_capital' => 'nullable|numeric|min:0',
            'r_price' => 'required|numeric|min:0',
            'r_unit' => 'nullable',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Adjust logic based on product type
        if ($request->product_type === '2') {
            $validated['packaging'] = 1;
            $validated['w_capital'] = 0;
            $validated['w_price'] = 0;
            $validated['r_capital'] = 0;
            $validated['w_unit'] = null;
        } else {
            $packaging = max((float) $request->packaging, 1);
            $wCapital = (float) $request->w_capital;

            if ($packaging > 1 && $wCapital > 0) {
                $validated['r_capital'] = $wCapital / $packaging;
                if (!$request->filled('r_price')) {
                    $validated['r_price'] = $validated['r_capital'] * 1.10;
                }
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $validated['image'] = 'uploads/products/' . $imageName;
        }

        Product::create($validated);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function getProductPresets(Request $request)
    {
        $barcode = $request->barcode;

        $preset = ProductPreset::where('barcode', $barcode)->first();

        if ($preset) {
            return response()->json([
                'success' => true,
                'preset' => $preset
            ]);
        }

        return response()->json([
            'success' => false,
            'preset' => null
        ]);
    }

    public function getNextBarcode(Request $request)
    {
        $latestBarcode = Product::whereRaw("barcode REGEXP '^[0-9]+$'")
            ->orderByRaw("CAST(barcode AS UNSIGNED) DESC")
            ->value('barcode');

        $nextBarcode = $latestBarcode
            ? str_pad(((int) $latestBarcode) + 1, 9, '0', STR_PAD_LEFT)
            : '000000001';

        return response()->json(['next_barcode' => $nextBarcode]);
    }
}
