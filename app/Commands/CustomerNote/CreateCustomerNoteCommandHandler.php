<?php namespace Biffy\Commands\CustomerNote;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\CustomerNote\CustomerNoteRepositoryInterface;
use Biffy\Services\Entities\CommandFailedException;
use Biffy\Services\Entities\RollbackFailedException;

/**
 * Class CreateCustomerNoteCommandHandler
 * @package Biffy\Commands\CustomerNote
 */
class CreateCustomerNoteCommandHandler implements CommandHandler
{
    /**
     * @var CustomerNoteRepositoryInterface
     */
    protected $customerNoteRepository;

    /**
     * @param CustomerNoteRepositoryInterface $customerNoteRepository
     */
    public function __construct(CustomerNoteRepositoryInterface $customerNoteRepository)
    {
        $this->customerNoteRepository = $customerNoteRepository;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        $command->result = $this->customerNoteRepository->create([
            'customer_id' => $command->customer_id,
            'note' => $command->note
        ]);
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {
//        $this->customerNoteRepository->delete($command->result->id);
    }
} 