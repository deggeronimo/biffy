<?php namespace Biffy\Entities\CompanyContact;

use Biffy\Entities\AbstractEntity;

class CompanyContact extends AbstractEntity
{
    protected $fillable = [
        'company_id',
        'name',
        'phone',
        'email',
    ];

    protected $appends = [];

    public $timestamps = false;

    public function company() {
        return $this->belongsTo('Biffy\Entities\Company\Company');
    }

}