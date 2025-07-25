<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        
        if(auth()->check()){
            $authrole = auth()->user()->isAdmin;
            if (($authrole == 0) && $request->is('create-ppmp/ceiling-amount*')) {
                return redirect()->route('dashboard')->with('error', 'You dont have Permission to access this page');
            }
            if ($authrole == 1) {
                if($request->is('account*') || $request->is('create-ppmp*') || $request->is('item-settings*') || $request->is('ppmp-items*') || $request->is('user-logs*')){
                    return redirect()->route('dashboard')->with('error', 'You dont have Permission to access this page');
                }
            }
            if (($authrole == 2)) {
                if($request->is('account*') || $request->is('item-settings*') || $request->is('ppmp-items*') || $request->is('user-logs*')){
                    return redirect()->route('dashboard')->with('error', 'You dont have Permission to access this page');
                }
            }
        }
        else {
            return redirect()->route('getLogin')->with('error', 'You have to sign in first to access this page');
        }
  
        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');

        return $response;
    }
}