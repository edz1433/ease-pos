<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    public function stockAdjustmentCreate(Request $request)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|numeric|min:0.01',
            'adjustment_type'  => 'required|in:sale,return,restock,adjustment,damage,expired,lost,inventory',
            'price_type'       => 'nullable|in:retail,wholesale,special',
            'reason'           => 'nullable|in:Customer Return,Damaged,Expired,Lost,Stock Count,Manual Adjustment,Others',
            'sale_id'          => 'nullable|exists:sales,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $previousQty = $product->rqty ?? 0;

        // 🔹 Compute new stock based on adjustment type
        switch ($request->adjustment_type) {
            case 'restock':
            case 'return':
            case 'inventory': // for stock take adjustments, you might handle differently
                $newQty = $previousQty + $request->quantity;
                break;

            case 'sale':
            case 'damage':
            case 'expired':
            case 'lost':
            case 'adjustment':
                $newQty = max(0, $previousQty - $request->quantity); // never negative
                break;

            default:
                $newQty = $previousQty;
        }

        // 🔹 Update product stock
        $product->rqty = $newQty;
        $product->save();

        // 🔹 Save to stock_adjustments (audit trail)
        InventoryLog::create([
            'product_id'        => $product->id,
            'quantity'          => $request->quantity,
            'adjustment_type'   => $request->adjustment_type,
            'price_type'        => $request->price_type ?? 'retail',
            'previous_quantity' => $previousQty,
            'new_quantity'      => $newQty,
            'reason'            => $request->reason,
            'price'             => $product->r_price ?? 0, // or wholesale if chosen
            'sale_id'           => $request->sale_id,
            'user_id'           => Auth::id(),
        ]);

        return back()->with('success', 'Stock adjustment recorded successfully.');
    }
}
