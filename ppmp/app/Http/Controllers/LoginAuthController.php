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
            $request->session()->regenerate();

            // Log user login
            DB::table('logs')->insert([
                'user_id'     => Auth::id(),
                'name'        => Auth::user()->fname.' '.Auth::user()->lname,
                'type'        => 0,
                'transaction' => 'has logged in the system',
                'created_at'  => now(),
                'updated_at'  => null,
            ]);

            return redirect()->route('dashboard')->with('success', 'Login Successfully');
        }

        return back()->with('error', 'Invalid credentials.')->withInput();
    }

}
