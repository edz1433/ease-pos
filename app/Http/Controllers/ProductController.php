<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPreset;
use Illuminate\Support\Facades\Storage;

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
            'w_unit' => 'nullable|string|max:50',
            'r_capital' => 'nullable|numeric|min:0',
            'r_price' => 'required|numeric|min:0',
            'r_unit' => 'nullable|string|max:50',
            'image' => 'nullable|image',
        ]);

        // Handle packaging logic
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
                    $validated['r_price'] = round($validated['r_capital'] * 1.10, 2);
                }
            }
        }

        // Ensure the upload directory exists
        $uploadPath = public_path('uploads/products');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if ($image->isValid()) {
                $imageName = uniqid('product_', true) . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $imageName);
                $validated['image'] = $imageName;
            } else {
                return redirect()->back()->withErrors(['image' => 'Uploaded image is invalid.']);
            }
        } else {
            $validated['image'] = 'default-product.png';
        }

        Product::create($validated);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $id,
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|in:1,2',
            'category' => 'required|string|max:255',
            'packaging' => 'required|numeric|min:1',
            'w_capital' => 'nullable|numeric|min:0',
            'w_price' => 'nullable|numeric|min:0',
            'w_unit' => 'nullable|string|max:50',
            'r_capital' => 'nullable|numeric|min:0',
            'r_price' => 'required|numeric|min:0',
            'r_unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp,gif|max:2048',
        ]);

        // Packaging logic
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
                    $validated['r_price'] = round($validated['r_capital'] * 1.10, 2);
                }
            }
        }

        // Handle new image upload (if any)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if it's not default
            if ($product->image && $product->image !== 'storage/uploads/products/default-product.png') {
                $oldPath = str_replace('storage/', '', $product->image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store new image
            $path = $request->file('image')->store('uploads/products', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $product->update($validated);

        return redirect()->back()->with('success', 'Product updated successfully!');
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
