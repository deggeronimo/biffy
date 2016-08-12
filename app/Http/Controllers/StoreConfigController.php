<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\StoreConfig\StoreConfigService;

/**
 * Class StoreConfigController
 * @package Biffy\Http\Controllers
 */
class StoreConfigController extends ApiController
{
    /**
     * @var StoreConfigService
     */
    private $service;

    /**
     * @param StoreConfigService $service
     */
    public function __construct(StoreConfigService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->data($this->service->getEntries())->respond();
    }

    public function store()
    {
        $this->service->process($this->input('entries'));
    }
} 