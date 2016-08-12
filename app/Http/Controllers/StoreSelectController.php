<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\Store\StoreRepositoryInterface;

/**
 * Class StoreSelectController
 * @package Biffy\Http\Controllers
 */
class StoreSelectController extends ApiController
{
    use Helpers\SelectControllerHelper;

    /**
     * @var StoreRepositoryInterface
     */
    public $repo;

    /**
     * @param StoreRepositoryInterface $repo
     */
    function __construct(StoreRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    function users($store_id) {
        return $this->data($this->repo->find($store_id)->users()->toArray())->respond();
    }

}