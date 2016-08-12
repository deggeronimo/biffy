<?php namespace Biffy\Entities\WebsiteFilter;

use Biffy\Entities\AbstractEntity;
use CreateWebsiteFiltersTable;

class WebsiteFilter extends AbstractEntity
{
    protected $table = CreateWebsiteFiltersTable::TABLENAME;

    protected $fillable = [
        'filter_group_id',
        'sort_order',
        'portal_filter_id'
    ];

    protected $strings = [
        'name'
    ];

    public $timestamps = false;

    public function deviceTypes()
    {
        return $this->belongsToMany('Biffy\Entities\DeviceType\DeviceType', 'website_filter_device_types')
            ->withPivot([])
            ->withTimestamps();
    }

    public function websiteFilterGroup()
    {
        return $this->belongsTo('Biffy\Entities\WebsiteFilterGroup\WebsiteFilterGroup', 'filter_group_id');
    }
}