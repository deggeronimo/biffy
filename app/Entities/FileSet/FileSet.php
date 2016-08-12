<?php namespace Biffy\Entities\FileSet;

use Biffy\Entities\AbstractEntity;

class FileSet extends AbstractEntity
{
    protected $fillable = [
        'name',
        'description',
        'file_category_id',
    ];

    protected $appends = ['category_name'];

    protected $hidden = ['category'];

    public $timestamps = false;

    public function category() {
        return $this->belongsTo('Biffy\Entities\FileCategory\FileCategory', 'file_category_id');
    }

    public function getCategoryNameAttribute() {
        return $this->category->name;
    }

    public function files() {
        return $this->hasMany('Biffy\Entities\File\File');
    }

    public function getListNameAttribute() {
        return $this->category_name . ' > ' . $this->name;
    }

}
