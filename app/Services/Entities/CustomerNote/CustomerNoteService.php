<?php namespace Biffy\Services\Entities\CustomerNote;

use Biffy\Commands\CustomerNote\CreateCustomerNoteCommand;
use Biffy\Entities\CustomerNote\CustomerNoteRepositoryInterface;
use Biffy\Facades\Command;
use Biffy\Services\Entities\Service;

/**
 * Class CustomerNote
 * @package Biffy\Services\Entities\CustomerNote
 */
class CustomerNoteService extends Service
{
    /**
     * @param CustomerNoteRepositoryInterface $customerNoteRepository
     */
    public function __construct(CustomerNoteRepositoryInterface $customerNoteRepository)
    {
        $this->repo = $customerNoteRepository;
    }

    /**
     * @param array $request
     * @return mixed
     */
    public function store($request)
    {
        $command = new CreateCustomerNoteCommand($request);

        Command::execute($command);

        return $command->result->toArray();
    }

    /**
     * @param $customerId
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getNotesForCustomer($customerId)
    {
        return $this->repo->getNotesForCustomer($customerId)->toArray();
    }
} 