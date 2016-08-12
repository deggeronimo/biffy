<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\Group\GroupService;

/**
 * Class GroupController
 * @package Biffy\Http\Controllers
 */
class GroupController extends ApiController
{
    use Helpers\ServiceCRUDControllerHelper;

    /**
     * @var GroupService $service
     */
    protected $service;

    /**
     * @param GroupService $service
     */
    public function __construct(GroupService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if ($this->input('noPagination')) {
            return $this->data($this->service->all())->respond();
        }

        $sorting = $this->input('sorting');

        $result = $this->service->paginate($this->input('count'), $this->input('page'))->sortBy($sorting)->get();

        return $this->data($result->toArray()['data'])->paginator($result)->respond();
    }
} 