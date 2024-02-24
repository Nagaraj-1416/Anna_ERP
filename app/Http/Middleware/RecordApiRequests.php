<?php

namespace App\Http\Middleware;

use App\Jobs\RecordAPiRequestJob;
use Closure;

class RecordApiRequests
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
        try{
            RecordAPiRequestJob::dispatch($request->all(), $request->path(), $request->method(), auth()->user());
        }catch (\Exception $e){

        }
        return $next($request);
    }
}
