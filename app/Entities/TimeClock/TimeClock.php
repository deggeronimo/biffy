<?php namespace Biffy\Entities\TimeClock;

use Biffy\Entities\AbstractEntity;

class TimeClock extends AbstractEntity
{
    const CLOCKTYPE_ONCLOCK = 1;
    const CLOCKTYPE_ONBREAK = 2;

    const DAY_HOURS_BEFORE_OVERTIME = 8;
    const WEEK_HOURS_BEFORE_OVERTIME = 40;

    protected $table = 'timeclock';

    protected $fillable = [
        'store_id',
        'user_id',
        'time_in',
        'time_out',
        'clock_type'
    ];

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }
}