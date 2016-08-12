<?php namespace Biffy\Entities\WebsiteFilterGroup;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentWebsiteFilterGroupRepository extends EloquentAbstractRepository implements WebsiteFilterGroupRepositoryInterface
{
    protected $sorters = [
        'sort_order' => []
    ];

    public function __construct(WebsiteFilterGroup $model)
    {
        $this->model = $model;

        $this->with([ 'filters' ]);
    }
}