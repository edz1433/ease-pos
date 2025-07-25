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
        $type = Type::all();
        $users = User::all();
        return view('users.index', compact('users', 'type', 'useredit'));
    }

    public function userCreate(Request $request)
    {
        $request->validate([
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'required|string|max:255',
            'position' => 'required|string',
            'office' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'role' => 'required|in:0,1,2', // match select values (0=Admin, 1=User, 2=Budget Officer)
            'username' => 'required|string|max:255|unique:users,username',
        ]);

        User::create([
            'lname' => $request->lname,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'position' => $request->position,
            'office' => $request->office,
            'gender' => $request->gender,
            'isAdmin' => $request->role, // assuming the DB column is 'isAdmin' (as in your form)
            'username' => $request->username,
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(60),
        ]);

        return redirect()->route('userRead')->with('success', 'User added successfully.');
    }

    public function userUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'lname' => 'required|string|max:255',
            'fname' => 'required|string|max:255',
            'mname' => 'required|string|max:255',
            'position' => 'required|string|in:Dean,Office Head,Budget Officer III',
            'office' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'role' => 'required|in:0,1,2',
            'username' => 'required|string|max:255|unique:users,username,' . $request->id,
            'password' => 'nullable|string',
        ]);

        $user = User::findOrFail($request->id);

        $user->update([
            'lname' => $request->lname,
            'fname' => $request->fname,
            'mname' => $request->mname,
            'position' => $request->position,
            'office' => $request->office,
            'gender' => $request->gender,
            'isAdmin' => $request->role,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('userRead')->with('success', 'User updated successfully.');
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
