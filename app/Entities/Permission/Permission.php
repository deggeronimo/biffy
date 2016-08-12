<?php namespace Biffy\Entities\Permission;

use Biffy\Entities\AbstractEntity;
//use Biffy\Transformers\PermissionTransformer;

class Permission extends AbstractEntity
{
    protected $fillable = [
        'name',
        'description',
        'global'
    ];

//    public function getTransformer()
//    {
//        return new PermissionTransformer();
//    }
}