<?php namespace Biffy\Entities\LanguageString;

use Biffy\Entities\AbstractEntity;

class LanguageString extends AbstractEntity
{
    protected $fillable = [
        'language_key_id',
        'language_id',
        'string'
    ];

    public $timestamps = false;
}