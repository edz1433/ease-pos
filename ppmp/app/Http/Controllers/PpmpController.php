<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Type;
use App\Models\Item;
use App\Models\Ppmp;
use App\Models\CreatePpmp;
use App\Models\CeilingAmount;
use Illuminate\Support\Facades\DB;

class PpmpController extends Controller
{
    public function ppmpView($id, $tier, $uid = null){
        $type = Type::all();
        $createdppmp = CreatePpmp::find($id);

        $userid = ($uid == null) ? auth()->user()->id : $uid;

        $ceilingamount = CeilingAmount::where('cppmt_id', $id)
        ->where('user_id', $userid)
        ->first();

        $role = auth()->user()->isAdmin;
        
        if($tier == 'All'){
            $ppmp_raw = Ppmp::where('cppmt_id', $id)->where('user_id', $userid)->get();
        }else{
            $ppmp_raw = Ppmp::where('cppmt_id', $id)->where('tier', $tier)->where('user_id', $userid)->get();
        }

        $items = Item::all();
        $ppmp = $ppmp_raw->groupBy('item_code')->map(function ($group) {
            return [
                'item_code'    => $group->first()->item_code,
                'item_spec'    => $group->first()->item_spec,
                'measure'      => $group->first()->measure,
                'cataloque'    => $group->first()->cataloque,
                'ppmpid'    => $group->first()->id,

                'jan'          => $group->sum('jan'),
                'feb'          => $group->sum('feb'),
                'mar'          => $group->sum('mar'),
                'q1'           => $group->sum('q1'),
                'q1_amount'    => $group->sum('q1_amount'),

                'apr'          => $group->sum('apr'),
                'may'          => $group->sum('may'),
                'jun'          => $group->sum('jun'),
                'q2'           => $group->sum('q2'),
                'q2_amount'    => $group->sum('q2_amount'),

                'jul'          => $group->sum('jul'),
                'aug'          => $group->sum('aug'),
                'sep'          => $group->sum('sep'),
                'q3'           => $group->sum('q3'),
                'q3_amount'    => $group->sum('q3_amount'),

                'oct'          => $group->sum('oct'),
                'nov'          => $group->sum('nov'),
                'decem'        => $group->sum('decem'),
                'q4'           => $group->sum('q4'),
                'q4_amount'    => $group->sum('q4_amount'),

                'total_qty'    => $group->sum('total_qty'),
                'total_amt'    => $group->sum('total_amt'),

                'ids'          => $group->pluck('id'), // collect IDs if needed
            ];
        })->values(); // reindex the array
        
        return view('ppmp.view', compact('type', 'createdppmp', 'ceilingamount', 'ppmp', 'items', 'tier', 'id', 'userid'));
    }

    public function ppmptAddNew(Request $request, $uid = null){
        $type = Type::all();
        $items = Item::all();
        
        $item_code = $request->item_code;
        $cppmt_id = $request->cppmt_id;
        $tier = $request->tier;

        $createdppmp = CreatePpmp::find($cppmt_id);
        $userid = ($uid == null) ? auth()->user()->id : $uid;

        $ceilingamount = CeilingAmount::where('cppmt_id', $cppmt_id)
        ->where('user_id', $userid)
        ->first();

        $ppmp = Ppmp::where('cppmt_id', $cppmt_id)->where('user_id', $userid)->get();
        $itemsseleted = Item::join('category', 'item.measure', '=', 'category.id')
            ->where('item_code', $item_code)
            ->first();

        // Check if PPMP already exists
   
        $existingPpmp = Ppmp::where('cppmt_id', $cppmt_id)
            ->where('user_id', $userid)
            ->where('item_id', $itemsseleted->id)
            ->where('tier', $tier)
            ->first();

        if($existingPpmp){
                return redirect()->route('ppmpView', ['id' => $cppmt_id, 'tier' => $tier])->with('error', 'PPMP data already exist.');
        }
   
        return view('ppmp.add-new', compact('type', 'items', 'item_code', 'cppmt_id', 'tier', 'ppmp', 'createdppmp', 'ceilingamount', 'itemsseleted', 'userid'));
    }

    public function ppmptAddNewEdit($id)
    {
        $type = Type::all();
        $items = Item::all();

        // Find the existing PPMP by its primary ID
        $existingPpmp = Ppmp::find($id);

        if (!$existingPpmp) {
            return redirect()->route('ppmpView', ['id' => 0])->with('error', 'PPMP record not found.');
        }

        $cppmt_id = $existingPpmp->cppmt_id;
        $tier = $existingPpmp->tier;
        $userid = ($existingPpmp->user_id == null) ? auth()->user()->id : $existingPpmp->user_id;

        $createdppmp = CreatePpmp::find($cppmt_id);

        $ceilingamount = CeilingAmount::where('cppmt_id', $cppmt_id)
            ->where('user_id', $userid)
            ->first();

        $ppmp = Ppmp::where('cppmt_id', $cppmt_id)
            ->where('user_id', $userid)
            ->get();

        // Get the related item (with category)
        $itemsseleted = Item::join('category', 'item.measure', '=', 'category.id')
            ->where('item_code', $existingPpmp->item_code)
            ->first();

        $item_code = $itemsseleted->item_code;

        return view('ppmp.add-new-edit', compact(
            'type',
            'items',
            'item_code',
            'cppmt_id',
            'tier',
            'ppmp',
            'createdppmp',
            'ceilingamount',
            'itemsseleted',
            'userid',
            'id',
            'existingPpmp'
        ));
    }

