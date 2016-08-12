<?php namespace Biffy\Entities\DeviceRepairType;

use Biffy\Entities\AbstractEntity;
use Biffy\Facades\LanguageTranslator;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceRepairType extends AbstractEntity
{
    protected $fillable = [
        'image_overlay',
        'class',
        'sort_order'
    ];

    protected $appends = [
        'estimated_cost',
        'name'
    ];

    public $timestamps = false;

    use SoftDeletes;

    public function getEstimatedCostAttribute()
    {
        $languageKey = LanguageTranslator::generateKey($this, $this->attributes['id'], 'EstimatedCost');

        return LanguageTranslator::string($languageKey);
    }

    public function getNameAttribute()
    {
        $languageKey = LanguageTranslator::generateKey($this, $this->attributes['id'], 'Name');

        return LanguageTranslator::string($languageKey);
    }
}