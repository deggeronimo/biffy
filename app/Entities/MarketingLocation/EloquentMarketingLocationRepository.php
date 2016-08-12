<?php namespace Biffy\Entities\MarketingLocation;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentMarketingLocationRepository extends EloquentAbstractRepository implements MarketingLocationRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'name' => [],
        'latitude' => [],
        'longitude' => []
    ];

    public function __construct(MarketingLocation $model)
    {
        $this->model = $model;

        $this->with([ 'marketingLocationType' ]);
    }
}