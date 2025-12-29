<?php

namespace App\Http\Middleware;

use Closure;

class AdminLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->get('user_type')=="F_F_HR"  || session()->get('user_type')=="F_F_Admin"  || session()->get('user_type')=="Super_Admin" || session()->get('user_type')=="Claims" || session()->get('user_type')=="Payroll_Finance"  || session()->get('user_type')=="Payroll_HR" || session()->get('user_type')=="HR-LEAD" || session()->get('user_type')=="Payroll_IT" || session()->get('user_type')=="IT-INFRA" || session()->get('user_type')=="Payroll_QC" || session()->get('user_type')=="SME") {
            return $next($request);
        }
        // abort(403);
        return redirect('login/admin');
    }
}
