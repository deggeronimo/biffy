<?php namespace Biffy\Entities\UserSetting;

use Biffy\Entities\AbstractEntity;

class UserSetting extends AbstractEntity
{
    protected $fillable = [
        'user_id',
        'setting_id',
        'value'
    ];
}