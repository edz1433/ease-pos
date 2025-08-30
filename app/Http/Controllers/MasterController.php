<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MasterController extends Controller
{
    public function dashboard() 
    {
        return view("admin.dashboard.index");
    }

    public function purchaseRead() 
    {
        $purchases = Purchase::join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select(
                'purchases.*',
                'suppliers.supplier_name'
            )
            ->where('purchases.status', 1)
            ->get();

        return view('admin.purchases.index', compact('purchases'));
    }

    public function supplierRead()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function productRead()
    {   
        $categories = Category::all();
        $units = Unit::all();
        $products = Product::select(
            'products.*',
            'categories.name as category_name',
            'r_unit.name as r_unit_name',
            'w_unit.name as w_unit_name'
        )
        ->leftJoin('categories', 'products.category', '=', 'categories.id')
        ->leftJoin('units as r_unit', 'products.r_unit', '=', 'r_unit.id')
        ->leftJoin('units as w_unit', 'products.w_unit', '=', 'w_unit.id')
        ->get();

        return view('admin.products.index', compact('categories', 'units', 'products'));
    }

    public function inventoryRead(){
        $inventories = Inventory::all();
        $checkinv = Inventory::where('status', 1)->first();
        return view('admin.inventory.index', compact('inventories', 'checkinv'));
    }

    public function userRead()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function delete(Request $request)
    {
        $model = $request->input('model');
        $id = $request->input('id');

        if ($model && class_exists("App\\Models\\$model")) {
            $modelClass = "App\\Models\\$model";
            $item = $modelClass::find($id);

            if ($item) {
                if (in_array('image', $item->getFillable()) && $item->image) {
                    Storage::delete($item->image);
                }
                $item->delete();

                return response()->json([
                    'success' => true,
                    'message' => "$model deleted successfully."
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "$model not found."
                ], 404);
            }
        }

        return response()->json([
            'success' => false,
            'message' => "Invalid model."
        ], 400);
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
