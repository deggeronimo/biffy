<?php namespace Biffy\Entities\SupportTicketStatus;

use Biffy\Entities\AbstractEntity;

class SupportTicketStatus extends AbstractEntity
{
    protected $fillable = [
        'name'
    ];

    protected $appends = [];

    public $timestamps = false;

}