<?php

namespace Biffy\Http\Middleware;

use Biffy\Services\Access\AccessService;
use Closure;
use Illuminate\Contracts\Routing\Middleware;

class AccessCheckMiddleware implements Middleware
{
    /** @var  AccessService */
    protected $access;

    public function __construct(AccessService $access)
    {
        $this->access = $access;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // todo needs route information
    }
}