<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function supplierCreate(Request $request)
    {
        $request->validate([
            'supplier_name'   => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'contact_number'  => 'required|string|max:50',
            'email'           => 'nullable|email|max:100',
            'address'         => 'nullable|string',
        ]);

        Supplier::create($request->only([
            'supplier_name',
            'contact_person',
            'contact_number',
            'email',
            'address',
        ]));

        return redirect()->route('supplierRead')->with('success', 'Supplier created successfully!');
    }

    public function supplierEdit(Request $request)
    {
        $supplierEdit = Supplier::find($request->id);
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers', 'supplierEdit'));
    }

    public function supplierUpdate(Request $request, $id)
    {
        $request->validate([
            'supplier_name'   => 'required|string|max:255',
            'contact_person'  => 'required|string|max:255',
            'contact_number'  => 'required|string|max:50',
            'email'           => 'nullable|email|max:100',
            'address'         => 'nullable|string',
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->update($request->only([
            'supplier_name',
            'contact_person',
            'contact_number',
            'email',
            'address',
        ]));

        return redirect()->route('supplierRead')->with('success', 'Supplier updated successfully!');
    }

}
