<?php namespace Biffy\Entities\BoardPostRep;

use Biffy\Entities\AbstractEntity;

class BoardPostRep extends AbstractEntity
{
    protected $table = \CreateBoardPostRepTable::TABLENAME;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'post_id',
        'rating'
    ];
}