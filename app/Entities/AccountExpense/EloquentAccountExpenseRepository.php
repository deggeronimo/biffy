<?php namespace Biffy\Entities\AccountExpense;

use Biffy\Entities\EloquentAbstractRepository;

class EloquentAccountExpenseRepository extends EloquentAbstractRepository implements AccountExpenseRepositoryInterface
{
    public function __construct(AccountExpense $model)
    {
        $this->model = $model;

        $this->with([ 'vendor', 'user', 'accountExpenseCategory' ]);
    }
}