<?php namespace Biffy\Entities\LanguageKey;

use Biffy\Entities\AbstractEntity;

class LanguageKey extends AbstractEntity
{
    protected $fillable = [
        'key'
    ];

    public $timestamps = false;

    public function languages()
    {
        return $this->belongsToMany('Biffy\Entities\Language\Language', 'language_strings')
            ->withPivot(['string']);
    }

    public function languageStrings()
    {
        return $this->hasMany('Biffy\Entities\LanguageString\LanguageString');
    }
}