<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Config\ConfigService;

/**
 * Class ConfigController
 * @package Biffy\Http\Controllers
 */
class ConfigController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var \Biffy\Entities\Config\ConfigRepositoryInterface
     */
    private $service;

    /**
     * @param ConfigService $service
     */
    public function __construct(ConfigService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
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
        $this->service->handleDeleted();
    }
} 