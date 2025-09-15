<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Unit;

class ProductsClassController extends Controller
{
    public function classificationRead()
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('admin.products.cassification', compact('categories', 'units'));
    }
}
