<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Branch;
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
use Carbon\Carbon;

class MasterController extends Controller
{
    public function dashboard(request $request)
    {
        $totalSales     = Sale::sum('total_wvat');      
        $totalPurchases = Purchase::sum('total_amount');   
        $totalExpenses  = 0;
        $totalStaff     = User::where('role', 2)->count();  
        $categories     = Category::all();  

        return view('admin.dashboard.index', compact(
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'totalStaff',
            'categories'
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
        $branches = Branch::where('id', '!=', env('BRANCH_ID'))->get();

        $products = Product::select(
                'products.*',
                'branch_products.*',
                'categories.name as category_name',
                'r_unit.name as r_unit_name',
                'w_unit.name as w_unit_name',
                DB::raw("(SELECT COALESCE(SUM(s.quantity),0) 
                        FROM sales_orders s 
                        WHERE s.product_id = products.id 
                            AND s.price_type = 'retail') as total_sold_r"),
                DB::raw("(SELECT COALESCE(SUM(s.quantity),0) 
                        FROM sales_orders s 
                        WHERE s.product_id = products.id 
                            AND s.price_type = 'wholesale') as total_sold_w")
            )
            ->leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')
            ->leftJoin('categories', 'products.category', '=', 'categories.id')
            ->leftJoin('units as r_unit', 'branch_products.r_unit', '=', 'r_unit.id')
            ->leftJoin('units as w_unit', 'branch_products.w_unit', '=', 'w_unit.id')
            ->get();

        return view('admin.products.index', compact('categories', 'units', 'branches', 'products'));
    }

    public function warehouseRead()
    {   
        $categories = Category::all();
        $units = Unit::all();
        $branches = Branch::where('id', '!=', env('BRANCH_ID'))->get();

        $products = Product::select(
                'products.*',
                'branch_products.*',
                'categories.name as category_name',
                'r_unit.name as r_unit_name',
                'w_unit.name as w_unit_name',
                DB::raw("(SELECT COALESCE(SUM(s.quantity),0) 
                        FROM sales_orders s 
                        WHERE s.product_id = products.id 
                            AND s.price_type = 'retail') as total_sold_r"),
                DB::raw("(SELECT COALESCE(SUM(s.quantity),0) 
                        FROM sales_orders s 
                        WHERE s.product_id = products.id 
                            AND s.price_type = 'wholesale') as total_sold_w")
            )
            ->leftJoin('branch_products', 'products.id', '=', 'branch_products.product_id')
            ->leftJoin('categories', 'products.category', '=', 'categories.id')
            ->leftJoin('units as r_unit', 'branch_products.r_unit', '=', 'r_unit.id')
            ->leftJoin('units as w_unit', 'branch_products.w_unit', '=', 'w_unit.id')
            ->leftJoin('branches', 'branch_products.branch_id', '=', 'branches.id')
            ->where('branches.type', '=', 'warehouse')
            ->get();

        return view('admin.warehouse.index', compact('categories', 'units', 'branches', 'products'));
    }

    public function salesRead(Request $request)
    {
        $query = Sale::query()
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->where('status', 1)
            ->select(
                'sales.*',
                DB::raw("CONCAT(users.fname, ' ', users.lname) as full_name")
            );

        if ($request->filled('date_range')) {
            $range = str_replace('+', ' ', $request->date_range); // normalize URL encoding

            // Split either " - " or "to"
            if (strpos($range, ' - ') !== false) {
                [$start, $end] = explode(' - ', $range);
            } elseif (stripos($range, 'to') !== false) {
                [$start, $end] = preg_split('/\s*to\s*/i', $range);
            } else {
                $start = $end = $range;
            }

            $start = trim($start);
            $end   = trim($end ?? $start);

            // ✅ Expecting Y-m-d directly
            $query->whereDate('sales.date', '>=', $start)
                ->whereDate('sales.date', '<=', $end);
        } else {
            // Default: today
            $query->whereDate('sales.date', now()->format('Y-m-d'));
        }

        // Transaction
        if ($request->filled('transaction')) {
            $query->where('sales.transaction_number', 'like', '%' . $request->transaction . '%');
        }

        // Customer
        if ($request->filled('customer')) {
            $query->where('sales.customer', 'like', '%' . $request->customer . '%');
        }

        // Payment Method
        if ($request->filled('payment_method')) {
            $query->where('sales.payment_method', $request->payment_method);
        }

        // Status
        if ($request->filled('status')) {
            $query->where('sales.status', $request->status);
        }

        $sales = $query->orderBy('sales.created_at', 'desc')->get();

        return view('admin.sales.index', compact('sales'));
    }

    public function inventoryRead()
    {
        $inventories = Inventory::orderBy('created_at', 'desc')->get();
        $checkinv = Inventory::where('status', 1)->first();

        return view('admin.inventory.index', compact('inventories', 'checkinv'));
    }

    public function customerRead()
    {
        $customers = Customer::all();
        return view('admin.customers.index', compact('customers'));
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

    public function cashbankRead($date = null)
    {
        // Ensure Carbon uses Asia/Manila timezone
        $date = $date 
            ? Carbon::parse($date)->timezone('Asia/Manila')->toDateString() 
            : Carbon::now('Asia/Manila')->toDateString();

        $transactions = CashBankTransaction::whereDate('transaction_date', $date)
            ->orderByDesc('transaction_date')
            ->get();

        return view('admin.cash-bank.index', compact('transactions', 'date'));
    }

    public function cashCountRead($id = null)
    {
        $today = Carbon::now('Asia/Manila')->toDateString();
        if ($id) {
            $cashcountdata = CashCount::find($id);
        }{
            $cashcountdata = null;
        }
        // Total Inflow for Cash today
        $totalCashInflow = CashBankTransaction::where('category', 1)
            ->whereDate('created_at', $today)
            ->get();

        // Total Outflow for Cash today
        $totalCashOutflow = CashBankTransaction::where('category', 2)
            ->whereDate('created_at', $today)
            ->get();

        $totalSalesToday = Sale::whereDate('date', Carbon::now('Asia/Manila')->toDateString()) // today in Manila
        ->where('status', 1)
        ->sum('total');

        $totalPurchases = Purchase::where('payment_mode', 'Cash')->whereDate('created_at', $today)
        ->sum('total_amount');

        $cashCounts = CashCount::latest()->get();

        return view('admin.cash-count.index', compact(
            'cashCounts',
            'totalCashInflow',
            'totalCashOutflow',
            'totalSalesToday',
            'totalPurchases',
            'cashcountdata'
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
