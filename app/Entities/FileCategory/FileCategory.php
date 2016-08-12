<?php namespace Biffy\Entities\FileCategory;

use Biffy\Entities\AbstractEntity;

class FileCategory extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    protected $appends = ['sets'];

    protected $hidden = [];

    public $timestamps = false;

    public function sets() {
        return $this->hasMany('Biffy\Entities\FileSet\FileSet');
    }

    public function getSetsAttribute() {
        return $this->sets()->get()->toArray();
    }

}
