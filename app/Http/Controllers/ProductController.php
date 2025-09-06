<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\ProductPreset;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function productCreate(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'w_barcode' => 'required|string|max:255|unique:products,w_barcode',
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|in:1,2',
            'category' => 'required|string|max:255',
            'packaging' => 'required|numeric|min:1',
            'w_capital' => 'nullable|numeric|min:0',
            'w_price' => 'nullable|numeric|min:0',
            'w_unit' => 'nullable|string|max:50',
            'w_stock_alert' => 'nullable|numeric|min:0',
            'r_capital' => 'nullable|numeric|min:0',
            'r_price' => 'required|numeric|min:0',
            'r_unit' => 'nullable|string|max:50',
            'r_stock_alert' => 'nullable|numeric|min:0',
            'image' => 'nullable|image',
        ]);

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = 'default-product.png';
        }

        // Create the product
        Product::create($validated);

        return redirect()->back()->with('success', 'Product created successfully!');
    }


    public function productEdit(Request $request)
    {   
        $categories = Category::all();
        $units = Unit::all();
        $products = Product::select(
            'products.*',
            'categories.name as category_name',
            'r_unit.name as r_unit_name',
            'w_unit.name as w_unit_name'
        )
        ->leftJoin('categories', 'products.category', '=', 'categories.id')
        ->leftJoin('units as r_unit', 'products.r_unit', '=', 'r_unit.id')
        ->leftJoin('units as w_unit', 'products.w_unit', '=', 'w_unit.id')
        ->get();

        $productsedit = Product::find($request->id);
        
        return view('admin.products.index', compact('categories', 'units', 'products', 'productsedit'));
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $id,
            'w_barcode' => 'required|string|max:255|unique:products,w_barcode,' . $id,
            'product_name' => 'required|string|max:255',
            'product_type' => 'required|in:1,2',
            'category' => 'required|string|max:255',
            'packaging' => 'required|numeric|min:1',
            'w_capital' => 'nullable|numeric|min:0',
            'w_price' => 'nullable|numeric|min:0',
            'w_unit' => 'nullable|string|max:50',
            'w_stock_alert' => 'nullable|numeric|min:0',
            'r_capital' => 'nullable|numeric|min:0',
            'r_price' => 'required|numeric|min:0',
            'r_unit' => 'nullable|string|max:50',
            'r_stock_alert' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp,gif|max:2048',
        ]);

        // --- Handle new image upload ---
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if it's not default
            if ($product->image && $product->image !== 'default-product.png') {
                if (Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
            }

            // Store new image
            $validated['image'] = $request->file('image')->store('products', 'public');
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

    public function products($category = null)
    {
        if ($category) {
            $products = Product::where('category', $category)
                ->leftJoin('units as retail_units', 'products.r_unit', '=', 'retail_units.id')
                ->leftJoin('units as wholesale_units', 'products.w_unit', '=', 'wholesale_units.id')
                ->select(
                    'products.*',
                    'retail_units.name as retail_unit_name',
                    'wholesale_units.name as wholesale_unit_name',
                )
                ->get();
        } else {
            $products = Product::query()
                ->leftJoin('sales_orders', 'sales_orders.product_id', '=', 'products.id')
                ->select(
                    'products.id',
                    'products.barcode',
                    'products.product_name',
                    'products.product_type',
                    'products.category',
                    'products.packaging',
                    'products.r_capital',
                    'products.r_price',
                    'products.r_unit',
                    'products.w_capital',
                    'products.w_price',
                    'products.w_unit',
                    'products.rqty',
                    'products.wqty',
                    'products.vatable',
                    'products.image',
                    \DB::raw('COALESCE(SUM(sales_orders.quantity), 0) as total_sold')
                )
                ->groupBy(
                    'products.id',
                    'products.barcode',
                    'products.product_name',
                    'products.product_type',
                    'products.category',
                    'products.packaging',
                    'products.r_capital',
                    'products.r_price',
                    'products.r_unit',
                    'products.w_capital',
                    'products.w_price',
                    'products.w_unit',
                    'products.rqty',
                    'products.wqty',
                    'products.vatable',
                    'products.image'
                )
                ->orderByDesc('total_sold')
                ->limit(15)
                ->get();


        }

        return response()->json($products);
    }

    public function getAllProducts()
    {
        try {
            $products = Product::query()
                ->leftJoin('units as retail_units', 'products.r_unit', '=', 'retail_units.id')
                ->leftJoin('units as wholesale_units', 'products.w_unit', '=', 'wholesale_units.id')
                ->select(
                    'products.id',
                    'products.barcode',
                    'products.product_name',
                    'products.packaging',
                    'products.r_capital',
                    'products.r_price',
                    'products.w_capital',
                    'products.w_price',
                    'products.rqty',
                    'products.wqty',
                    'products.vatable',
                    'products.image',
                    'retail_units.name as retail_unit_name',
                    'wholesale_units.name as wholesale_unit_name'
                )
                ->get();

            $finalProducts = collect();

            foreach ($products as $product) {
                // Split barcodes
                $retailBarcode = null;
                $wholesaleBarcode = null;

                if (strpos($product->barcode, ',') !== false) {
                    $parts = explode(',', $product->barcode);
                    $retailBarcode = trim($parts[0]);
                    $wholesaleBarcode = trim($parts[1]);
                } else {
                    $retailBarcode = $product->barcode;
                }

                // Retail row
                $finalProducts->push([
                    'id' => $product->id,
                    'barcode' => $retailBarcode,
                    'product_name' => $product->product_name,
                    'packaging' => $product->packaging,
                    'capital' => $product->r_capital,
                    'price' => $product->r_price,
                    'qty' => $product->rqty,
                    'vatable' => $product->vatable,
                    'image' => $product->image,
                    'unit_name' => $product->retail_unit_name,
                    'type' => 'retail'
                ]);

                // Wholesale row (only if barcode exists)
                if ($wholesaleBarcode) {
                    $finalProducts->push([
                        'id' => $product->id,
                        'barcode' => $wholesaleBarcode,
                        'product_name' => $product->product_name,
                        'packaging' => $product->packaging,
                        'capital' => $product->w_capital,
                        'price' => $product->w_price,
                        'qty' => $product->wqty,
                        'vatable' => $product->vatable,
                        'image' => $product->image,
                        'unit_name' => $product->wholesale_unit_name,
                        'type' => 'wholesale'
                    ]);
                }
            }

            return response()->json($finalProducts->values());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch products',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductByBarcode($barcode)
    {
        $type = null;

        // First try to find product by retail barcode
        $product = Product::where('barcode', $barcode)->first();
        if ($product) {
            $type = 'retail';
        }

        // If not found by retail barcode, try wholesale barcode
        if (!$product) {
            $product = Product::where('w_barcode', $barcode)->first();
            if ($product) {
                $type = 'wholesale';
            }
        }

        if ($product) {
            return response()->json([
                'type'    => $type,
                'product' => $product
            ]);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|integer',
            'product_id' => 'required|integer',
            'capital'    => 'required|numeric',
            'price'      => 'required|numeric',
            'price_type' => 'required|in:retail,wholesale',
            'qty'        => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', $validated['user_id'])
                        ->where('product_id', $validated['product_id'])
                        ->where('price_type', $validated['price_type'])
                        ->first();

        if ($cartItem) {
            $cartItem->qty += $validated['qty'];
            $cartItem->save();
        } else {
            Cart::create($validated);
        }

        return response()->json(['success' => true]);
    }

}
