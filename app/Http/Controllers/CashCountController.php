<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashCount;
use App\Models\CashBankTransaction;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;

class CashCountController extends Controller
{
    public function cashCountEntry($id = null)
    {
        if ($id) {
            $cashcounts = CashCount::find($id);
            $date = $cashcounts->created_at->format('Y-m-d');

            $totalCashInflow = CashBankTransaction::where('category', 1)
                ->whereDate('transaction_date', $date)
                ->get(); 

            $totalCashOutflow = CashBankTransaction::where('category', 2)
                ->whereDate('transaction_date', $date) 
                ->get(); 

            $totalSalesToday = Sale::whereDate('date', $date)
            ->where('status', 1)
            ->sum('total');

            $totalPurchases = Purchase::where('payment_mode', 'Cash')->whereDate('created_at', $date)
            ->sum('total_amount');
        }else{     
            $today = Carbon::now('Asia/Manila')->toDateString();
            $totalCashInflow = CashBankTransaction::where('category', 1)
                ->whereDate('created_at', $today)
                ->get();
            
            $totalSalesToday = Sale::whereDate('date', Carbon::now('Asia/Manila')->toDateString()) // today in Manila
            ->where('status', 1)
            ->sum('total');

            $totalCashOutflow = CashBankTransaction::where('category', 2)
                ->whereDate('created_at', $today)
                ->get();

            $totalPurchases = Purchase::where('payment_mode', 'Cash')->whereDate('created_at', $today)
            ->sum('total_amount');

            $cashcounts = NULL;
        }

        return view('admin.cash-count.cash-entry', compact(
            'cashcounts',
            'totalCashInflow',
            'totalCashOutflow',
            'totalSalesToday',
            'totalPurchases',
        ));
    }

    public function cashCountCreate(Request $request)
    {
        $data = $request->validate([
            // Denominations (must be whole numbers)
            'qty_0_25' => 'nullable|integer|min:0',
            'qty_0_50' => 'nullable|integer|min:0',
            'qty_1'    => 'nullable|integer|min:0',
            'qty_5'    => 'nullable|integer|min:0',
            'qty_10'   => 'nullable|integer|min:0',
            'qty_20'   => 'nullable|integer|min:0',
            'qty_50'   => 'nullable|integer|min:0',
            'qty_100'  => 'nullable|integer|min:0',
            'qty_500'  => 'nullable|integer|min:0',
            'qty_1000' => 'nullable|integer|min:0',

            // Other values
            'gcash' => 'nullable|numeric|min:0',
            'bank'  => 'nullable|numeric|min:0',

            // System values
            'total_inflow'      => 'required|numeric',
            'total_outflow'     => 'required|numeric',
            'total_purchases'   => 'required|numeric',
            'total_sales_today' => 'required|numeric',
            'variance'          => 'required|numeric',

            // Hidden ID (optional)
            'cashcount_id' => 'required|integer',
        ]);

        // Normalize null values → set to 0
        foreach ($data as $key => $value) {
            $data[$key] = is_null($value) ? 0 : $value;
        }

        if ($data['cashcount_id'] != 0) {
            // Update existing record
            $cashCount = CashCount::find($data['cashcount_id']);
            $cashCount->update($data);
            $message = 'Cash count updated successfully';
        } else {
            // Create new record
            $cashCount = CashCount::create($data);
            $message = 'Cash count saved successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function cashCountEdit($id)
    {
        $cashCount = CashCount::findOrFail($id);

        return view('admin.cash-count.cash-entry', compact('cashCount'));
    }
}
