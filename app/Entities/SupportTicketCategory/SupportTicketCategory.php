<?php namespace Biffy\Entities\SupportTicketCategory;

use Biffy\Entities\AbstractEntity;

class SupportTicketCategory extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    protected $appends = [];

    protected $hidden = ['pivot'];

    public $timestamps = false;

}