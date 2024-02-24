<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class TwoFactorVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->tfa == 'No') {
            return $next($request);
        }
        // removed
//        $tfaExpiry = auth()->user()->tfa_expiry;
        $tfaExpiry = session('tfa_expiry');
        if ($tfaExpiry > \Carbon\Carbon::now()) {
            return $next($request);
        }
        return redirect('/face-verification');
    }
}
