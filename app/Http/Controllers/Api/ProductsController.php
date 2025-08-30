<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\SalesOrder;
use Carbon\Carbon;

class ProductsController extends Controller
{
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
                ->leftJoin('units as retail_units', 'products.r_unit', '=', 'retail_units.id')
                ->leftJoin('units as wholesale_units', 'products.w_unit', '=', 'wholesale_units.id')
                ->select(
                    'products.*',
                    'retail_units.name as retail_unit_name',
                    'wholesale_units.name as wholesale_unit_name',
                )
                ->get();
        }

        // Map over products to add base64 image string
        $productsWithImages = $products->map(function ($product) {
            $imagePath = public_path('uploads/products/' . $product->image);
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                // Get mime type to form proper data URI
                $mimeType = mime_content_type($imagePath);
                $product->image_base64 = "data:$mimeType;base64,$imageData";
            } else {
                $product->image_base64 = null; // or a placeholder image string
            }
            return $product;
        });

        return response()->json($productsWithImages);
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
