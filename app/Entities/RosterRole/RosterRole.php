<?php namespace Biffy\Entities\RosterRole;

use Biffy\Entities\AbstractEntity;

class RosterRole extends AbstractEntity
{
    protected $fillable = [
        'category',
        'name'
    ];

    protected $appends = ['public_name'];

    protected $hidden = [];

    public $timestamps = false;

    public function getPublicNameAttribute() {
        return $this->category . ' > ' . $this->name;
    }

}
