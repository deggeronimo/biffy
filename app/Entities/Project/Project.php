<?php namespace Biffy\Entities\Project;

use Biffy\Entities\AbstractEntity;

class Project extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    public function tasks()
    {
        return $this->hasMany('Biffy\Entities\ProjectTask\ProjectTask');
    }

    public function users()
    {
        return $this->belongsToMany('Biffy\Entities\User\User');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('completed_at');
    }

    public function completeProject()
    {
        $this->completed_at = $this->freshTimestamp();
        return $this->save();
    }
}