<?php

namespace App\Http\Middleware;

use App\WorkHour;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WorkHours
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

            $currentDate = carbon()->now()->tz('Asia/Colombo')->toDateString();
            $currentTime = carbon()->now()->tz('Asia/Colombo')->toTimeString();

            $workHour = WorkHour::where('date', $currentDate)
                ->where('status', 'Allocated')
                ->where('user_id', $user->id)->first();

            if($user && $user->role->access_level < 500){
                if($workHour){
                    $workHourStartTime = date("H:i:s", strtotime($workHour->start));
                    $workHourEndTime = date("H:i:s", strtotime($workHour->end));

                    if ($currentTime >= $workHourStartTime && $currentTime <= $workHourEndTime) {
                        return $next($request);
                    }else{
                        Auth::guard($guard)->logout();
                        Session::put('workHrsMessage', 'Please try login between your work hours.');
                        return redirect('/');
                    }
                }else{
                    Auth::guard($guard)->logout();
                    Session::put('nowWrkHrsMessage', 'Please try login between your work hours.');
                    return redirect('/');
                }
            }
        }
        return $next($request);
    }
}
