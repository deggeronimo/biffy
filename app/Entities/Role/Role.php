<?php namespace Biffy\Entities\Role;

use Biffy\Entities\AbstractEntity;

class Role extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public function permissions()
    {
        return $this->belongsToMany('Biffy\Entities\Permission\Permission');
    }
} 