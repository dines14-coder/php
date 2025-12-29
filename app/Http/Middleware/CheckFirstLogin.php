<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFirstLogin
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
        if (auth()->check()) {
            $user = auth()->user();
            
            // If user is in first-time login mode
            if ($user->is_first_login) {
                // Allow access only to password update page and logout
                $allowedRoutes = [
                    'password_update_landing',
                    'check_password',
                    'update_pass',
                    'logout'
                ];
                
                if (!in_array($request->route()->getName(), $allowedRoutes)) {
                    return redirect()->route('password_update_landing');
                }
            }
        }
        
        return $next($request);
    }
}