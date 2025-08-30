<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\SalesOrder;
use Carbon\Carbon;

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

            DB::transaction(function () use ($data, $status, $vat, $totalWithVat) {
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
                $imageBase64 = null;
                if ($order->image) {
                    $imagePath = public_path('uploads/products/' . $order->image);
                    if (file_exists($imagePath)) {
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $mimeType = mime_content_type($imagePath);
                        $imageBase64 = "data:$mimeType;base64,$imageData";  
                    }
                }

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
                        'image_base64' => $imageBase64
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


}
