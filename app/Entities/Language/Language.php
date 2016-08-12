<?php namespace Biffy\Entities\Language;

use Biffy\Entities\AbstractEntity;

class Language extends AbstractEntity
{
    const EN = 1;
    const CA_FR = 2;
    const TT = 3;
    const CA = 4;

    protected $fillable = [
        'lang'
    ];

    public $timestamps = false;
}