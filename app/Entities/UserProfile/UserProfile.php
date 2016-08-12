<?php namespace Biffy\Entities\UserProfile;

use Biffy\Entities\AbstractEntity;

class UserProfile extends AbstractEntity
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'phone',
        'position',
        'birthday',
        'about',
        'signature'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }
}