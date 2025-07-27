<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function dashboard() 
    {
        return view("admin.dashboard.index");
    }

    public function userRead()
    {
        $users = User::all();
        $type = Type::all();
        return view('users.index', compact('users', 'type'));
    }

    public function productRead()
    {   
        $categories = Category::all();
        $units = Unit::all();
        $products = Product::all();
        return view('admin.products.index', compact('categories', 'units', 'products'));
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
