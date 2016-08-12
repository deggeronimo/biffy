<?php namespace Biffy\Entities\DeviceType;

use Biffy\Entities\AbstractEntity;
use Biffy\Entities\DeviceRepair\DeviceRepair;
use Biffy\Entities\LanguageString\LanguageString;
use CreateDeviceTypeTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceType extends AbstractEntity
{
    protected $table = CreateDeviceTypeTable::TABLENAME;

    protected $fillable = [
        'id',
        'selectable',
        'parent_device_type_id',
        'device_manufacturer_id',
        'device_family_id',
        'image',
        'top',
        'sort_order',
        'product',
        'status',
        'model',
        'view_count',
        'release_date',
        'filters'
    ];

    protected $appends = [
        'has_repairs',
        'parent_name',
        'pos_selectable'
    ];

    protected $strings = [
        'name',
        'meta_description',
        'meta_keywords',
        'web_description'
    ];

    public $timestamps = true;

    use SoftDeletes;

    public function carriers()
    {
        return $this->belongsToMany('Biffy\Entities\DeviceCarrier\DeviceCarrier', 'device_type_carriers');
    }

    public function children()
    {
        return $this->hasMany('Biffy\Entities\DeviceType\DeviceType', 'parent_device_type_id');
    }

    public function device()
    {
        return $this->hasMany('Biffy\Entities\Device\Device');
    }

    public function deviceChecklist()
    {
        return $this->hasMany('Biffy\Entities\DeviceChecklist\DeviceChecklist');
    }

    public function deviceDisplaySize()
    {
        return $this->belongsTo('Biffy\Entities\DeviceDisplaySize\DeviceDisplaySize');
    }

    public function deviceFamily()
    {
        return $this->belongsTo('Biffy\Entities\DeviceFamily\DeviceFamily');
    }

    public function deviceItemChecklist()
    {
        return $this->hasMany('Biffy\Entities\DeviceItemChecklist\DeviceItemChecklist');
    }

    public function deviceManufacturer()
    {
        return $this->belongsTo('Biffy\Entities\DeviceManufacturer\DeviceManufacturer');
    }

    public function getHasRepairsAttribute()
    {
        return DeviceRepair::firstByAttributes([ 'device_type_id' => $this->id ]) ? true : false;
    }

    public function getParentNameAttribute()
    {
        $parent = $this->find($this->parent_device_type_id);

        if (is_null($parent))
        {
            return '';
        }
        else
        {
            $languageString = LanguageString::firstByAttributes([ 'language_key_id' => $parent->name_language_key_id, 'language_id' => 1 ]);

            return $languageString->string;
        }
    }

    public function getPosSelectableAttribute()
    {
        return $this->attributes['status'] == 1 &&
            ( DeviceType::firstByAttributes(
                [
                    'parent_device_type_id' => $this->id,
                    'status' => 1
                ]
            ) ? false : true );
    }

    public function parentDeviceType()
    {
        return $this->belongsTo('Biffy\Entities\DeviceType\DeviceType', 'parent_device_type_id')->strings();
    }

    public function repairs()
    {
        return $this->hasMany('Biffy\Entities\DeviceRepair\DeviceRepair');
    }

    public function setDeviceFamilyIdAttribute($value)
    {
        $this->attributes['device_family_id'] = $value ?: null;
    }

    public function setDeviceManufacturerIdAttribute($value)
    {
        $this->attributes['device_manufacturer_id'] = $value ?: null;
    }

    public function setParentDeviceTypeIdAttribute($value)
    {
        $this->attributes['parent_device_type_id'] = $value ?: null;
    }
}