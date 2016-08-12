<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\LanguageKey\LanguageKeyService;

class LanguageKeyController extends ApiController
{
    public function __construct(LanguageKeyService $service)
    {
        $this->service = $service;
    }

    public function getKey($key)
    {
        $result = $this->service->findFirstBy('key', '=', $key);

        return $this->data($result->toArray())->respond();
    }
}