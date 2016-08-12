<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Role\RoleService;

/**
 * Class RoleController
 * @package Biffy\Http\Controllers
 */
class RoleController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var RoleService
     */
    protected $service;

    /**
     * @param RoleService $service
     */
    function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->data($this->service->all()->toArray())->respond();
    }

    /**
     * @param $id
     */
    public function update($id)
    {
        if (is_array($this->input('permissions')))
        {
            $this->service->setPermissions($id, $this->input('permissions'));
        }

        $this->service->update($id, $this->inputAll());
    }

    /**
     * @param mixed $data
     * @return $this
     */
    protected function data($data)
    {
        $data = $this->keysOnly($data, ['permissions']);

        return parent::data($data);
    }
}