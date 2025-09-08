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

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $data[$key] = 0.00;
            } else {
                $data[$key] = number_format((float) $value, 2, '.', '');
            }
        }

        $cashCount = CashCount::create($data);

        return redirect()->back()->with('success', 'Cash count saved successfully!');
    }

}
