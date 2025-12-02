<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        
        // Check if logged in
        if (!Auth::check()) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Please login as admin first.');
        }

        // Check role = admin
        if (Auth::user()->role !== 'admin') {
            return redirect()
                ->route('admin.login')
                ->with('error', 'You are not authorized to access the admin area.');
        }

        return $next($request);
    }
}
