<?php namespace Biffy\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;

class GoogleAuthenticated implements Middleware
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
        if (!\Auth::check()) {
            return \Response::json(['messages' => ['error' => 'middleware_error_unauthorized']], 401);
        }

        return $next($request);
    }
}