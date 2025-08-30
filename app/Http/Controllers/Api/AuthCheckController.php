<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCheckController extends Controller
{
    /**
     * Check if user is authenticated
     */
    public function checkAuth(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->id,
                    'fname' => $user->fname,
                    'lname' => $user->lname,
                    'mname' => $user->mname,
                    'name' => $user->fname . ' ' . $user->lname,
                    'email' => $user->email,
                    'username' => $user->username,  
                    'position' => $user->position,
                    'office' => $user->office,
                    'ceiling_amount' => $user->ceiling_amount,
                    'role' => $user->role,
                    'status' => $user->status
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false,
            'redirect_url' => 'http://localhost/ease-pos/public/',
            'message' => 'User not authenticated'
        ], 401);
    }

    public function authStatus()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->id,
                    'fname' => $user->fname,
                    'lname' => $user->lname,
                    'name' => $user->fname . ' ' . $user->lname,
                    'role' => $user->role
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false
        ]);
    }
}