<?php namespace Biffy\Entities\ProjectTaskComment;

use Biffy\Entities\AbstractEntity;

class ProjectTaskComment extends AbstractEntity
{
    protected $fillable = [
        'user_id',
        'task_id',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }
}