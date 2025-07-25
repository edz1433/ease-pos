<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Type;
use App\Models\CreatePpmp;
use App\Models\CeilingAmount;

class CreatePppmpController extends Controller
{
    public function createPpmpCreate(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $exist = CreatePpmp::where('year', $request->year)->first();

        if ($exist) {
            return redirect()->back()->with('error', 'PPMP already exists for year ' . $request->year . '.');
        }

        CreatePpmp::query()->update(['status' => 2]);

        $ppmp = CreatePpmp::create([
            'year' => $request->year,
            'start' => $request->start,
            'end' => $request->end,
            'status' => 1,
        ]);

        $users = User::where('isAdmin', 2)->get();
        foreach ($users as $user) {
            CeilingAmount::create([
                'cppmt_id' => $ppmp->id,
                'user_id' => $user->id,
                'amount' => 0.00,
            ]);
        }

        return redirect()->back()->with('success', 'PPMP created successfully with default ceiling amounts.');
    }

    public function createPpmpUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:create_ppmps,id',
            'year' => 'required|integer',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $exist = CreatePpmp::where('year', $request->year)->where('id', '!=', $request->id)->first();

        if($exist){
            return redirect()->back()->with('error', 'PPMP Already exist for year '. $request->year.'.');
        }else{
            $ppmp = CreatePpmp::findOrFail($request->id);
            $ppmp->update([
                'year' => $request->year,
                'start' => $request->start,
                'end' => $request->end,
            ]);
        }
        
        return redirect()->route('createPpmpRead')->with('success', 'PPMP updated successfully.');
    }

    public function createPpmpEdit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:create_ppmps,id',
        ]);
        
        $type = Type::all();
        $createppmp = CreatePpmp::all();
        $createppmpedit = CreatePpmp::findOrFail($request->id);
        $users = User::where('isAdmin', 2)->get();

        $statusList = [];

        foreach ($createppmp as $ppmp) {
            $withCeiling = 0;
            $withoutCeiling = 0;

            foreach ($users as $user) {
                $exists = \DB::table('ceiling_amounts')
                    ->where('amount', '!=', 0.00)
                    ->where('cppmt_id', $ppmp->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($exists) {
                    $withCeiling++;
                } else {
                    $withoutCeiling++;
                }
            }

            $statusList[$ppmp->id] = [
                'with' => $withCeiling,
                'without' => $withoutCeiling
            ];
        }

        return view('create-ppmp.index', compact('type', 'createppmp', 'createppmpedit', 'statusList'));
    }

    public function createPpmpDelete($id)
    {
        $ppmp = CreatePpmp::find($id);

        if (!$ppmp) {
            return response()->json([
                'status' => 404,
                'message' => "Record not found",
            ]);
        }

        // Delete related ceiling_amounts entries
        CeilingAmount::where('cppmt_id', $id)->delete();
        Ppmp::where('cppmt_id', $id)->delete();

        // Delete the CreatePpmp record
        $ppmp->delete();

        return response()->json([
            'status' => 200,
            'message' => "Record deleted successfully",
        ]);
    }
    
    public function setCeilingAmount($id)
    {
        $ppmp = CreatePpmp::findOrFail($id);
        $type = Type::all();
        $users = User::where('isAdmin', 2)->get();
        $ceilingamounts = CeilingAmount::where('cppmt_id', $id)->get();

        return view('create-ppmp.set-ceiling-amount', compact('ppmp', 'type', 'users', 'ceilingamounts', 'id'));
    }

    public function activatePpmp($id)
    {
        CreatePpmp::where('id', '!=', $id)->update(['status' => 2]);
        CreatePpmp::where('id', $id)->update(['status' => 1]);

        return redirect()->back()->with('success', 'PPMP activated successfully.');
    }

    public function storeCeilingAmount(Request $request)
    {
        $cppmtId = $request->cppmt_id;
        $userIds = $request->input('users', []);
        $amounts = $request->input('amounts', []);

        foreach ($userIds as $index => $userId) {
            $amount = $amounts[$index];

            CeilingAmount::updateOrCreate(
                ['cppmt_id' => $cppmtId, 'user_id' => $userId],
                ['amount' => $amount]
            );
        }

        return redirect()->back()->with('success', 'Ceiling amounts updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        // Example logic
        $ppmp = CreatePpmp::find($request->id);

        if ($ppmp && $ppmp->year == $request->year) {
            $ppmp->status = 2; // or any status update
            $ppmp->save();

            return response()->json(['message' => 'Status updated successfully.']);
        }

        return response()->json(['message' => 'Not found or year mismatch.'], 404);
    }

}
