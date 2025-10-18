<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CustomerPayment;
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
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalSales     = Sale::sum('total_wvat');      
        $totalPurchases = Purchase::sum('total_amount');   
        $totalExpenses  = 0; // Update when you add an expenses table
        $totalStaff     = User::where('role', 2)->count();  
        // This is for initial page load
        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $data = $this->getDashboardData($filter, $startDate, $endDate);

        return view('dashboard.index', [
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'totalStaff',
            'filter' => $filter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'data' => $data,
        ]);
    }

    public function dashboardData(Request $request)
    {
        $filter   = $request->input('filter', 'month');
        $category = $request->input('category');
        $start    = $request->input('start_date');
        $end      = $request->input('end_date');

        $totalSalesQuery     = Sale::query();
        $totalPurchasesQuery = Purchase::query(); 

        $query = SalesOrder::join('products', 'sales_orders.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.product_name',
                \DB::raw('SUM(sales_orders.price * sales_orders.quantity) as total_wvat'),
                \DB::raw('SUM(sales_orders.quantity) as total_items_sold')
            );

        if ($category && $category !== 'All') {
            $query->where('products.category', $category);
        }

        if (auth()->user()->role != 1) {
            $filter = 'day';
            $category = null;
            $start = null;
            $end = null;
        }

        // Apply filter
        if ($filter === 'day') {
            $query->whereDate('sales_orders.created_at', now());
            $totalSalesQuery->whereDate('created_at', now());
            $totalPurchasesQuery->whereDate('created_at', now());
        } elseif ($filter === 'week') {
            $query->whereBetween('sales_orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $totalSalesQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $totalPurchasesQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('sales_orders.created_at', now()->month);
            $totalSalesQuery->whereMonth('created_at', now()->month);
            $totalPurchasesQuery->whereMonth('created_at', now()->month);
        } elseif ($filter === 'year') {
            $query->whereYear('sales_orders.created_at', now()->year);
            $totalSalesQuery->whereYear('created_at', now()->year);
            $totalPurchasesQuery->whereYear('created_at', now()->year);
        } elseif ($filter === 'custom' && $start && $end) {
            $query->whereBetween('sales_orders.created_at', [$start, $end]);
            $totalSalesQuery->whereBetween('created_at', [$start, $end]);
            $totalPurchasesQuery->whereBetween('created_at', [$start, $end]);
        }

        $topProducts = $query
            ->groupBy('sales_orders.product_id', 'products.id', 'products.product_name')
            ->orderByDesc('total_items_sold')
            ->limit(10)
            ->get()
            ->map(function ($item, $key) {
                $item->row_number = $key + 1;
                return $item;
            });

        // ---------------- Categories Pie ----------------
        $categoryQuery = SalesOrder::join('products', 'sales_orders.product_id', '=', 'products.id')
            ->select(
                'categories.name as category_name',
                \DB::raw('SUM(sales_orders.price * sales_orders.quantity) as total_sales')
            )
            ->join('categories', 'products.category', '=', 'categories.id');

        // Apply only date filters (no category filter here)
        if ($filter === 'day') {
            $categoryQuery->whereDate('sales_orders.created_at', now());
        } elseif ($filter === 'week') {
            $categoryQuery->whereBetween('sales_orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $categoryQuery->whereMonth('sales_orders.created_at', now()->month);
        } elseif ($filter === 'year') {
            $categoryQuery->whereYear('sales_orders.created_at', now()->year);
        } elseif ($filter === 'custom' && $start && $end) {
            $categoryQuery->whereBetween('sales_orders.created_at', [$start, $end]);
        }

        $categories = $categoryQuery
            ->groupBy('categories.name')
            ->orderByDesc('total_sales')
            ->get();

        // Compute percentages
        $totalCategorySales = $categories->sum('total_sales');
        $categories = $categories->map(function ($item) use ($totalCategorySales) {
            $item->percentage = $totalCategorySales > 0 
                ? round(($item->total_sales / $totalCategorySales) * 100, 2)
                : 0;
            return $item;
        });

        // Adjust rounding error on last item
        $sum  = $categories->sum('percentage');
        $diff = round(100 - $sum, 2); // round diff too

        if ($categories->count() > 0) {
            $last = $categories->last();
            $last->percentage = round($last->percentage + $diff, 2);
        }

        // ---------------- Sales Analytics ----------------
        $salesQuery = Sale::query()
            ->join('sales_orders', 'sales.id', '=', 'sales_orders.sales_id')
            ->join('products', 'sales_orders.product_id', '=', 'products.id')
            ->where('sales.status', 1);

        // Apply category filter
        if ($category && $category !== 'All') {
            $salesQuery->where('products.category', $category);
        }

        // Apply date filter
        if ($filter === 'day') {
            $salesQuery->whereDate('sales.created_at', now());
        } elseif ($filter === 'week') {
            $salesQuery->whereBetween('sales.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $salesQuery->whereMonth('sales.created_at', now()->month);
        } elseif ($filter === 'year') {
            $salesQuery->whereYear('sales.created_at', now()->year);
        } elseif ($filter === 'custom' && $start && $end) {
            $salesQuery->whereBetween('sales.created_at', [$start, $end]);
        }

        // Compute sales & profit
        $salesAnalytics = $salesQuery
            ->selectRaw('DATE(sales.created_at) as date')
            ->selectRaw('SUM(sales_orders.price * sales_orders.quantity) as total_sales')
            ->selectRaw('SUM((sales_orders.price - sales_orders.capital) * sales_orders.quantity) as total_profit')
            ->groupBy(\DB::raw('DATE(sales.created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $analyticsLabels = $salesAnalytics->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $salesData   = $salesAnalytics->pluck('total_sales');
        $grossprofitData  = $salesAnalytics->pluck('total_profit');

        $analyticsSummary = [
            'total_sales'   => $salesData->sum(),
            'total_profit'  => $grossprofitData->sum(),
            'profit_margin' => $salesData->sum() > 0
                ? round(($grossprofitData->sum() / $salesData->sum()) * 100, 2) : 0,
        ];

        $totalSales     = $totalSalesQuery->sum('total_wvat');
        $totalPurchases = $totalPurchasesQuery->sum('total_amount');
        $totalExpenses  = 0;

        return response()->json([
            'total_sales'     => $totalSales,
            'total_purchases' => $totalPurchases,
            'total_expenses'  => $totalExpenses,
            // Top Products
            'labels' => $topProducts->pluck('row_number'),
            'data'   => $topProducts->pluck('total_items_sold'),
            'names'  => $topProducts->pluck('product_name'),
            'list'   => $topProducts->map(fn($p) => [
                'rank' => $p->row_number,
                'name' => $p->product_name,
            ]),

            // Categories Pie
            'categories' => [
                'labels' => $categories->pluck('category_name'),
                'data'   => $categories->pluck('percentage'), // return percentages
            ],

            // Gross Sales Analytics
            'analytics' => [
                'labels'  => $analyticsLabels,
                'sales'   => $salesData,
                'profit'  => $grossprofitData,
                'summary' => $analyticsSummary,
            ],

            // Net Profit Sales Analytics
            'analytics1' => [
                'labels'  => $analyticsLabels,
                'sales'   => $salesData,
                'profit'  => $grossprofitData,
                'summary' => $analyticsSummary,
            ]
        ]);
    }

}
