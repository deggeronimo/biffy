<?php namespace Biffy\Entities\StoreConfig;

use Biffy\Entities\AbstractEntity;
use Biffy\Entities\Config\Config;

class StoreConfig extends AbstractEntity
{
    public $table = 'store_config';

    protected $fillable = [
        'store_id',
        'config_id',
        'value'
    ];

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }

    public function config()
    {
        return $this->belongsTo('Biffy\Entities\Config\Config');
    }

    public static function create(array $attributes)
    {
        if (!array_key_exists('value', $attributes)) {
            $config = Config::find($attributes['config_id']);
            $attributes['value'] = $config->default;
        }

        parent::create($attributes);
    }
} 