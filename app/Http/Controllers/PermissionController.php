<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Permission\PermissionService;

/**
 * Class PermissionController
 * @package Biffy\Http\Controllers
 */
class PermissionController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var PermissionService $service
     */
    protected $service;

    /**
     * @param PermissionService $service
     */
    function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (is_null($this->input('global'))) {
            return $this->data($this->service->all())->respond();
        }

        if ($this->input('global') == 0) {
            return $this->data($this->service->getStorePermissions())->respond();
        }

        return $this->data($this->service->getGlobalPermissions())->respond();
    }

    public function afterStore($result)
    {
        $this->service->needsCache();
        return $result;
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
