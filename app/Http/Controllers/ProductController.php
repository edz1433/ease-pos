<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\BranchProduct;
use App\Models\Inventory;
use App\Models\InventoryItems;  
use App\Models\ProductPreset;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function productCreate(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'w_barcode' => 'nullable|string|max:255|unique:products,w_barcode',
            'product_name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',  
            'more_details' => 'nullable|string|max:1000',  
            'product_type' => 'required|in:1,2',
            'category' => 'required|string|max:255',
            'packaging' => 'required|numeric|min:1',
            'warranty' => 'nullable',
            'rep_duration' => 'nullable',
            'image' => 'nullable|image',
        ]);

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = 'default-product.png';
        }

        // Create product
        $product = Product::create($validated);

        // Get branch ID from .env
        $branchId = env('BRANCH_ID');

        // Create branch product record
        BranchProduct::create([
            'branch_id' => $branchId,
            'product_id' => $product->id,
            'w_capital' => $request->w_capital ?? 0,
            'w_price' => $request->w_price ?? 0,
            'w_unit' => $request->w_unit,
            'w_stock_alert' => $request->w_stock_alert ?? 0,
            'r_capital' => $request->r_capital ?? 0,
            'r_price' => $request->r_price ?? 0,
            'r_unit' => $request->r_unit,
            'r_stock_alert' => $request->r_stock_alert ?? 0,
        ]);

        // Add to inventory if active
        $inventory = Inventory::where('status', 1)->first();
        if ($inventory) {
            $w_capital = $request->w_capital ?? 0;
            $r_capital = $request->r_capital ?? 0;

            if ($validated['packaging'] > 1) {
                InventoryItems::create([
                    'inventory_id' => $inventory->id,
                    'product_id' => $product->id,
                    'r_qty' => 0,
                    'w_qty' => 0,
                    'r_capital' => $r_capital,
                    'w_capital' => $w_capital,
                    'r_subtotal' => 0,
                    'w_subtotal' => 0,
                    'price_type' => 'wholesale',
                    'status' => 1,
                ]);
            }

            InventoryItems::create([
                'inventory_id' => $inventory->id,
                'product_id' => $product->id,
                'r_qty' => 0,
                'w_qty' => 0,
                'r_capital' => $r_capital,
                'w_capital' => $w_capital,
                'r_subtotal' => 0,
                'w_subtotal' => 0,
                'price_type' => 'retail',
                'status' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    public function productEdit(Request $request)
    {   
        $categories = Category::all();
        $units = Unit::all();
        $products = Product::select(
            'products.*',
            'branch_products.*',
            'categories.name as category_name',
            'r_unit.name as r_unit_name',
            'w_unit.name as w_unit_name'
        )
        ->leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')
        ->leftJoin('categories', 'products.category', '=', 'categories.id')
        ->leftJoin('units as r_unit', 'products.r_unit', '=', 'r_unit.id')
        ->leftJoin('units as w_unit', 'products.w_unit', '=', 'w_unit.id')
        ->where('branch_products.branch_id', env('BRANCH_ID'))
        ->get();

        $productsedit = Product::find($request->id);
        
        return view('admin.products.index', compact('categories', 'units', 'products', 'productsedit'));
    }

    public function productUpdate(Request $request, $id)
    {
        // Find product
        $product = Product::findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $id,
            'w_barcode' => 'nullable|string|max:255|unique:products,w_barcode,' . $id,
            'product_name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',  
            'more_details' => 'nullable|string|max:1000',  
            'product_type' => 'required|in:1,2',
            'category' => 'required|integer|exists:categories,id',
            'packaging' => 'required|numeric|min:1',
            'warranty' => 'nullable',
            'rep_duration' => 'nullable',
            'w_capital' => 'nullable|numeric|min:0',
            'w_price' => 'nullable|numeric|min:0',
            'w_unit' => 'nullable|integer|exists:units,id',
            'w_stock_alert' => 'nullable|numeric|min:0',
            'r_capital' => 'nullable|numeric|min:0',
            'r_price' => 'required|numeric|min:0',
            'r_unit' => 'nullable|integer|exists:units,id',
            'r_stock_alert' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($product->image && $product->image !== 'default-product.png') {
                if (Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update product main info
        $product->update([
            'barcode' => $validated['barcode'],
            'w_barcode' => $validated['w_barcode'] ?? null,
            'product_name' => $validated['product_name'],
            'model' => $validated['model'] ?? null,
            'more_details' => $validated['more_details'] ?? null,
            'product_type' => $validated['product_type'],
            'category' => $validated['category'],
            'warranty' => $validated['warranty'],
            'packaging' => $validated['packaging'],
            'rep_duration' => $validated['rep_duration'],
            'image' => $validated['image'] ?? $product->image,
        ]);

        // Update branch_products table manually
        $branchProduct = BranchProduct::where('product_id', $id)
            ->where('branch_id', env('BRANCH_ID'))
            ->first();
      
        $branchProduct->update([
            'w_capital' => $validated['w_capital'] ?? 0,
            'w_price' => $validated['w_price'] ?? 0,
            'w_unit' => $validated['w_unit'] ?? null,
            'w_stock_alert' => $validated['w_stock_alert'] ?? 0,
            'r_capital' => $validated['r_capital'] ?? 0,
            'r_price' => $validated['r_price'],
            'r_unit' => $validated['r_unit'] ?? null,
            'r_stock_alert' => $validated['r_stock_alert'] ?? 0,
        ]);
        

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

    public function getNextBarcode($id)
    {
        // $id will be 'barcode' or 'w_barcode'
        if (!in_array($id, ['barcode', 'w_barcode'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        // Get the latest for this specific column only
        $latestBarcode = Product::whereRaw("$id REGEXP '^[0-9]{9}$'")
            ->orderByRaw("CAST($id AS UNSIGNED) DESC")
            ->value($id);

        // Start from 000000001 if empty
        $nextBarcode = $latestBarcode
            ? str_pad(((int) $latestBarcode) + 1, 9, '0', STR_PAD_LEFT)
            : '000000001';

        // Ensure uniqueness in THIS column only
        while (Product::where($id, $nextBarcode)->exists()) {
            $nextBarcode = str_pad(((int) $nextBarcode) + 1, 9, '0', STR_PAD_LEFT);
        }

        return response()->json(['next_barcode' => $nextBarcode]);
    }

}
