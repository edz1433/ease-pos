<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\BranchProduct;
use App\Models\Inventory;
use App\Models\InventoryItems;  
use App\Models\ProductPreset;
use Illuminate\Support\Facades\Storage;

class ProductsControllerApi extends Controller
{
    public function products($category = null)
    {
        if ($category) {
            $products = Product::where('category', $category)
                ->leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')
                ->leftJoin('units as retail_units', 'branch_products.r_unit', '=', 'retail_units.id')
                ->leftJoin('units as wholesale_units', 'branch_products.w_unit', '=', 'wholesale_units.id')
                ->select(
                    'products.*',
                    'branch_products.*',
                    'retail_units.name as retail_unit_name',
                    'wholesale_units.name as wholesale_unit_name',
                )
                ->get();
        } else {
            $products = Product::query()
                ->leftJoin('sales_orders', 'sales_orders.product_id', '=', 'products.id')
                ->leftJoin('branch_products', 'sales_orders.product_id', '=', 'branch_products.product_id')
                ->select(
                    'products.id',
                    'products.barcode',
                    'products.product_name',
                    'products.product_type',
                    'products.category',
                    'products.packaging',
                    'products.vatable',
                    'products.image',
                    
                    'branch_products.r_capital',
                    'branch_products.r_price',
                    'branch_products.r_unit',
                    'branch_products.w_capital',
                    'branch_products.w_price',
                    'branch_products.w_unit',
                    'branch_products.rqty',
                    'branch_products.wqty',
                    \DB::raw('COALESCE(SUM(sales_orders.quantity), 0) as total_sold')
                )
                ->groupBy(
                    'products.id',
                    'products.barcode',
                    'products.product_name',
                    'products.product_type',
                    'products.category',
                    'products.packaging',
                    'products.vatable',
                    'products.image',
                    
                    'branch_products.r_capital',
                    'branch_products.r_price',
                    'branch_products.r_unit',
                    'branch_products.w_capital',
                    'branch_products.w_price',
                    'branch_products.w_unit',
                    'branch_products.rqty',
                    'branch_products.wqty',
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
                ->leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')
                ->leftJoin('units as retail_units', 'branch_products.r_unit', '=', 'retail_units.id')
                ->leftJoin('units as wholesale_units', 'branch_products.w_unit', '=', 'wholesale_units.id')
                ->select(
                    'products.id',
                    'products.barcode',       // retail barcode
                    'products.w_barcode',     // wholesale barcode
                    'products.product_name',
                    'products.model',
                    'products.packaging',
                    'products.vatable',
                    'products.image',
                    'retail_units.name as retail_unit_name',
                    'wholesale_units.name as wholesale_unit_name',
                    'branch_products.r_capital',
                    'branch_products.r_price',
                    'branch_products.w_capital',
                    'branch_products.w_price',
                    'branch_products.rqty',
                    'branch_products.wqty'
                )
                ->where('branch_products.branch_id', env('BRANCH_ID'))
                ->get();

            $finalProducts = collect();

            foreach ($products as $product) {
                // Retail row
                $finalProducts->push([
                    'id' => $product->id,
                    'barcode' => $product->barcode,   // retail barcode
                    'product_name' => $product->product_name,
                    'model' => $product->model,
                    'packaging' => $product->packaging,
                    'capital' => $product->r_capital,
                    'price' => $product->r_price,
                    'qty' => $product->rqty,
                    'vatable' => $product->vatable,
                    'image' => $product->image,
                    'unit_name' => $product->retail_unit_name,
                    'type' => 'retail'
                ]);

                // Wholesale row (only if w_barcode exists)
                if (!empty($product->w_barcode)) {
                    $finalProducts->push([
                        'id' => $product->id,
                        'barcode' => $product->w_barcode,  // wholesale barcode
                        'product_name' => $product->product_name,
                        'model' => $product->model,
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
        $product = Product::leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')->where('barcode', $barcode)->first();
        if ($product) {
            $type = 'retail';
        }

        // If not found by retail barcode, try wholesale barcode
        if (!$product) {
            $product = Product::leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')->where('w_barcode', $barcode)->first();
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
}