    public function ppmptAddCreate(Request $request)
    {
        $request->validate([
            'cppmt_id' => 'required',
            'item_id' => 'required',
            'item_code' => 'required|string',
            'item_spec' => 'required|string',
            'measure' => 'required|string',
            'price' => 'required|numeric',
            'jan' => 'nullable|numeric',
            'feb' => 'nullable|numeric',
            'mar' => 'nullable|numeric',
            'apr' => 'nullable|numeric',
            'may' => 'nullable|numeric',
            'jun' => 'nullable|numeric',
            'jul' => 'nullable|numeric',
            'aug' => 'nullable|numeric',
            'sep' => 'nullable|numeric',
            'oct' => 'nullable|numeric',
            'nov' => 'nullable|numeric',
            'decem' => 'nullable|numeric',
            'q1' => 'nullable|numeric',
            'q2' => 'nullable|numeric',
            'q3' => 'nullable|numeric',
            'q4' => 'nullable|numeric',
            'q1_amount' => 'nullable|numeric',
            'q2_amount' => 'nullable|numeric',
            'q3_amount' => 'nullable|numeric',
            'q4_amount' => 'nullable|numeric',
            'total_qty' => 'nullable|numeric',
            'cataloque' => 'nullable|numeric',
            'total_amt' => 'required|numeric|min:1',
            'tier' => 'numeric',
            'id' => 'nullable|exists:ppmp,id', // This field will tell us if it's update
        ]);

        // Create or Update
        $ppmp = $request->id ? Ppmp::findOrFail($request->id) : null;

        if (!$ppmp) {
            $duplicate = Ppmp::where('item_code', $request->item_code)
                ->where('user_id', auth()->id())
                ->where('cppmt_id', $request->cppmt_id)
                ->where('tier', $request->tier)
                ->first();

            if ($duplicate) {
                return redirect()->route('ppmpView', ['id' => $request->cppmt_id, 'tier' => $request->tier])->with('error', 'A PPMP entry already exists with the same item code, user, cppmt, and tier.');
            }

            $ppmp = new Ppmp();
        }

        $ppmp->user_id = auth()->id();
        $ppmp->item_code = $request->item_code;
        $ppmp->item_spec = $request->item_spec;
        $ppmp->measure = $request->measure;
        $ppmp->price = $request->price;

        $ppmp->jan = $request->jan ?? 0;
        $ppmp->feb = $request->feb ?? 0;
        $ppmp->mar = $request->mar ?? 0;
        $ppmp->apr = $request->apr ?? 0;
        $ppmp->may = $request->may ?? 0;
        $ppmp->jun = $request->jun ?? 0;
        $ppmp->jul = $request->jul ?? 0;
        $ppmp->aug = $request->aug ?? 0;
        $ppmp->sep = $request->sep ?? 0;
        $ppmp->oct = $request->oct ?? 0;
        $ppmp->nov = $request->nov ?? 0;
        $ppmp->decem = $request->decem ?? 0;

        $ppmp->q1 = $request->q1 ?? 0;
        $ppmp->q2 = $request->q2 ?? 0;
        $ppmp->q3 = $request->q3 ?? 0;
        $ppmp->q4 = $request->q4 ?? 0;

        $ppmp->q1_amount = $request->q1_amount ?? 0;
        $ppmp->q2_amount = $request->q2_amount ?? 0;
        $ppmp->q3_amount = $request->q3_amount ?? 0;
        $ppmp->q4_amount = $request->q4_amount ?? 0;

        $ppmp->total_qty = $request->total_qty ?? 0;
        $ppmp->cataloque = $request->cataloque ?? 0;
        $ppmp->total_amt = $request->total_amt ?? 0;

        $ppmp->cppmt_id = $request->cppmt_id;
        $ppmp->item_id = $request->item_id;
        $ppmp->tier = $request->tier;

        $ppmp->save();

        $message = $request->id ? 'PPMP data updated successfully.' : 'PPMP data added successfully.';

        return redirect()->route('ppmpView', ['id' => $request->cppmt_id, 'tier' => $request->tier])->with('success', $message);
    }

    public function ppmpDelete($id)
    {
        $ppmp = Ppmp::find($id);
        
        if (!$ppmp) {
            return response()->json([
                'status' => 404,
                'message' => "Item not found",
            ]);
        }

        $ppmp->delete();

        return response()->json([
            'status' => 200,
            'message' => "Item deleted successfully",
        ]);
    }
}
