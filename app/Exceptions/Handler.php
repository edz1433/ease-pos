<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // For API requests, return JSON response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'authenticated' => false,
                'redirect_url' => 'http://localhost/ease-pos/public/',
                'message' => 'Unauthenticated. Please login first.'
            ], 401);
        }

        // For web requests, redirect to login page
        return redirect()->guest('http://localhost/ease-pos/public/');
    }
}