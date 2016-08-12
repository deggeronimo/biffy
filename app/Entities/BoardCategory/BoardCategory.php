<?php namespace Biffy\Entities\BoardCategory;

use Biffy\Entities\AbstractNode;

class BoardCategory extends AbstractNode
{
    protected $fillable = [
        'name'
    ];

    public function threads()
    {
        return $this->hasMany('Biffy\Entities\BoardThread\BoardThread', 'category_id');
    }
} 