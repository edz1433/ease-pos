<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashCount;

class CashCountController extends Controller
{
    public function cashCountCreate(Request $request)
    {
        $data = $request->validate([
            // Denominations
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

            // Other
            'gcash' => 'nullable|numeric|min:0',
            'bank'  => 'nullable|numeric|min:0',

            // System values
            'total_inflow'      => 'required|numeric',
            'total_outflow'     => 'required|numeric',
            'total_purchases'   => 'required|numeric',
            'total_sales_today' => 'required|numeric',
            'variance'          => 'required|numeric',
        ]);

        // Normalize null values to 0.00
        foreach ($data as $key => $value) {
            $data[$key] = is_null($value) ? 0.00 : number_format((float)$value, 2, '.', '');
        }

        // Check if a cash count already exists for today
        $today = now()->startOfDay();
        $cashCount = CashCount::whereDate('created_at', $today)->first();

        if ($cashCount) {
            // Update existing record
            $cashCount->update($data);
            $message = 'Cash count updated successfully';
        } else {
            // Create new record
            $cashCount = CashCount::create($data);
            $message = 'Cash count saved successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
