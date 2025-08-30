<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;

class PurchasesController extends Controller
{
    public function purchaseForm() 
    {
        $products = Product::where('product_type', 1)->get();
        $purchasecheck = Purchase::where('status', 0)->first(); // ongoing purchase (optional)
        
        $suppliers = Supplier::all();

        // -------------------------
        // Generate next transaction number
        // -------------------------
        $lastPurchase = Purchase::where('status', 1) // look for last completed purchase
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = (int) str_replace('PUR-', '', $lastPurchase->transaction_no);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $transactionNo = 'PUR-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        // -------------------------
        // Get purchase items for ongoing purchase
        // -------------------------
        $purchaseitems = [];
        if($purchasecheck){
            $purchaseitems = PurchaseItem::select(
                'purchase_items.id',
                'purchase_items.purchase_id',
                'purchase_items.product_id',
                'purchase_items.price',
                'purchase_items.selling_price',
                'purchase_items.price_type',
                'purchase_items.quantity',
                'purchase_items.subtotal',
                'products.product_name'
            )
            ->join('products', 'products.id', '=', 'purchase_items.product_id')
            ->where('purchase_items.purchase_id', $purchasecheck->id)
            ->get();
        }

        return view('admin.purchases.form', compact(
            'products', 
            'purchasecheck', 
            'purchaseitems', 
            'suppliers', 
            'transactionNo'
        ));
    }

    public function purchaseCreate(Request $request) 
    {
        $request->validate([
            'transaction_no' => 'required',
            'supplier_id' => 'required',
            'purchase_date' => 'required',
        ]);

        $purchase = new Purchase();
        $purchase->transaction_no = $request->transaction_no;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->purchase_date = $request->purchase_date;
        $purchase->save();

        return redirect()->back()->with('success', 'Purchase created successfully.');
    }

    public function purchaseStoreItem(Request $request)
    {
        $request->validate([
            'purchase_id'    => 'required|integer',
            'product_id'     => 'required|integer',
            'price'          => 'required|numeric',
            'selling_price'  => 'required|numeric',
            'price_type'     => 'required|string|in:Retail,Wholesale',
            'quantity'       => 'required|integer|min:1',
        ]);

        // Check if item already exists in this purchase
        $existingItem = PurchaseItem::where('purchase_id', $request->purchase_id)
            ->where('product_id', $request->product_id)
            ->where('price_type', $request->price_type)
            ->first();

        if ($existingItem) {
            // Update existing item → add qty and recalc subtotal
            $existingItem->quantity += $request->quantity;
            $existingItem->price = $request->price; // update last cost
            $existingItem->selling_price = $request->selling_price; // update selling price
            $existingItem->subtotal = $existingItem->price * $existingItem->quantity;
            $existingItem->save();
        } else {
            // Create new item
            PurchaseItem::create([
                'purchase_id'   => $request->purchase_id,
                'product_id'    => $request->product_id,
                'price'         => $request->price,
                'selling_price' => $request->selling_price,
                'price_type'    => $request->price_type,
                'quantity'      => $request->quantity,
                'subtotal'      => $request->price * $request->quantity,
            ]);
        }

        return back()->with('success', 'Item saved successfully.');
    }

    public function purchaseCancel($id)
    {
        try {
            $purchase = Purchase::find($id);

            PurchaseItem::where('purchase_id', $id)->delete();

            // Delete the purchase
            $purchase->delete();

            return response()->json([
                'success' => true,
                'message' => 'Purchase and its items have been deleted.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function purchasesSave(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_no'   => 'required',
            'po_number'        => 'required',
            'supplier_id'      => 'required|integer',
            'purchase_date'    => 'required|date',
            'payment_mode'     => 'required|in:Cash,Credit,Postdated Check',
            'total_amount'     => 'required|numeric|min:0',
            // Optional fields
            'due_date'         => 'nullable|date',
            'check_number'     => 'nullable|string|max:100',
            'bank_name'        => 'nullable|string|max:150',
            'check_date'       => 'nullable|date',
            'account_name'     => 'nullable|string|max:150',
        ]);

        $purchase = Purchase::findOrFail($id);

        // Update purchase main data
        $purchase->supplier_id   = $validated['supplier_id'];
        $purchase->purchase_date = $validated['purchase_date'];
        $purchase->payment_mode  = $validated['payment_mode'];
        $purchase->total_amount  = $validated['total_amount'];

        $purchase->due_date      = $validated['due_date'] ?? null;
        $purchase->check_number  = $validated['check_number'] ?? null;
        $purchase->bank_name     = $validated['bank_name'] ?? null;
        $purchase->check_date    = $validated['check_date'] ?? null;
        $purchase->account_name  = $validated['account_name'] ?? null;
        $purchase->payment_status = ($validated['payment_mode'] == "Cash") ? 'paid' : 'unpaid';
        $purchase->status        = 1;

        $purchase->save();

        if($validated['payment_mode'] != "Cash"){
            $supplier = Supplier::find($purchase->supplier_id);
            $supplier->amount_payable += $validated['total_amount'];
            $supplier->save();
        }

        // Get purchase items
        $purchaseItems = PurchaseItem::where('purchase_id', $id)->get();

        foreach ($purchaseItems as $item) {
            $product = Product::find($item->product_id);

            if ($product) {
                if ($item->price_type === 'retail') {
                    // Update retail fields
                    $product->rqty     += $item->quantity;
                    $product->r_capital = $item->price;       // last purchase cost
                    $product->r_price   = $item->selling_price; // selling price
                } elseif ($item->price_type === 'wholesale') {
                    // Update wholesale fields
                    $product->wqty     += $item->quantity;
                    $product->w_capital = $item->price;
                    $product->w_price   = $item->selling_price;
                }

                $product->save();
            }
        }

        return redirect()->back()->with('success', 'Purchase save successfully!');
    }

}
