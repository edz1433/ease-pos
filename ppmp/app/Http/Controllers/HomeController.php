<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\Logs;
use App\Models\User;
use App\Models\Ppmp;
use App\Models\Category;
use App\Models\Type;
use App\Models\Item;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function erroraction()
    {
        return view('error'); // use helper function
    }

    public function postLogin(Request $request)
    {
        // Validation
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->route('index')
                             ->withErrors($validate)
                             ->withInput();
        }

        // Remember me functionality
        $remember = $request->has('remember');

        // Attempt login
        if (Auth::attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ], $remember)) {

            // Save login logs
            $logs = new Logs();
            $logs->user_id = Auth::user()->id;
            $logs->name = Auth::user()->fname . ' ' . Auth::user()->lname;
            $logs->type = "Login";
            $logs->transaction = "has logged in the system";
            $logs->created_at = Carbon::now(); // optional, can be removed if using timestamps
            $logs->save();

            return redirect()->intended('control')->with('success', 'You have successfully logged in.');
        }

        // Login failed
        return redirect()->route('index')->with('fail', 'Your password or username is wrong.');
    }

    public function control()
    {
        $year = Carbon::now()->year;

        $user = Auth::user();
        $users = User::orderBy('created_at', 'desc')->paginate();
        $notipost = Ppmp::whereDate('created_at', now()->toDateString())->paginate();
        $notiuser = User::whereDate('created_at', now()->toDateString())->paginate();
        
        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $category = Type::orderBy('created_at', 'desc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();

        $totalppmp = Ppmp::where('user_id', $user->id)->sum('total_amt');

        $submittedppmp = Ppmp::whereYear('created_at', $year)
                            // ->groupBy('user_id')
                            ->get();

        $consolidatedcount = Ppmp::whereYear('created_at', $year)
                                // ->groupBy('item_code')
                                ->get();

        $userscount = User::all();

        return view('dashboard.control', [
            'category' => $category,
            'userscount' => $userscount,
            'consolidatedcount' => $consolidatedcount,
            'submittedppmp' => $submittedppmp,
            'categorys' => $categorys,
            'measure' => $measure,
            'part' => $part,
            'totalppmp' => $totalppmp,
            'notipost' => $notipost,
            'notiuser' => $notiuser,
            'users' => $users,
            'user' => $user,
            'year' => $year,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();

        Logs::create([
            'user_id' => $user->id,
            'name' => $user->fname . ' ' . $user->lname,
            'type' => 'Login',
            'transaction' => 'has logged out the system',
            'created_at' => now(),
        ]);

        Auth::logout();

        return redirect()->route('index')->with('success', 'You have successfully signed out.');
    }

    public function createPPMP()
    {
        $today = Carbon::today();

        $notipost = Ppmp::whereDate('created_at', $today)->paginate();
        $notiuser = User::whereDate('created_at', $today)->paginate();

        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $category = Type::orderBy('created_at', 'desc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();

        $totalppmp = Ppmp::where('user_id', Auth::id())->sum('total_amt');

        return view('dashboard.createppmp', compact(
            'categorys',
            'measure',
            'part',
            'category',
            'notipost',
            'notiuser',
            'totalppmp'
        ));
    }

    public function checkPPMP()
    {
        $today = Carbon::today();

        $notipost = Ppmp::whereDate('created_at', $today)->paginate();
        $notiuser = User::whereDate('created_at', $today)->paginate();
        $totalppmp = Ppmp::where('user_id', Auth::id())->sum('total_amt');

        $item = Item::orderBy('created_at', 'asc')->get();
        $category = Type::orderBy('created_at', 'desc')->get();
        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();

        return view('dashboard.checkppmp', compact(
            'item',
            'category',
            'notipost',
            'notiuser',
            'categorys',
            'measure',
            'part',
            'totalppmp'
        ));
    }

    public function specDel($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->route('control')->with('fail', 'The item does not exist.');
        }

        if ($item->delete()) {
            return redirect()->route('checkPPMP')->with('success', 'The item was successfully deleted.');
        } else {
            return redirect()->route('checkPPMP')->with('fail', 'An error occurred while deleting the data.');
        }
    }

    public function specEdit($id)
    {
        $today = Carbon::today();

        $notipost = Ppmp::whereDate('created_at', $today)->paginate();
        $notiuser = User::whereDate('created_at', $today)->paginate();
        $item = Item::find($id);
        $category = Type::orderBy('created_at', 'desc')->get();
        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();
        $totalppmp = Ppmp::where('user_id', Auth::id())->sum('total_amt');

        return view('dashboard.specEdit', compact(
            'item',
            'category',
            'categorys',
            'measure',
            'part',
            'notipost',
            'notiuser',
            'totalppmp'
        ));
    }

    public function specUpdate(Request $request, $id)
    {
        $rules = [
            'item_code' => 'required',
            'item_spec' => 'required',
            'amount' => 'required',
            'category' => 'required',
            'measure' => 'required',
            'partno' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return Redirect::route('specEdit', $id)
                ->withErrors($validation)
                ->withInput()
                ->with('fail', 'Error in updating the item data');
        }

        $item = Item::find($id);
        if (!$item) {
            return Redirect::route('specEdit', $id)
                ->with('fail', 'Item not found.');
        }

        // Update fields manually or use fill() if $fillable is set in Model
        $item->item_code = $request->input('item_code');
        $item->item_spec = $request->input('item_spec');
        $item->amount = $request->input('amount');
        $item->category = $request->input('category');
        $item->measure = $request->input('measure');
        $item->partno = $request->input('partno');
        $item->updated_at = Carbon::now();

        $item->save();

        return Redirect::route('specEdit', $id)->with('success', 'Success in updating item data');
    }

    public function postCategory(Request $request)
    {
        $rules = [
            'title' => 'required|unique:category,title', // specifying column is good practice
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::route('checkPPMP')
                ->withErrors($validator)
                ->withInput();
        }

        $category = new Category();
        $category->type_id = 1;
        $category->partno = $request->input('partno');
        $category->title = $request->input('title');
        $category->created_at = Carbon::now();

        if ($category->save()) {
            return Redirect::route('checkPPMP')->with('success', 'The category has been saved');
        } else {
            return Redirect::route('checkPPMP')->with('fail', 'Error in uploading');
        }
    }

	public function postMeasure()
	{
		$validate = Validator::make(Input::all(), array(
				'title' => 'required|unique:category',
			));

		if($validate -> fails())
		{
			return Redirect::route('checkPPMP')->withErrors($validate)->withInput();
		}
		else
		{
			$title = new Category();					
					$title->type_id= 2;
					$title->partno= Input::get('partno');					
					$title->title= Input::get('title');
					$dt = Carbon::now();
					$title->created_at = $dt;


			if ($title->save())
			{
				return Redirect::route('checkPPMP')->with('success','The measure has been saved');
			}
			else
			{
				return Redirect::route('checkPPMP')->with('fail', 'Error in uploading');
			}
		}
	}

    public function postPart(Request $request){
        $rules = [
            'partno' => 'required|unique:category,partno', // specify column for uniqueness
            'des' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::route('checkPPMP')
                ->withErrors($validator)
                ->withInput();
        }

        $category = new Category();
        $category->type_id = 3;
        $category->partno = $request->input('partno');
        $category->title = $request->input('des');
        $category->created_at = Carbon::now();

        if ($category->save()) {
            return Redirect::route('checkPPMP')->with('success', 'The part number has been saved');
        } else {
            return Redirect::route('checkPPMP')->with('fail', 'Error in uploading');
        }
    }

    public function postItem(Request $request)
    {
        $rules = [
            'item_code' => 'required|unique:item,item_code',
            'item_spec' => 'required',
            'amount' => 'required',
            'category' => 'required',
            'measure' => 'required',
            'partno' => 'required',
        ];

        $request->validate($rules);

        $item = Item::create($request->only([
            'item_code', 'item_spec', 'amount', 'category', 'measure', 'partno'
        ]));

        if ($item) {
            return Redirect::route('createPPMP')->with('success', 'The item has been saved');
        }

        return Redirect::route('createPPMP')->with('fail', 'Error in uploading');
    }

    public function viewItems($id)
    {
        $today = Carbon::today()->toDateString();

        $notipost = Ppmp::whereDate('created_at', $today)->paginate();
        $notiuser = User::whereDate('created_at', $today)->paginate();
        $type = Type::findOrFail($id); // Use findOrFail to automatically handle missing type
        $itemss = Category::where('type_id', $type->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $category = Type::orderBy('created_at', 'desc')->get();
        $totalppmp = Ppmp::where('user_id', Auth::id())->sum('total_amt');

        return view('dashboard.viewitems', compact(
            'type', 'id', 'itemss', 'category', 'notipost', 'notiuser', 'totalppmp'
        ));
    }

    public function itemDel($id)
    {
        $item = Category::find($id);

        if (!$item) {
            return redirect()->route('control')->with('fail', 'The item does not exist.');
        }

        if ($item->delete()) {
            return redirect()->route('createPPMP')->with('success', 'The item was successfully deleted.');
        }

        return redirect()->route('createPPMP')->with('fail', 'An error occurred while deleting the data.');
    }

    public function itemEdit($id)
    {
        $notipost = Ppmp::whereDate('created_at', '=', Carbon::today()->toDateString())->paginate();
        $notiuser = User::whereDate('created_at', '=', Carbon::today()->toDateString())->paginate();
        $item = Category::find($id);
        $types = Type::orderBy('created_at', 'desc')->get();

        return view('dashboard.itemEdit')
            ->with('item', $item)
            ->with('types', $types)
            ->with('notipost', $notipost)
            ->with('notiuser', $notiuser);
    }

    public function itemUpdate($id)
    {
        $rules = [
            'title' => 'required',
        ];    

        $itemInfo = Input::all();
        $validation = Validator::make($itemInfo, $rules);

        if ($validation->passes()) {
            $item = Category::find($id);
            if (!$item) {
                return Redirect::route('itemEdit', $id)->with('fail', 'Item not found.');
            }

            $item->title = Input::get('title');
            // Laravel will handle updated_at automatically
            $item->save();

            return Redirect::route('itemEdit', $id)->with('success', 'Success in updating item data');
        }

        return Redirect::route('itemEdit', $id)->withInput()->withErrors($validation)->with('fail', 'Error in updating the item data');
    }

    public function fill()
    {
        $year = Carbon::now()->addYear()->year;

        $notipost = Ppmp::whereDate('created_at', Carbon::today())->paginate();
        $notiuser = User::whereDate('created_at', Carbon::today())->paginate();
        $category = Type::orderBy('created_at', 'desc')->get();
        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();
        $totalppmp = Ppmp::where('user_id', Auth::user()->id)->sum('total_amt');
        $items = Item::orderBy('created_at', 'desc')->get();

        return View::make('dashboard.fill')
            ->with('category', $category)
            ->with('categorys', $categorys)
            ->with('measure', $measure)
            ->with('part', $part)
            ->with('item', $items)
            ->with('notipost', $notipost)
            ->with('totalppmp', $totalppmp)
            ->with('notiuser', $notiuser)
            ->with('year', $year);
    }

    public function postPPMP()
    {
        $rules = [
            'item_code' => 'required',
            'item_spec' => 'required',
            'measure' => 'required',
            // Add more validation rules as needed, e.g.:
            // 'jan' => 'numeric|nullable',
            // 'feb' => 'numeric|nullable',
            // etc...
        ];

        $validate = Validator::make(Input::all(), $rules);

        if ($validate->fails()) {
            return Redirect::route('fill')->withErrors($validate)->withInput();
        }

        $item = new Ppmp();
        $item->user_id = Auth::user()->id;
        $item->item_id = Input::get('id');                    
        $item->item_code = Input::get('item_code');
        $item->item_spec = Input::get('item_spec');
        $item->measure = Input::get('measure');
        $item->jan = Input::get('jan');
        $item->feb = Input::get('feb');
        $item->mar = Input::get('mar');
        $item->q1 = Input::get('q1');
        $item->q1_amount = Input::get('q1_amount');
        $item->apr = Input::get('apr');
        $item->may = Input::get('may');
        $item->jun = Input::get('jun');
        $item->q2 = Input::get('q2');
        $item->q2_amount = Input::get('q2_amount');
        $item->jul = Input::get('jul');
        $item->aug = Input::get('aug');
        $item->sep = Input::get('sep');
        $item->q3 = Input::get('q3');
        $item->q3_amount = Input::get('q3_amount');
        $item->oct = Input::get('oct');
        $item->nov = Input::get('nov');
        $item->decem = Input::get('dec');  // Confirm 'decem' column vs input name 'dec'
        $item->q4 = Input::get('q4');
        $item->q4_amount = Input::get('q4_amount');
        $item->total_qty = Input::get('total_qty');
        $item->cataloque = Input::get('cataloque');
        $item->total_amt = Input::get('total_amt');
        $item->tier = Input::get('tier');

        // If you want to manually set created_at, otherwise Laravel does it
        // $item->created_at = Carbon::now();

        if ($item->save()) {
            return Redirect::route('fill')->with('success', 'The item has been saved');
        }

        return Redirect::route('fill')->with('fail', 'Error in saving the item');
    }

    public function search()
    {
        $keyword = trim(Input::get('keyword'));
        $tier = Input::get('tier');
        $year = date('Y') + 1;

        if (empty($keyword)) {
            return Redirect::route('control')->with('fail', 'No keyword entered, please try again');
        }

        // URL encode keyword and tier to avoid problems with special chars
        $keywordEncoded = urlencode($keyword);
        $tierEncoded = urlencode($tier);

        return Redirect::to("results/{$keywordEncoded}/{$tierEncoded}")
            ->with('success', 'Total Search results')
            ->with('year', $year);
    }

    public function results(string $keyword, int $tier)
    {
        $year = now()->addYear()->year; // Using now() helper and addYear() for clarity
        
        // Paginate with a specific count (e.g., 10 per page)
        $notipost = Ppmp::whereDate('created_at', Carbon::today())->paginate(10);
        $notiuser = User::whereDate('created_at', Carbon::today())->paginate(10);

        $ppmp = Ppmp::where('user_id', auth()->id())
                    ->whereYear('created_at', $year)
                    ->groupBy('item_code')
                    ->get();

        $totalppmp = Ppmp::where('user_id', auth()->id())
                        ->whereYear('created_at', $year)
                        ->sum('total_amt');

        // Searching items that contain the keyword in item_code
        $items = Item::where('item_code', 'like', '%' . $keyword . '%')->get();

        $itemsrch = Item::where('item_code', $keyword)->first();

        if ($itemsrch) {
            $checkppmp = Ppmp::where('item_id', $itemsrch->id)
                            ->where('user_id', auth()->id())
                            ->where('tier', $tier)
                            ->whereYear('created_at', $year)
                            ->first();

            if ($checkppmp) {
                return redirect()->route('fill')->with('fail', 'Item has already been saved');
            }
        }

        $item = Item::orderBy('created_at', 'desc')->get();

        return view('dashboard.fillresults', [
            'items' => $items,
            'item' => $item,
            'ppmp' => $ppmp,
            'totalppmp' => $totalppmp,
            'success' => 'Research List - Search Results',
            'notipost' => $notipost,
            'notiuser' => $notiuser,
            'year' => $year,
            'keyword' => $keyword,
            'tier' => $tier,
        ]);
    }

    public function view(string $year)
    {
        $year = urldecode($year);

        if (!is_numeric($year) || strlen($year) !== 4) {
            $year = now()->addYear()->year;  // Default to next year using now() helper
        } else {
            $year = (int) $year;
        }

        // Add pagination count, for example 10 per page
        $notipost = Ppmp::whereDate('created_at', Carbon::today())->paginate(10);
        $notiuser = User::whereDate('created_at', Carbon::today())->paginate(10);

        $ppmp = Ppmp::where('user_id', auth()->id())
                    ->whereYear('created_at', $year)
                    ->groupBy('item_code')
                    ->get();

        $totalppmp = Ppmp::where('user_id', auth()->id())
                        ->whereYear('created_at', $year)
                        ->sum('total_amt');

        return view('dashboard.viewppmp', [
            'ppmp' => $ppmp,
            'notipost' => $notipost,
            'notiuser' => $notiuser,
            'totalppmp' => $totalppmp,
            'year' => $year,
        ]);
    }

    public function ppmpexport(string $year)
    {
        $year = urldecode($year);

        if (!is_numeric($year) || strlen($year) !== 4) {
            $year = now()->addYear()->year;
        } else {
            $year = (int) $year;
        }

        $ppmpRecords = Ppmp::orderBy('created_at', 'desc')
            ->whereYear('created_at', $year)
            ->where('user_id', auth()->id())
            ->select([
                'item_code', 'item_spec', 'measure', 'jan', 'feb', 'mar', 'q1', 'q1_amount',
                'apr', 'may', 'jun', 'q2', 'q2_amount', 'jul', 'aug', 'sep', 'q3', 'q3_amount',
                'oct', 'nov', 'decem', 'q4', 'q4_amount', 'total_qty', 'cataloque', 'total_amt'
            ])
            ->get();

        $export = new class($ppmpRecords) implements FromCollection, WithHeadings {
            private $records;

            public function __construct(Collection $records)
            {
                $this->records = $records;
            }

            public function collection()
            {
                return $this->records;
            }

            public function headings(): array
            {
                return [
                    'Item Code', 'Item Spec', 'Unit of Measure', 'January', 'February', 'March', 'Q1', 'Q1 Amount',
                    'April', 'May', 'June', 'Q2', 'Q2 Amount', 'July', 'August', 'September', 'Q3', 'Q3 Amount',
                    'October', 'November', 'December', 'Q4', 'Q4 Amount', 'Total Quantity for the Year',
                    'Price Catalogue', 'Total Amount for the Year',
                ];
            }
        };

        $fileName = 'PPMP_' . date('d_M_Y') . '.xlsx';

        return Excel::download($export, $fileName);
    }

    public function ppmpexportAdmin($id, $year)
    {
        $year = urldecode($year);

        if (!is_numeric($year) || strlen($year) !== 4) {
            $year = (int) date('Y') + 1;
        } else {
            $year = (int) $year;
        }

        $ppmpRecords = Ppmp::orderBy('created_at', 'desc')
            ->whereYear('created_at', $year)
            ->where('user_id', $id)
            ->select([
                'item_code', 'item_spec', 'measure', 'jan', 'feb', 'mar', 'q1', 
                'q1_amount', 'apr', 'may', 'jun', 'q2', 'q2_amount', 'jul', 'aug', 
                'sep', 'q3', 'q3_amount', 'oct', 'nov', 'decem', 'q4', 'q4_amount', 
                'total_qty', 'cataloque', 'total_amt'
            ])
            ->get()
            ->map(function ($item) {
                $columnsToRound = ['q1_amount', 'q2_amount', 'q3_amount', 'q4_amount', 'total_amt'];
                foreach ($columnsToRound as $column) {
                    if (isset($item->$column)) {
                        $item->$column = round($item->$column, 2);
                    }
                }
                return $item;
            });

        $export = new class($ppmpRecords) implements FromCollection, WithHeadings {
            private $records;

            public function __construct(Collection $records)
            {
                $this->records = $records;
            }

            public function collection()
            {
                return $this->records;
            }

            public function headings(): array
            {
                return [
                    'Item Code', 'Item Spec', 'Unit of Measure', 'January', 'February', 'March', 'Q1', 'Q1 Amount',
                    'April', 'May', 'June', 'Q2', 'Q2 Amount', 'July', 'August', 'September', 'Q3', 'Q3 Amount',
                    'October', 'November', 'December', 'Q4', 'Q4 Amount', 'Total Quantity for the Year',
                    'Price Catalogue', 'Total Amount for the Year',
                ];
            }
        };

        $fileName = 'PPMP_' . date('d_M_Y') . '.xlsx';

        return Excel::download($export, $fileName);
    }

    public function ppmpexportAll($year)
    {
        $year = urldecode($year);

        if (!is_numeric($year) || strlen($year) !== 4) {
            $year = (int)date('Y') + 1;
        } else {
            $year = (int)$year;
        }

        $ppmpRecords = Ppmp::orderBy('created_at', 'desc')
            ->whereYear('created_at', $year)
            ->select([
                'item_code', 'item_spec', 'measure', 'jan', 'feb', 'mar', 'q1', 
                'q1_amount', 'apr', 'may', 'jun', 'q2', 'q2_amount', 'jul', 'aug', 
                'sep', 'q3', 'q3_amount', 'oct', 'nov', 'decem', 'q4', 'q4_amount', 
                'total_qty', 'cataloque', 'total_amt'
            ])
            ->get()
            ->map(function ($item) {
                $columnsToRound = ['q1_amount', 'q2_amount', 'q3_amount', 'q4_amount', 'total_amt'];
                foreach ($columnsToRound as $column) {
                    if (isset($item->$column)) {
                        $item->$column = round($item->$column, 2);
                    }
                }
                return $item;
            });

        $export = new class($ppmpRecords) implements FromCollection, WithHeadings {
            private $records;

            public function __construct(Collection $records)
            {
                $this->records = $records;
            }

            public function collection()
            {
                return $this->records;
            }

            public function headings(): array
            {
                return [
                    'Item Code', 'Item Spec', 'Unit of Measure', 'January', 'February', 'March', 'Q1', 'Q1 Amount',
                    'April', 'May', 'June', 'Q2', 'Q2 Amount', 'July', 'August', 'September', 'Q3', 'Q3 Amount',
                    'October', 'November', 'December', 'Q4', 'Q4 Amount', 'Total Quantity for the Year',
                    'Price Catalogue', 'Total Amount for the Year',
                ];
            }
        };

        $fileName = 'PPMP_All_' . date('d_M_Y') . '.xlsx';

        return Excel::download($export, $fileName);
    }

    public function ppmpconsolidatedexport()
    {
        // Get consolidated sums grouped by item_code (like in viewConsolidatedPPMP)
        $ppmpRecords = Ppmp::select(
                'item_code', 'item_spec', 'measure', 'cataloque',
                DB::raw('SUM(jan) as jan'),
                DB::raw('SUM(feb) as feb'),
                DB::raw('SUM(mar) as mar'),
                DB::raw('SUM(apr) as apr'),
                DB::raw('SUM(may) as may'),
                DB::raw('SUM(jun) as jun'),
                DB::raw('SUM(jul) as jul'),
                DB::raw('SUM(aug) as aug'),
                DB::raw('SUM(sep) as sep'),
                DB::raw('SUM(oct) as oct'),
                DB::raw('SUM(nov) as nov'),
                DB::raw('SUM(decem) as decem'),
                DB::raw('SUM(q1) as q1'),
                DB::raw('SUM(q2) as q2'),
                DB::raw('SUM(q3) as q3'),
                DB::raw('SUM(q4) as q4'),
                DB::raw('SUM(q1_amount) as q1_amount'),
                DB::raw('SUM(q2_amount) as q2_amount'),
                DB::raw('SUM(q3_amount) as q3_amount'),
                DB::raw('SUM(q4_amount) as q4_amount'),
                DB::raw('SUM(total_qty) as total_qty'),
                DB::raw('SUM(total_amt) as total_amt')
            )
            ->groupBy('item_code', 'item_spec', 'measure', 'cataloque')
            ->orderBy('item_code', 'asc')
            ->get()
            ->map(function ($item) {
                $columnsToRound = ['q1_amount', 'q2_amount', 'q3_amount', 'q4_amount', 'total_amt'];
                foreach ($columnsToRound as $column) {
                    if (isset($item->$column)) {
                        $item->$column = round($item->$column, 2);
                    }
                }
                return $item;
            });

        $export = new class($ppmpRecords) implements FromCollection, WithHeadings {
            private $records;

            public function __construct(Collection $records)
            {
                $this->records = $records;
            }

            public function collection()
            {
                return $this->records;
            }

            public function headings(): array
            {
                return [
                    'Item Code', 'Item Spec', 'Unit of Measure',
                    'January', 'February', 'March', 'Q1', 'Q1 Amount',
                    'April', 'May', 'June', 'Q2', 'Q2 Amount',
                    'July', 'August', 'September', 'Q3', 'Q3 Amount',
                    'October', 'November', 'December', 'Q4', 'Q4 Amount',
                    'Total Quantity for the Year', 'Price Catalogue', 'Total Amount for the Year'
                ];
            }
        };

        $fileName = 'PPMP_Consolidated_' . date('d_M_Y') . '.xlsx';

        return Excel::download($export, $fileName);
    }

    public function viewConsolidatedPPMP($year)
    {
        $year = urldecode($year);

        if (!is_numeric($year) || strlen($year) !== 4) {
            $year = date('Y');
        }

        $notipostCount = Ppmp::whereDate('created_at', Carbon::today())->count();
        $notiuserCount = User::whereDate('created_at', Carbon::today())->count();

        $ppmpQuery = Ppmp::whereYear('created_at', $year);

        $ppmp = $ppmpQuery->select(
                'item_code', 'item_spec', 'measure', 'cataloque',
                DB::raw('SUM(jan) as jansum'),
                DB::raw('SUM(feb) as febsum'),
                DB::raw('SUM(mar) as marsum'),
                DB::raw('SUM(apr) as aprsum'),
                DB::raw('SUM(may) as maysum'),
                DB::raw('SUM(jun) as junsum'),
                DB::raw('SUM(jul) as julsum'),
                DB::raw('SUM(aug) as augsum'),
                DB::raw('SUM(sep) as sepsum'),
                DB::raw('SUM(oct) as octsum'),
                DB::raw('SUM(nov) as novsum'),
                DB::raw('SUM(decem) as decemsum'),
                DB::raw('SUM(q1) as q1sum'),
                DB::raw('SUM(q2) as q2sum'),
                DB::raw('SUM(q3) as q3sum'),
                DB::raw('SUM(q4) as q4sum'),
                DB::raw('SUM(q1_amount) as q1_amountsum'),
                DB::raw('SUM(q2_amount) as q2_amountsum'),
                DB::raw('SUM(q3_amount) as q3_amountsum'),
                DB::raw('SUM(q4_amount) as q4_amountsum'),
                DB::raw('SUM(total_qty) as total_qtysum'),
                DB::raw('SUM(total_amt) as total_amtsum')
            )
            ->groupBy('item_code', 'item_spec', 'measure', 'cataloque')
            ->orderBy('item_code', 'asc')
            ->get();

        $jan = $ppmpQuery->groupBy('item_code')->orderBy('created_at', 'desc')->get();
        $ppmpqty = $ppmpQuery->sum('total_qty');
        $totalppmp = $ppmpQuery->sum('total_amt');
        $totalcataloque = $ppmpQuery->sum('cataloque');

        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();
        $category = Type::orderBy('created_at', 'desc')->get();

        return View::make('dashboard.viewconsolidatedppmp')
            ->with(compact(
                'ppmp', 'jan', 'categorys', 'measure', 'part', 'category',
                'notipostCount', 'notiuserCount', 'totalppmp', 'totalcataloque', 'ppmpqty', 'year'
            ));
    }

    public function userProfile($year)
    {
        $year = urldecode($year);

        if (!is_numeric($year) || strlen($year) !== 4) {
            $year = date('Y');
        }

        $notipostCount = Ppmp::whereDate('created_at', Carbon::today())->count();
        $notiuserCount = User::whereDate('created_at', Carbon::today())->count();

        $category = Type::orderBy('created_at', 'desc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();

        return view('dashboard.users.userProfile', compact('measure', 'part', 'category', 'notipostCount', 'notiuserCount', 'year'));
    }

    public function changepassupdate()
    {
        $input = request()->only('old_password', 'password', 'password_confirmation', 'username');

        $validator = Validator::make($input, [
            'old_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            // Optional username validation, e.g. unique and string length
            'username' => 'sometimes|string|max:255|unique:users,username,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return redirect()->route('userProfile', ['year' => date('Y')]) // add year param if needed
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($input['old_password'], $user->password)) {
            return redirect()->route('userProfile', ['year' => date('Y')])
                ->withErrors(['old_password' => 'Your current password is incorrect'])
                ->withInput();
        }

        if (!empty($input['username'])) {
            $user->username = $input['username'];
        }

        $user->password = Hash::make($input['password']);
        $user->save();

        Auth::logout();

        return redirect()->route('index')->with('success', 'Your authentication has been changed');
    }

    public function editAvatar()
    {
        $validator = Validator::make(request()->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('userProfile', ['year' => date('Y')])
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (request()->hasFile('image')) {
            $file = request()->file('image');

            // Create a safe, unique filename
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Store in 'public/dist/img', returns path relative to 'storage/app/public'
            $path = $file->storeAs('dist/img', $filename, 'public');

            // Optionally delete old avatar (if exists and not default)
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $user->image = $path;
        }

        $user->updated_at = Carbon::now();
        $user->save();

        return redirect()->route('userProfile', ['year' => date('Y')])->with('success', 'Your avatar has been updated');
    }

    public function unisearch()
    {
        $keyword = trim(request()->input('keyword'));

        if (empty($keyword)) {
            return redirect()->route('control')->with('fail', 'No keyword entered, please try again');
        }

        $encodedKeyword = urlencode($keyword);

        // Assuming you have a route named 'uniresults' expecting a 'keyword' parameter
        return redirect()->route('uniresults', ['keyword' => $encodedKeyword])
            ->with('success', 'Search completed');
    }

    public function uniresults($keyword)
    {
        // Decode the keyword if it was URL-encoded
        $keyword = urldecode($keyword);

        // Notifications (no pagination if not needed)
        $notipost = Ppmp::whereDate('created_at', Carbon::today())->get();
        $notiuser = User::whereDate('created_at', Carbon::today())->get();

        // Perform search via User model static method
        $user = User::unisearch($keyword);

        // Fetch categories and types
        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();
        $category = Type::orderBy('created_at', 'desc')->get();

        return view('dashboard.filluniresults', compact(
            'user', 'categorys', 'measure', 'part', 'category', 'notipost', 'notiuser'
        ))->with('success', 'Research List - Search Results');
    }

    public function deleteppmpitem($id)
    {
        $item = Ppmp::find($id);

        if (!$item) {
            return redirect()->route('control')->with('fail', 'The item does not exist.');
        }

        if ($item->delete()) {
            return redirect()->route('view')->with('success', 'The item was successfully deleted.');
        }

        return redirect()->route('view')->with('fail', 'An error occurred while deleting the data.');
    }

    public function userPpmpItem($id)
    {
        $year = date('Y');

        $notipost = Ppmp::whereDate('created_at', Carbon::today())->paginate();
        $notiuser = User::whereDate('created_at', Carbon::today())->paginate();

        $ppmp = Ppmp::find($id);
        if (!$ppmp) {
            return redirect()->route('some.route')->with('fail', 'PPMP item not found.');
        }

        $item = Item::find($ppmp->item_id);
        if (!$item) {
            return redirect()->route('some.route')->with('fail', 'Associated item not found.');
        }

        $totalppmp = Ppmp::where('user_id', Auth::id())->sum('total_amt');
        $category = Type::orderBy('created_at', 'desc')->get();
        $categorys = Category::where('type_id', 1)->orderBy('created_at')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at')->get();

        return view('dashboard.userppmpedit', compact(
            'ppmp', 'category', 'categorys', 'measure', 'part', 
            'notipost', 'notiuser', 'item', 'totalppmp', 'year'
        ));
    }
	
    public function updatePPMPitem($id)
    {
        $rules = [
            'item_code' => 'required',
            'jan' => 'nullable|numeric',
            'feb' => 'nullable|numeric',
            'mar' => 'nullable|numeric',
            'q1' => 'nullable|numeric',
            'q1_amount' => 'nullable|numeric',
            'apr' => 'nullable|numeric',
            'may' => 'nullable|numeric',
            'jun' => 'nullable|numeric',
            'q2' => 'nullable|numeric',
            'q2_amount' => 'nullable|numeric',
            'jul' => 'nullable|numeric',
            'aug' => 'nullable|numeric',
            'sep' => 'nullable|numeric',
            'q3' => 'nullable|numeric',
            'q3_amount' => 'nullable|numeric',
            'oct' => 'nullable|numeric',
            'nov' => 'nullable|numeric',
            'dec' => 'nullable|numeric',
            'q4' => 'nullable|numeric',
            'q4_amount' => 'nullable|numeric',
            'total_qty' => 'nullable|numeric',
            'cataloque' => 'nullable|string',
            'total_amt' => 'nullable|numeric',
        ];

        $data = request()->all();

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            return redirect()->route('userPpmpItem', $id)
                ->withErrors($validation)
                ->withInput()
                ->with('fail', 'Error in updating the item data');
        }

        $item = Ppmp::find($id);
        if (!$item) {
            return redirect()->route('userPpmpItem', $id)
                ->with('fail', 'Item not found');
        }

        // Normalize 'decem' key if sent by the form
        if (isset($data['decem'])) {
            $data['dec'] = $data['decem'];
            unset($data['decem']);
        }

        $item->fill($data);
        $item->save(); // this automatically updates updated_at

        return redirect()->route('userPpmpItem', $id)
            ->with('success', 'Success in updating item data');
    }

    public function submittedppmp()
    {
        $year = Carbon::now()->year;

        $notipost = Ppmp::whereDate('created_at', Carbon::today())->paginate();
        $notiuser = User::whereDate('created_at', Carbon::today())->paginate();

        $users = User::orderBy('created_at', 'desc')->get();
        $categories = Type::orderBy('created_at', 'desc')->get();

        $measurements = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $parts = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();

        $totalppmp = Ppmp::where('user_id', Auth::id())->sum('total_amt');

        return view('dashboard.submittedppmp', compact(
            'measurements', 'parts', 'categories', 'users', 
            'notipost', 'notiuser', 'totalppmp', 'year'
        ));
    }

    public function viewsubmittedppmp($id, $year)
    {
        $year = urldecode($year);

        if (!is_numeric($year) || strlen($year) !== 4) {
            $year = Carbon::now()->year;
        } else {
            $year = (int) $year;
        }

        $notipost = Ppmp::whereDate('created_at', Carbon::today())->paginate();
        $notiuser = User::whereDate('created_at', Carbon::today())->paginate();

        $user = User::findOrFail($id);

        $ppmp = Ppmp::where('user_id', $user->id)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $category = Type::orderBy('created_at', 'desc')->get();

        $categorys = Category::where('type_id', 1)->orderBy('created_at', 'asc')->get();
        $measure = Category::where('type_id', 2)->orderBy('created_at', 'asc')->get();
        $part = Category::where('type_id', 3)->orderBy('created_at', 'asc')->get();

        $totalppmp = Ppmp::where('user_id', $id)
            ->whereYear('created_at', $year)
            ->sum('total_amt');

        return view('dashboard.viewsubmittedppmp', compact(
            'user', 'category', 'categorys', 'measure', 'part', 'ppmp',
            'notipost', 'notiuser', 'totalppmp', 'year'
        ));
    }
}
