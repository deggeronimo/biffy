<?php namespace Biffy\Entities\Group;

use Biffy\Entities\AbstractEntity;

class Group extends AbstractEntity
{
    protected $fillable = [
        'name',
        'email',
        'is_store'
    ];

    public function store()
    {
        return $this->hasOne('Biffy\Entities\Store\Store');
    }

    public function users()
    {
        return $this->belongsToMany('Biffy\Entities\User\User');
    }
} 