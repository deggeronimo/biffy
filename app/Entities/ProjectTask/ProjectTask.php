<?php namespace Biffy\Entities\ProjectTask;

use Biffy\Entities\AbstractEntity;

class ProjectTask extends AbstractEntity
{
    protected $fillable = [
        'name',
        'project_id',
        'parent',
        'description',
        'completed_at'
    ];

    protected $dates = ['completed_at'];

    public function project()
    {
        return $this->belongsTo('Biffy\Entities\Project\Project');
    }

    public function subtasks()
    {
        return $this->hasMany('Biffy\Entities\ProjectTask\ProjectTask', 'parent');
    }

    public function comments()
    {
        return $this->hasMany('Biffy\Entities\ProjectTaskComment\ProjectTaskComment', 'task_id');
    }

    public function completeTask()
    {
        $this->completed_at = $this->freshTimestamp();
        return $this->save();
    }
}