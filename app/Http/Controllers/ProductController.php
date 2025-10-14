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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function productReadAjax(Request $request)
    {
        $products = Product::select(
                'products.*',
                'branch_products.*',
                'categories.name as category_name',
                'r_unit.name as r_unit_name',
                'w_unit.name as w_unit_name',
                DB::raw("(SELECT COALESCE(SUM(s.quantity),0) 
                        FROM sales_orders s 
                        WHERE s.product_id = products.id 
                            AND s.price_type = 'retail') as total_sold_r"),
                DB::raw("(SELECT COALESCE(SUM(s.quantity),0) 
                        FROM sales_orders s 
                        WHERE s.product_id = products.id 
                            AND s.price_type = 'wholesale') as total_sold_w")
            )
            ->leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')
            ->leftJoin('categories', 'products.category', '=', 'categories.id')
            ->leftJoin('units as r_unit', 'branch_products.r_unit', '=', 'r_unit.id')
            ->leftJoin('units as w_unit', 'branch_products.w_unit', '=', 'w_unit.id')
            ->where('branch_products.branch_id', env('BRANCH_ID'))
            ->get();

        return response()->json(['data' => $products]);
    }

    public function storeOrUpdate(Request $request)
    {
        $isUpdate = strtoupper($request->input('_method')) === 'PUT' && $request->filled('id');
        $productId = $request->input('id');

        // Adjust unique rule for updates
        $rules = [
            'barcode' => [
                'required',
                'string',
                'max:255',
                $isUpdate ? 'unique:products,barcode,' . $productId : 'unique:products,barcode'
            ],
            'w_barcode' => [
                'nullable',
                'string',
                'max:255',
                $isUpdate ? 'unique:products,w_barcode,' . $productId : 'unique:products,w_barcode'
            ],
            'product_name' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'more_details' => 'nullable|string|max:1000',
            'product_type' => 'required|in:1,2',
            'category' => 'required|integer|exists:categories,id', // Ensure category exists
            'packaging' => 'required|integer|min:1', // Changed to integer for consistency
            'warranty' => 'nullable|string|max:255',
            'rep_duration' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'w_capital' => 'required|numeric|min:0',
            'w_price' => 'required|numeric|min:0',
            'w_unit' => 'nullable|integer|exists:units,id', // Ensure unit exists
            'w_stock_alert' => 'nullable|numeric|min:0',
            'r_capital' => 'required|numeric|min:0',
            'r_price' => 'required|numeric|min:0',
            'r_unit' => 'required|integer|exists:units,id', // Ensure unit exists
            'r_stock_alert' => 'required|numeric|min:0',
        ];

        // Custom error messages
        $messages = [
            'category.exists' => 'The selected category is invalid.',
            'w_unit.exists' => 'The selected wholesale unit is invalid.',
            'r_unit.exists' => 'The selected retail unit is invalid.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be larger than 2MB.',
            'packaging.min' => 'Packaging must be at least 1.',
        ];

        $validated = $request->validate($rules, $messages);

        try {
            // Handle image upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Store image in /storage/app/public/products
                $path = $request->file('image')->store('uploads/products', 'public');

                // Extract only the filename (no folder path)
                $validated['image'] = basename($path);

                if ($isUpdate) {
                    $product = Product::findOrFail($productId);
                    if ($product->image && $product->image !== 'default-product.png') {
                        Storage::disk('public')->delete('uploads/products/' . $product->image);
                    }
                }
            } elseif ($request->input('remove_image', 0) && $isUpdate) {
                $product = Product::findOrFail($productId);
                if ($product->image && $product->image !== 'default-product.png') {
                    Storage::disk('public')->delete('uploads/products/' . $product->image);
                }
                $validated['image'] = 'default-product.png';
            } elseif (!$isUpdate && !$request->hasFile('image')) {
                $validated['image'] = 'default-product.png';
            } elseif ($isUpdate) {
                unset($validated['image']); // Exclude image if no change
            }

            $branchId = env('BRANCH_ID', config('app.default_branch_id', 1));
            if (!$branchId) {
                Log::warning('Branch ID not configured, using default: ' . $branchId);
            }

            // Fields allowed for Product model
            $productFields = collect($validated)->only([
                'barcode', 'w_barcode', 'product_name', 'model', 'more_details',
                'product_type', 'category', 'packaging', 'warranty', 'rep_duration', 'image'
            ])->filter()->toArray(); // Filter out null values

            if ($isUpdate) {
                $product = Product::findOrFail($productId);
                $product->update($productFields);

                BranchProduct::updateOrCreate(
                    ['product_id' => $productId, 'branch_id' => $branchId],
                    collect($validated)->only([
                        'w_capital', 'w_price', 'w_unit', 'w_stock_alert',
                        'r_capital', 'r_price', 'r_unit', 'r_stock_alert'
                    ])->filter()->toArray()
                );

                if ($inventory = Inventory::where('status', 1)->first()) {
                    InventoryItems::where('inventory_id', $inventory->id)
                        ->where('product_id', $productId)
                        ->update([
                            'r_capital' => $validated['r_capital'],
                            'w_capital' => $validated['w_capital'],
                        ]);
                }

                $message = 'Product updated successfully!';
            } else {
                $product = Product::create($productFields);

                BranchProduct::create([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'w_capital' => $validated['w_capital'],
                    'w_price' => $validated['w_price'],
                    'w_unit' => $validated['w_unit'] ?? null, // Handle nullable w_unit
                    'w_stock_alert' => $validated['w_stock_alert'] ?? 0,
                    'r_capital' => $validated['r_capital'],
                    'r_price' => $validated['r_price'],
                    'r_unit' => $validated['r_unit'],
                    'r_stock_alert' => $validated['r_stock_alert'],
                ]);

                if ($inventory = Inventory::where('status', 1)->first()) {
                    InventoryItems::create([
                        'inventory_id' => $inventory->id,
                        'product_id' => $product->id,
                        'r_qty' => 0,
                        'w_qty' => 0,
                        'r_capital' => $validated['r_capital'],
                        'w_capital' => $validated['w_capital'],
                        'r_subtotal' => 0,
                        'w_subtotal' => 0,
                        'price_type' => 'retail',
                        'status' => 1,
                    ]);

                    if ($validated['packaging'] > 1) {
                        InventoryItems::create([
                            'inventory_id' => $inventory->id,
                            'product_id' => $product->id,
                            'r_qty' => 0,
                            'w_qty' => 0,
                            'r_capital' => $validated['r_capital'],
                            'w_capital' => $validated['w_capital'],
                            'r_subtotal' => 0,
                            'w_subtotal' => 0,
                            'price_type' => 'wholesale',
                            'status' => 1,
                        ]);
                    }
                } else {
                    Log::warning('No active inventory found, skipping inventory item creation.');
                }

                $message = 'Product created successfully!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error('Product store/update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

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
