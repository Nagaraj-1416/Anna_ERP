<?php

namespace App\Http\Middleware;

use App\Company;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class NonWorkingHrs
{
    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /** get logged user's company */
        $user = loggedUser();
        $company = userCompany($user);
        if($company){
            $currentTime = carbon()->now()->tz('Asia/Colombo')->toTimeString();

            $companyOpenTime = date("H:i:s", strtotime($company->business_starts_at));
            $companyCloseTime = date("H:i:s", strtotime($company->business_end_at));

            if($user){
                if($user['allowed_non_working_hrs'] == 'No') {
                    if ($currentTime >= $companyOpenTime && $currentTime <= $companyCloseTime) {
                        return $next($request);
                    }else{
                        Auth::guard($guard)->logout();
                        Session::put('nonWorkingHrsMessage', 'Please try between office hours.');
                        return redirect('/');
                    }
                }
            }
        }
        return $next($request);
    }
}
