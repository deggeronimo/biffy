<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\PermissionUser\PermissionUserService;

/**
 * Class PermissionUserController
 * @package Biffy\Http\Controllers
 */
class PermissionUserController extends ApiController
{
    /**
     * @var PermissionUserService
     */
    protected $service;

    /**
     * @param PermissionUserService $service
     */
    public function __construct(PermissionUserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function index($userId)
    {
        if ($this->input('store_only')) {
            return $this->data($this->service->getStorePermissions($userId, \Auth::user()->storeId()))->respond();
        }

        return $this->data($this->service->getGlobalPermissions($userId))->respond();
    }

    public function store($userId)
    {
        if ($this->input('store_only')){
            $this->service->setStorePermissions($userId, \Auth::user()->storeId(), $this->input('permissions'));
        } else {
            $this->service->setGlobalPermissions($userId, $this->input('permissions'));
        }
    }
}
