<?php namespace Biffy\Entities\WebsiteFilterGroup;

use Biffy\Entities\AbstractEntity;

class WebsiteFilterGroup extends AbstractEntity
{
    protected $fillable = [
        'id',
        'sort_order',
        'portal_filter'
    ];

    protected $strings = [
        'name'
    ];

    public $timestamps = false;

    public function filters()
    {
        return $this->hasMany('Biffy\Entities\WebsiteFilter\WebsiteFilter', 'filter_group_id');
    }
}