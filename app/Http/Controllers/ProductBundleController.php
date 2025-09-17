<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BundleItem;
use Illuminate\Support\Facades\DB;

class ProductBundleController extends Controller
{
    // Show all bundles (products that are bundles)
    public function bundleRead($id = null)
    {
        // Get all products that are bundles
        $products = Product::all(); 
        
        $bundleitems = Product::where('is_bundle', 1)->get();
        return view('admin.products.bundles', compact('products', 'bundleitems'));
    }

    public function bundleCreate(Request $request)
    {
        $request->validate([
            'bundle_name' => 'required|string|max:255',
            'product_id' => 'required|array',
            'rqty' => 'required|array',
            'r_price' => 'required|numeric',
            'r_capital' => 'required|numeric',
            'r_stock_alert' => 'required|numeric',
            'more_details' => 'nullable|string',
            'quantity' => 'required|numeric'
        ]);

        DB::transaction(function() use ($request) {
            // 1. Create the bundle in products
            $bundle = Product::create([
                'product_name' => $request->bundle_name,
                'r_price' => $request->r_price,
                'r_capital' => $request->r_capital,
                'r_stock_alert' => $request->r_stock_alert,
                'more_details' => $request->more_details,
                'rqty' => $request->quantity,
                'is_bundle' => 1
            ]);

            // 2. Loop through selected products and quantities
            foreach ($request->product_id as $index => $productId) {
                $qty = $request->rqty[$index];

                // Insert into bundle_items
                BundleItem::create([
                    'bundle_id' => $bundle->id,
                    'product_id' => $productId,
                    'quantity' => $qty * $request->quantity, 
                ]);

                // Deduct quantity from product
                $product = Product::find($productId);
                if ($product) {
                    $product->rqty = max(0, $product->rqty - $qty); // avoid negative
                    $product->save();
                }
            }
        });

        return redirect()->back()->with('success', 'Bundle created successfully!');
    }
}
