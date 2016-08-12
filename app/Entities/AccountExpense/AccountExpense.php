<?php namespace Biffy\Entities\AccountExpense;

use Biffy\Entities\AbstractEntity;

class AccountExpense extends AbstractEntity
{
    protected $fillable = [
        'amount',
        'vendor_id',
        'store_id',
        'user_id',
        'filename',
        'comments',
        'account_expense_category_id'
    ];

    public function accountExpenseCategory()
    {
        return $this->belongsTo('Biffy\Entities\AccountExpenseCategory\AccountExpenseCategory');
    }

    public function store()
    {
        return $this->belongsTo('Biffy\Entities\Store\Store');
    }

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }

    public function vendor()
    {
        return $this->belongsTo('Biffy\Entities\Vendor\Vendor');
    }
}
