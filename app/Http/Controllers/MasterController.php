<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SalesOrder;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\CashBankTransaction;
use App\Models\CashCount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MasterController extends Controller
{
    public function dashboard()
    {
        // Totals
        $totalSales     = Sale::sum('total_wvat');      
        $totalPurchases = Purchase::sum('total_amount');   
        $totalExpenses  = 0; // Update when you add an expenses table
        $totalStaff     = User::where('role', 2)->count();  

        $topProducts = SalesOrder::join('products', 'sales_orders.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',        
                'products.product_name',            
                \DB::raw('SUM(sales_orders.price * sales_orders.quantity) as total_wvat')
            )
            ->groupBy('sales_orders.product_id', 'products.id', 'products.product_name')
            ->orderByDesc('total_wvat')
            ->limit(10)
            ->get()
            ->map(function ($item, $key) {
                $item->row_number = $key + 1;      
                return $item;
            });

        return view('admin.dashboard.index', compact(
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'totalStaff',
            'topProducts'
        ));
    }

    public function purchaseRead() 
    {
        $purchases = Purchase::join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select(
                'purchases.*',
                'suppliers.supplier_name'
            )
            ->where('purchases.status', 1)
            ->get();

        return view('admin.purchases.index', compact('purchases'));
    }

    public function supplierRead()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function productRead()
    {   
        $categories = Category::all();
        $units = Unit::all();
        $products = Product::select(
            'products.*',
            'categories.name as category_name',
            'r_unit.name as r_unit_name',
            'w_unit.name as w_unit_name'
        )
        ->leftJoin('categories', 'products.category', '=', 'categories.id')
        ->leftJoin('units as r_unit', 'products.r_unit', '=', 'r_unit.id')
        ->leftJoin('units as w_unit', 'products.w_unit', '=', 'w_unit.id')
        ->get();

        return view('admin.products.index', compact('categories', 'units', 'products'));
    }

    public function inventoryRead(){
        $inventories = Inventory::all();
        $checkinv = Inventory::where('status', 1)->first();
        return view('admin.inventory.index', compact('inventories', 'checkinv'));
    }

    public function userRead()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Calculate total cash counted
        $denominations = [1,5,10,20,50,100,500,1000];
        $totalCounted = 0;

        foreach ($denominations as $denom) {
            $totalCounted += ($request->input("qty_$denom") ?? 0) * $denom;
        }

        $expensesPaid = $request->input('expenses_paid') ?? 0;
        $pettyCashUsed = $request->input('petty_cash_used') ?? 0;

        $closingBalance = $totalCounted - $expensesPaid - $pettyCashUsed;

        $cashCount = CashCount::create([
            'qty_1' => $request->input('qty_1') ?? 0,
            'qty_5' => $request->input('qty_5') ?? 0,
            'qty_10' => $request->input('qty_10') ?? 0,
            'qty_20' => $request->input('qty_20') ?? 0,
            'qty_50' => $request->input('qty_50') ?? 0,
            'qty_100' => $request->input('qty_100') ?? 0,
            'qty_500' => $request->input('qty_500') ?? 0,
            'qty_1000' => $request->input('qty_1000') ?? 0,
            'expenses_paid' => $expensesPaid,
            'petty_cash_used' => $pettyCashUsed,
            'closing_balance' => $closingBalance,
        ]);

        return view('cash-count.index', compact(
            'cashCounts',
            'totalCash',
            'expensesPaid',
            'pettyCashUsed'
        ));
    }

    public function cashbankRead()
    {
        $transactions = CashBankTransaction::orderByDesc('transaction_date')->get();
        return view('admin.cash-bank.index', compact('transactions'));
    }

    public function cashCountRead()
    {
        // Get totals from cash transactions
        $totalCash = CashBankTransaction::where('account_type', 'Cash')
            ->whereIn('transaction_type', ['Deposit', 'Withdrawal', 'Expense', 'Petty Cash', 'Salary'])
            ->sum('amount');

        $expensesPaid = CashBankTransaction::where('transaction_type', 'Expense')->sum('amount');
        $pettyCashUsed = CashBankTransaction::where('transaction_type', 'Petty Cash')->sum('amount');

        $cashCounts = CashCount::latest()->get();

        return view('admin.cash-count.index', compact(
            'cashCounts',
            'totalCash',
            'expensesPaid',
            'pettyCashUsed'
        ));
    }

    public function delete(Request $request)
    {
        $model = $request->input('model');
        $id = $request->input('id');

        if ($model && class_exists("App\\Models\\$model")) {
            $modelClass = "App\\Models\\$model";
            $item = $modelClass::find($id);

            if ($item) {
                if (in_array('image', $item->getFillable()) && $item->image) {
                    Storage::delete($item->image);
                }
                $item->delete();

                return response()->json([
                    'success' => true,
                    'message' => "$model deleted successfully."
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "$model not found."
                ], 404);
            }
        }

        return response()->json([
            'success' => false,
            'message' => "Invalid model."
        ], 400);
    }
    
    public function logout()
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            return redirect()->route('getLogin')->with('success', 'You have been successfully logged out');
        }

        return redirect()->route('getLogin')->with('error', 'No authenticated user to log out');
    }
}
