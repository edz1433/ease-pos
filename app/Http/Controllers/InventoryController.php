<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\BranchProduct;
use App\Models\Inventory;
use App\Models\InventoryItems;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function inventoryStart(Request $request)
    {
        $now = Carbon::now('Asia/Manila');
        $end = $now->copy()->addDays(1);

        // Start inventory session
        $inventory = Inventory::create([
            'branch_id' => env('BRANCH_ID'),
            'start_date' => $now,
            'end_date'   => $end,
            'status'     => 1
        ]);

        // Fetch all products
        $products = Product::select(
            'products.*',
            'branch_products.*',
        )
        ->leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')
        ->where('branch_products.branch_id', env('BRANCH_ID'))
        ->get();

        $insertData = [];

        foreach ($products as $product) {
            // Add retail row
            $insertData[] = [
                'inventory_id' => $inventory->id,
                'product_id'   => $product->id,
                'r_qty'        => $product->rqty ?? 0,
                'w_qty'        => 0,
                'r_capital'    => $product->r_capital ?? 0,
                'w_capital'    => 0,
                'r_subtotal'   => ($product->rqty ?? 0) * ($product->r_capital ?? 0),
                'w_subtotal'   => 0,
                'price_type'   => 'retail',
                'created_at'   => $now,
                'updated_at'   => $now,
            ];

            // Add wholesale row only if packaging > 1
            if ($product->packaging > 1) {
                $insertData[] = [
                    'inventory_id' => $inventory->id,
                    'product_id'   => $product->id,
                    'r_qty'        => 0,
                    'w_qty'        => $product->wqty ?? 0,
                    'r_capital'    => 0,
                    'w_capital'    => $product->w_capital ?? 0,
                    'r_subtotal'   => 0,
                    'w_subtotal'   => ($product->wqty ?? 0) * ($product->w_capital ?? 0),
                    'price_type'   => 'wholesale',
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }

        // Insert all at once using batch insert for speed
        if (!empty($insertData)) {
            InventoryItems::insert($insertData);
        }

        return response()->json([
            'message' => 'Inventory started successfully!',
            'inventory_id' => $inventory->id,
            'start_date' => $inventory->start_date,
            'end_date' => $inventory->end_date
        ]);
    }

    public function inventoryForm($id)
    {
        $inventory = Inventory::findOrFail($id);

        $inventoryItems = InventoryItems::join('products', 'inventory_items.product_id', '=', 'products.id')
            ->where('inventory_items.inventory_id', $inventory->id)
            ->select(
                'inventory_items.*',
                'products.barcode',
                'products.w_barcode',
                'products.product_name',
                'products.model',
                'products.product_type',
            )
            ->get();

        return view('admin.inventory.form', compact('inventory', 'inventoryItems'));
    }

    public function inventoriesItemSave(Request $request, $inventory_id)
    {
        $items = $request->input('items', []);

        if(empty($items)){
            return response()->json(['message' => 'No items to update'], 400);
        }

        foreach($items as $item){
            try {
                $inventoryItem = InventoryItems::find($item['id']); // <- updated
                if($inventoryItem){
                    if($inventoryItem->price_type == 'retail'){
                        $inventoryItem->r_qty = $item['qty'];
                        $inventoryItem->r_capital = $item['capital'];
                        $inventoryItem->r_subtotal = $item['qty'] * $item['capital'];
                    } else {
                        $inventoryItem->w_qty = $item['qty'];
                        $inventoryItem->w_capital = $item['capital'];
                        $inventoryItem->w_subtotal = $item['qty'] * $item['capital'];
                    }
                    $inventoryItem->status = 1;
                    $inventoryItem->save();
                }
            } catch(\Exception $e){
                \Log::error('Error saving inventory item: '.$e->getMessage());
                return response()->json(['message' => 'Error saving item: '.$e->getMessage()], 500);
            }
        }

        // Recalculate total for the inventory
        $inventoryTotal = InventoryItems::where('inventory_id', $inventory_id) // <- updated
            ->sum(\DB::raw('r_subtotal + w_subtotal'));

        return response()->json([
            'message' => 'Inventory items updated successfully',
            'inventory_total' => number_format($inventoryTotal, 2)
        ]);
    }

    public function cancelInventory($inventory_id)
    {
        try {
            // Delete all inventory items of this inventory
            InventoryItems::where('inventory_id', $inventory_id)->delete();

            // Delete the inventory itself
            Inventory::where('id', $inventory_id)->delete();

            return response()->json(['message' => 'Inventory cancelled successfully']);
        } catch(\Exception $e){
            return response()->json(['message' => 'Error cancelling inventory: '.$e->getMessage()], 500);
        }
    }

    public function finalizeInventory()
    {
        // Group inventory by product_id and aggregate retail/wholesale quantities
        $inventoryItems = InventoryItems::join('inventories', 'inventory_items.inventory_id', '=', 'inventories.id')
            ->where('inventories.status', 1)
            ->select(
                'inventory_items.product_id',
                DB::raw('MAX(inventory_items.updated_at) as last_update'),
                DB::raw('SUM(CASE WHEN inventory_items.price_type = "retail" THEN inventory_items.r_qty ELSE 0 END) as r_qty'),
                DB::raw('SUM(CASE WHEN inventory_items.price_type = "wholesale" THEN inventory_items.w_qty ELSE 0 END) as w_qty')
            )
            ->groupBy('inventory_items.product_id')
            ->get(); // ✅ get() instead of first()

        $updatedProducts = [];

        foreach ($inventoryItems as $inventory) {
            // Get product with packaging info
            $product = Product::find($inventory->product_id);
            if (!$product) {
                continue;
            }

            // Get branch product record (stock lives here)
            $branchProduct = BranchProduct::where('product_id', $inventory->product_id)
                ->where('branch_id', env('BRANCH_ID'))
                ->first();

            if (!$branchProduct) {
                continue;
            }

            // Get all sales orders for this product after the last inventory update
            $sales = SalesOrder::where('product_id', $inventory->product_id)
                ->where('created_at', '>=', $inventory->last_update)
                ->get();

            // Start with inventory snapshot quantities
            $finalRQty = $inventory->r_qty;
            $finalWQty = $inventory->w_qty;

            // Deduct sales accordingly
            foreach ($sales as $sale) {
                if ($sale->price_type === 'retail') {
                    $finalRQty -= $sale->quantity;
                } elseif ($sale->price_type === 'wholesale') {
                    if ($product->packaging > 1) {
                        $finalWQty -= $sale->quantity;
                    } else {
                        $finalRQty -= $sale->quantity;
                    }
                }
            }

            // Update the branch product table with final quantities
            $changed = false;
            $oldR = $branchProduct->rqty;
            $oldW = $branchProduct->wqty;

            if ($branchProduct->rqty != $finalRQty) {
                $branchProduct->rqty = $finalRQty;
                $changed = true;
            }

            if ($product->packaging > 1) {
                if ($branchProduct->wqty != $finalWQty) {
                    $branchProduct->wqty = $finalWQty;
                    $changed = true;
                }
            } else {
                if ($branchProduct->wqty != 0) {
                    $branchProduct->wqty = 0;
                    $changed = true;
                }
            }

            if ($changed) {
                $branchProduct->save();

                $updatedProducts[] = [
                    'product_id' => $product->id,
                    'old_rqty'   => $oldR,
                    'new_rqty'   => $finalRQty,
                    'old_wqty'   => $oldW,
                    'new_wqty'   => $branchProduct->wqty,
                ];
            }
        }

        // Mark all active inventories as finalized (branch-specific if needed)
        Inventory::where('status', 1)
            ->where('branch_id', env('BRANCH_ID'))
            ->update(['status' => 2]);

        return response()->json([
            'message' => 'Inventory saved successfully',
            'updated_products' => $updatedProducts
        ]);
    }

}
