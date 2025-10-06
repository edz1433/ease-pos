<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerControllerApi extends Controller
{
    public function getCustomers(){
        $customers = Customer::all(['id', 'name']);

        return response()->json($customers);
    }
}
