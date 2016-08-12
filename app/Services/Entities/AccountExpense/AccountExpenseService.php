<?php namespace Biffy\Services\Entities\AccountExpense;

use Biffy\Entities\AccountExpense\AccountExpenseRepositoryInterface;
use Biffy\Services\Entities\Service;

class AccountExpenseService extends Service
{
    public function __construct(AccountExpenseRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
}