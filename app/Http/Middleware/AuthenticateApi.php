<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateApi extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, ['web']); // Use web guard
        
        return $next($request);
    }
    
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return 'http://localhost/ease-pos/public/';
        }
    }
}