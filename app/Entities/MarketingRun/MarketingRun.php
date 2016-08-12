<?php namespace Biffy\Entities\MarketingRun;

use Biffy\Entities\AbstractEntity;
use CreateMarketingVisitsTable;

class MarketingRun extends AbstractEntity
{
    protected $fillable = [
        'stopped',
        'store_id',
        'user_id'
    ];

    public function marketingLocations()
    {
        return $this->belongsToMany('Biffy\Entities\MarketingLocation\MarketingLocation', CreateMarketingVisitsTable::TABLENAME)
            ->withPivot('marketing_run_type_id', 'comments')
            ->withTimestamps();
    }
}