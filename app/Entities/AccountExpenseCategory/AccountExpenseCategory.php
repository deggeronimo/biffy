<?php namespace Biffy\Entities\AccountExpenseCategory;

use Biffy\Entities\AbstractEntity;

class AccountExpenseCategory extends AbstractEntity
{
    const INVENTORY = 1;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function accountExpenses()
    {
        return $this->hasMany('Biffy\Entities\AccountExpense\AccountExpense');
    }
}