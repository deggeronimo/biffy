<?php namespace Biffy\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;

class VerifyStoreId implements Middleware
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
        // todo remove $request->header('X-Store-Id') when request interceptor implemented
        if ($request->header('X-Store-Id') && $request->header('X-Store-Id') != \Auth::user()->storeId()) {
            return \Response::json(['messages' => ['error' => 'middleware_error_store_conflict']], 409);
        }

        return $next($request);
    }
}