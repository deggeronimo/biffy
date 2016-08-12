<?php namespace Biffy\Entities\MarketingLocation;

use Biffy\Entities\AbstractEntity;

class MarketingLocation extends AbstractEntity
{
    protected $fillable = [
        'name',
        'marketing_location_type_id',
        'latitude',
        'longitude',
        'address',
        'phone'
    ];

    public function marketingLocationType()
    {
        return $this->belongsTo('Biffy\Entities\MarketingLocationType\MarketingLocationType');
    }

    public function marketingRuns()
    {
        return $this->belongsToMany('Biffy\Entities\MarketingRun\MarketingRun')
            ->withPivot('marketing_run_type_id', 'comments')
            ->withTimestamps();
    }
}