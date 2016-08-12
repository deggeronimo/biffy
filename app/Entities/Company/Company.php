<?php namespace Biffy\Entities\Company;

use Biffy\Entities\AbstractEntity;

class Company extends AbstractEntity
{
    protected $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'phone',
        'email',
        'discount'
    ];

    protected $hidden = [];

    protected $appends = [];

    public function companyInstructions()
    {
        return $this->hasOne('Biffy\Entities\CompanyInstructions\CompanyInstructions');
    }

    public function companyStoreItems()
    {
        return $this->hasMany('Biffy\Entities\CompanyStoreItem\CompanyStoreItem');
    }

    public function contacts()
    {
        return $this->hasMany('Biffy\Entities\CompanyContact\CompanyContact');
    }

    public function sales()
    {
        return $this->hasMany('Biffy\Entities\Sale\Sale');
    }
}