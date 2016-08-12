<?php namespace Biffy\Entities\UrlAlias;

use Biffy\Entities\AbstractEntity;

class UrlAlias extends AbstractEntity
{
    protected $table = 'url_alias';

    protected $fillable = [
        'model',
        'model_id',
        'keyword'
    ];

    public $timestamps = false;
}