<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginAuthController extends Controller
{
    public function getLogin()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role == 1) {
                return redirect()->route('dashboard')->with('success', 'Login Successfully');
            } elseif ($user->role == 2) {
                return redirect()->route('pos')->with('success', 'Login Successfully');
            } else {
                Auth::logout();
                return back()->with('error', 'Unauthorized role.')->withInput();
            }
        }

        return back()->with('error', 'Invalid credentials.')->withInput();
    }

}
