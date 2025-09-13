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
    public function salesFilter(Request $request)
    {
        $query = Sale::query();

        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('date', [$request->startDate, $request->endDate]);
        }

        if ($request->filled('transaction')) {
            $query->where('transaction_number', 'like', "%{$request->transaction}%");
        }

        if ($request->filled('customer')) {
            $query->where('customer', 'like', "%{$request->customer}%");
        }

        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->get();

        return view('sales.index', compact('sales'));
    }

}
