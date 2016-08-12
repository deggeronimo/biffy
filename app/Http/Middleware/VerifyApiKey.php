<?php namespace Biffy\Http\Middleware;

use Biffy\Services\Entities\ApiKey\ApiKeyService;
use Closure;
use Illuminate\Contracts\Routing\Middleware;

class VerifyApiKey implements Middleware
{
    /**
     * @var ApiKeyService
     */
    private $service;

    public function __construct(ApiKeyService $service)
    {
        $this->service = $service;
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
        $apiKey = $request->headers->get('X-Api-Key');

        if (is_null($apiKey) || !$this->service->keyExists($apiKey)) {
            return \Response::json(['error' => 'Invalid or missing API key.'])->setStatusCode(401);
        }

        return $next($request);
    }
}