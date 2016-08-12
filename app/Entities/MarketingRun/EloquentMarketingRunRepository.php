<?php namespace Biffy\Entities\MarketingRun;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentMarketingRunRepository extends EloquentAbstractRepository implements MarketingRunRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'id' => [],
        'stopped' => [],
        'created_at' => []
    ];

    public function __construct(MarketingRun $model)
    {
        $this->model = $model;
        $this->with([ 'marketingLocations', 'marketingLocations.marketingLocationType' ]);
    }
}