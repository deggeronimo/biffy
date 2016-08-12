<?php namespace Biffy\Entities\Roster;

use Biffy\Entities\AbstractEntity;

class Roster extends AbstractEntity
{
    protected $fillable = [
        'store_id',
        'employee_id',
        'start_time',
        'time_interval',
        'allowed_break',
        'roster_role_ids'
    ];

    protected $appends = ['roster_role_ids', 'title'];

    protected $hidden = ['created_at', 'updated_at', 'store', 'employee'];

    public function store() {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }

    public function getStoreNameAttribute() {
        return $this->store->name;
    }

    public function employee() {
        return $this->belongsTo('Biffy\Entities\User\User');
    }

    public function getEmployeeNameAttribute() {
        return $this->employee->name;
    }

	public function roster_roles()
	{
		return $this->belongsToMany('Biffy\Entities\RosterRole\RosterRole');
	}

	public function getRosterRoleIdsAttribute()
	{
		return $this->roster_roles()->lists('roster_role_id');
	}

	public function setRosterRoleIdsAttribute($values)
	{
		$this->roster_roles()->sync($values);
	}

    public function getTitleAttribute() {
        return implode(',', $this->roster_roles()->lists('name'));
    }

    public function getStartTimeAttribute() {
        return (new \DateTime($this->attributes["start_time"]))->format('c');
    }

}
