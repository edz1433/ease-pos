<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userEdit(Request $request)
    {
        $useredit = User::find($request->id);
        $users = User::all();
        return view('admin.users.index', compact('users', 'useredit'));
    }

    public function userCreate(Request $request)
    {
        $request->validate([
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'role' => 'nullable|in:1,2',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = new User();
        $user->lname = $request->lname;
        $user->fname = $request->fname;
        $user->mname = $request->mname;
        $user->gender = $request->gender;

        // default role = cashier
        $user->role = $request->role ?? 2;

        $user->username = $request->username;
        $user->password = bcrypt($request->password);

        // assign default remember_token as null
        $user->remember_token = null;

        // handle profile upload
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $user->profile = $filename;
        } else {
            if ($user->role == 1) {
                $user->profile = 'admin-default.png';
            } else {
                $user->profile = $user->gender === 'Male'
                    ? 'cashier-default-male.png'
                    : 'cashier-default-female.png';
            }
        }

        $user->save();

        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'role' => 'nullable|in:1,2',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->lname = $request->lname;
        $user->fname = $request->fname;
        $user->mname = $request->mname;
        $user->gender = $request->gender;

        // default role = cashier
        $user->role = $request->role ?? 2;

        $user->username = $request->username;

        // only update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // keep remember_token as is or reset if needed
        $user->remember_token = $user->remember_token ?? null;

        // handle profile upload
        if ($request->hasFile('profile')) {
            // delete old profile if not default
            if ($user->profile && !in_array($user->profile, [
                'admin-default.png',
                'cashier-default-male.png',
                'cashier-default-female.png'
            ])) {
                $oldPath = public_path('uploads/profile/' . $user->profile);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('profile');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $user->profile = $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function userDelete($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'status' => 404,
            'message' => "Record not found",
        ]);
    }

    public function userAccount(Request $request)
    {
        $accnt = User::find(auth()->user()->id);
        $type = Type::all();
        return view('users.account', compact('accnt', 'type'));
    }
    
    public function userAccntUpdate(Request $request)
    {
        $request->validate([
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'required|string|max:255',
            'position' => 'required|string|in:Dean,Office Head,Budget Officer III',
            'office' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->user()->id,
            'password' => 'nullable|string',
        ]);

        $user = User::findOrFail(auth()->user()->id);

        $user->update([
            'lname' => $request->lname,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'position' => $request->position,
            'office' => $request->office,
            'gender' => $request->gender,
            'isAdmin' => auth()->user()->isAdmin,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('userAccount')->with('success', 'User updated successfully.');
    }
}
