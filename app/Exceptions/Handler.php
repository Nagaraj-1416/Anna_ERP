<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    protected $nonRedirectRoutes = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if(config('app.env') == 'production') {
            if ($exception instanceof AuthenticationException) {
                if ($request->is('api*')) {
                    return response([
                        'message' => "unauthorized",
                        'status_code' => 401
                    ], 401);
                }
                return $this->unauthenticated($request, $exception);
            }

            if ($exception instanceof AuthorizationException) {
                $authExceptionCount = session()->get('auth_exception_count', 0);

                session()->put('auth_exception_count', $authExceptionCount + 1);

                alert()->error($exception->getMessage())->persistent('Close');
                if (session()->get('auth_exception_count') > 3) {
                    session()->forget('auth_exception_count');
                    return redirect()->route('dashboard');
                }

                return redirect()->route('dashboard');
            }
        }
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest('login');
    }

    public function checkRedirectUrls()
    {
        $redirectUrl = redirect()->back()->getTargetUrl();
        $return = true;
        foreach ($this->nonRedirectRoutes as $url) {
            $url = url($url);
            if (substr($redirectUrl, 0, strlen($url)) == $url) {
                $return =  false;
                break;
            }
        }
        return $return;
    }
}
