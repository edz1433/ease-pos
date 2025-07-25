<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\User;
use App\Models\CreatePpmp;
use App\Models\CeilingAmount;
use App\Models\Ppmp;
use App\Models\Item;
use App\Models\Logs;

class ReportsController extends Controller
{
    
    public function reportsResult(Request $request){
        $type = Type::all();
        $createppmp = CreatePpmp::all();
        $users = User::where('isAdmin', '=', '1')->get();
        $userid = $request->userid;
        $cppmtid = $request->cppmtid;
        $tier = $request->tier;
        return view("reports.index", compact('type', 'users', 'userid', 'cppmtid', 'tier', 'createppmp'));
    }

    public function reportsShow($userid, $cppmtid, $tier)
    {
        $createppmp = CreatePpmp::find($cppmtid);
        $ppmp_raw = Ppmp::where('cppmt_id', $cppmtid)
            ->join('users', 'ppmp.user_id', '=', 'users.id')
            ->when($userid !== 'All', function ($query) use ($userid) {
                return $query->where('ppmp.user_id', $userid);
            })
            ->when($tier !== 'All', function ($query) use ($tier) {
                return $query->where('ppmp.tier', $tier);
            })
            ->select('ppmp.*', 'users.fname', 'users.lname', 'users.office') // select user fields
            ->get();

        $ppmp = $ppmp_raw->groupBy('item_code')->map(function ($group) {
            return [
                'item_code'    => $group->first()->item_code,
                'item_spec'    => $group->first()->item_spec,
                'measure'      => $group->first()->measure,
                'cataloque'    => $group->first()->cataloque,
                'fname'         =>$group->first()->fname,
                'lname'         =>$group->first()->lname,
                'office'        =>$group->first()->office,

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

        // Use large custom paper size (17 x 11 inches = 1224 x 792 points)
        $customPaper = [0, 0, 1300, 900]; // Increase width as needed

        $pdf = \PDF::loadView('reports.ppmt-report-gen', compact('ppmp', 'createppmp', 'userid', 'tier'))
            ->setPaper($customPaper, 'landscape')
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);

        $pdf->setCallbacks([
            'before_render' => function ($domPdf) {
                $domPdf->getCanvas()->page_text(10, 10, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);
            },
        ]);

        return $pdf->stream();
    }

}
