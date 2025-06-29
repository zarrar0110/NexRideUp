<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        if ($user->role !== $role) {
            $currentRole = ucfirst($user->role);
            $requestedRole = ucfirst($role);
            return redirect('/')->with('error', "Access denied. You are logged in as a {$currentRole}, but you're trying to access the {$requestedRole} dashboard. Please use the appropriate dashboard for your role.");
        }

        return $next($request);
    }
} 