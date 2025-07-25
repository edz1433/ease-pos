<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function pos()
    {
        return view("cashier.index");
    }
}
