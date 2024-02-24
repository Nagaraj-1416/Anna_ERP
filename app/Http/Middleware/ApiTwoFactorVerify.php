<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiTwoFactorVerify
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
        /** @var User $user */
        $user = auth()->user();
        if ($user->tfa == 'No') {
            return $next($request);
        }
        // removed
        $tfaExpiry = auth()->user()->tfa_expiry;
        if ($tfaExpiry > \Carbon\Carbon::now()) {
            return $next($request);
        }
        return response()->json(array(
            'message' => 'unauthorized.',
            'errors' => 'Please authorized two factor authentication'
        ), 401);
    }
}
