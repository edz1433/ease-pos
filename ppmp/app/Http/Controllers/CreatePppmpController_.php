<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\CreatePpmp;

class CreatePpmpController extends Controller
{
    public function createPpmpCreate(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|unique:create_ppmps,year',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        CreatePpmp::create([
            'year' => $request->year,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return redirect()->back()->with('success', 'PPMP created successfully.');
    }

    public function createPpmpUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:create_ppmps,id',
            'year' => 'required|integer',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $ppmp = CreatePpmp::findOrFail($request->id);
        $ppmp->update([
            'year' => $request->year,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return redirect()->back()->with('success', 'PPMP updated successfully.');
    }

    public function createPpmpDelete($id)
    {
        $ppmp = CreatePpmp::findOrFail($id);
        $ppmp->delete();

        return redirect()->back()->with('success', 'PPMP deleted successfully.');
    }

    public function createPpmpEdit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:create_ppmps,id',
        ]);
        $type = Type::all();
        $createppmp = CreatePpmp::all();
        $createppmpedit = CreatePpmp::findOrFail($request->id);

        return view('users.index', compact('type', 'createppmp', 'createppmpedit'));
    }
 
    public function storeCeilingAmount(Request $request)
    {
        $request->validate([
            'ppmp_id' => 'required|exists:create_ppmps,id',
            'ceiling_amount' => 'required|numeric|min:0',
        ]);

        // Assuming you have a ceiling_amount column in your create_ppmps table
        $ppmp = CreatePpmp::findOrFail($request->ppmp_id);
        $ppmp->ceiling_amount = $request->ceiling_amount;
        $ppmp->save();

        return redirect()->back()->with('success', 'Ceiling amount updated successfully.');
    }

}
