<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Quote\QuoteService;

class QuoteController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    public function __construct(QuoteService $service)
    {
        $this->service = $service;
    }
}