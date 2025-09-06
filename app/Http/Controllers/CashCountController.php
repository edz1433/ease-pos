<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashCountController extends Controller
{
    public function cashCountRead(Request $request)
    {
        return view('cash-count.index');
    }
}
