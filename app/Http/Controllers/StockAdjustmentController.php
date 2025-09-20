<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockAdjustment;

class StockAdjustmentController extends Controller
{
    public function stockAdjustmentCreate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'adjustment_type' => 'required|in:increase,decrease',
            'reason' => 'required',
        ]);

        $product = Product::findOrFail($request->product_id);
        $previousQty = $product->rqty;

        if ($request->adjustment_type === 'increase') {
            $newQty = $previousQty + $request->quantity;
        } else {
            $newQty = max(0, $previousQty - $request->quantity); // prevent negative stock
        }

        // update product stock
        $product->rqty = $newQty;
        $product->save();

        // log audit trail
        StockAdjustment::create([
            'product_id'        => $product->id,
            'quantity'          => $request->quantity,
            'adjustment_type'   => $request->adjustment_type,
            'previous_quantity' => $previousQty,
            'new_quantity'      => $newQty,
            'reason'            => $request->reason,
            'user_id'           => auth()->id(),
            'price'             => $product->price ?? null,
            'sale_id'           => $request->sale_id ?? null,
        ]);

        return back()->with('success', 'Stock adjustment saved.');
    }
}
