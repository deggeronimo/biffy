<?php namespace Biffy\Entities\User;

use Biffy\Entities\AbstractEntity;
use Biffy\Entities\Store\Store;

class User extends AbstractEntity
{
    protected $fillable = [
        'given_name',
        'family_name',
        'username',
        'email',
        'admin',
        'pin',
        'picture_url'
    ];

    protected $appends = [
        'name',
    ];

    protected $hidden = ['admin', 'pivot', 'created_at', 'updated_at', 'pin'];

    public function accountExpense()
    {
        return $this->belongsTo('Biffy\Entities\AccountExpense\AccountExpense');
    }

    public function feedbacks()
    {
        return $this->belongsToMany('Biffy\Entities\Feedback\Feedback');
    }

    public function permissions()
    {
        return $this->belongsToMany('Biffy\Entities\Permission\Permission')->withPivot('store_id');
    }

    public function groups()
    {
        return $this->belongsToMany('Biffy\Entities\Group\Group');
    }

    public function timeClock()
    {
        return $this->hasMany('Biffy\Entities\TimeClockItem\TimeClockItem');
    }

    public function workOrderNote()
    {
        return $this->hasMany('Biffy\Entities\WorkOrderNote\WorkOrderNote');
    }

    public function getNameAttribute()
    {
        return $this->given_name . ' ' . $this->family_name;
    }

    // This is not a relationship but query
    public function stores()
    {
        return Store::join('groups', 'stores.group_id', '=', 'groups.id')
            ->join('group_user', 'group_user.group_id', '=', 'groups.id')
            ->join('users', 'users.id', '=', 'group_user.user_id')
            ->where('users.id', $this->id)
            ->select('stores.*')
            ->get();
    }

    public function storeIds()
    {
        return $this->stores()->modelKeys();
    }

    public function profile()
    {
        return $this->hasOne('Biffy\Entities\UserProfile\UserProfile');
    }

    public function threads()
    {
        return $this->hasMany('Biffy\Entities\BoardThread\BoardThread');
    }

    public function posts()
    {
        return $this->hasMany('Biffy\Entities\BoardPost\BoardPost');
    }

    public function settings()
    {
        return $this->hasMany('Biffy\Entities\UserSetting\UserSetting');
    }
}