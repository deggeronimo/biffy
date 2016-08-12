<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Controllers\Helpers\ServiceCRUDControllerHelper;
use Biffy\Services\Entities\Setting\SettingService;

class SettingController extends ApiController
{
    use ServiceCRUDControllerHelper;

    /**
     * @var SettingService
     */
    private $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->data($this->service->all())->respond();
    }

    public function afterUpdate()
    {
        $this->service->needsCache();
    }

    public function afterDestroy()
    {
        $this->service->needsCache();
    }
} 