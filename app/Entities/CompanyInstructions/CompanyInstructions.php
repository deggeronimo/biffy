<?php namespace Biffy\Entities\CompanyInstructions;

use Biffy\Entities\AbstractEntity;

class CompanyInstructions extends AbstractEntity
{
    protected $fillable = [
        'company_id',
        'lock_trade_credit',
        'email_template',
        'display_instructions'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function company()
    {
        return $this->belongsTo('Biffy\Entities\Company\Company');
    }
}