<?php namespace Biffy\Entities\ProjectTemplate;

use Biffy\Entities\AbstractEntity;

class ProjectTemplate extends AbstractEntity
{
    protected $fillable = [
        'name',
        'data'
    ];
}