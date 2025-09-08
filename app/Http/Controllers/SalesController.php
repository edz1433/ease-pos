<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\InventoryItems;
use App\Models\InventoryLog;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function nextTransactionNumber()
    {
        $todayCount = Sale::whereDate('created_at', now()->toDateString())->count();
        $nextNumber = str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
        $transactionNumber = now()->format('mdyHis') . '-' . $nextNumber;

        return response()->json([
            'transaction_number' => $transactionNumber
        ]);
    }

    public function checkout(Request $request)
    {
        try {
            $data = $request->all();

            // Helper to clean numbers
            $cleanNumber = function ($value) {
                return is_string($value) ? floatval(str_replace(',', '', $value)) : $value;
            };

            $data['total'] = $cleanNumber($data['total']);
            $data['discount'] = isset($data['discount']) ? $cleanNumber($data['discount']) : 0;
            $data['amt_tendered'] = $cleanNumber($data['amt_tendered']);
            $data['amount_change'] = $cleanNumber($data['amount_change']);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $key => $item) {
                    $data['items'][$key]['capital'] = $cleanNumber($item['capital']);
                    $data['items'][$key]['price'] = $cleanNumber($item['price']);
                    $data['items'][$key]['quantity'] = intval($item['quantity']);
                }
            }

            // Compute VAT
            $vatRate = 0.12;
            $netSales = $data['total'] - $data['discount'];
            $vat = $netSales * $vatRate;
            $totalWithVat = $netSales + $vat;

            $status = isset($data['status']) ? intval($data['status']) : 1;

            $paymentMethod = isset($data['payment_method']) ? $data['payment_method'] : 'Cash';

            DB::transaction(function () use ($data, $status, $vat, $totalWithVat, $paymentMethod) {
                $sale = Sale::create([
                    'transaction_number' => $data['transaction_number'],
                    'date' => Carbon::now('Asia/Manila')->toDateString(),
                    'total' => $data['total'],
                    'discount' => $data['discount'],
                    'amt_tendered' => $data['amt_tendered'],
                    'amount_change' => $data['amount_change'],
                    'vat' => $vat,
                    'total_wvat' => $totalWithVat,
                    'status' => $status,
                    'customer' => $data['customer'] ?? null,
                    'table_no' => $data['table_no'] ?? null,
                    'payment_method' => $paymentMethod,
                ]);

                foreach ($data['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if (!$product) continue;

                    $quantity = $item['quantity'];
                    $packaging = $product->packaging ?? 1;

                    if ($item['price_type'] === 'retail') {
                        // For retail items, first try to deduct from retail stock
                        $retailDeduction = min($product->rqty, $quantity);
                        $remaining = $quantity - $retailDeduction;
                        
                        if ($remaining > 0 && $product->wqty > 0) {
                            // Convert wholesale packages to retail units
                            $wholesalePackagesNeeded = ceil($remaining / $packaging);
                            $wholesalePackagesUsed = min($product->wqty, $wholesalePackagesNeeded);
                            
                            // Calculate how much retail stock this creates
                            $retailFromWholesale = $wholesalePackagesUsed * $packaging;
                            
                            // Update quantities
                            $product->rqty = $retailFromWholesale - $remaining;
                            $product->wqty -= $wholesalePackagesUsed;
                        } else {
                            $product->rqty -= $retailDeduction;
                        }
                    } else {
                        // For wholesale items, just deduct from wholesale stock
                        $product->wqty -= $quantity;
                    }

                    $product->save();

                    SalesOrder::create([
                        'sales_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'capital' => $item['capital'],
                        'price' => $item['price'],
                        'price_type' => $item['price_type'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'retail_equivalent' => $item['price_type'] === 'wholesale' 
                            ? $item['quantity'] * $packaging 
                            : $item['quantity'],
                    ]);
                }
            });

            $message = $status === 2 
                ? 'Order marked as Next successfully' 
                : 'Checkout completed successfully';

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'vat' => $vat,
                'total_wvat' => $totalWithVat,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function getSales($date = null)
    {
        try {
            date_default_timezone_set('Asia/Manila');
            
            // If no date provided, use current date in Manila timezone
            $targetDate = $date 
                ? Carbon::createFromFormat('Y-m-d', $date, 'Asia/Manila')
                : Carbon::now('Asia/Manila');

            $sales = DB::table('sales')
                ->whereDate('date', $targetDate->format('Y-m-d'))
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'date_queried' => $targetDate->format('Y-m-d'),
                'timezone' => 'Asia/Manila',
                'data' => $sales,
                'count' => $sales->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Sales fetch error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sales data',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function editSales($saleId) {
        try {
            $sale = Sale::findOrFail($saleId);
            
            $salesOrders = SalesOrder::select(
                    'sales_orders.id',
                    'sales_orders.product_id',
                    'sales_orders.capital',
                    'sales_orders.price',
                    'sales_orders.quantity',
                    'sales_orders.price_type',
                    'products.vatable',
                    'products.packaging',
                    'products.product_name',
                    'products.image', // Add this to get the image filename
                    'retail_unit.name as retail_unit_name',
                    'wholesale_unit.name as wholesale_unit_name'
                )
                ->leftJoin('products', 'sales_orders.product_id', '=', 'products.id')
                ->leftJoin('units as retail_unit', 'products.r_unit', '=', 'retail_unit.id')
                ->leftJoin('units as wholesale_unit', 'products.w_unit', '=', 'wholesale_unit.id')
                ->where('sales_orders.sales_id', $saleId)
                ->get();

            // Process image data for each sales order
            $processedOrders = $salesOrders->map(function($order) {
                return [
                    'id' => $order->id,
                    'product_id' => $order->product_id,
                    'capital' => $order->capital,
                    'price' => $order->price,
                    'quantity' => $order->quantity,
                    'price_type' => $order->price_type,
                    'vatable' => $order->vatable,
                    'packaging' => $order->packaging,
                    'product' => [
                        'product_name' => $order->product_name,
                        'retail_unit_name' => $order->retail_unit_name,
                        'wholesale_unit_name' => $order->wholesale_unit_name,
                        'image' => $order->image
                    ]
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'sale' => $sale,
                    'sales_orders' => $processedOrders
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Edit sales error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sale data',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function updateSales(Request $request, $id)
    {
        try {
            $data = $request->all();

            // Helper to clean numbers
            $cleanNumber = function ($value) {
                return is_string($value) ? floatval(str_replace(',', '', $value)) : $value;
            };

            $data['total'] = $cleanNumber($data['total']);
            $data['discount'] = isset($data['discount']) ? $cleanNumber($data['discount']) : 0;
            $data['amt_tendered'] = $cleanNumber($data['amt_tendered']);
            $data['amount_change'] = $cleanNumber($data['amount_change']);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $key => $item) {
                    $data['items'][$key]['capital'] = $cleanNumber($item['capital']);
                    $data['items'][$key]['price'] = $cleanNumber($item['price']);
                    $data['items'][$key]['quantity'] = intval($item['quantity']);
                }
            }

            // Compute VAT
            $vatRate = 0.12;
            $netSales = $data['total'] - $data['discount'];
            $vat = $netSales * $vatRate;
            $totalWithVat = $netSales + $vat;

            $status = isset($data['status']) ? intval($data['status']) : 1;

            DB::transaction(function () use ($data, $status, $vat, $totalWithVat, $id) {
                $sale = Sale::findOrFail($id);

                // ✅ Preserve the original sale date
                $originalDate = $sale->date;

                // Update sale but keep original date
                $sale->update([
                    'date' => $originalDate,   // keep old order date
                    'total' => $data['total'],
                    'discount' => $data['discount'],
                    'amt_tendered' => $data['amt_tendered'],
                    'amount_change' => $data['amount_change'],
                    'customer' => $data['customer'] ?? null,
                    'table_no' => $data['table_no'] ?? null,
                    'vatable_sales' => $data['vatable_sales'],
                    'vat_amount' => $data['vat_amount'],
                    'non_vatable_sales' => $data['non_vatable_sales'],
                    'updated_at' => now(),     // still mark modification
                    'status' => 1,             // Set status to completed (1)
                ]);

                // 1️⃣ Restore stock from old orders before deleting
                $oldOrders = SalesOrder::where('sales_id', $sale->id)->get();

                foreach ($oldOrders as $order) {
                    $product = Product::find($order->product_id);
                    if (!$product) continue;

                    if ($order->price_type === 'retail') {
                        $product->rqty += $order->quantity;
                    } else {
                        $product->wqty += $order->quantity;
                    }

                    $product->save();
                }

                // Delete old orders
                SalesOrder::where('sales_id', $sale->id)->delete();

                // 2️⃣ Insert new items and deduct stock again
                foreach ($data['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if (!$product) continue;

                    $quantity = $item['quantity'];
                    $packaging = $product->packaging ?? 1;

                    if ($item['price_type'] === 'retail') {
                        $retailDeduction = min($product->rqty, $quantity);
                        $remaining = $quantity - $retailDeduction;

                        if ($remaining > 0 && $product->wqty > 0) {
                            $wholesalePackagesNeeded = ceil($remaining / $packaging);
                            $wholesalePackagesUsed = min($product->wqty, $wholesalePackagesNeeded);

                            $retailFromWholesale = $wholesalePackagesUsed * $packaging;

                            $product->rqty = $retailFromWholesale - $remaining;
                            $product->wqty -= $wholesalePackagesUsed;
                        } else {
                            $product->rqty -= $retailDeduction;
                        }
                    } else {
                        $product->wqty -= $quantity;
                    }

                    $product->save();

                    SalesOrder::create([
                        'sales_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'capital' => $item['capital'],
                        'price' => $item['price'],
                        'price_type' => $item['price_type'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'retail_equivalent' => $item['price_type'] === 'wholesale'
                            ? $item['quantity'] * $packaging
                            : $item['quantity'],
                    ]);
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Sale updated successfully',
                'vat' => $vat,
                'total_wvat' => $totalWithVat,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

public function cancelOrReturnSale($saleId)
{
    DB::beginTransaction();

    try {
        // Find the sale with items
        $sale = Sale::with('items')->findOrFail($saleId);
        
        // Get request data
        $isPartialReturn = request('is_partial_return', false);
        $returnItems = request('return_items', []);

        // Determine action based on status
        if ($sale->status == 1) { // Paid → Return
            if ($isPartialReturn) {
                $targetStatus = 5; // Partially Returned
            } else {
                $targetStatus = 4; // Returned
            }
            $adjustmentType = 'return_restock';
        } elseif ($sale->status == 2) { // Unpaid → Cancel
            $targetStatus = 3; // Cancelled
            $adjustmentType = null; // No inventory change
        } elseif ($sale->status == 3) { // Already cancelled
            return response()->json([
                'status' => 'error',
                'message' => 'Sale has already been cancelled.'
            ], 422);
        } elseif ($sale->status == 4 || $sale->status == 5) { // Already returned
            return response()->json([
                'status' => 'error',
                'message' => 'Sale has already been returned.'
            ], 422);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot process this sale status.'
            ], 422);
        }

        // Handle partial returns
        if ($isPartialReturn && !empty($returnItems)) {
            foreach ($returnItems as $returnItem) {
                $order = SalesOrder::find($returnItem['sales_order_id']);
                
                if (!$order || $order->sales_id != $saleId) {
                    continue; // Skip invalid items
                }
                
                $product = Product::find($order->product_id);
                if (!$product) continue;

                // Validate return quantity
                $returnQty = min($returnItem['quantity'], $order->quantity);
                
                if ($returnQty <= 0) continue;

                // Previous quantity
                $previousQty = $order->price_type === 'retail' ? $product->rqty : $product->wqty;

                // Restore inventory
                if ($order->price_type === 'retail') {
                    $product->increment('rqty', $returnQty);
                } else {
                    $product->increment('wqty', $returnQty);
                }

                $newQty = $order->price_type === 'retail' ? $product->rqty : $product->wqty;

                // Log inventory change
                InventoryLog::create([
                    'product_id' => $order->product_id,
                    'quantity' => $returnQty,
                    'adjustment_type' => $adjustmentType,
                    'price_type' => $order->price_type,
                    'previous_quantity' => $previousQty,
                    'new_quantity' => $newQty,
                    'reason' => 'Partial Return - ' . $sale->transaction_number,
                    'sale_id' => $saleId,
                    'user_id' => auth()->id(),
                    'price' => $order->unit_price
                ]);

                // Create return history record
                ReturnHistory::create([
                    'sale_id' => $saleId,
                    'sales_order_id' => $order->id,
                    'product_id' => $order->product_id,
                    'quantity' => $returnQty,
                    'price' => $order->unit_price,
                    'price_type' => $order->price_type,
                    'reason' => $returnItem['reason'] ?? 'No reason provided',
                    'user_id' => auth()->id()
                ]);

                // Update the sales order quantity if not returning all
                if ($returnQty < $order->quantity) {
                    $order->decrement('quantity', $returnQty);
                } else {
                    // If returning all, mark as returned
                    $order->update(['is_returned' => true]);
                }
            }
            
            // Recalculate sale total
            $newTotal = SalesOrder::where('sales_id', $saleId)
                ->where('is_returned', false)
                ->sum(DB::raw('quantity * unit_price'));
                
            $sale->update([
                'total' => $newTotal,
                'status' => $targetStatus
            ]);
            
        } else {
            // Full return/cancellation logic (your existing code)
            $salesOrders = SalesOrder::where('sales_id', $saleId)->get();

            if ($adjustmentType) { // Only restore inventory if returning
                foreach ($salesOrders as $order) {
                    $product = Product::find($order->product_id);
                    if (!$product) continue;

                    // Previous quantity
                    $previousQty = $order->price_type === 'retail' ? $product->rqty : $product->wqty;

                    // Restore inventory
                    if ($order->price_type === 'retail') {
                        $product->increment('rqty', $order->quantity);
                    } else {
                        $product->increment('wqty', $order->quantity);
                    }

                    $newQty = $order->price_type === 'retail' ? $product->rqty : $product->wqty;

                    // Log inventory change
                    InventoryLog::create([
                        'product_id' => $order->product_id,
                        'quantity' => $order->quantity,
                        'adjustment_type' => $adjustmentType,
                        'price_type' => $order->price_type,
                        'previous_quantity' => $previousQty,
                        'new_quantity' => $newQty,
                        'reason' => ucfirst($adjustmentType) . ' - ' . $sale->transaction_number,
                        'sale_id' => $saleId,
                        'user_id' => auth()->id(),
                        'price' => $order->unit_price
                    ]);

                    // Create return history record for full return
                    if ($targetStatus === 4) {
                        ReturnHistory::create([
                            'sale_id' => $saleId,
                            'sales_order_id' => $order->id,
                            'product_id' => $order->product_id,
                            'quantity' => $order->quantity,
                            'price' => $order->unit_price,
                            'price_type' => $order->price_type,
                            'reason' => 'Full return',
                            'user_id' => auth()->id()
                        ]);
                    }
                }
            }

            // Update sale status
            $sale->update([
                'status' => $targetStatus,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id()
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => $targetStatus === 3 ? 'Sale cancelled successfully' : 
                        ($targetStatus === 4 ? 'Sale returned successfully' : 'Partial return processed successfully'),
            'data' => [
                'sale_id' => $saleId,
                'status' => $sale->status,
                'cancelled_at' => $sale->cancelled_at
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to process sale: ' . $e->getMessage()
        ], 500);
    }
}
    public function processReturn(Request $request, $saleId)
    {
        $validator = Validator::make($request->all(), [
            'return_items' => 'required|array',
            'return_items.*.product_id' => 'required|exists:products,id',
            'return_items.*.quantity' => 'required|numeric|min:0.01',
            'return_items.*.price_type' => 'required|in:retail,wholesale,special',
            'return_items.*.reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($saleId);

            if ($sale->status != Sale::STATUS_PAID) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only paid orders can be returned.'
                ], 422);
            }

            $totalReturnAmount = 0;

            foreach ($request->return_items as $item) {
                $order = $sale->salesOrders()
                    ->where('product_id', $item['product_id'])
                    ->where('price_type', $item['price_type'])
                    ->firstOrFail();

                $alreadyReturned = InventoryLog::where('sale_id', $saleId)
                    ->where('product_id', $item['product_id'])
                    ->where('price_type', $item['price_type'])
                    ->where('adjustment_type', 'return')
                    ->sum('quantity');

                $maxReturnable = $order->quantity - $alreadyReturned;
                if ($item['quantity'] > $maxReturnable) {
                    throw new \Exception("Cannot return more than purchased quantity for product ID: {$item['product_id']}");
                }

                // Update product inventory
                $product = $order->product;
                $previousQty = $item['price_type'] === 'retail' ? $product->rqty : $product->wqty;
                $product->increment($item['price_type'] === 'retail' ? 'rqty' : 'wqty', $item['quantity']);
                $newQty = $item['price_type'] === 'retail' ? $product->rqty : $product->wqty;

                InventoryLog::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'adjustment_type' => 'return',
                    'price_type' => $item['price_type'],
                    'previous_quantity' => $previousQty,
                    'new_quantity' => $newQty,
                    'reason' => $item['reason'],
                    'price' => $order->unit_price,
                    'sale_id' => $saleId,
                    'user_id' => auth()->id()
                ]);

                $totalReturnAmount += $item['quantity'] * $order->unit_price;
            }

            // Update sale status
            $totalSoldQty = $sale->salesOrders()->sum('quantity');
            $totalReturnedQty = InventoryLog::where('sale_id', $saleId)
                ->where('adjustment_type', 'return')
                ->sum('quantity');

            $sale->status = ($totalReturnedQty >= $totalSoldQty) ? Sale::STATUS_RETURNED : Sale::STATUS_PAID;
            $sale->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Return processed successfully',
                'data' => [
                    'sale_id' => $saleId,
                    'total_return_amount' => $totalReturnAmount,
                    'new_status' => $sale->status
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process return: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getStatusText($status)
    {
        switch ($status) {
            case 1: return 'Paid';
            case 2: return 'Unpaid';
            case 3: return 'Cancelled';
            case 4: return 'Returned';
            default: return 'Unknown';
        }
    }

}
