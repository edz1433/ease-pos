<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\BranchProduct;
use App\Models\BundleItem;
use Illuminate\Support\Facades\DB;

class ProductBundleController extends Controller
{
    // Show all bundles (products that are bundles)
    public function bundleRead($id = null)
    {
        $branchId = env('BRANCH_ID');
        
        $products = Product::join('branch_products', 'products.id', '=', 'branch_products.product_id')
            ->select('products.*', 'branch_products.*')
            ->where('branch_products.branch_id', $branchId)
            ->get(); 

        $productsbundle = BundleItem::join('products', 'bundle_items.product_id', '=', 'products.id')
            ->select('products.*', 'bundle_items.product_id', 'bundle_items.bundle_id', 'bundle_items.quantity')
            ->get(); 

        $categories = Category::all();

        $bundleitems = Product::where('is_bundle', 1)->get();
        return view('admin.products.bundles', compact('products', 'bundleitems', 'categories', 'productsbundle'));
    }

    public function bundleCreate(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'bundle_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
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
                'barcode' => $request->barcode,
                'product_name' => $request->bundle_name,
                'category' => $request->category,
                'more_details' => $request->more_details,
                'image' => 'default-product.png',
                'is_bundle' => 1
            ]);

            BranchProduct::create([
                'branch_id' => env('BRANCH_ID'),
                'product_id' => $bundle->id,
                'r_price' => $request->r_price,
                'r_capital' => $request->r_capital,
                'r_stock_alert' => $request->r_stock_alert,
                'rqty' => $request->quantity,
            ]);

            // 2. Loop through selected products and quantities
            foreach ($request->product_id as $index => $productId) {
                $qty = $request->rqty[$index];

                // Insert into bundle_items
                BundleItem::create([
                    'bundle_id' => $bundle->id,
                    'product_id' => $productId,
                    'quantity' => $request->quantity, 
                ]);

                // Deduct quantity from product
                $product = BranchProduct::where('product_id', $productId)->first();
                if ($product) {
                    $product->rqty = max(0, $product->rqty - ($qty * $request->quantity)); // avoid negative
                    $product->save();
                }
            }
        });

        return redirect()->back()->with('success', 'Bundle created successfully!');
    }
}
