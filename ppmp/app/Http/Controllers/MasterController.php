<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Type;
use App\Models\User;
use App\Models\CreatePpmp;
use App\Models\CeilingAmount;
use App\Models\Ppmp;
use App\Models\Item;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function dashboard() 
    {
        $type = Type::all();
        $userCount = User::count();

        $PpmtCount = Ppmp::select('user_id', 'cppmt_id')
            ->groupBy('user_id', 'cppmt_id')
            ->get()
            ->count();

        $itemCount = Item::count();

        $CreatePpmp = CreatePpmp::where('status', 1)->first();

        $role = auth()->user()->isAdmin;

        $ppmp = collect(); // default to empty collection

        if ($CreatePpmp) {
            if ($role == 1) {
                $ppmp = CeilingAmount::where('cppmt_id', $CreatePpmp->id)
                                    ->where('user_id', auth()->user()->id)
                                    ->first();
            } else {
                $ppmp = CeilingAmount::where('cppmt_id', $CreatePpmp->id)->get();
            }
        }

        return view("dashboard.index", compact('userCount', 'PpmtCount', 'itemCount', 'type', 'ppmp', 'CreatePpmp'));
    }

    public function createPpmpRead() {
        $type = Type::all();
        $createppmp = CreatePpmp::orderBy('created_at', 'desc')->get();
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

        return view('create-ppmp.index', compact('createppmp', 'type', 'statusList'));
    }

    public function userRead()
    {
        $users = User::all();
        $type = Type::all();
        return view('users.index', compact('users', 'type'));
    }

    public function itemsettingsRead($type){
        $itemid = ($type == 'category') ? 1 : (($type == 'measure') ? 2 : 3);
        $items = Category::where('type_id', $itemid)->get();
        $type = Type::all();

        return view('items.settings', compact('type', 'items', 'itemid'));
    }

    public function itemsRead() 
    {
        $type = Type::all();
        $categories = Category::all();

        $items = DB::table('item as i')
            ->leftJoin('category as c', 'i.category', '=', 'c.id')
            ->leftJoin('category as m', 'i.measure', '=', 'm.id')
            ->leftJoin('category as p', 'i.partno', '=', 'p.id')
            ->select(
                'i.*',
                'c.title as category_name',
                'm.title as measure_name',
                'p.title as partno_name'
            )
            ->get();

        return view("items.index", compact('type', 'items', 'categories'));
    }

    public function userlogsRead(){
        $type = Type::all();
        $logs = Logs::all();
        return view("user-logs.index", compact('type', 'logs'));
    }

    public function ppmpRead(){
        $userrole = auth()->user()->isAdmin;
        $type = Type::all();
        $items = Item::all();

        if($userrole == 1){
            $createppmp = CreatePpmp::leftJoin('ceiling_amounts', function ($join) {
                    $join->on('create_ppmps.id', '=', 'ceiling_amounts.cppmt_id')
                        ->where('ceiling_amounts.user_id', auth()->user()->id);
                })
                ->orderBy('created_at', 'desc')
                ->select('create_ppmps.*', 'ceiling_amounts.amount')
                ->get();
        }else{
            $createppmp = CreatePpmp::leftJoin('ceiling_amounts', 'create_ppmps.id', '=', 'ceiling_amounts.cppmt_id')
                ->leftJoin('users', 'ceiling_amounts.user_id', '=', 'users.id')
                ->where('users.isAdmin', '!=', [0, 2])
                ->orderBy('created_at', 'desc')
                ->select('create_ppmps.*', 'ceiling_amounts.amount', 'ceiling_amounts.user_id', 'users.fname', 'users.lname')
                ->get();
        }


        return view("ppmp.index", compact('type', 'items', 'createppmp'));
    }

    public function reportSearch()
    {
        $type = Type::all();
        $users = User::where('isAdmin', '!=', 1)->get();
        $createppmp = CreatePpmp::all();
        return view("reports.index", compact('type', 'createppmp', 'users'));
    }
    
    public function logout()
    {
        if (Auth::guard('web')->check()) {
            DB::table('logs')->insert([
                'user_id'     => Auth::id(),
                'name'        => Auth::user()->fname.' '.Auth::user()->lname,
                'type'        => 0,
                'transaction' => 'has logged out the system',
                'created_at'  => now(),
                'updated_at'  => null,
            ]);

            Auth::guard('web')->logout();
            return redirect()->route('getLogin')->with('success', 'You have been successfully logged out');
        }

        return redirect()->route('getLogin')->with('error', 'No authenticated user to log out');
    }
}
