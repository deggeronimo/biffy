<?php namespace Biffy\Entities\PermissionUser;

use Biffy\Entities\AbstractEntity;

class PermissionUser extends AbstractEntity
{
    public $table = \CreatePermissionUserTable::TABLENAME;

    public $fillable = [
        'user_id',
        'store_id',
        'permission_id'
    ];
} 