<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BranchProduct;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    public function stockAdjustmentCreate(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:restock,sale,return,damage,expired,lost,adjustment,inventory',
            'type' => 'required|in:retail,wholesale', // updated field name and options
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
            'sale_id' => 'nullable|integer|exists:sales_orders,id',
            'trans_number' => 'nullable|integer'
        ]);

        $branchId = env('BRANCH_ID');
        $productId = $validated['product_id'];
        $qty = $validated['quantity'];
        $priceType = $validated['type']; // use 'type' field
        $adjustmentType = $validated['adjustment_type'];

        // Fetch or create branch product record
        $branchProduct = BranchProduct::firstOrCreate(
            ['product_id' => $productId, 'branch_id' => $branchId],
            [
                'r_price' => 0, 'w_price' => 0,
                'r_unit' => null, 'w_unit' => null,
                'r_capital' => 0, 'w_capital' => 0,
                'r_stock_alert' => 0, 'w_stock_alert' => 0,
                'rqty' => 0, 'wqty' => 0
            ]
        );

        // Determine if stock should be added or deducted
        $addStockTypes = ['restock', 'return', 'inventory', 'adjustment'];
        $deductStockTypes = ['sale', 'damage', 'expired', 'lost'];

        $sign = in_array($adjustmentType, $addStockTypes) ? 1 : (in_array($adjustmentType, $deductStockTypes) ? -1 : 0);

        // Get previous quantity before adjustment
        if ($priceType === 'retail') {
            $previousQty = $branchProduct->rqty;
            $branchProduct->rqty += $sign * $qty;
            $newQty = $branchProduct->rqty;
        } elseif ($priceType === 'wholesale') {
            $previousQty = $branchProduct->wqty;
            $branchProduct->wqty += $sign * $qty;
            $newQty = $branchProduct->wqty;
        }

        $branchProduct->save();

        // Log adjustment
        InventoryLog::create([
            'branch_id' => $branchId,
            'product_id' => $productId,
            'quantity' => $sign * $qty,
            'price_type' => $priceType,
            'previous_quantity' => $previousQty,
            'new_quantity' => $newQty,
            'adjustment_type' => $adjustmentType,
            'reason' => $validated['reason'] ?? null,
            'sale_id' => $validated['sale_id'] ?? null,
            'trans_number' => $validated['trans_number'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Stock adjusted successfully!');
    }
}
