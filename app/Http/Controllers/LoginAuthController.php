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
            
            // Check if user is active/approved
            if (isset($user->status) && $user->status != 1) {
                Auth::logout();
                return back()->with('error', 'Your account is not active. Please contact administrator.')->withInput();
            }

            // Use role field consistently
            if ($user->role == 1) {
                // Admin user - redirect to Laravel dashboard
                return redirect()->route('dashboard')->with('success', 'Login Successful');
            } else {
                // Cashier user (role == 2) - redirect to React app
               return redirect(config('app.react_url'));
            }
        }

        return back()->with('error', 'Invalid credentials.')->withInput();
    }

}
