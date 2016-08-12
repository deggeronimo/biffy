<?php namespace Biffy\Entities\CompanySaleApproval;

use Biffy\Entities\AbstractEntity;

class CompanySaleApproval extends AbstractEntity
{
    protected $fillable = [
        'company_id',
        'sale_id',
        'workorder_id',
        'approval_code',
        'approved'
    ];

    protected $hidden = [
        'approval_code'
    ];

    public function company()
    {
        return $this->belongsTo('Biffy\Entities\Company\Company');
    }

    public function sale()
    {
        return $this->belongsTo('Biffy\Entities\Sale\Sale');
    }

    public function workorder()
    {
        return $this->belongsTo('Biffy\Entities\WorkOrder\WorkOrder');
    }
}
